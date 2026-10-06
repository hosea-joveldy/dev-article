import { chromium } from '@playwright/test';
import { spawn } from 'child_process';

const PORT = 8008;
const BASE_URL = `http://127.0.0.1:${PORT}`;

async function startServer() {
    return new Promise((resolve) => {
        const proc = spawn('php', ['artisan', 'serve', `--port=${PORT}`], {
            cwd: process.cwd(),
            stdio: ['ignore', 'pipe', 'pipe']
        });

        proc.stdout.on('data', (data) => {
            if (data.toString().includes('Server running')) {
                resolve(proc);
            }
        });

        setTimeout(() => resolve(proc), 2000);
    });
}

async function runDevToolsAudit() {
    console.log('--- Starting Chrome DevTools Protocol Audit ---');
    const serverProc = await startServer();

    const browser = await chromium.launch({
        executablePath: '/etc/profiles/per-user/lumi/bin/chromium',
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });

    const context = await browser.newContext({
        viewport: { width: 1280, height: 800 }
    });

    const page = await context.newPage();
    const cdp = await page.context().newCDPSession(page);
    await cdp.send('Performance.enable');

    const issues = [];
    const consoleLogs = [];

    page.on('console', msg => {
        if (msg.type() === 'error') {
            issues.push(`Console Error on ${page.url()}: ${msg.text()}`);
        } else {
            consoleLogs.push(`[${msg.type()}] ${msg.text()}`);
        }
    });

    page.on('pageerror', err => {
        issues.push(`Page Uncaught Exception on ${page.url()}: ${err.message}`);
    });

    page.on('requestfailed', req => {
        if (req.failure()?.errorText !== 'net::ERR_ABORTED') {
            issues.push(`Failed Request: ${req.url()} (${req.failure()?.errorText})`);
        }
    });

    const results = [];

    async function loginAs(email, password) {
        await context.clearCookies();
        await page.goto(`${BASE_URL}/login`);
        await page.fill('input[name="email"]', email);
        await page.fill('input[name="password"]', password);
        await page.click('button[type="submit"]');
        await page.waitForLoadState('networkidle');
    }

    async function auditRoute(name, urlPath) {
        const fullUrl = urlPath.startsWith('http') ? urlPath : `${BASE_URL}${urlPath}`;
        const res = await page.goto(fullUrl);
        await page.waitForLoadState('networkidle');

        const metrics = await cdp.send('Performance.getMetrics');
        const metricMap = Object.fromEntries(metrics.metrics.map(m => [m.name, m.value]));

        // Measure computed styles of key tokens
        const bodyStyles = await page.evaluate(() => {
            const el = document.body;
            const computed = window.getComputedStyle(el);
            return {
                fontFamily: computed.fontFamily,
                color: computed.color,
                backgroundColor: computed.backgroundColor,
                lineHeight: computed.lineHeight
            };
        });

        // Test responsive behavior
        await page.setViewportSize({ width: 375, height: 667 });
        await page.waitForTimeout(50);
        const mobileOverflow = await page.evaluate(() => document.documentElement.scrollWidth > window.innerWidth);

        // Reset to desktop
        await page.setViewportSize({ width: 1280, height: 800 });

        results.push({
            name,
            path: urlPath,
            status: res?.status(),
            domNodes: metricMap.Nodes || 0,
            jsHeapUsedSize: Math.round((metricMap.JSHeapUsedSize || 0) / 1024) + ' KB',
            mobileOverflow,
            bodyStyles
        });
    }

    try {
        console.log('1. Auditing Public Pages (Guest mode)...');
        await auditRoute('Landing Page', '/');
        await auditRoute('Sign In', '/login');
        await auditRoute('Register', '/register');
        await auditRoute('Forgot Password', '/forgot-password');
        await auditRoute('404 Page', '/non-existent-page-test-404');

        console.log('2. Auditing Authenticated Pages (User mode)...');
        await loginAs('user@example.com', 'password');
        await auditRoute('Story Feed', '/artikel');
        await auditRoute('Write Story', '/artikel/create');
        await auditRoute('Profile Settings', '/profile');

        const firstArticleUrl = await page.evaluate(() => {
            const link = document.querySelector('.feed-card h2 a');
            return link ? link.getAttribute('href') : null;
        });

        if (firstArticleUrl) {
            await auditRoute('Story Detail', firstArticleUrl);
        }

        console.log('3. Auditing Admin Console (Admin mode)...');
        await loginAs('admin@example.com', 'password');
        await auditRoute('Admin Dashboard', '/admin');
        await auditRoute('Admin Categories', '/admin/categories');
        await auditRoute('Admin Users', '/admin/users');
        await auditRoute('Admin Comments', '/admin/comments');

        console.log('\n========================================');
        console.log('    CHROME DEVTOOLS PROTOCOL AUDIT      ');
        console.log('========================================');
        console.log(`Audited Routes: ${results.length}`);
        console.log(`Console / Network / Uncaught Issues: ${issues.length}`);

        if (issues.length > 0) {
            console.error('\nIssues Detected:');
            issues.forEach(iss => console.error('  - ' + iss));
        } else {
            console.log('✓ All pages rendered with ZERO console errors and ZERO network failures.');
        }

        console.log('\nAudit Detail per Route:');
        results.forEach(r => {
            console.log(`• [HTTP ${r.status}] ${r.name} (${r.path}) | Heap: ${r.jsHeapUsedSize} | Mobile Overflow: ${r.mobileOverflow ? 'FAILED' : 'PASSED'}`);
        });

        console.log('\nDesign Token Verification:');
        console.log(`• Default font family: ${results[0]?.bodyStyles?.fontFamily}`);
        console.log(`• Default text color: ${results[0]?.bodyStyles?.color}`);
        console.log('========================================\n');

    } finally {
        await browser.close();
        serverProc.kill();
    }
}

runDevToolsAudit().catch(err => {
    console.error('Audit script failed:', err);
    process.exit(1);
});

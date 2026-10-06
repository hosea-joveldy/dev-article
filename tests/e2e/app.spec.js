import { test, expect } from '@playwright/test';

test.describe('Ruang App Suite', () => {
    test('guest landing page matches reference and redirects guest on explore', async ({ page }) => {
        await page.goto('/');

        // Brand and title
        await expect(page).toHaveTitle(/Ruang — Stories & Ideas/);
        await expect(page.locator('.brand')).toHaveText('Ruang.');

        // Hero and Feature strip
        await expect(page.locator('.hero h1')).toContainText('Ideas worth');
        await expect(page.locator('.feature-strip')).toContainText('01 — CURATED');
        await expect(page.locator('.feature-strip')).toContainText('02 — EDITORIAL');
        await expect(page.locator('.feature-strip')).toContainText('03 — SIMPLE');

        // Explore topics & Latest stories
        await expect(page.locator('#latest')).toContainText('Latest stories');
        await expect(page.locator('#topics')).toContainText('Explore topics');

        // Clicking explore redirects guest to /login
        await page.click('nav.navlinks a:has-text("Explore")');
        await expect(page).toHaveURL(/\/login/);
    });

    test('guest attempting to visit /artikel or detail is redirected to /login', async ({ page }) => {
        await page.goto('/artikel');
        await expect(page).toHaveURL(/\/login/);

        await page.goto('/artikel/1');
        await expect(page).toHaveURL(/\/login/);

        await page.goto('/artikel/create');
        await expect(page).toHaveURL(/\/login/);
    });

    test('user can log in, view article feed, search and filter topics', async ({ page }) => {
        await page.goto('/login');
        await expect(page.locator('h1')).toContainText('Welcome back.');

        await page.fill('input[name="email"]', 'user@example.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');

        // Should land on article feed
        await expect(page).toHaveURL(/\/artikel/);
        await expect(page.locator('.page-title')).toHaveText('Explore stories.');
        await expect(page.locator('.sidebar')).toContainText('ABOUT RUANG');
        await expect(page.locator('.sidebar')).toContainText('RECOMMENDED TOPICS');

        // When logged in, visiting / redirects to /artikel
        await page.goto('/');
        await expect(page).toHaveURL(/\/artikel/);
    });

    test('authenticated user can view article detail and post comment', async ({ page }) => {
        // Log in
        await page.goto('/login');
        await page.fill('input[name="email"]', 'user@example.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');
        await expect(page).toHaveURL(/\/artikel/);

        // Click first article
        const firstArticleLink = page.locator('.feed-card h2 a').first();
        await firstArticleLink.click();

        await expect(page).toHaveURL(/\/artikel\/\d+/);
        await expect(page.locator('.article-title')).toBeVisible();
        await expect(page.locator('.article-byline')).toBeVisible();
        await expect(page.locator('.article-body')).toBeVisible();
        await expect(page.locator('#comments')).toBeVisible();

        // Submit comment
        const commentBody = 'Remarkable insights, loved reading this!';
        await page.fill('textarea[name="body"]', commentBody);
        await page.click('button:has-text("Publish response")');

        await expect(page.locator('#comments')).toContainText(commentBody);
    });

    test('authenticated user can write and publish a new story', async ({ page }) => {
        // Log in
        await page.goto('/login');
        await page.fill('input[name="email"]', 'user@example.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');
        await expect(page).toHaveURL(/\/artikel/);

        // Go to write
        await page.click('nav.navlinks a:has-text("Write")');
        await expect(page).toHaveURL(/\/artikel\/create/);
        await expect(page.locator('h1')).toContainText('Write a story.');

        const testTitle = 'E2E Playwright Automated Story ' + Date.now();
        await page.fill('input[name="judul"]', testTitle);
        await page.fill('textarea[name="konten"]', 'Here is a comprehensive narrative created during E2E verification.');
        await page.click('button[type="submit"]');

        // Should redirect to article detail
        await expect(page.locator('.article-title')).toHaveText(testTitle);
    });

    test('admin user can access admin console', async ({ page }) => {
        await page.goto('/login');
        await page.fill('input[name="email"]', 'admin@example.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');

        await page.goto('/admin');
        await expect(page.locator('h1')).toHaveText('Admin Overview');
        await expect(page.locator('aside')).toContainText('Ruang Admin');
    });
});

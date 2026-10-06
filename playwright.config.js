import { defineConfig } from '@playwright/test';

export default defineConfig({
  testDir: './tests/e2e',
  timeout: 30000,
  use: {
    baseURL: 'http://127.0.0.1:8000',
    headless: true,
    launchOptions: {
      executablePath: '/etc/profiles/per-user/lumi/bin/chromium',
      args: ['--no-sandbox', '--disable-setuid-sandbox'],
    },
  },
  webServer: {
    command: 'php artisan serve --port=8000',
    port: 8000,
    reuseExistingServer: false,
  },
});

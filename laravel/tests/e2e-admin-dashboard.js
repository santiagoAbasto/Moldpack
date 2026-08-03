const { chromium } = require('playwright-core');

const baseUrl = process.env.APP_URL || 'http://127.0.0.1:8001';
const chromePath = process.env.CHROME_PATH || '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const adminUser = process.env.ADMIN_USER;
const adminPassword = process.env.ADMIN_PASSWORD;

function assert(condition, message) {
  if (!condition) {
    throw new Error(message);
  }
}

(async () => {
  const browser = await chromium.launch({
    headless: true,
    executablePath: chromePath,
    args: ['--no-sandbox', '--disable-dev-shm-usage'],
  });

  const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });
  const consoleErrors = [];
  const failedRequests = [];

  await page.route('**/favicon.ico', (route) => route.fulfill({ status: 204, body: '' }));

  page.on('console', (message) => {
    if (message.type() === 'error') {
      consoleErrors.push(message.text());
    }
  });

  page.on('requestfailed', (request) => {
    failedRequests.push(`${request.url()} :: ${request.failure() && request.failure().errorText}`);
  });

  const loginResponse = await page.goto(`${baseUrl}/login`, { waitUntil: 'networkidle' });
  assert(loginResponse && loginResponse.status() === 200, 'Login no respondio 200');
  assert(await page.locator('text=Acceso al panel').isVisible(), 'No se ve el titulo del login');
  assert(await page.locator('#username').isVisible(), 'No se ve el campo usuario/correo');
  assert(await page.locator('#password').isVisible(), 'No se ve el campo password');

  const cssResponse = await page.goto(`${baseUrl}/css/admin-dashboard.css`);
  assert(cssResponse && cssResponse.status() === 200, 'CSS del dashboard no respondio 200');

  const jsResponse = await page.goto(`${baseUrl}/js/admin-dashboard.js`);
  assert(jsResponse && jsResponse.status() === 200, 'JS del dashboard no respondio 200');

  let authenticatedDashboard = false;

  if (adminUser && adminPassword) {
    await page.goto(`${baseUrl}/login`, { waitUntil: 'networkidle' });
    await page.fill('#username', adminUser);
    await page.fill('#password', adminPassword);
    await Promise.all([
      page.waitForNavigation({ waitUntil: 'networkidle' }),
      page.click('button[type="submit"]'),
    ]);

    await page.goto(`${baseUrl}/adm`, { waitUntil: 'networkidle' });
    await page.waitForSelector('#admin-dashboard-root', { timeout: 10000 });
    await page.waitForSelector('.react-admin-dashboard', { timeout: 10000 });
    await page.waitForSelector('.recharts-responsive-container', { timeout: 10000 });
    await page.click('.rd-button');
    await page.waitForTimeout(600);

    authenticatedDashboard = true;
  } else {
    const protectedResponse = await page.goto(`${baseUrl}/adm`, { waitUntil: 'networkidle' });
    assert(protectedResponse && protectedResponse.status() === 200, 'Redireccion protegida no termino en pagina 200');
    assert(page.url().includes('/login'), 'El admin sin sesion no redirigio a login');
  }

  assert(consoleErrors.length === 0, `Errores de consola: ${consoleErrors.join(' | ')}`);
  assert(failedRequests.length === 0, `Requests fallidos: ${failedRequests.join(' | ')}`);

  await browser.close();

  console.log(JSON.stringify({
    ok: true,
    authenticatedDashboard,
    login: 'ok',
    assets: 'ok',
    protectedRoutes: authenticatedDashboard ? 'not_checked' : 'ok',
  }, null, 2));
})().catch(async (error) => {
  console.error(error.message);
  process.exit(1);
});

const { test, expect } = require('@playwright/test');
const path = require('path');
const fs = require('fs');
const { execSync } = require('child_process');

const BASE_URL = 'http://localhost:8081';
const TEST_EMAIL = 'playwright@teste.com';
const TEST_PASSWORD = '12345678';

// ─── Helpers ───────────────────────────────────────────────

function ensureTestImages() {
  const dir = '/tmp/playwright-test-images';
  execSync(`mkdir -p ${dir}`);
  const images = [
    { name: 'foto-principal.jpg', color: 'red',   label: 'PRINCIPAL' },
    { name: 'foto-secundaria.jpg', color: 'blue',  label: 'SECUNDARIA' },
    { name: 'foto-terciaria.jpg', color: 'green', label: 'TERCIARIA' },
  ];
  for (const img of images) {
    const filePath = `${dir}/${img.name}`;
    if (!fs.existsSync(filePath)) {
      try {
        execSync(`python3 -c "
from PIL import Image, ImageDraw
img = Image.new('RGB', (600, 800), '${img.color}')
draw = ImageDraw.Draw(img)
draw.text((200, 380), '${img.label}', fill='white')
img.save('${filePath}', 'JPEG', quality=85)
"`);
      } catch {
        fs.writeFileSync(filePath, Buffer.alloc(100));
      }
    }
  }
  return images.map(i => `${dir}/${i.name}`);
}

/**
 * Click age gate confirmation button if it's visible.
 * Laravel encrypts cookies, so we can't set them via JS.
 */
async function dismissAgeGate(page) {
  try {
    const ageGateBtn = page.locator('button:has-text("Tenho 18 anos ou mais"), button:has-text("Confirmar"), button:has-text("Entrar")');
    await ageGateBtn.first().click({ timeout: 3000 });
    await page.waitForTimeout(500);
  } catch {
    // Age gate already dismissed
  }
}

/**
 * Accept cookie consent if visible.
 */
async function acceptCookies(page) {
  try {
    const cookieBtn = page.locator('button:has-text("Aceitar Todos"), button:has-text("Aceitar")');
    await cookieBtn.first().click({ timeout: 2000 });
    await page.waitForTimeout(300);
  } catch {
    // Cookie consent already accepted
  }
}

/**
 * Full dismiss flow: bypass age gate + accept cookies + wait for page to be ready.
 */
async function bypassGateways(page) {
  await page.goto(BASE_URL);
  await page.waitForLoadState('networkidle');
  await dismissAgeGate(page);
  await acceptCookies(page);
}

/**
 * Log in as the test user.
 */
async function login(page) {
  // Dismiss gateways before navigating
  await bypassGateways(page);

  // Go to login page
  await page.goto(`${BASE_URL}/entrar`);
  await page.waitForLoadState('networkidle');

  // Dismiss gateways again if they appear on login page
  await dismissAgeGate(page);
  await acceptCookies(page);

  // Fill login form
  await page.fill('input[name="email"]', TEST_EMAIL);
  await page.fill('input[name="password"]', TEST_PASSWORD);

  // Submit
  await page.click('button[type="submit"]');

  // Wait for redirect away from login page
  await page.waitForFunction(
    () => !window.location.pathname.includes('/entrar') && !window.location.pathname.includes('/login'),
    { timeout: 15000 }
  );
}

/**
 * Helpers to fill profile form correctly.
 */
async function fillProfileForm(page, data) {
  if (data.name) await page.fill('input[name="name"]', data.name);
  if (data.age) await page.fill('input[name="age"]', String(data.age));
  if (data.state) await page.fill('input[name="state"]', data.state);
  if (data.description) await page.fill('textarea[name="description"]', data.description);

  // City is a <select> combobox — DB has broken encoding issues ("SÃ£o" instead of "São")
  // Use ASCII-only trailing substring to match reliably across encoding problems
  if (data.city) {
    await page.evaluate(({ city }) => {
      const sel = document.querySelector('select[name="city"]');
      if (!sel) return;
      const opts = Array.from(sel.options);
      // Use trailing ASCII substring (e.g. "ulo - SP" for "São Paulo - SP")
      const asciiKey = city.slice(-10);
      const matchIdx = opts.findIndex(o => o.text.includes(asciiKey));
      if (matchIdx > 0) {
        sel.selectedIndex = matchIdx;
        sel.dispatchEvent(new Event('change', { bubbles: true }));
      }
    }, { city: data.city });
  }
}

/**
 * Click the correct submit button depending on whether we're creating or editing.
 * Create form has "Criar Perfil", edit form has "Salvar".
 */
async function clickSubmit(page) {
  const createBtn = page.locator('button:has-text("Criar Perfil")');
  const editBtn = page.locator('button:has-text("Salvar")');

  if (await createBtn.isVisible({ timeout: 2000 }).catch(() => false)) {
    await createBtn.click();
  } else if (await editBtn.isVisible({ timeout: 2000 }).catch(() => false)) {
    await editBtn.click();
  } else {
    // Fallback: use visible submit button
    const allSubmits = page.locator('button[type="submit"]');
    const count = await allSubmits.count();
    for (let i = 0; i < count; i++) {
      if (await allSubmits.nth(i).isVisible()) {
        await allSubmits.nth(i).click();
        break;
      }
    }
  }
}

// ─── Tests ─────────────────────────────────────────────────

test.describe('Gerenciamento de Imagens - E2E Playwright', () => {

  let imagePaths;

  test.beforeAll(() => {
    imagePaths = ensureTestImages();
    console.log('Imagens de teste:', imagePaths);
  });

  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('CT01 - Criar perfil com imagem principal', async ({ page }) => {
    await page.goto(`${BASE_URL}/meu-perfil/criar`);
    await page.waitForLoadState('networkidle');

    // If redirected to /editar/{id}, profile already exists from previous runs
    if (page.url().includes('/editar/')) {
      console.log('Perfil já existe (editar mode), pulando CT01');
      test.skip();
      return;
    }

    // Fill form
    await fillProfileForm(page, {
      name: 'João Playwright',
      age: 28,
      city: 'São Paulo - SP',
      state: 'SP',
      description: 'Descrição criada pelo Playwright E2E.',
    });

    // Upload image
    const fileInput = page.locator('input[type="file"]').first();
    await fileInput.setInputFiles(imagePaths[0]);

    // Select first available service by clicking its label
    const firstServiceLabel = page.locator('label.group').first();
    if (await firstServiceLabel.isVisible({ timeout: 2000 }).catch(() => false)) {
      await firstServiceLabel.click();
      console.log('Serviço selecionado');
    }

    // Submit
    await clickSubmit(page);

    // Wait for redirect to /meu-perfil
    await page.waitForFunction(
      () => window.location.pathname.includes('/meu-perfil'),
      { timeout: 15000 }
    );

    // Verify success message
    const successEl = page.locator('text=sucesso').first();
    await expect(successEl).toBeVisible({ timeout: 5000 });
  });

  test('CT02 - Adicionar mais imagens na edição', async ({ page }) => {
    await page.goto(`${BASE_URL}/meu-perfil/criar`);
    await page.waitForLoadState('networkidle');

    // Should be on edit page since profile exists
    expect(page.url()).toContain('/meu-perfil/editar/');

    // Upload 2 more images
    const fileInput = page.locator('input[type="file"]').first();
    await fileInput.setInputFiles([imagePaths[1], imagePaths[2]]);

    // Submit
    await clickSubmit(page);
    await page.waitForFunction(
      () => window.location.pathname.includes('/meu-perfil'),
      { timeout: 15000 }
    );

    // Verify success
    const successEl = page.locator('text=sucesso').first();
    await expect(successEl).toBeVisible({ timeout: 5000 });
  });

  test('CT03 - Alterar imagem principal', async ({ page }) => {
    await page.goto(`${BASE_URL}/meu-perfil/criar`);
    await page.waitForLoadState('networkidle');

    // Look for main image radio buttons
    const radios = page.locator('input[type="radio"]');
    const count = await radios.count();
    console.log(`Radio buttons de imagem encontrados: ${count}`);

    if (count >= 2) {
      await radios.nth(1).click();
    }

    // Submit
    await clickSubmit(page);
    await page.waitForFunction(
      () => window.location.pathname.includes('/meu-perfil'),
      { timeout: 15000 }
    );

    const successEl = page.locator('text=sucesso').first();
    await expect(successEl).toBeVisible({ timeout: 5000 });
  });

  test('CT04 - Remover imagem do perfil', async ({ page }) => {
    await page.goto(`${BASE_URL}/meu-perfil/criar`);
    await page.waitForLoadState('networkidle');

    // Click the delete button (red X) on the first image to remove it
    const deleteBtns = page.locator('button:has(svg path[d="M6 18L18 6M6 6l12 12"])');
    const count = await deleteBtns.count();
    console.log(`Botões de deletar imagem encontrados: ${count}`);

    if (count > 0) {
      await deleteBtns.first().click();
      console.log('Imagem removida via clique no botão X');
    }

    // Submit
    await clickSubmit(page);
    await page.waitForFunction(
      () => window.location.pathname.includes('/meu-perfil'),
      { timeout: 15000 }
    );

    const successEl = page.locator('text=sucesso').first();
    await expect(successEl).toBeVisible({ timeout: 5000 });
  });

  test('CT05 - Perfil público exibe imagens', async ({ page }) => {
    // Go to user's profile page
    await page.goto(`${BASE_URL}/meu-perfil`);
    await page.waitForLoadState('networkidle');

    // Find a link to the public profile
    let publicUrl = null;
    const link = page.locator('a[href*="/perfis/"]').first();
    try {
      await link.waitFor({ state: 'visible', timeout: 3000 });
      publicUrl = await link.getAttribute('href');
    } catch {
      // Try to navigate via slug
      const name = await page.locator('text=João Playwright').first().textContent().catch(() => null);
      if (name) {
        const slug = name.trim().toLowerCase().replace(/\s+/g, '-');
        publicUrl = `/perfis/${slug}`;
      }
    }

    if (publicUrl) {
      const targetUrl = publicUrl.startsWith('http') ? publicUrl : `${BASE_URL}${publicUrl}`;
      await page.goto(targetUrl);
      await page.waitForLoadState('networkidle');

      // Verify "Sobre mim" section
      const sobreMim = page.locator('text=Sobre mim');
      await expect(sobreMim).toBeVisible({ timeout: 5000 });

      // Verify at least one image
      const images = page.locator('img[src*="storage"]');
      const imgCount = await images.count();
      console.log(`Imagens visíveis no perfil público: ${imgCount}`);
      expect(imgCount).toBeGreaterThanOrEqual(1);
    } else {
      console.log('Não foi possível encontrar link para o perfil público');
      test.skip();
    }
  });

  test('CT06 - Rejeitar upload de arquivo não imagem', async ({ page }) => {
    await page.goto(`${BASE_URL}/meu-perfil/criar`);
    await page.waitForLoadState('networkidle');

    // Create a fake PDF
    const pdfPath = '/tmp/playwright-test-images/fake.pdf';
    if (!fs.existsSync(pdfPath)) {
      fs.writeFileSync(pdfPath, '%PDF-1.4 fake content for testing');
    }

    // Upload PDF
    const fileInput = page.locator('input[type="file"]').first();
    await fileInput.setInputFiles(pdfPath);

    // Submit
    await clickSubmit(page);
    await page.waitForLoadState('networkidle');

    // Should either show validation error or stay on same page
    const stillOnEdit = page.url().includes('/meu-perfil/editar/') || page.url().includes('/meu-perfil/criar');
    const hasError = await page.locator('.text-red-500, .text-red-400, [aria-invalid="true"], .invalid-feedback')
      .first().isVisible().catch(() => false);

    expect(stillOnEdit || hasError).toBeTruthy();
    console.log(`Upload de PDF rejeitado. Ainda na página de edição: ${stillOnEdit}, erro visível: ${hasError}`);
  });
});

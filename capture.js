const puppeteer = require('puppeteer');

(async () => {
    const url = process.argv[2];
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto(url);
    await page.screenshot({ path: 'screenshot.png' });
    await browser.close();
})();

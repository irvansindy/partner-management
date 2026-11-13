const assert = require('node:assert/strict');
const { execFileSync } = require('node:child_process');
const path = require('node:path');
const vm = require('node:vm');

async function check(locale) {
    const script = execFileSync('php', [path.join(__dirname, '../Support/render-company-export-script.php'), locale], {
        env: { ...process.env, APP_CONFIG_CACHE: '/tmp/partner-no-config-cache.php' },
        stdio: ['ignore', 'pipe', 'ignore'],
    }).toString();
    let submit;
    let selected = 1;
    let calls = 0;
    let downloads = 0;
    let disabled = false;
    const alerts = [];
    const button = { innerHTML: 'Export', formAction: '/export' };
    const document = {
        getElementById: () => button,
        createElement: () => ({ click() { downloads++; }, remove() {} }),
        body: { appendChild() {} },
    };
    function $(selector) {
        return {
            get length() { return selected; },
            ready(fn) { fn(); }, click() {}, change() {}, text() {},
            on(event, fn) { if (selector === '#exportForm') submit = fn; },
            prop(name, value) { if (name === 'disabled') disabled = value; },
        };
    }
    const context = {
        $, document, FormData: class {},
        Swal: { fire(alert) { alerts.push(alert); } },
        URL: { createObjectURL: () => 'blob:test', revokeObjectURL() {} },
        setTimeout: (fn) => fn(),
        fetch: () => { calls++; return new Promise(resolve => { context.resolveDownload = resolve; }); },
    };
    vm.runInNewContext(script, context);
    const event = { preventDefault() {}, originalEvent: { submitter: button } };
    const form = { action: '/export' };

    selected = 0;
    await submit.call(form, event);
    assert.equal(calls, 0);
    assert.equal(alerts.pop().icon, 'warning');

    selected = 1;
    const pending = submit.call(form, event);
    assert.equal(alerts.length, 0, 'No success before the response arrives');
    assert.equal(disabled, true);
    context.resolveDownload({ ok: true, headers: { get: name => name === 'Content-Type' ? 'application/pdf' : 'attachment; filename="report.pdf"' }, blob: async () => ({}) });
    await pending;
    assert.equal(downloads, 1);
    assert.equal(alerts.pop().icon, 'success');
    assert.equal(disabled, false);
    assert.equal(button.innerHTML, 'Export');

    const invalid = submit.call(form, event);
    context.resolveDownload({ ok: true, headers: { get: () => 'text/html' } });
    await invalid;
    assert.equal(downloads, 1, 'HTML redirects must not become downloads');
    const failure = alerts.pop();
    assert.equal(failure.icon, 'error');
    assert.equal(failure.text, locale === 'id' ? 'Ekspor gagal. Silakan coba kembali.' : 'Export failed. Please try again.');

    context.fetch = async () => { throw new TypeError('Failed to fetch'); };
    await submit.call(form, event);
    assert.equal(alerts.pop().text, failure.text, 'Network errors use the active locale');
    assert.equal(disabled, false);
}

(async () => {
    await check('id');
    await check('en');
    console.log('Company export download: ID/EN validation, pending, success, HTML redirect and network failure passed.');
})().catch(error => { console.error(error); process.exitCode = 1; });

<style>
.pw-shell{display:grid;grid-template-columns:300px 1fr;max-width:880px;margin:8px auto 24px;background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 10px 40px rgba(30,41,59,.10);border:1px solid #e8eaf0}

/* side panel */
.pw-side{position:relative;padding:32px 28px;color:#fff;background:linear-gradient(155deg,#4f46e5 0%,#6d5cf0 55%,#8b5cf6 100%);overflow:hidden}
.pw-side::before,.pw-side::after{content:"";position:absolute;border-radius:50%;background:rgba(255,255,255,.10)}
.pw-side::before{width:220px;height:220px;right:-90px;top:-70px}
.pw-side::after{width:180px;height:180px;left:-70px;bottom:-60px}
.pw-side>*{position:relative;z-index:1}
.pw-badge{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;margin-bottom:20px;backdrop-filter:blur(4px)}
.pw-badge svg{width:28px;height:28px}
.pw-side h2{margin:0 0 6px;font-size:20px;line-height:1.25}
.pw-side p{margin:0 0 22px;font-size:13.5px;line-height:1.5;opacity:.88}
.pw-tips{list-style:none;margin:0;padding:0;display:grid;gap:12px;font-size:13px}
.pw-tips li{display:flex;gap:10px;align-items:flex-start;line-height:1.4}
.pw-tips li span{flex:none;width:20px;height:20px;border-radius:50%;background:rgba(255,255,255,.22);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700}

/* form */
.pw-main{padding:32px 34px}
.pw-main h3{margin:0 0 4px;font-size:18px}
.pw-main .sub{margin:0 0 24px;font-size:13px;color:#64748b}
.fgroup{margin-bottom:18px}
.field-label{display:block;font-size:12px;font-weight:700;letter-spacing:.4px;text-transform:uppercase;color:#334155;margin:0 0 7px}
.req{color:#dc2626}
.pw-wrap{position:relative}
.pw-wrap .lead{position:absolute;left:14px;top:50%;transform:translateY(-50%);width:18px;height:18px;color:#94a3b8;pointer-events:none;transition:color .15s}
.pw-wrap input{width:100%;padding:13px 46px 13px 42px;border:1.5px solid #e2e8f0;border-radius:12px;font:inherit;font-size:14.5px;background:#f8fafc;transition:border-color .15s,box-shadow .15s,background .15s}
.pw-wrap input::placeholder{color:#a0aec0}
.pw-wrap input:hover{border-color:#cbd5e1}
.pw-wrap input:focus{outline:none;border-color:#6366f1;background:#fff;box-shadow:0 0 0 4px rgba(99,102,241,.14)}
.pw-wrap:focus-within .lead{color:#6366f1}
.pw-wrap input.bad{border-color:#ef4444;background:#fef2f2;box-shadow:0 0 0 4px rgba(239,68,68,.10)}
.pw-wrap input.good{border-color:#22c55e}
.eye{position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:0;padding:7px;border-radius:8px;cursor:pointer;color:#94a3b8;display:flex;transition:background .15s,color .15s}
.eye:hover{background:#eef2ff;color:#4f46e5}
.eye.on{color:#4f46e5}
.eye svg{width:19px;height:19px}

/* strength */
.strength{margin:-6px 0 10px}
.seg{display:grid;grid-template-columns:repeat(5,1fr);gap:5px}
.seg i{height:5px;border-radius:99px;background:#e2e8f0;transition:background .2s}
.strength-row{display:flex;justify-content:space-between;align-items:center;margin-top:7px;font-size:12px;color:#64748b;min-height:18px}
.strength-row b{font-weight:700}

/* rule chips */
.rules{list-style:none;margin:0 0 20px;padding:0;display:flex;flex-wrap:wrap;gap:7px}
.rules li{display:inline-flex;align-items:center;gap:6px;padding:5px 11px;border-radius:99px;background:#f1f5f9;color:#64748b;font-size:12px;font-weight:500;transition:all .2s}
.rules li::before{content:"";width:6px;height:6px;border-radius:50%;background:#cbd5e1;transition:background .2s}
.rules li.ok{background:#ecfdf5;color:#047857}
.rules li.ok::before{background:#10b981}

.pw-msg{display:flex;gap:9px;align-items:flex-start;font-size:13px;margin:0 0 16px;padding:11px 14px;border-radius:10px;line-height:1.4}
.pw-msg.err{color:#b91c1c;background:#fef2f2;border:1px solid #fecaca}
.pw-msg.ok{color:#166534;background:#f0fdf4;border:1px solid #bbf7d0}

.pw-actions{display:flex;justify-content:flex-end;gap:10px;padding-top:6px}
.pw-actions .btn{padding:11px 22px;border-radius:11px;font-weight:600}
.pw-actions .btn-primary{box-shadow:0 6px 16px rgba(79,70,229,.30)}
.pw-actions .btn-primary:disabled{opacity:.7;cursor:wait;box-shadow:none}
[hidden]{display:none !important}

@media(max-width:760px){
  .pw-shell{grid-template-columns:1fr;margin:0 0 20px}
  .pw-side{padding:22px 22px}
  .pw-tips{display:none}
  .pw-side p{margin:0}
  .pw-main{padding:24px 20px}
}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Change Password</h1>
      <p class="page-sub">Update your account password</p>
    </div>
  </div>

  <div class="pw-shell">
    <!-- Side panel -->
    <aside class="pw-side">
      <div class="pw-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4 6v6c0 4.5 3.2 8.3 8 9 4.8-.7 8-4.5 8-9V6l-8-3Z"/><path d="m9 12 2 2 4-4"/></svg>
      </div>
      <h2>Keep your account secure</h2>
      <p>Choose a strong password that you don't use anywhere else.</p>
      <ul class="pw-tips">
        <li><span>1</span>Use a mix of letters, numbers and symbols</li>
        <li><span>2</span>Avoid names, birthdays and common words</li>
        <li><span>3</span>Never share your password with anyone</li>
      </ul>
    </aside>

    <!-- Form -->
    <form id="pwForm" class="pw-main" novalidate autocomplete="off">
      <h3>Password details</h3>
      <p class="sub">Enter your current password, then choose a new one.</p>

      <div class="fgroup">
        <label class="field-label" for="curPw">Current Password <span class="req">*</span></label>
        <div class="pw-wrap">
          <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
          <input type="password" id="curPw" placeholder="Enter current password" autocomplete="current-password">
          <button type="button" class="eye" data-eye="curPw" aria-label="Show password"></button>
        </div>
      </div>

      <div class="fgroup">
        <label class="field-label" for="newPw">New Password <span class="req">*</span></label>
        <div class="pw-wrap">
          <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="15" r="4"/><path d="m11 12 9-9M16 7l3 3M14 9l2 2"/></svg>
          <input type="password" id="newPw" placeholder="Enter new password" autocomplete="new-password">
          <button type="button" class="eye" data-eye="newPw" aria-label="Show password"></button>
        </div>
      </div>

      <div class="strength">
        <div class="seg" id="seg"><i></i><i></i><i></i><i></i><i></i></div>
        <div class="strength-row"><span id="strengthText">Enter a password to see its strength</span></div>
      </div>

      <ul class="rules" id="rules">
        <li data-rule="len">8+ characters</li>
        <li data-rule="upper">Uppercase</li>
        <li data-rule="lower">Lowercase</li>
        <li data-rule="num">Number</li>
        <li data-rule="sym">Special character</li>
      </ul>

      <div class="fgroup">
        <label class="field-label" for="cfmPw">Confirm New Password <span class="req">*</span></label>
        <div class="pw-wrap">
          <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4 6v6c0 4.5 3.2 8.3 8 9 4.8-.7 8-4.5 8-9V6l-8-3Z"/></svg>
          <input type="password" id="cfmPw" placeholder="Re-enter new password" autocomplete="new-password">
          <button type="button" class="eye" data-eye="cfmPw" aria-label="Show password"></button>
        </div>
      </div>

      <p class="pw-msg" id="pwMsg" hidden></p>

      <div class="pw-actions">
        <button type="reset" class="btn">Clear</button>
        <button type="submit" class="btn btn-primary" id="saveBtn">Update Password</button>
      </div>
    </form>
  </div>
</main>

<script>
(function () {
  const $ = id => document.getElementById(id);
  const form = $('pwForm'), msg = $('pwMsg');
  const cur = $('curPw'), nw = $('newPw'), cfm = $('cfmPw');

  const EYE = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>';
  const EYE_OFF = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.9 17.9A10.9 10.9 0 0 1 12 20C5 20 1 12 1 12a18.5 18.5 0 0 1 5.1-5.9M9.9 4.2A10.7 10.7 0 0 1 12 4c7 0 11 8 11 8a18.6 18.6 0 0 1-2.2 3.2M1 1l22 22"/><path d="M14.1 14.1a3 3 0 1 1-4.2-4.2"/></svg>';
  form.querySelectorAll('.eye').forEach(b => b.innerHTML = EYE);

  const RULES = {
    len:   v => v.length >= 8,
    upper: v => /[A-Z]/.test(v),
    lower: v => /[a-z]/.test(v),
    num:   v => /\d/.test(v),
    sym:   v => /[^A-Za-z0-9]/.test(v)
  };
  const LEVELS = [
    { t: 'Enter a password to see its strength', c: '#e2e8f0' },
    { t: 'Very weak', c: '#ef4444' },
    { t: 'Weak',      c: '#f97316' },
    { t: 'Fair',      c: '#eab308' },
    { t: 'Good',      c: '#22c55e' },
    { t: 'Strong',    c: '#15803d' }
  ];

  const showMsg = (text, type) => {
    msg.innerHTML = (type === 'ok' ? '✓ ' : '⚠ ') + text.replace(/</g, '&lt;');
    msg.className = 'pw-msg ' + type;
    msg.hidden = false;
  };
  const clearMsg = () => { msg.hidden = true; };

  function updateStrength() {
    const v = nw.value;
    let score = 0;
    Object.keys(RULES).forEach(k => {
      const ok = RULES[k](v);
      if (ok) score++;
      document.querySelector('[data-rule="' + k + '"]').classList.toggle('ok', ok);
    });
    const lvl = v ? Math.max(score, 1) : 0;
    const lv = LEVELS[lvl];
    $('seg').querySelectorAll('i').forEach((s, i) => { s.style.background = i < lvl ? lv.c : '#e2e8f0'; });
    $('strengthText').innerHTML = v ? 'Strength: <b style="color:' + lv.c + '">' + lv.t + '</b>' : lv.t;
  }

  function checkMatch() {
    cfm.classList.toggle('good', !!cfm.value && cfm.value === nw.value);
  }

  form.querySelectorAll('[data-eye]').forEach(btn => {
    btn.addEventListener('click', () => {
      const inp = $(btn.dataset.eye);
      const show = inp.type === 'password';
      inp.type = show ? 'text' : 'password';
      btn.innerHTML = show ? EYE_OFF : EYE;
      btn.classList.toggle('on', show);
      btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });
  });

  nw.addEventListener('input', () => { updateStrength(); checkMatch(); clearMsg(); nw.classList.remove('bad'); });
  cfm.addEventListener('input', () => { checkMatch(); clearMsg(); cfm.classList.remove('bad'); });
  cur.addEventListener('input', () => { clearMsg(); cur.classList.remove('bad'); });

  form.addEventListener('reset', () => {
    setTimeout(() => {
      [cur, nw, cfm].forEach(i => { i.classList.remove('bad', 'good'); i.type = 'password'; });
      form.querySelectorAll('.eye').forEach(b => { b.innerHTML = EYE; b.classList.remove('on'); });
      updateStrength();
      clearMsg();
    });
  });

  form.addEventListener('submit', e => {
    e.preventDefault();
    [cur, nw, cfm].forEach(i => i.classList.remove('bad'));
    clearMsg();
    const fail = (el, text) => { el.classList.add('bad'); el.focus(); showMsg(text, 'err'); };

    if (!cur.value) return fail(cur, 'Please enter your current password.');
    if (!nw.value) return fail(nw, 'Please enter a new password.');
    if (Object.keys(RULES).some(k => !RULES[k](nw.value))) return fail(nw, 'New password does not meet all the rules.');
    if (nw.value === cur.value) return fail(nw, 'New password must be different from the current password.');
    if (!cfm.value) return fail(cfm, 'Please confirm your new password.');
    if (cfm.value !== nw.value) return fail(cfm, 'New password and confirm password do not match.');

    const btn = $('saveBtn');
    btn.disabled = true;
    btn.textContent = 'Updating…';

    // TODO: send to your backend (the server must verify the current password), e.g.
    // fetch('/api/change-password', {
    //   method: 'POST',
    //   headers: { 'Content-Type': 'application/json' },
    //   body: JSON.stringify({ current_password: cur.value, new_password: nw.value, new_password_confirmation: cfm.value })
    // }).then(r => r.json()).then(res => { ...show success or error from res... });

    setTimeout(() => {          // demo only: remove when the real API is connected
      btn.disabled = false;
      btn.textContent = 'Update Password';
      form.reset();
      showMsg('Password updated successfully.', 'ok');
    }, 600);
  });

  updateStrength();
})();
</script>
const DEMO_CURRENT_PASSWORD = 'Radha@2026';

function togglePw(inputId, btn) {
  const input = document.getElementById(inputId);
  const isHidden = input.type === 'password';
  input.type = isHidden ? 'text' : 'password';
  btn.innerHTML = isHidden
    ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
    : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
}

function setCheck(id, met) {
  document.getElementById(id).classList.toggle('met', met);
}

function getPasswordRules(pw, confirm) {
  return {
    length: pw.length >= 8,
    upper: /[A-Z]/.test(pw),
    lower: /[a-z]/.test(pw),
    number: /[0-9]/.test(pw),
    special: /[^A-Za-z0-9]/.test(pw),
    match: pw.length > 0 && pw === confirm
  };
}

function checkPasswordStrength() {
  const pw = document.getElementById('newPassword').value;
  const confirm = document.getElementById('confirmPassword').value;
  const rules = getPasswordRules(pw, confirm);

  setCheck('chk-length', rules.length);
  setCheck('chk-upper', rules.upper);
  setCheck('chk-lower', rules.lower);
  setCheck('chk-number', rules.number);
  setCheck('chk-special', rules.special);
  setCheck('chk-match', rules.match);

  let score = 0;
  if (rules.length) score++;
  if (rules.upper) score++;
  if (rules.lower) score++;
  if (rules.number) score++;
  if (rules.special) score++;

  const labels = ['', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
  const classes = ['', 'weak', 'fair', 'good', 'strong', 'strong'];
  const label = pw.length ? labels[score] : 'â€”';
  const cls = pw.length ? classes[score] : '';

  document.getElementById('strengthText').textContent = label;
  document.getElementById('strengthText').style.color =
    score <= 1 ? '#e74c3c' : score === 2 ? '#f39c12' : score === 3 ? '#f1c40f' : '#27ae60';

  for (let i = 1; i <= 4; i++) {
    const seg = document.getElementById('seg' + i);
    seg.className = 'pw-strength-seg';
    if (score >= i) seg.classList.add('on', cls || 'weak');
  }

  checkPasswordMatch();
}

function checkPasswordMatch() {
  const pw = document.getElementById('newPassword').value;
  const confirm = document.getElementById('confirmPassword').value;
  const confirmInput = document.getElementById('confirmPassword');

  setCheck('chk-match', pw.length > 0 && pw === confirm);

  if (confirm.length > 0) {
    confirmInput.classList.toggle('error', pw !== confirm);
    confirmInput.classList.toggle('success', pw === confirm && pw.length > 0);
  } else {
    confirmInput.classList.remove('error', 'success');
  }
}

function allRulesMet() {
  const pw = document.getElementById('newPassword').value;
  const confirm = document.getElementById('confirmPassword').value;
  const r = getPasswordRules(pw, confirm);
  return r.length && r.upper && r.lower && r.number && r.special && r.match;
}

function submitPasswordChange(e) {
  e.preventDefault();

  const current = document.getElementById('currentPassword').value;
  const currentInput = document.getElementById('currentPassword');

  if (current !== DEMO_CURRENT_PASSWORD) {
    currentInput.classList.add('error');
    showToast('Current password is incorrect. (Demo: Radha@2026)', 'red');
    return;
  }
  currentInput.classList.remove('error');

  if (!allRulesMet()) {
    showToast('Please meet all password requirements.', 'red');
    return;
  }

  const newPw = document.getElementById('newPassword').value;
  if (newPw === current) {
    showToast('New password must be different from current password.', 'red');
    return;
  }

  document.getElementById('pwSuccessBanner').classList.add('show');
  showToast('Password changed successfully!', 'green');
  document.getElementById('changePasswordForm').reset();
  checkPasswordStrength();
  setTimeout(() => document.getElementById('pwSuccessBanner').classList.remove('show'), 6000);
}

function resetPasswordForm() {
  document.getElementById('changePasswordForm').reset();
  document.getElementById('pwSuccessBanner').classList.remove('show');
  document.getElementById('currentPassword').classList.remove('error');
  document.getElementById('confirmPassword').classList.remove('error', 'success');
  checkPasswordStrength();
  showToast('Form cleared.', 'gold');
}

function initChangePasswordPage() {
  checkPasswordStrength();
}

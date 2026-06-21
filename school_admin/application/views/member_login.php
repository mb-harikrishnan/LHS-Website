<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>School — Login</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --gold: #B8962E;
    --gold-light: #D4AF5A;
    --gold-pale: #F5EDD6;
    --green-deep: #1B3A2D;
    --green-mid: #2D5A40;
    --green-soft: #4A7C59;
    --green-pale: #EBF3EE;
    --cream: #FAFAF7;
    --white: #FFFFFF;
    --text-dark: #1A2820;
    --text-mid: #4A5C52;
    --text-light: #8A9E92;
    --border: #D8E8DC;
    --shadow: rgba(27, 58, 45, 0.08);
  }

  html, body {
    height: 100%;
    font-family: 'DM Sans', sans-serif;
    background: var(--cream);
    color: var(--text-dark);
    overflow-x: hidden;
  }

  /* ── LAYOUT ── */
  .page {
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1fr 1fr;
  }

  /* ── LEFT PANEL ── */
  .left {
    position: relative;
    background: var(--green-deep);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 56px 52px;
    overflow: hidden;
  }

  .left::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
      radial-gradient(ellipse 60% 50% at 20% 80%, rgba(184,150,46,0.18) 0%, transparent 70%),
      radial-gradient(ellipse 50% 60% at 80% 20%, rgba(74,124,89,0.25) 0%, transparent 70%);
    pointer-events: none;
  }

  /* Decorative dots */
  .dots {
    position: absolute;
    width: 100%; height: 100%;
    top: 0; left: 0;
    pointer-events: none;
  }
  .dot {
    position: absolute;
    border-radius: 50%;
    background: rgba(184,150,46,0.35);
    animation: pulse 4s ease-in-out infinite;
  }
  .dot:nth-child(1) { width: 4px; height: 4px; top: 18%; left: 15%; animation-delay: 0s; }
  .dot:nth-child(2) { width: 3px; height: 3px; top: 35%; left: 80%; animation-delay: 1s; }
  .dot:nth-child(3) { width: 5px; height: 5px; top: 65%; left: 25%; animation-delay: 2s; }
  .dot:nth-child(4) { width: 3px; height: 3px; top: 82%; left: 70%; animation-delay: 0.5s; }
  .dot:nth-child(5) { width: 4px; height: 4px; top: 50%; left: 90%; animation-delay: 1.5s; }

  @keyframes pulse {
    0%, 100% { opacity: 0.3; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.8); }
  }

  /* Decorative ring */
  .ring {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(184,150,46,0.15);
  }
  .ring-1 { width: 320px; height: 320px; bottom: -80px; right: -80px; }
  .ring-2 { width: 200px; height: 200px; bottom: -20px; right: -20px; border-color: rgba(184,150,46,0.25); }
  .ring-3 { width: 480px; height: 480px; top: -160px; left: -160px; }

  .left-logo { position: relative; z-index: 2; }

  .logo-mark {
    width: 56px; height: 56px;
    background: var(--white);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 20px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.2);
  }
  .logo-mark svg { width: 32px; height: 32px; }

  .brand-name {
    font-family: 'Cormorant Garamond', serif;
    font-size: 13px;
    font-weight: 400;
    letter-spacing: 0.28em;
    color: var(--gold-light);
    text-transform: uppercase;
    margin-bottom: 4px;
  }
  .brand-sub {
    font-size: 10px;
    letter-spacing: 0.2em;
    color: rgba(255,255,255,0.35);
    text-transform: uppercase;
  }

  .left-hero { position: relative; z-index: 2; }

  .left-hero h1 {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(36px, 4vw, 52px);
    font-weight: 300;
    line-height: 1.15;
    color: var(--white);
    margin-bottom: 20px;
  }
  .left-hero h1 em {
    font-style: italic;
    color: var(--gold-light);
  }

  .left-hero p {
    font-size: 13.5px;
    line-height: 1.7;
    color: rgba(255,255,255,0.55);
    max-width: 300px;
    margin-bottom: 36px;
  }

  .pillars {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }
  .pillar {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .pillar-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--gold);
    flex-shrink: 0;
  }
  .pillar span {
    font-size: 11px;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.5);
  }

  .left-footer {
    position: relative; z-index: 2;
    font-size: 11px;
    color: rgba(255,255,255,0.25);
    letter-spacing: 0.05em;
  }

  /* ── RIGHT PANEL ── */
  .right {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 40px;
    background: var(--white);
    position: relative;
  }

  .right::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 4px;
    background: linear-gradient(90deg, var(--green-deep), var(--green-soft), var(--gold));
  }

  /* Top-right decorative element */
  .corner-deco {
    position: absolute;
    top: 40px; right: 40px;
    width: 80px; height: 80px;
    border-top: 1px solid var(--border);
    border-right: 1px solid var(--border);
    border-radius: 0 12px 0 0;
    opacity: 0.5;
  }
  .corner-deco-bl {
    position: absolute;
    bottom: 40px; left: 40px;
    width: 60px; height: 60px;
    border-bottom: 1px solid var(--border);
    border-left: 1px solid var(--border);
    border-radius: 0 0 0 12px;
    opacity: 0.5;
  }

  .login-card {
    width: 100%;
    max-width: 380px;
    animation: fadeUp 0.7s ease both;
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(24px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .login-eyebrow {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 28px;
  }
  .eyebrow-line {
    height: 1px;
    width: 32px;
    background: var(--gold);
  }
  .eyebrow-text {
    font-size: 10px;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: var(--gold);
    font-weight: 500;
  }

  .login-heading {
    font-family: 'Cormorant Garamond', serif;
    font-size: 38px;
    font-weight: 300;
    line-height: 1.1;
    color: var(--text-dark);
    margin-bottom: 8px;
  }
  .login-heading span { color: var(--green-deep); font-weight: 600; }

  .login-sub {
    font-size: 13px;
    color: var(--text-light);
    margin-bottom: 40px;
    line-height: 1.5;
  }

  /* FORM */
  .field-group {
    margin-bottom: 20px;
    position: relative;
    animation: fadeUp 0.7s ease both;
  }
  .field-group:nth-child(1) { animation-delay: 0.1s; }
  .field-group:nth-child(2) { animation-delay: 0.2s; }

  .field-label {
    display: block;
    font-size: 10.5px;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: var(--text-mid);
    margin-bottom: 8px;
    font-weight: 500;
  }

  .field-wrap {
    position: relative;
  }

  .field-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    transition: color 0.2s;
    display: flex;
  }

  input[type="text"],
  input[type="password"] {
    width: 100%;
    height: 52px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    background: var(--cream);
    padding: 0 48px 0 46px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    color: var(--text-dark);
    outline: none;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
  }

  input[type="text"]:focus,
  input[type="password"]:focus {
    border-color: var(--green-soft);
    background: var(--white);
    box-shadow: 0 0 0 3px rgba(74,124,89,0.08);
  }

  input[type="text"]:focus ~ .field-icon,
  input[type="password"]:focus ~ .field-icon {
    color: var(--green-soft);
  }

  .field-wrap:focus-within .field-icon { color: var(--green-soft); }

  .eye-toggle {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: var(--text-light);
    display: flex;
    padding: 4px;
    transition: color 0.2s;
  }
  .eye-toggle:hover { color: var(--green-deep); }

  /* Floating label trick on focus */
  input::placeholder { color: var(--text-light); font-size: 13px; }

  .field-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
  }

  .remember-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
  }
  .remember-wrap input[type="checkbox"] { display: none; }
  .check-box {
    width: 16px; height: 16px;
    border: 1.5px solid var(--border);
    border-radius: 4px;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
    background: var(--white);
  }
  .remember-wrap input:checked + .check-box {
    background: var(--green-deep);
    border-color: var(--green-deep);
  }
  .check-mark {
    display: none;
    color: white;
  }
  .remember-wrap input:checked ~ * .check-mark { display: block; }
  .remember-label {
    font-size: 12px;
    color: var(--text-mid);
    user-select: none;
  }

  .forgot-link {
    font-size: 12px;
    color: var(--green-soft);
    text-decoration: none;
    transition: color 0.2s;
  }
  .forgot-link:hover { color: var(--green-deep); }

  /* LOGIN BUTTON */
  .btn-login {
    width: 100%;
    height: 52px;
    background: var(--green-deep);
    color: var(--white);
    border: none;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    cursor: pointer;
    margin-top: 28px;
    position: relative;
    overflow: hidden;
    transition: transform 0.15s, box-shadow 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    animation: fadeUp 0.7s 0.3s ease both;
  }

  .btn-login::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(184,150,46,0.25) 0%, transparent 60%);
    opacity: 0;
    transition: opacity 0.3s;
  }

  .btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(27,58,45,0.28);
  }
  .btn-login:hover::before { opacity: 1; }
  .btn-login:active { transform: translateY(0); }

  .btn-arrow {
    width: 18px; height: 18px;
    border: 1px solid rgba(255,255,255,0.4);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    transition: transform 0.2s;
  }
  .btn-login:hover .btn-arrow { transform: translateX(4px); }

  /* DIVIDER */
  .divider {
    display: flex;
    align-items: center;
    gap: 14px;
    margin: 24px 0;
    animation: fadeUp 0.7s 0.35s ease both;
  }
  .divider-line { flex: 1; height: 1px; background: var(--border); }
  .divider-text { font-size: 11px; color: var(--text-light); letter-spacing: 0.1em; }

  /* GOOGLE BUTTON */
  .btn-google {
    width: 100%;
    height: 48px;
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    color: var(--text-mid);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    gap: 10px;
    transition: border-color 0.2s, box-shadow 0.2s;
    animation: fadeUp 0.7s 0.4s ease both;
  }
  .btn-google:hover {
    border-color: var(--green-soft);
    box-shadow: 0 4px 16px var(--shadow);
  }

  .signup-row {
    text-align: center;
    margin-top: 28px;
    font-size: 12.5px;
    color: var(--text-light);
    animation: fadeUp 0.7s 0.45s ease both;
  }
  .signup-row a {
    color: var(--green-deep);
    text-decoration: none;
    font-weight: 500;
    margin-left: 4px;
  }
  .signup-row a:hover { color: var(--gold); }

  /* TRUST BADGES */
  .trust-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    margin-top: 36px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
    animation: fadeUp 0.7s 0.5s ease both;
  }
  .trust-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 10.5px;
    color: var(--text-light);
    letter-spacing: 0.04em;
  }
  .trust-item svg { color: var(--green-soft); }

  /* RIPPLE */
  .ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    transform: scale(0);
    animation: ripple-anim 0.6s linear;
    pointer-events: none;
  }
  @keyframes ripple-anim {
    to { transform: scale(4); opacity: 0; }
  }

  /* RESPONSIVE */
  @media (max-width: 768px) {
    .page { grid-template-columns: 1fr; }
    .left { display: none; }
    .right { padding: 40px 24px; }
    .right::before { display: none; }
    .corner-deco, .corner-deco-bl { display: none; }
  }
  .error{
    color:red;
  }
</style>
</head>
<body>

<div class="page">

  <!-- LEFT PANEL -->
  <div class="left">
    <div class="dots">
      <div class="dot"></div><div class="dot"></div><div class="dot"></div>
      <div class="dot"></div><div class="dot"></div>
    </div>
    <div class="ring ring-1"></div>
    <div class="ring ring-2"></div>
    <div class="ring ring-3"></div>

    <div class="left-logo">
      <div class="logo-mark">
        <img 
            src="<?php echo base_url('assets/logos/school_logo.png'); ?>" 
            alt="School Logo"
            class="school-logo"
        >
    </div>
      <div class="brand-name">Little Hearts School</div>
      <div class="brand-sub">AFFILIATED TO CBSE, NEW DELHI NO.930601, KIZHAKKEPRAM, N. PARAVUR - 683 513</div>
    </div>

    <div class="left-hero">
      <h1>Grow Together,<br><em>Grow Beyond.</em></h1>
      <p>Building bright minds and kind hearts — one child at a time. Welcome to a future filled with learning, creativity, and care..</p>
      <div class="pillars">
        <div class="pillar"><div class="pillar-dot"></div><span>Future Learning</span></div>
        <div class="pillar"><div class="pillar-dot"></div><span>Future Confidence</span></div>
        <div class="pillar"><div class="pillar-dot"></div><span>Future Success</span></div>
      </div>
    </div>

    <div class="left-footer">© 2025 Little Hearts School </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="right">
    <div class="corner-deco"></div>
    <div class="corner-deco-bl"></div>

    <form method="post" id="loginForm" action="<?php echo base_url('member_login_check') ;?>" >

      <div class="login-card">

        <div class="login-eyebrow">
          <div class="eyebrow-line"></div>
          <span class="eyebrow-text">Member Portal</span>
        </div>

        <h1 class="login-heading">Welcome<br><span>Back</span></h1>
        <p class="login-sub">Sign in to access your growth dashboard</p>

        <!-- USERNAME -->
        <div class="field-group">
          <label class="field-label" for="username">Username</label>
          <div class="field-wrap">
            <input type="text" id="username" name="username" placeholder="Enter your username or email" autocomplete="username">
            <span class="field-icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
            </span>
          </div>
        </div>

        <!-- PASSWORD -->
        <div class="field-group">
          <label class="field-label" for="password">Password</label>
          <div class="field-wrap">
            <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password">
            <span class="field-icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
            </span>
            <button class="eye-toggle" id="eyeToggle" type="button" aria-label="Toggle password">
              <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
          <div class="field-meta">
            <label class="remember-wrap">
              <input type="checkbox" id="remember">
              <div class="check-box">
                <svg class="check-mark" width="10" height="10" viewBox="0 0 12 12" fill="none">
                  <path d="M2 6l3 3 5-5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <span class="remember-label">Remember me</span>
            </label>
            <a href="#" class="forgot-link">Forgot password?</a>
          </div>
        </div>

        <!-- LOGIN BUTTON -->
        <button class="btn-login" id="loginBtn" type="submit">
          <span>Sign In</span>
          <div class="btn-arrow">
            <svg width="10" height="10" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <path d="M2 6h8M6 2l4 4-4 4"/>
            </svg>
          </div>
        </button>

        <!-- DIVIDER -->
        <div class="divider">
          <div class="divider-line"></div>
          <span class="divider-text">or continue with</span>
          <div class="divider-line"></div>
        </div>

      

        <div class="signup-row">
          Don't have an account?<a href="<?php echo base_url('registration') ; ?>">Request Access</a>
        </div>

        <!-- TRUST BADGES -->
        <div class="trust-row">
          <div class="trust-item">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Secure Login
          </div>
          <div class="trust-item">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            256-bit Encrypted
          </div>
          <div class="trust-item">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            DPDP Compliant
          </div>
        </div>

      </div>

    </form>
  </div>
</div>



<script>
  // Eye toggle
  const eyeToggle = document.getElementById('eyeToggle');
  const pwdInput = document.getElementById('password');
  const eyeIcon = document.getElementById('eyeIcon');
  let pwdVisible = false;

  eyeToggle.addEventListener('click', () => {
    pwdVisible = !pwdVisible;
    pwdInput.type = pwdVisible ? 'text' : 'password';
    eyeIcon.innerHTML = pwdVisible
      ? `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`
      : `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
  });

  // Checkbox
  document.getElementById('remember').addEventListener('change', function() {
    const box = this.nextElementSibling;
    const mark = box.querySelector('.check-mark');
    mark.style.display = this.checked ? 'block' : 'none';
  });

  // Ripple on button
  document.getElementById('loginBtn').addEventListener('click', function(e) {
    const btn = this;
    const circle = document.createElement('span');
    const diameter = Math.max(btn.clientWidth, btn.clientHeight);
    const radius = diameter / 2;
    const rect = btn.getBoundingClientRect();
    circle.className = 'ripple';
    circle.style.width = circle.style.height = `${diameter}px`;
    circle.style.left = `${e.clientX - rect.left - radius}px`;
    circle.style.top = `${e.clientY - rect.top - radius}px`;
    const existing = btn.querySelector('.ripple');
    if (existing) existing.remove();
    btn.appendChild(circle);
  });

  // Input focus label lift effect
  document.querySelectorAll('input[type="text"], input[type="password"]').forEach(input => {
    input.addEventListener('focus', () => {
      input.closest('.field-wrap').querySelector('.field-icon').style.color = 'var(--green-soft)';
    });
    input.addEventListener('blur', () => {
      input.closest('.field-wrap').querySelector('.field-icon').style.color = '';
    });
  });
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>


  <script>

$("#loginForm").validate({

    rules:
    {
        username:
        {
            required:true,

            remote:
            {
                url:"<?php echo base_url('check_username'); ?>",
                type:"post",
                data:
                {
                    username:function()
                    {
                        return $("#username").val();
                    }
                }
            }
        },

        password:
        {
            required:true,

            remote:
            {
                url:"<?php echo base_url('check_password'); ?>",
                type:"post",
                data:
                {
                    username:function()
                    {
                        return $("#username").val();
                    },

                    password:function()
                    {
                        return $("#password").val();
                    }
                }
            }
        }
    },

    messages:
    {
        username:
        {
            required:"Please enter username",
            remote:"Username does not exist"
        },

        password:
        {
            required:"Please enter password",
            remote:"Wrong password"
        }
    }

});

</script>







</body>
</html>

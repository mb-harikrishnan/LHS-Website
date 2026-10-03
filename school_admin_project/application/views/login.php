<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In — Little Hearts</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
  <style>
    /* login-specific layout */
    .login-wrap{min-height:100vh;display:grid;grid-template-columns:1.05fr 1fr}
    .login-visual{position:relative;overflow:hidden;background:linear-gradient(150deg,#111a34 0%,#1c2b57 55%,#312e81 100%);color:#fff;display:flex;flex-direction:column;justify-content:space-between;padding:44px}
    .login-visual::before{content:'';position:absolute;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.45),transparent 70%);top:-120px;right:-100px}
    .login-visual::after{content:'';position:absolute;width:360px;height:360px;border-radius:50%;background:radial-gradient(circle,rgba(56,189,248,.28),transparent 70%);bottom:-120px;left:-80px}
    .login-brand{display:flex;align-items:center;gap:12px;font-family:var(--font-display);font-size:20px;font-weight:600;position:relative}
    .login-brand .brand-mark{background:rgba(255,255,255,.14)}
    .login-art{position:relative;margin:auto 0;padding:40px 0}
    .login-art svg{width:100%;max-width:420px;height:auto;display:block}
    .login-quote{position:relative;font-family:var(--font-display);font-size:24px;font-weight:500;line-height:1.45;max-width:420px}
    .login-quote small{display:block;font-family:var(--font-sans);font-size:13px;font-weight:400;color:rgba(255,255,255,.65);margin-top:10px}
    .login-form-col{display:flex;align-items:center;justify-content:center;padding:32px;background:var(--canvas)}
    .login-card{width:100%;max-width:410px;background:#fff;border:1px solid var(--line);border-radius:24px;box-shadow:var(--shadow-lift);padding:36px 34px}
    .login-card h1{font-size:26px;margin:0 0 6px}
    .login-card .muted{margin:0 0 22px}
    .role-tabs{display:grid;grid-template-columns:1fr 1fr;background:var(--canvas);border:1px solid var(--line);border-radius:14px;padding:4px;gap:4px;margin-bottom:22px}
    .role-tab{border:0;background:transparent;padding:9px 10px;border-radius:10px;font:600 14px var(--font-display);color:var(--muted);cursor:pointer;transition:all .18s}
    .role-tab.active{background:#fff;color:var(--brand-700);box-shadow:var(--shadow-soft)}
    .demo-pill{margin-top:20px;background:var(--brand-50);border:1px dashed var(--brand-200);border-radius:14px;padding:12px 16px;font-size:12.5px;color:var(--brand-800);display:flex;gap:10px;align-items:flex-start}
    .demo-pill .ic{flex:none;margin-top:1px}
    @media (max-width:900px){.login-wrap{grid-template-columns:1fr}.login-visual{display:none}.login-form-col{min-height:100vh}}
   
/* ---------- error styles ---------- */
:root{
  --err:#dc2626;
  --err-bg:#fef2f2;
  --err-line:#fecaca;
}

/* red border + soft ring on invalid input */
.field input.is-invalid{
  border-color:var(--err);
  background:#fffafa;
}
.field input.is-invalid:focus{
  border-color:var(--err);
  box-shadow:0 0 0 4px rgba(220,38,38,.12);
}

/* password wrapper turns red too */
.pw-wrap:has(input.is-invalid) .pw-toggle{color:var(--err)}

/* field message under the input (the <small> jQuery Validate creates) */
small.field-error{
  display:flex;
  align-items:center;
  gap:6px;
  margin-top:6px;
  font-size:12.5px;
  font-weight:500;
  line-height:1.3;
  color:var(--err);
  animation:errIn .18s ease-out;
}
small.field-error::before{
  content:'!';
  flex:none;
  width:14px;
  height:14px;
  border-radius:50%;
  background:var(--err);
  color:#fff;
  font-size:10px;
  font-weight:700;
  display:inline-flex;
  align-items:center;
  justify-content:center;
}

/* server error box under the Sign In button */
.login-error{
  margin:14px 0 0;
  padding:10px 14px;
  background:var(--err-bg);
  border:1px solid var(--err-line);
  border-radius:12px;
  color:var(--err);
  font-size:13px;
  font-weight:500;
  text-align:center;
  animation:errShake .35s ease;
}
.login-error[hidden]{display:none}

@keyframes errIn{
  from{opacity:0;transform:translateY(-3px)}
  to{opacity:1;transform:none}
}
@keyframes errShake{
  0%,100%{transform:translateX(0)}
  20%{transform:translateX(-6px)}
  40%{transform:translateX(6px)}
  60%{transform:translateX(-4px)}
  80%{transform:translateX(3px)}
}
  
  </style>
</head>
<body data-page="login">
  <div class="login-wrap">
    <div class="login-visual">
      <div class="login-brand">
        <span class="brand-mark">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/></svg>
        </span>
        Little Hearts
      </div>
      <div class="login-art">
        <svg viewBox="0 0 420 300" fill="none" aria-hidden="true">
          <circle cx="210" cy="150" r="130" stroke="rgba(255,255,255,.12)" stroke-width="1.5"/>
          <circle cx="210" cy="150" r="90" stroke="rgba(255,255,255,.18)" stroke-width="1.5" stroke-dasharray="4 6"/>
          <rect x="120" y="130" width="180" height="90" rx="12" fill="rgba(255,255,255,.1)"/>
          <rect x="136" y="150" width="60" height="10" rx="5" fill="rgba(255,255,255,.35)"/>
          <rect x="136" y="170" width="100" height="10" rx="5" fill="rgba(255,255,255,.22)"/>
          <rect x="136" y="190" width="80" height="10" rx="5" fill="rgba(255,255,255,.22)"/>
          <circle cx="272" cy="164" r="16" fill="#818cf8"/>
          <path d="M264 164l6 6 10-12" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
          <circle cx="90" cy="80" r="5" fill="rgba(255,255,255,.6)"/>
          <circle cx="340" cy="70" r="7" fill="rgba(255,255,255,.35)"/>
          <circle cx="60" cy="230" r="6" fill="rgba(255,255,255,.3)"/>
          <circle cx="356" cy="236" r="5" fill="rgba(255,255,255,.55)"/>
          <path d="M210 20v18M210 262v18M38 150h18M364 150h18" stroke="rgba(255,255,255,.25)" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </div>
      <p class="login-quote">“Nurturing minds, building futures.”<small>Admin & Faculty Portal — Academic Year 2025–2026</small></p>
    </div>

    <div class="login-form-col">
      <div class="login-card">
        <h1>Welcome back 👋</h1>
        <p class="muted">Sign in to the school management panel.</p>

       
        <form id="login-form" novalidate>
          <div class="field">
            <label class="field-label" for="username">Username</label>
            <input type="text" id="username" name="username" autocomplete="username" placeholder="Enter username">
          </div>
          <div class="field">
            <label class="field-label" for="password">Password</label>
            <div class="pw-wrap">
              <input type="password" id="password" name="password" autocomplete="current-password" placeholder="Enter password">
              <button type="button" class="pw-toggle" id="pw-toggle" aria-label="Show password">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>
          <div class="login-row">
            <label class="check"><input type="checkbox" id="remember" name="remember" value="1"> Remember me</label>
            <a class="link" href="#" onclick="UI.toast('Contact the administrator to reset your password','error');return false">Forgot password?</a>
          </div>
          <button class="btn btn-primary btn-block" type="submit">Sign In</button>
          <p class="login-error" id="login-error" hidden></p>
        </form>

      
      </div>
    </div>
  </div>

  <div id="modal-root"></div>
  <div class="toast-host"></div>

  <script src="<?php echo base_url('assets/js/icons.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/db.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/ui.js'); ?>"></script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

 <script>
$(function () {

  // CSRF (only used if $config['csrf_protection'] = TRUE)
  let csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  let csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

  $.validator.addMethod('usernameChars', function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9._]+$/.test(value);
  }, 'Only letters, numbers, dot and underscore allowed');

  const $form  = $('#login-form');
  const $error = $('#login-error');
  const $btn   = $form.find('button[type="submit"]');

  $form.validate({
    errorElement: 'small',
    errorClass: 'field-error',
    rules: {
      username: { required: true, minlength: 3, maxlength: 30, usernameChars: true },
      password: { required: true, minlength: 6, maxlength: 50 }
    },
    messages: {
      username: {
        required: 'Please enter your username',
        minlength: 'Username must be at least 3 characters',
        maxlength: 'Username cannot exceed 30 characters'
      },
      password: {
        required: 'Please enter your password',
        minlength: 'Password must be at least 6 characters',
        maxlength: 'Password cannot exceed 50 characters'
      }
    },
    errorPlacement: function (error, element) {
      if (element.attr('id') === 'password') {
        error.insertAfter(element.closest('.pw-wrap'));
      } else {
        error.insertAfter(element);
      }
    },
    highlight:   function (el) { $(el).addClass('is-invalid'); },
    unhighlight: function (el) { $(el).removeClass('is-invalid'); },

    submitHandler: function () {
      $error.prop('hidden', true).text('');
      $btn.prop('disabled', true).text('Signing in...');

      const payload = {
        username: $('#username').val().trim(),
        password: $('#password').val(),
        remember: $('#remember').is(':checked') ? 1 : 0
      };
      payload[csrfName] = csrfHash;

      $.ajax({
        url: '<?php echo base_url("login_submit"); ?>',
        type: 'POST',
        dataType: 'json',
        data: payload,
        success: function (res) {
          if (res.csrf_hash) csrfHash = res.csrf_hash;   // keep token fresh

          if (res.status === 'success') {
            window.location.href = res.redirect;
          } else {
            $error.text(res.message || 'Invalid username or password').prop('hidden', false);
            $btn.prop('disabled', false).text('Sign In');
          }
        },
        error: function () {
          $error.text('Server error. Please try again.').prop('hidden', false);
          $btn.prop('disabled', false).text('Sign In');
        }
      });

      return false;
    }
  });

  $('#pw-toggle').on('click', function () {
    const $pw = $('#password');
    $pw.attr('type', $pw.attr('type') === 'password' ? 'text' : 'password');
  });
});
</script>
<?php /* $error, $mobile */ ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex">
  <title>Parent Login — Little Hearts School</title>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/fee.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/parent.css') ?>">
</head>
<body class="pp">
  <div class="pp-login">
    <div class="box">
      <div class="pp-brand" style="margin-bottom:18px">
        <span class="mark"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10 12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/></svg></span>
        <span><strong>Little Hearts School</strong><small>Parent Portal</small></span>
      </div>
      <h1 class="page-title" style="font-size:22px">Sign in</h1>
      <p class="page-sub" style="margin-bottom:18px">See attendance, results, fees and school notices for your children.</p>
      <form method="post" action="<?= site_url('parent/login') ?>" novalidate>
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
        <label class="field-label" for="mobile">Registered mobile number</label>
        <input class="field-input" id="mobile" name="mobile" inputmode="numeric" autocomplete="username" maxlength="14" value="<?= html_escape($mobile) ?>" autofocus>
        <label class="field-label" for="password" style="margin-top:14px">Password</label>
        <input class="field-input" type="password" id="password" name="password" autocomplete="current-password" maxlength="64">
        <?php if ($error): ?><p class="field-error" role="alert"><?= html_escape($error) ?></p><?php endif; ?>
        <button type="submit" class="btn btn-primary btn-block" style="margin-top:18px">Sign in</button>
      </form>
      <p class="field-hint" style="margin-top:16px;text-align:center">Forgot your password or not registered? Please contact the school office.</p>
    </div>
  </div>
</body>
</html>

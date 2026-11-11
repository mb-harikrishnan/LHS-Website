<?php /* $child, $parent, $children, $msg, $err */
$g = array('0' => 'Female', '1' => 'Male');
$dob = ($child->smDOB && substr($child->smDOB, 0, 4) !== '0000') ? date('d M Y', strtotime($child->smDOB)) : '—';
$rows = array(
    'Name' => $child->smName, 'Admission No' => $child->smAdmissionNo, 'Class' => $child->class, 'Academic Year' => $child->amYear,
    'Date of Birth' => $dob, 'Gender' => isset($g[(string)$child->smGender]) ? $g[(string)$child->smGender] : '—',
    'Father' => $child->smFatherName ?: '—', 'Mother' => $child->smMotherName ?: '—', 'Mobile' => $child->smMobile ?: '—',
    'Address' => $child->smAddress ?: '—',
);
?>
<h1 class="pp-h1">Profile</h1>
<div class="pp-grid">
  <section class="pp-card">
    <div class="pp-card-h"><h3><?= html_escape($child->smName) ?></h3><a class="small" href="<?= site_url('parent/store') ?>">Store invoices</a></div>
    <ul class="pp-list"><?php foreach ($rows as $k => $v): ?><li><div class="m"><?= $k ?></div><div class="t"><?= html_escape($v) ?></div></li><?php endforeach; ?></ul>
    <p class="field-hint" style="padding:0 16px 14px">To correct any detail, please contact the school office.</p>
  </section>
  <section class="pp-card">
    <div class="pp-card-h"><h3>My account</h3></div>
    <div class="pp-card-b">
      <p class="small muted" style="margin:0 0 12px"><?= html_escape($parent['name']) ?> · login <?= html_escape($parent['mobile']) ?> · <?= count($children) ?> child<?= count($children) === 1 ? '' : 'ren' ?> linked</p>
      <?php if ($msg): ?><p class="ok-txt" role="status"><?= html_escape($msg) ?></p><?php endif; ?>
      <?php if ($err): ?><p class="field-error" role="alert" style="margin:0 0 10px"><?= html_escape($err) ?></p><?php endif; ?>
      <form method="post" action="<?= site_url('parent/password') ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
        <label class="field-label" for="cur">Current password</label><input class="field-input" type="password" id="cur" name="current" autocomplete="current-password" maxlength="64">
        <label class="field-label" for="new" style="margin-top:10px">New password</label><input class="field-input" type="password" id="new" name="new" autocomplete="new-password" minlength="6" maxlength="64">
        <label class="field-label" for="rep" style="margin-top:10px">Repeat new password</label><input class="field-input" type="password" id="rep" name="repeat" autocomplete="new-password" maxlength="64">
        <button type="submit" class="btn btn-primary" style="margin-top:14px">Change password</button>
      </form>
    </div>
  </section>
</div>

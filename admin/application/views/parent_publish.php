<?php /* $exams [{emId, exam, emIsGrade, emPublishParents, tmName, final_cd, any_cd}], $perm, $year */
$can = !empty($perm['can_edit']); ?>
<style>
.switch2{position:relative;display:inline-block;width:46px;height:26px}
.switch2 input{opacity:0;width:0;height:0}
.switch2 span{position:absolute;inset:0;background:#cbd5e1;border-radius:999px;transition:.2s;cursor:pointer}
.switch2 span:before{content:"";position:absolute;width:20px;height:20px;left:3px;top:3px;background:#fff;border-radius:50%;transition:.2s}
.switch2 input:checked + span{background:#059669}
.switch2 input:checked + span:before{transform:translateX(20px)}
.switch2 input:disabled + span{opacity:.5;cursor:not-allowed}
</style>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Publish Results</h1>
      <p class="page-sub">Exams of <?= html_escape($year) ?>. Parents see an exam's marks only when it is published <strong>and</strong> the class teacher has final-submitted the marks for that class / division.</p></div>
  </div>
  <div class="card">
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Exam</th><th>Term</th><th>Type</th><th>Classes ready (final submitted)</th><th>Visible to parents</th></tr></thead>
        <tbody>
        <?php foreach ($exams as $e): ?>
          <tr>
            <td><strong><?= html_escape($e->exam) ?></strong></td>
            <td><?= html_escape($e->tmName) ?></td>
            <td><?= (int)$e->emIsGrade === 1 ? 'Grades' : 'Marks' ?></td>
            <td><?= (int)$e->final_cd ?> of <?= (int)$e->any_cd ?> class-divisions with marks
              <?php if ((int)$e->any_cd > (int)$e->final_cd): ?><div class="small warn-txt"><?= (int)$e->any_cd - (int)$e->final_cd ?> not yet final — parents of those classes will not see this exam.</div><?php endif; ?></td>
            <td><label class="switch2"><input type="checkbox" data-id="<?= (int)$e->emId ?>" <?= $e->emPublishParents ? 'checked' : '' ?> <?= $can ? '' : 'disabled' ?> aria-label="Visible to parents"><span></span></label></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$exams): ?><div class="empty">No exams for this academic year.</div><?php endif; ?>
    </div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI;
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  document.querySelectorAll('.switch2 input').forEach(i => i.addEventListener('change', async () => {
    const fd = new FormData(); fd.append('id', i.dataset.id); fd.append('on', i.checked ? '1' : '0');
    const res = await U.post('<?= site_url('parent_publish_toggle') ?>', fd);
    if (!res.status) { i.checked = !i.checked; return U.err(res.msg); }
    Swal.fire({toast: true, position: 'top-end', icon: 'success', title: res.msg, timer: 1800, showConfirmButton: false});
  }));
})();
</script>

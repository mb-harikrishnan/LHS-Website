<?php /* Receives: $exams (exam_master rows), $terms (term_master rows: tmId, tmName) */ ?>
<style>
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:520px;max-height:92vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #e2e8f0}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:#64748b}
.modal-body{padding:20px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid #e2e8f0;background:#f8fafc}
.field-label{display:block;font-size:12px;font-weight:700;letter-spacing:.3px;text-transform:uppercase;margin:0 0 6px}
.req{color:#dc2626}
.field-input{width:100%;padding:11px 12px;border:1px solid #cbd5e1;border-radius:10px;font:inherit;font-size:14px;margin-bottom:16px;background:#f8fafc}
.field-input:focus{outline:2px solid #6366f1;outline-offset:1px;background:#fff}
.opts{display:flex;flex-wrap:wrap;gap:8px 18px;margin-bottom:6px}
.opts label{display:flex;align-items:center;gap:6px;font-size:13px;font-weight:700;text-transform:uppercase;cursor:pointer}
.opts input{width:16px;height:16px;accent-color:#4f46e5;cursor:pointer}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}
[hidden]{display:none !important}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Exams</h1>
      <p class="page-sub">Manage exams and their display order</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Exam
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search by name or abbreviation…" aria-label="Search exams">
      </div>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:70px">#SL</th>
            <th>Abbreviation</th>
            <th>Name</th>
            <th style="width:130px">Display Order</th>
            <th style="width:80px;text-align:center">Edit</th>
            <th style="width:80px;text-align:center">Action</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php foreach ($exams as $i => $e):
          $row = array(
                'id'      => (int)$e->emId,
                'name'    => $e->emDisplayName,
                'abbr'    => $e->emName,
                'term_id' => (string)$e->emTmId,
                'opened'  => (int)$e->emIsOpened,
                'ongoing' => (int)$e->emIsOngoing,
                'grade'   => (int)$e->emIsGrade,
                'active'  => (int)$e->emActive
            );
          ?>
          <tr data-e="<?= html_escape(json_encode($row)) ?>">
            <td class="num"><?= $i + 1 ?></td>
            <td><strong><?= html_escape($e->emName) ?></strong></td>
            <td><?= html_escape($e->emDisplayName) ?></td>
            <td><?= (int)$e->emDisplayOrder ?></td>
            <td style="text-align:center">
              <button class="icon-btn" type="button" title="Edit" data-edit aria-label="Edit <?= html_escape($e->emName) ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
              </button>
            </td>
            <td style="text-align:center">
              <button class="icon-btn danger" type="button" title="Delete" data-del aria-label="Delete <?= html_escape($e->emName) ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="empty" id="empty" <?= empty($exams) ? '' : 'hidden' ?>>No exams found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<!-- Add / Edit modal -->
<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Exam</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <div class="modal-body">
        <label class="field-label" for="eName">Exam Name <span class="req">*</span></label>
        <input type="text" id="eName" class="field-input" placeholder="Enter Exam Name" maxlength="100">

        <label class="field-label" for="eAbbr">Abbreviation of Exam <span class="req">*</span></label>
        <input type="text" id="eAbbr" class="field-input" placeholder="Enter Abbreviation" maxlength="20">

        <label class="field-label" for="eTerm">Term <span class="req">*</span></label>
        <select id="eTerm" class="field-input">
          <option value="">-- Select Term --</option>
          <?php foreach ($terms as $t): ?>
            <option value="<?= (int)$t->tmId ?>"><?= html_escape($t->tmName) ?></option>
          <?php endforeach; ?>
        </select>

        <label class="field-label">Options</label>
        <div class="opts">
          <label><input type="checkbox" id="oOpened"> Opened</label>
          <label><input type="checkbox" id="oOngoing"> Ongoing</label>
          <label><input type="checkbox" id="oGrade"> Grade</label>
          <label><input type="checkbox" id="oActive"> Active</label>
          <!-- <label><input type="checkbox" id="oClosed"> Closed</label> -->
        </div>

        <p class="field-error" id="formError" hidden></p>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('addModal'), form = $('addForm'), errorEl = $('formError'), saveBtn = $('saveBtn');

  const SAVE_URL   = '<?= site_url('save_exam') ?>';
  const DELETE_URL = '<?= site_url('delete_exam') ?>';
  const CSRF_NAME  = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH  = '<?= $this->security->get_csrf_hash() ?>';

  let editId = 0;
  const showError = m => { errorEl.textContent = m; errorEl.hidden = false; };

  /* ---------- search + serial numbers ---------- */
  function refresh() {
    const q = $('search').value.trim().toLowerCase();
    const rows = Array.from($('rows').querySelectorAll('tr'));
    let n = 0;
    rows.forEach(tr => {
      const e = JSON.parse(tr.dataset.e);
      const match = !q || e.name.toLowerCase().includes(q) || e.abbr.toLowerCase().includes(q);
      tr.hidden = !match;
      if (match) tr.querySelector('.num').textContent = ++n;
    });
    $('empty').hidden = n > 0;
    $('count').textContent = 'Showing ' + n + ' of ' + rows.length + ' exams';
  }
  $('search').addEventListener('input', refresh);

  /* ---------- modal ---------- */
  function open(e) {
    form.reset();
    errorEl.hidden = true;
    editId = e ? e.id : 0;
    $('addTitle').textContent = e ? 'Edit Exam' : 'Add Exam';
    saveBtn.textContent = e ? 'Update' : 'Save';
    $('eName').value = e ? e.name : '';
    $('eAbbr').value = e ? e.abbr : '';
    $('eTerm').value = e ? e.term_id : '';
    // new exam defaults: Opened, Grade, Active ticked
    $('oOpened').checked  = e ? !!e.opened  : true;
    $('oOngoing').checked = e ? !!e.ongoing : false;
    $('oGrade').checked   = e ? !!e.grade   : true;
    $('oActive').checked  = e ? !!e.active  : true;
    // $('oClosed').checked  = e ? !!e.closed  : false;
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    $('eName').focus();
  }
  function close() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    errorEl.hidden = true;
    editId = 0;
  }
  $('openAddModal').addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', ev => { if (ev.target === modal) close(); });
  document.addEventListener('keydown', ev => { if (ev.key === 'Escape' && modal.classList.contains('open')) close(); });

  /* ---------- save ---------- */
  form.addEventListener('submit', async ev => {
    ev.preventDefault();
    const name = $('eName').value.trim(), abbr = $('eAbbr').value.trim(), term = $('eTerm').value;

    if (!name) return showError('Please enter the exam name.');
    if (!abbr) return showError('Please enter the abbreviation.');
    if (!term) return showError('Please select a term.');

    const fd = new FormData();
    fd.append('id', editId);
    fd.append('name', name);
    fd.append('abbr', abbr);
    fd.append('term_id', term);
    ['opened', 'ongoing', 'grade', 'active'].forEach(k => {
      if ($('o' + k.charAt(0).toUpperCase() + k.slice(1)).checked) fd.append(k, 1);
    });
    fd.append(CSRF_NAME, CSRF_HASH);

    saveBtn.disabled = true;
    try {
      const res = await (await fetch(SAVE_URL, { method: 'POST', body: fd })).json();
      if (res.csrf) CSRF_HASH = res.csrf;
      if (!res.status) return showError(res.msg);
      close();
      Swal.fire({ icon: 'success', title: 'Success', text: res.msg, timer: 1500, showConfirmButton: false })
          .then(() => location.reload());
    } catch (err) {
      showError('Something went wrong. Please try again.');
    } finally { saveBtn.disabled = false; }
  });

  /* ---------- edit / delete ---------- */
  $('rows').addEventListener('click', ev => {
    const tr = ev.target.closest('tr');
    if (!tr || !tr.dataset.e) return;
    const e = JSON.parse(tr.dataset.e);

    if (ev.target.closest('[data-edit]')) return open(e);

    if (ev.target.closest('[data-del]')) {
      Swal.fire({
        title: 'Are you sure?', text: e.name + ' will be deleted.', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#dc2626',
        confirmButtonText: 'Yes, delete it', cancelButtonText: 'Cancel'
      }).then(async r => {
        if (!r.isConfirmed) return;
        const fd = new FormData();
        fd.append('id', e.id);
        fd.append(CSRF_NAME, CSRF_HASH);
        try {
          const res = await (await fetch(DELETE_URL, { method: 'POST', body: fd })).json();
          if (res.csrf) CSRF_HASH = res.csrf;
          if (!res.status) return Swal.fire({ icon: 'error', title: 'Error', text: res.msg });
          Swal.fire({ icon: 'success', title: 'Deleted!', text: res.msg, timer: 1500, showConfirmButton: false })
              .then(() => location.reload());
        } catch (err) {
          Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
        }
      });
    }
  });

  refresh();
})();
</script>
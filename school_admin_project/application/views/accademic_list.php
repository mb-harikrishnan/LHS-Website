<?php /* Receives: $academic (rows of academic_master with amId, amYear) */ ?>
<style>
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:440px;max-height:92vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--slate-200)}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:var(--slate-500)}
.modal-body{padding:20px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid var(--slate-200)}
.field-label{display:block;font-size:13px;font-weight:600;margin:0 0 6px}
.req{color:#dc2626}
.field-input{width:100%;padding:9px 10px;border:1px solid var(--slate-300);border-radius:8px;font:inherit;margin-bottom:8px;background:#fff}
.field-input:focus{outline:2px solid #6366f1;outline-offset:1px}
.field-hint{font-size:12px;color:#94a3b8;margin:0 0 8px}
.field-error{color:#dc2626;font-size:13px;margin:8px 0 0}
[hidden]{display:none !important}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Academic Year</h1>
      <p class="page-sub">Manage academic years</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Academic Year
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search year…" aria-label="Search academic year">
      </div>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:80px">Sl No</th>
            <th>Academic Year</th>
            <th style="width:110px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php foreach ($academic as $i => $a): ?>
          <tr data-id="<?= (int)$a->amId ?>" data-year="<?= html_escape($a->amYear) ?>">
            <td class="num"><?= $i + 1 ?></td>
            <td class="v-title"><?= html_escape($a->amYear) ?></td>
            <td>
              <div class="row-actions" style="justify-content:flex-end;">
                <button class="icon-btn" type="button" title="Edit" data-edit aria-label="Edit <?= html_escape($a->amYear) ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </button>
                <button class="icon-btn danger" type="button" title="Delete" data-del aria-label="Delete <?= html_escape($a->amYear) ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="empty" id="empty" <?= empty($academic) ? '' : 'hidden' ?>>No academic years found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<!-- Add / Edit modal -->
<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Academic Year</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <div class="modal-body">
        <label class="field-label" for="yName">Academic Year <span class="req">*</span></label>
        <input type="text" id="yName" class="field-input" placeholder="e.g. 2026-27" maxlength="7">
        <p class="field-hint">Format: 2026-27</p>
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

  const SAVE_URL   = '<?= site_url('save_academic') ?>';
  const DELETE_URL = '<?= site_url('delete_accademic') ?>';
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
      const match = !q || tr.dataset.year.toLowerCase().includes(q);
      tr.hidden = !match;
      if (match) tr.querySelector('.num').textContent = ++n;
    });
    $('empty').hidden = n > 0;
    $('count').textContent = 'Showing ' + n + ' of ' + rows.length + ' academic years';
  }
  $('search').addEventListener('input', refresh);

  /* ---------- modal ---------- */
  function open(id, year) {
    editId = id || 0;
    $('addTitle').textContent = id ? 'Edit Academic Year' : 'Add Academic Year';
    saveBtn.textContent = id ? 'Update' : 'Save';
    $('yName').value = year || '';
    errorEl.hidden = true;
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    $('yName').focus();
  }
  function close() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    form.reset();
    errorEl.hidden = true;
    editId = 0;
  }
  $('openAddModal').addEventListener('click', () => open(0, ''));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  /* ---------- save ---------- */
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const year = $('yName').value.trim();

    if (!year) return showError('Please enter the academic year.');
    if (!/^\d{4}-\d{2}$/.test(year)) return showError('Use the format 2026-27.');
    const s = +year.slice(0, 4), en = +year.slice(5);
    if ((s + 1) % 100 !== en) return showError('The end year must follow the start year (e.g. 2026-27).');

    const fd = new FormData();
    fd.append('id', editId);
    fd.append('year', year);
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
  $('rows').addEventListener('click', e => {
    const tr = e.target.closest('tr');
    if (!tr) return;
    const id = +tr.dataset.id, year = tr.dataset.year;

    if (e.target.closest('[data-edit]')) return open(id, year);

    if (e.target.closest('[data-del]')) {
      Swal.fire({
        title: 'Are you sure?', text: year + ' will be deleted.', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#dc2626',
        confirmButtonText: 'Yes, delete it', cancelButtonText: 'Cancel'
      }).then(async r => {
        if (!r.isConfirmed) return;
        const fd = new FormData();
        fd.append('id', id);
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
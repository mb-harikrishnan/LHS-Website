<?php
/* Save as: application/views/members_area/divition_list.php
   Receives from controller: $divisions (dmId, dmName, used_count) */
?>
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
.field-input{width:100%;padding:9px 10px;border:1px solid var(--slate-300);border-radius:8px;font:inherit;margin-bottom:6px;background:#fff}
.field-input.invalid{border-color:#dc2626;outline-color:#dc2626}
.field-hint{font-size:12px;color:var(--slate-500);margin:0 0 12px}
.field-error{color:#dc2626;font-size:13px;margin:0}
[hidden]{display:none !important}

/* action buttons */
.act-btn{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;cursor:pointer;border:1px solid transparent;transition:background .15s,color .15s,border-color .15s,box-shadow .15s}
.act-btn svg{width:15px;height:15px}
.act-btn:focus-visible{outline:2px solid #93c5fd;outline-offset:2px}
.act-btn.ed{background:#f8fafc;color:#334155;border-color:#cbd5e1}
.act-btn.ed:hover{background:#e2e8f0}
.act-btn.rm{background:#fef2f2;color:#b91c1c;border-color:#fecaca}
.act-btn.rm:hover:not(:disabled){background:#dc2626;color:#fff;border-color:#dc2626;box-shadow:0 4px 10px rgba(220,38,38,.25)}
.act-btn.rm:disabled{opacity:.4;cursor:not-allowed}
.cell-actions{display:flex;gap:6px;justify-content:flex-end}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Division List</h1>
      <p class="page-sub">Manage school divisions</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Division
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search division…" aria-label="Search divisions">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:80px">Sl No</th>
            <th>Division Name</th>
            <th>Used In</th>
            <th style="width:110px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php foreach ($divisions as $d): $used = (int)$d->used_count; ?>
          <tr data-name="<?= html_escape($d->dmName) ?>">
            <td class="num"></td>
            <td class="v-title"><?= html_escape($d->dmName) ?></td>
            <td>
              <?php if ($used > 0): ?>
                <span class="badge badge-info"><?= $used ?> class<?= $used > 1 ? 'es' : '' ?></span>
              <?php else: ?>
                &mdash;
              <?php endif; ?>
            </td>
            <td>
              <div class="cell-actions">
                <button class="act-btn ed" type="button" title="Edit" aria-label="Edit <?= html_escape($d->dmName) ?>"
                        data-edit data-id="<?= (int)$d->dmId ?>" data-name="<?= html_escape($d->dmName) ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </button>
                <button class="act-btn rm" type="button"
                        title="<?= $used > 0 ? 'In use, cannot delete' : 'Delete' ?>"
                        aria-label="Delete <?= html_escape($d->dmName) ?>"
                        data-del="<?= (int)$d->dmId ?>" <?= $used > 0 ? 'disabled' : '' ?>>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v6M14 11v6"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="empty" id="empty" hidden>No divisions found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Division</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <input type="hidden" id="dId" value="0">
      <div class="modal-body">
        <label class="field-label" for="dName">Division Name <span class="req">*</span></label>
        <input type="text" id="dName" class="field-input" placeholder="e.g. A" maxlength="50" autocomplete="off">
        <p class="field-hint">Letters, numbers, spaces and hyphens only.</p>
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
  const modal = $('addModal'), form = $('addForm'), errorEl = $('formError');
  const nameInp = $('dName'), saveBtn = $('saveBtn'), tbody = $('rows');

  const INSERT_URL = '<?= site_url('insert_divition') ?>';
  const UPDATE_URL = '<?= site_url('update_divition') ?>';
  const DELETE_URL = '<?= site_url('delete_divition_table') ?>';
  const CSRF_NAME  = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH  = '<?= $this->security->get_csrf_hash() ?>';

  const clean = s => s.replace(/\s+/g, ' ').trim();
  const showError = m => { errorEl.textContent = m; errorEl.hidden = false; nameInp.classList.add('invalid'); };
  const clearError = () => { errorEl.hidden = true; nameInp.classList.remove('invalid'); };

  /* ---------- list: search + numbering ---------- */
  function applyFilter() {
    const q = $('search').value.trim().toLowerCase();
    const trs = [...tbody.querySelectorAll('tr')];
    let shown = 0;
    trs.forEach(tr => {
      const ok = !q || tr.dataset.name.toLowerCase().includes(q);
      tr.hidden = !ok;
      if (ok) { shown++; tr.querySelector('.num').textContent = shown; }
    });
    $('empty').hidden = shown > 0;
    $('count').textContent = `Showing ${shown} of ${trs.length} divisions`;
  }

  /* ---------- validation (same rules as the server) ---------- */
  function validate(name, editId) {
    if (!name) return 'Please enter a division name.';
    if (name.length > 50) return 'Division name must be 50 characters or fewer.';
    if (!/^[A-Za-z0-9 \-]+$/.test(name)) return 'Use only letters, numbers, spaces and hyphens.';
    const dup = [...tbody.querySelectorAll('tr')].some(tr => {
      const id = tr.querySelector('[data-edit]').dataset.id;
      return String(id) !== String(editId) && tr.dataset.name.toLowerCase() === name.toLowerCase();
    });
    if (dup) return 'This division already exists.';
    return '';
  }

  /* ---------- add / edit modal ---------- */
  function open(item) {
    form.reset(); clearError();
    $('dId').value = item ? item.id : 0;
    $('addTitle').textContent = item ? 'Edit Division' : 'Add Division';
    saveBtn.textContent = item ? 'Update' : 'Save';
    nameInp.value = item ? item.name : '';
    modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false');
    nameInp.focus(); nameInp.select();
  }
  const close = () => { modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true'); form.reset(); clearError(); };

  $('openAddModal').addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });
  nameInp.addEventListener('input', () => {
    // live duplicate / format feedback while typing
    const err = nameInp.value.trim() ? validate(clean(nameInp.value), +$('dId').value) : '';
    err ? showError(err) : clearError();
  });

  /* ---------- save (insert / update) ---------- */
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const id = +$('dId').value, name = clean(nameInp.value);
    const err = validate(name, id);
    if (err) return showError(err);

    const fd = new FormData();
    fd.append('id', id); fd.append('name', name); fd.append(CSRF_NAME, CSRF_HASH);

    saveBtn.disabled = true;
    try {
      const res = await (await fetch(id ? UPDATE_URL : INSERT_URL, { method: 'POST', body: fd })).json();
      if (res.csrf) CSRF_HASH = res.csrf;
      if (!res.status) return showError(res.msg);
      close();
      Swal.fire({ icon: 'success', title: 'Success', text: res.msg, timer: 1500, showConfirmButton: false })
          .then(() => location.reload());
    } catch (err) {
      showError('Something went wrong. Please try again.');
    } finally { saveBtn.disabled = false; }
  });

  /* ---------- edit + delete ---------- */
  tbody.addEventListener('click', e => {
    const ed = e.target.closest('[data-edit]');
    if (ed) return open({ id: ed.dataset.id, name: ed.dataset.name });

    const del = e.target.closest('[data-del]');
    if (!del || del.disabled) return;
    Swal.fire({
      title: 'Are you sure?', text: 'This division will be deleted.', icon: 'warning',
      showCancelButton: true, confirmButtonColor: '#dc2626',
      confirmButtonText: 'Yes, delete it', cancelButtonText: 'Cancel'
    }).then(async r => {
      if (!r.isConfirmed) return;
      const fd = new FormData();
      fd.append('id', del.dataset.del); fd.append(CSRF_NAME, CSRF_HASH);
      try {
        const res = await (await fetch(DELETE_URL, { method: 'POST', body: fd })).json();
        if (res.csrf) CSRF_HASH = res.csrf;
        if (!res.status) return Swal.fire({ icon: 'error', title: 'Cannot delete', text: res.msg });
        Swal.fire({ icon: 'success', title: 'Deleted!', text: res.msg, timer: 1500, showConfirmButton: false })
            .then(() => location.reload());
      } catch (err) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
      }
    });
  });

  $('search').addEventListener('input', applyFilter);
  applyFilter();
})();
</script>
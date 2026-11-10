<?php /* Receives: $academic (amId, amYear, amIsCurrent, amActive) */ ?>
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

.badge-current{display:inline-block;margin-left:8px;padding:2px 10px;font-size:11px;font-weight:700;letter-spacing:.3px;border-radius:999px;background:#dcfce7;color:#15803d;border:1px solid #86efac;vertical-align:middle}
.badge-off{display:inline-block;margin-left:8px;padding:2px 10px;font-size:11px;font-weight:700;border-radius:999px;background:#f1f5f9;color:#64748b;border:1px solid #cbd5e1;vertical-align:middle}
tr.is-current{background:#f0fdf4}
tr.is-current td:first-child{box-shadow:inset 3px 0 0 #16a34a}
tr.is-current .v-title{font-weight:700;color:#15803d}
tr.is-inactive .v-title{color:#94a3b8}

.cur-check{width:18px;height:18px;cursor:pointer;accent-color:#16a34a}
/* toggle switch */
.switch{position:relative;display:inline-block;width:42px;height:24px}
.switch input{opacity:0;width:0;height:0}
.slider{position:absolute;inset:0;background:#cbd5e1;border-radius:999px;cursor:pointer;transition:.2s}
.slider:before{content:"";position:absolute;height:18px;width:18px;left:3px;top:3px;background:#fff;border-radius:50%;transition:.2s}
.switch input:checked + .slider{background:#16a34a}
.switch input:checked + .slider:before{transform:translateX(18px)}
.switch input:focus-visible + .slider{outline:2px solid #6366f1;outline-offset:2px}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Academic Year</h1>
      <p class="page-sub">Manage academic years</p>
    </div>
    <div>
      <button class="btn btn-primary" type="button" id="openAddModal">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        Add Academic Year
      </button>
    </div>
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
            <th style="width:110px;text-align:center">Current</th>
            <th style="width:110px;text-align:center">Active</th>
            <th style="width:110px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php foreach ($academic as $i => $a): ?>
          <tr data-id="<?= (int)$a->amId ?>" data-year="<?= html_escape($a->amYear) ?>"
              class="<?= ((int)$a->amIsCurrent === 1) ? 'is-current' : '' ?> <?= ((int)$a->amActive !== 1) ? 'is-inactive' : '' ?>">
            <td class="num"><?= $i + 1 ?></td>
            <td class="v-title">
              <?= html_escape($a->amYear) ?>
              <?php if ((int)$a->amIsCurrent === 1): ?><span class="badge-current">CURRENT</span><?php endif; ?>
              <?php if ((int)$a->amActive !== 1): ?><span class="badge-off">DISABLED</span><?php endif; ?>
            </td>
            <td style="text-align:center">
              <input type="checkbox" class="cur-check" data-current
                     title="Set as current year"
                     aria-label="Set <?= html_escape($a->amYear) ?> as current year"
                     <?= ((int)$a->amIsCurrent === 1) ? 'checked' : '' ?>>
            </td>
            <td style="text-align:center">
              <label class="switch" title="Enable / Disable">
                <input type="checkbox" data-active aria-label="Enable <?= html_escape($a->amYear) ?>"
                       <?= ((int)$a->amActive === 1) ? 'checked' : '' ?>>
                <span class="slider"></span>
              </label>
            </td>
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

  const SAVE_URL    = '<?= site_url('save_academic') ?>';
  const DELETE_URL  = '<?= site_url('delete_accademic') ?>';
  const CURRENT_URL = '<?= site_url('set_current_year') ?>';
  const ACTIVE_URL  = '<?= site_url('toggle_academic_active') ?>';
  const CSRF_NAME   = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH   = '<?= $this->security->get_csrf_hash() ?>';

  let editId = 0;

  const showError = m => { errorEl.textContent = m; errorEl.hidden = false; };

  /* ---------- small POST helper (keeps CSRF fresh) ---------- */
  async function post(url, data) {
    const fd = new FormData();
    Object.keys(data).forEach(k => fd.append(k, data[k]));
    fd.append(CSRF_NAME, CSRF_HASH);
    const res = await (await fetch(url, { method: 'POST', body: fd })).json();
    if (res.csrf) CSRF_HASH = res.csrf;
    return res;
  }

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

    saveBtn.disabled = true;
    try {
      const res = await post(SAVE_URL, { id: editId, year: year });
      if (!res.status) return showError(res.msg);
      close();
      Swal.fire({ icon: 'success', title: 'Success', text: res.msg, timer: 1500, showConfirmButton: false })
          .then(() => location.reload());
    } catch (err) {
      showError('Something went wrong. Please try again.');
    } finally { saveBtn.disabled = false; }
  });

  /* ---------- current checkbox + active switch ---------- */
  $('rows').addEventListener('change', async e => {
    const tr = e.target.closest('tr');
    if (!tr) return;
    const id = +tr.dataset.id, year = tr.dataset.year;

    // ---- make this year current ----
    if (e.target.matches('[data-current]')) {
      const box = e.target;

      // unticking the current year is not allowed: one year must always be current
      if (!box.checked) {
        box.checked = true;
        return Swal.fire({ icon: 'info', title: 'One year must be current',
                           text: 'To change it, tick another academic year.' });
      }

      box.checked = false; // stays unticked until the server confirms
      const r = await Swal.fire({
        icon: 'question', title: 'Change current year?',
        text: year + ' will become the current academic year.',
        showCancelButton: true, confirmButtonText: 'Yes, change', cancelButtonText: 'Cancel'
      });
      if (!r.isConfirmed) return;

      try {
        const res = await post(CURRENT_URL, { id: id });
        if (!res.status) return Swal.fire({ icon: 'warning', title: 'Not allowed', text: res.msg });
        Swal.fire({ icon: 'success', title: 'Done', text: res.msg, timer: 1500, showConfirmButton: false })
            .then(() => location.reload());
      } catch (err) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
      }
      return;
    }

    // ---- enable / disable (amActive 1 / 0) ----
    if (e.target.matches('[data-active]')) {
      const sw = e.target, want = sw.checked ? 1 : 0;
      sw.disabled = true;
      try {
        const res = await post(ACTIVE_URL, { id: id, active: want });
        if (!res.status) {
          sw.checked = !want;                       // revert
          return Swal.fire({ icon: 'warning', title: 'Not allowed', text: res.msg });
        }
        Swal.fire({ icon: 'success', title: 'Done', text: res.msg, timer: 1200, showConfirmButton: false })
            .then(() => location.reload());
      } catch (err) {
        sw.checked = !want;
        Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
      } finally { sw.disabled = false; }
    }
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
        try {
          const res = await post(DELETE_URL, { id: id });
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
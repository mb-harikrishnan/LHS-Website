<?php
/* Save as: application/views/vaccancy_list.php
   Receives from controller: $rows, $from, $to */
?>
<style>
/* modal */
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:500px;max-height:92vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--slate-200)}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:var(--slate-500)}
.modal-body{padding:20px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid var(--slate-200)}
.field-label{display:block;font-size:13px;font-weight:600;margin:0 0 6px}
.req{color:#dc2626}
.field-input{width:100%;padding:9px 10px;border:1px solid var(--slate-300);border-radius:8px;font:inherit;margin-bottom:16px;background:#fff}
textarea.field-input{min-height:130px;resize:vertical}
.field-error{color:#dc2626;font-size:13px;margin:0}
.field-error[hidden]{display:none}

/* view description modal */
.view-meta{font-size:13px;color:var(--slate-500);margin:0 0 14px}
.view-desc{white-space:pre-wrap;word-break:break-word;line-height:1.6;margin:0;max-height:55vh;overflow:auto}
.btn-view{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;font-size:13px}

/* list */
.date-filter{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.date-filter input[type=date]{padding:8px 10px;border:1px solid var(--slate-300);border-radius:8px;font:inherit;background:#fff}
.date-filter label{font-size:13px;color:var(--slate-500)}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Vacancy List</h1>
      <p class="page-sub">Open positions with their title and description</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Vacancy
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search by title or description…" aria-label="Search vacancies">
      </div>

      <form class="date-filter" method="get" action="<?= site_url('vaccancy_list') ?>">
        <label for="fFrom">From</label>
        <input type="date" id="fFrom" name="from" value="<?= html_escape($from) ?>">
        <label for="fTo">To</label>
        <input type="date" id="fTo" name="to" value="<?= html_escape($to) ?>">
        <button type="submit" class="btn">Filter</button>
        <?php if ($from !== '' || $to !== ''): ?>
          <a class="btn" href="<?= site_url('vaccancy_list') ?>">Clear</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th><th>Date</th><th>Title</th><th>Description</th>
            <th style="width:120px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php $n = 1; foreach ($rows as $r):
            $dmy = date('d-m-Y', strtotime($r->d_date));
          ?>
          <tr data-title="<?= html_escape($r->c_title) ?>"
              data-desc="<?= html_escape($r->c_description) ?>"
              data-date="<?= $dmy ?>">
            <td class="num"><?= $n++ ?></td>
            <td><?= $dmy ?></td>
            <td class="v-title"><?= html_escape($r->c_title) ?></td>
            <td>
              <button class="btn btn-view" type="button" data-view aria-label="View description of <?= html_escape($r->c_title) ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                View
              </button>
            </td>
            <td>
              <div class="row-actions">
                <button class="icon-btn" type="button" title="Edit" aria-label="Edit <?= html_escape($r->c_title) ?>"
                        data-edit
                        data-id="<?= (int)$r->n_slno ?>"
                        data-title="<?= html_escape($r->c_title) ?>"
                        data-desc="<?= html_escape($r->c_description) ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </button>
                <button class="icon-btn danger" type="button" title="Delete" aria-label="Delete <?= html_escape($r->c_title) ?>"
                        data-del="<?= (int)$r->n_slno ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="empty" id="empty" hidden>No vacancies found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<!-- Add / Edit Modal -->
<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Vacancy</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <input type="hidden" id="vId" value="0">
      <div class="modal-body">
        <label class="field-label" for="vTitle">Title <span class="req">*</span></label>
        <input type="text" id="vTitle" class="field-input" placeholder="e.g. PGT Mathematics Teacher" maxlength="120">

        <label class="field-label" for="vDesc">Description <span class="req">*</span></label>
        <textarea id="vDesc" class="field-input" placeholder="Qualifications, experience, how to apply…"></textarea>

        <p class="field-error" id="formError" hidden></p>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- View Description Modal -->
<div class="modal-overlay" id="viewModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="viewTitle">
    <div class="modal-head">
      <h2 id="viewTitle"></h2>
      <button class="modal-close" type="button" data-vclose aria-label="Close">&times;</button>
    </div>
    <div class="modal-body">
      <p class="view-meta" id="viewDate"></p>
      <p class="view-desc" id="viewDesc"></p>
    </div>
    <div class="modal-foot">
      <button type="button" class="btn" data-vclose>Close</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('addModal'), form = $('addForm'), errorEl = $('formError'), saveBtn = $('saveBtn');

  const SAVE_URL   = '<?= site_url('save_vaccancy') ?>';
  const DELETE_URL = '<?= site_url('delete_vaccancy_list') ?>';
  const CSRF_NAME  = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH  = '<?= $this->security->get_csrf_hash() ?>';

  const showError = m => { errorEl.textContent = m; errorEl.hidden = false; };

  /* ---------- list: search ---------- */
  function applyFilter() {
    const q = $('search').value.trim().toLowerCase();
    const trs = [...document.querySelectorAll('#rows tr')];
    let shown = 0;
    trs.forEach(tr => {
      const ok = !q ||
        tr.dataset.title.toLowerCase().includes(q) ||
        tr.dataset.desc.toLowerCase().includes(q) ||
        tr.dataset.date.includes(q);
      tr.hidden = !ok;
      if (ok) { shown++; tr.querySelector('.num').textContent = shown; }
    });
    $('empty').hidden = shown > 0;
    $('count').textContent = `Showing ${shown} of ${trs.length} vacancies`;
  }

  /* ---------- add / edit modal ---------- */
  function resetForm() { form.reset(); $('vId').value = 0; errorEl.hidden = true; }
  function open(edit) {
    resetForm();
    $('addTitle').textContent = edit ? 'Edit Vacancy' : 'Add Vacancy';
    if (edit) {
      $('vId').value = edit.id;
      $('vTitle').value = edit.title;
      $('vDesc').value = edit.desc;
    }
    modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false'); $('vTitle').focus();
  }
  const close = () => { modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true'); resetForm(); };

  $('openAddModal').addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });

  /* ---------- view description modal ---------- */
  const viewModal = $('viewModal');
  function openView(tr) {
    $('viewTitle').textContent = tr.dataset.title;
    $('viewDate').textContent  = 'Posted on ' + tr.dataset.date;
    $('viewDesc').textContent  = tr.dataset.desc;
    viewModal.classList.add('open'); viewModal.setAttribute('aria-hidden', 'false');
  }
  const closeView = () => { viewModal.classList.remove('open'); viewModal.setAttribute('aria-hidden', 'true'); };
  viewModal.querySelectorAll('[data-vclose]').forEach(b => b.addEventListener('click', closeView));
  viewModal.addEventListener('click', e => { if (e.target === viewModal) closeView(); });

  /* Esc closes whichever modal is open */
  document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    if (viewModal.classList.contains('open')) closeView();
    else if (modal.classList.contains('open')) close();
  });

  /* ---------- save (insert / update) ---------- */
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const id = +$('vId').value, title = $('vTitle').value.trim(), desc = $('vDesc').value.trim();
    if (!title || !desc) return showError('Please enter both a title and a description.');

    const fd = new FormData();
    fd.append('id', id); fd.append('title', title); fd.append('description', desc);
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

  /* ---------- edit + delete (row buttons) ---------- */
  $('rows').addEventListener('click', e => {
    const vw = e.target.closest('[data-view]');
    if (vw) return openView(vw.closest('tr'));

    const ed = e.target.closest('[data-edit]');
    if (ed) return open({ id: ed.dataset.id, title: ed.dataset.title, desc: ed.dataset.desc });

    const del = e.target.closest('[data-del]');
    if (!del) return;
    Swal.fire({
      title: 'Are you sure?', text: 'This vacancy will be deleted.', icon: 'warning',
      showCancelButton: true, confirmButtonColor: '#dc2626',
      confirmButtonText: 'Yes, delete it', cancelButtonText: 'Cancel'
    }).then(async r => {
      if (!r.isConfirmed) return;
      const fd = new FormData();
      fd.append('id', del.dataset.del); fd.append(CSRF_NAME, CSRF_HASH);
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
  });

  $('search').addEventListener('input', applyFilter);
  applyFilter();
})();
</script>
<?php
/* Save as: application/views/members_area/class_divition_list.php
   Receives from controller:
     $allocations  [ {cmId, cmName, divisions:[{dmId, dmName}]} ]
     $classes      all rows of class_master    (cmId, cmName)
     $divisions    all rows of division_master (dmId, dmName) */

$classes_js = array();
foreach ($classes as $c) {
    $classes_js[] = array('id' => (int)$c->cmId, 'name' => $c->cmName);
}
$divisions_js = array();
foreach ($divisions as $d) {
    $divisions_js[] = array('id' => (int)$d->dmId, 'name' => $d->dmName);
}
$allocated_ids = array();
foreach ($allocations as $a) {
    $allocated_ids[] = (int)$a->cmId;
}
?>
<style>
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:760px;max-height:92vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--slate-200)}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:var(--slate-500)}
.modal-body{padding:20px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid var(--slate-200)}
.field-label{display:block;font-size:12px;font-weight:700;letter-spacing:.3px;text-transform:uppercase;margin:0 0 8px}
.req{color:#dc2626}
.field-input{width:100%;padding:11px 12px;border:1px solid var(--slate-300);border-radius:8px;font:inherit;margin-bottom:18px;background:#fff}
.field-input:focus{outline:2px solid #6366f1;outline-offset:1px}
.field-input:disabled{background:#f1f5f9;cursor:not-allowed}
.field-note{font-size:12px;color:var(--slate-500);margin:-12px 0 16px}
.div-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
.div-head .field-label{margin:0}
.link-btn{background:none;border:0;padding:0;font:inherit;font-size:12px;font-weight:600;color:#4f46e5;cursor:pointer}
.link-btn:hover{text-decoration:underline}
.div-box{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;padding:12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px}
.div-chip{display:flex;align-items:center;gap:8px;padding:9px 12px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;font-weight:500;cursor:pointer}
.div-chip:hover{border-color:#6366f1}
.div-chip input{width:16px;height:16px;cursor:pointer;accent-color:#4f46e5}
.div-chip:has(input:checked){border-color:#6366f1;background:#eef2ff}
.div-empty{grid-column:1/-1;color:var(--slate-500);font-size:14px}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}
.tag{display:inline-block;padding:3px 10px;margin:2px 4px 2px 0;border-radius:999px;background:#eef2ff;color:#4338ca;font-size:12px;font-weight:600}
[hidden]{display:none !important}

/* action buttons */
.act-btn{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;cursor:pointer;border:1px solid transparent;transition:background .15s,color .15s,border-color .15s,box-shadow .15s}
.act-btn svg{width:15px;height:15px}
.act-btn:focus-visible{outline:2px solid #93c5fd;outline-offset:2px}
.act-btn.ed{background:#f8fafc;color:#334155;border-color:#cbd5e1}
.act-btn.ed:hover{background:#e2e8f0}
.act-btn.rm{background:#fef2f2;color:#b91c1c;border-color:#fecaca}
.act-btn.rm:hover{background:#dc2626;color:#fff;border-color:#dc2626;box-shadow:0 4px 10px rgba(220,38,38,.25)}
.cell-actions{display:flex;gap:6px;justify-content:flex-end}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Class Divisions</h1>
      <p class="page-sub">Assign divisions to each class</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Class Division
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search class or division…" aria-label="Search">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:80px">Sl No</th>
            <th style="width:140px">Class</th>
            <th>Divisions</th>
            <th style="width:110px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php foreach ($allocations as $a):
            $ids = array(); $names = array();
            foreach ($a->divisions as $d) { $ids[] = (int)$d->dmId; $names[] = $d->dmName; }
          ?>
          <tr data-search="<?= html_escape(strtolower($a->cmName . ' ' . implode(' ', $names))) ?>">
            <td class="num"></td>
            <td><strong><?= html_escape($a->cmName) ?></strong></td>
            <td>
              <?php foreach ($a->divisions as $d): ?>
                <span class="tag"><?= html_escape($d->dmName) ?></span>
              <?php endforeach; ?>
            </td>
            <td>
              <div class="cell-actions">
                <button class="act-btn ed" type="button" title="Edit" aria-label="Edit class <?= html_escape($a->cmName) ?>"
                        data-edit
                        data-class-id="<?= (int)$a->cmId ?>"
                        data-divs="<?= html_escape(json_encode($ids)) ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </button>
                <button class="act-btn rm" type="button" title="Delete" aria-label="Delete class <?= html_escape($a->cmName) ?>"
                        data-del="<?= (int)$a->cmId ?>" data-name="<?= html_escape($a->cmName) ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v6M14 11v6"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="empty" id="empty" hidden>No records found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<!-- Add / Edit Modal -->
<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Class Division</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <input type="hidden" id="isEdit" value="0">
      <div class="modal-body">
        <label class="field-label" for="cSelect">Select Class <span class="req">*</span></label>
        <select id="cSelect" class="field-input"></select>
        <p class="field-note" id="classNote" hidden>Every class already has divisions. Edit a class from the list instead.</p>

        <div class="div-head">
          <label class="field-label">Select Divisions <span class="req">*</span></label>
          <span>
            <button type="button" class="link-btn" id="selAll">Select all</button> &middot;
            <button type="button" class="link-btn" id="selNone">Clear</button>
          </span>
        </div>
        <div class="div-box" id="divBox"></div>

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
  const cSelect = $('cSelect'), saveBtn = $('saveBtn'), tbody = $('rows');

  const SAVE_URL   = '<?= site_url('save_class_division') ?>';
  const DELETE_URL = '<?= site_url('delete_class_division') ?>';
  const CSRF_NAME  = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH  = '<?= $this->security->get_csrf_hash() ?>';

  const CLASSES   = <?= json_encode($classes_js) ?>;      // [{id, name}]
  const DIVISIONS = <?= json_encode($divisions_js) ?>;    // [{id, name}]
  const ALLOCATED = <?= json_encode($allocated_ids) ?>;   // class ids that already have divisions

  const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const showError = m => { errorEl.textContent = m; errorEl.hidden = false; };
  const checks = () => [...form.querySelectorAll('input[name="div"]')];

  /* ---------- division checkboxes (built once) ---------- */
  $('divBox').innerHTML = DIVISIONS.length
    ? DIVISIONS.map(d => `<label class="div-chip"><input type="checkbox" name="div" value="${d.id}"> ${esc(d.name)}</label>`).join('')
    : '<div class="div-empty">No divisions found. <a href="<?= site_url('divition_list') ?>">Add divisions first</a>.</div>';

  /* ---------- list: search + numbering ---------- */
  function applyFilter() {
    const q = $('search').value.trim().toLowerCase();
    const trs = [...tbody.querySelectorAll('tr')];
    let shown = 0;
    trs.forEach(tr => {
      const ok = !q || tr.dataset.search.includes(q);
      tr.hidden = !ok;
      if (ok) { shown++; tr.querySelector('.num').textContent = shown; }
    });
    $('empty').hidden = shown > 0;
    $('count').textContent = `Showing ${shown} of ${trs.length} classes`;
  }

  /* ---------- add / edit modal ---------- */
  function open(edit) {
    form.reset(); errorEl.hidden = true;
    $('isEdit').value = edit ? 1 : 0;
    $('addTitle').textContent = edit ? 'Edit Class Division' : 'Add Class Division';
    saveBtn.textContent = edit ? 'Update' : 'Save';

    if (edit) {
      // class is fixed while editing
      const c = CLASSES.find(x => x.id === edit.classId);
      cSelect.innerHTML = `<option value="${edit.classId}">${esc(c ? c.name : edit.classId)}</option>`;
      cSelect.value = edit.classId; cSelect.disabled = true;
      $('classNote').hidden = true;
      checks().forEach(cb => { cb.checked = edit.divs.includes(+cb.value); });
    } else {
      // only classes that don't have divisions yet
      const free = CLASSES.filter(c => !ALLOCATED.includes(c.id));
      cSelect.innerHTML = '<option value="">Select class</option>' +
        free.map(c => `<option value="${c.id}">${esc(c.name)}</option>`).join('');
      cSelect.disabled = false;
      $('classNote').hidden = free.length > 0;
      checks().forEach(cb => { cb.checked = false; });
    }
    modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false');
  }
  const close = () => { modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true'); errorEl.hidden = true; };

  $('openAddModal').addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });
  $('selAll').addEventListener('click', () => checks().forEach(cb => cb.checked = true));
  $('selNone').addEventListener('click', () => checks().forEach(cb => cb.checked = false));

  /* ---------- save (insert / update) ---------- */
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const isEdit = +$('isEdit').value === 1;
    const classId = +cSelect.value;
    const divs = checks().filter(cb => cb.checked).map(cb => cb.value);

    if (!classId) return showError('Please select a class.');
    if (!divs.length) return showError('Please select at least one division.');
    if (!isEdit && ALLOCATED.includes(classId)) return showError('This class already has divisions. Edit it from the list.');

    const fd = new FormData();
    fd.append('class_id', classId); fd.append('is_edit', isEdit ? 1 : 0);
    divs.forEach(v => fd.append('divisions[]', v));
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

  /* ---------- edit + delete ---------- */
  tbody.addEventListener('click', e => {
    const ed = e.target.closest('[data-edit]');
    if (ed) return open({ classId: +ed.dataset.classId, divs: JSON.parse(ed.dataset.divs) });

    const del = e.target.closest('[data-del]');
    if (!del) return;
    Swal.fire({
      title: 'Are you sure?', text: 'All divisions of class ' + del.dataset.name + ' will be removed.', icon: 'warning',
      showCancelButton: true, confirmButtonColor: '#dc2626',
      confirmButtonText: 'Yes, delete it', cancelButtonText: 'Cancel'
    }).then(async r => {
      if (!r.isConfirmed) return;
      const fd = new FormData();
      fd.append('class_id', del.dataset.del); fd.append(CSRF_NAME, CSRF_HASH);
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
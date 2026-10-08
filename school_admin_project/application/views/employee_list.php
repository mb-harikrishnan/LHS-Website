<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.frow{display:grid;grid-template-columns:130px 1fr;gap:4px 14px;align-items:center;margin-bottom:12px;text-align:left}
.frow>label{font-size:13px;font-weight:600;color:#1e3a8a}
.frow .hint{grid-column:2;font-size:12px;color:#94a3b8}
.sw-input{width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:10px;font:inherit;font-size:14px;background:#fff;box-sizing:border-box}
.sw-input:disabled{background:#f1f5f9;cursor:not-allowed}
.mask{letter-spacing:2px;color:#94a3b8}
.tag{display:inline-block;padding:3px 10px;border-radius:999px;background:#eef2ff;color:#4338ca;font-size:12px;font-weight:600}
.ibtn{border:1px solid #cbd5e1;background:#fff;border-radius:8px;width:36px;height:36px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;color:#334155}
.ibtn:hover{background:#f1f5f9}
.ibtn.danger{color:#dc2626;border-color:#fecaca;background:#fef2f2}
.acts{display:flex;gap:6px;justify-content:flex-end}
[hidden]{display:none !important}
@media(max-width:560px){.frow{grid-template-columns:1fr}.frow .hint{grid-column:1}}
</style>

<?php
$cls_js  = []; foreach ($classes as $c) $cls_js[]  = ['id' => (int) $c->cmId,     'name' => $c->cmName];
$role_js = []; foreach ($roles as $r)   $role_js[] = ['id' => (int) $r->role_id,  'name' => $r->role_name];
?>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Employee List</h1>
      <p class="page-sub">Manage employees, their designations and classes</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAdd">+ Add Employee</button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <input type="text" id="search" placeholder="Search name, mobile, designation, class…" aria-label="Search employees">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:60px">SL</th>
            <th style="width:110px">Date</th>
            <th>Name</th>
            <th style="width:110px">Password</th>
            <th>Mobile</th>
            <th>Designation</th>
            <th>Class</th>
            <th>Division</th>
            <th style="width:110px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php if (!empty($employees)): $n = 1; foreach ($employees as $e): ?>
          <tr data-text="<?= html_escape(strtolower($e->emName.' '.$e->emPhoneNo.' '.$e->role_name.' '.$e->cmName.' '.$e->dmName)) ?>">
            <td class="num"><?= $n++ ?></td>
            <td><?= $e->emTS ? date('d-m-Y', strtotime($e->emTS)) : '-' ?></td>
            <td><strong><?= html_escape($e->emName) ?></strong></td>
            <td><span class="mask">••••••••</span></td>
            <td><?= html_escape($e->emPhoneNo) ?></td>
            <td><?= html_escape($e->role_name) ?></td>
            <td><?= html_escape($e->cmName) ?></td>
            <td><span class="tag"><?= html_escape($e->dmName) ?></span></td>
            <td>
              <div class="acts">
                <button class="ibtn js-edit" type="button" title="Edit"
                  data-id="<?= $e->emId ?>"
                  data-name="<?= html_escape($e->emName) ?>"
                  data-mobile="<?= html_escape($e->emPhoneNo) ?>"
                  data-role="<?= (int) $e->emDesigId ?>"
                  data-class="<?= (int) $e->emClass ?>"
                  data-div="<?= (int) $e->emDiv ?>">
                  <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </button>
                <button class="ibtn danger js-del" type="button" title="Delete"
                  data-id="<?= $e->emId ?>" data-name="<?= html_escape($e->emName) ?>">
                  <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
      <div class="empty" id="empty" <?= !empty($employees) ? 'hidden' : '' ?>>No employees found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<script>
(function () {
  const $ = id => document.getElementById(id);
  const CLASSES = <?= json_encode($cls_js,  JSON_HEX_TAG | JSON_HEX_AMP) ?>;
  const ROLES   = <?= json_encode($role_js, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
  const URLS = {
    save:   "<?= base_url('employee_save') ?>",
    update: "<?= base_url('employee_update') ?>",
    del:    "<?= base_url('employee_delete') ?>",
    divs:   "<?= base_url('employee_divisions') ?>"
  };
  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  async function post(url, data) {
    const fd = new FormData();
    Object.keys(data).forEach(k => fd.append(k, data[k]));
    // If CSRF is enabled, uncomment:
    // fd.append('<?= $this->security->get_csrf_token_name() ?>', '<?= $this->security->get_csrf_hash() ?>');
    try {
      const res = await fetch(url, { method: 'POST', body: fd });
      return await res.json();
    } catch (err) {
      return { success: false, message: 'Server error. Please try again.' };
    }
  }
  const done = msg => Swal.fire({ icon: 'success', title: msg, timer: 1200, showConfirmButton: false }).then(() => location.reload());
  const opts = (list, ph) => `<option value="">${ph}</option>` + list.map(o => `<option value="${o.id}">${esc(o.name)}</option>`).join('');

  async function loadDivisions(classId, selected) {
    const sel = $('fDiv');
    if (!classId) { sel.innerHTML = opts([], 'Select Class first'); sel.disabled = true; return; }
    sel.disabled = true;
    sel.innerHTML = opts([], 'Loading...');
    const r = await post(URLS.divs, { class_id: classId });
    sel.innerHTML = opts(r.divisions || [], 'Select Option');
    sel.disabled = false;
    if (selected) sel.value = String(selected);
  }

  async function empForm(item) {
    item = item || null;
    const html = `
      <div class="frow"><label>Name *</label><input id="fName" class="sw-input" placeholder="Enter Name" maxlength="80"></div>
      <div class="frow"><label>Password ${item ? '' : '*'}</label>
        <input id="fPw" type="password" class="sw-input" placeholder="Enter Password" autocomplete="new-password">
        ${item ? '<span class="hint">Leave empty to keep the current password.</span>' : ''}</div>
      <div class="frow"><label>Mobile *</label><input id="fMobile" class="sw-input" placeholder="10 digit mobile" inputmode="numeric" maxlength="10"></div>
      <div class="frow"><label>Designation *</label><select id="fRole" class="sw-input">${opts(ROLES, 'Select Option')}</select></div>
      <div class="frow"><label>Class *</label><select id="fClass" class="sw-input">${opts(CLASSES, 'Select Option')}</select></div>
      <div class="frow"><label>Division *</label><select id="fDiv" class="sw-input" disabled>${opts([], 'Select Class first')}</select></div>`;

    const result = await Swal.fire({
      title: item ? 'Edit Employee' : 'Add Employee',
      html: html,
      width: 560,
      showCancelButton: true,
      confirmButtonText: item ? 'Update' : 'Save',
      showLoaderOnConfirm: true,
      allowOutsideClick: () => !Swal.isLoading(),
      didOpen: () => {
        $('fClass').addEventListener('change', () => loadDivisions($('fClass').value));
        if (item) {
          $('fName').value   = item.name;
          $('fMobile').value = item.mobile;
          $('fRole').value   = item.role ? String(item.role) : '';
          $('fClass').value  = item.cls ? String(item.cls) : '';
          loadDivisions(item.cls, item.div);
        }
        $('fName').focus();
      },
      preConfirm: async () => {
        const v = {
          name: $('fName').value.trim(), password: $('fPw').value, mobile: $('fMobile').value.trim(),
          role_id: $('fRole').value, class_id: $('fClass').value, division_id: $('fDiv').value
        };
        const bad = m => { Swal.showValidationMessage(m); return false; };

        if (!v.name) return bad('Please enter the name.');
        if (!item && !v.password) return bad('Please enter a password.');
        if (v.password && v.password.length < 6) return bad('Password must be at least 6 characters.');
        if (!/^\d{10}$/.test(v.mobile)) return bad('Mobile number must be 10 digits.');
        if (!v.role_id) return bad('Please select a designation.');
        if (!v.class_id) return bad('Please select a class.');
        if (!v.division_id) return bad('Please select a division.');

        let r;
        if (item) { v.emp_id = item.id; r = await post(URLS.update, v); }
        else      { r = await post(URLS.save, v); }
        if (!r.success) return bad(r.message);
        return r;
      }
    });
    if (result.isConfirmed) done(result.value.message);
  }

  $('openAdd').addEventListener('click', () => empForm(null));

  $('rows').addEventListener('click', async e => {
    const ed = e.target.closest('.js-edit'), del = e.target.closest('.js-del');

    if (ed) {
      empForm({ id: ed.dataset.id, name: ed.dataset.name, mobile: ed.dataset.mobile,
                role: +ed.dataset.role, cls: +ed.dataset.class, div: +ed.dataset.div });
      return;
    }
    if (del) {
      const c = await Swal.fire({
        title: 'Are you sure?',
        text: 'Delete employee "' + del.dataset.name + '"? Their login will also be removed.',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc2626', confirmButtonText: 'Yes, delete'
      });
      if (!c.isConfirmed) return;
      const r = await post(URLS.del, { emp_id: del.dataset.id });
      r.success ? done(r.message) : Swal.fire({ icon: 'error', title: 'Oops', text: r.message });
    }
  });

  function filter() {
    const q = $('search').value.trim().toLowerCase();
    const rows = [...$('rows').querySelectorAll('tr')];
    let shown = 0;
    rows.forEach(tr => {
      const ok = !q || tr.dataset.text.includes(q);
      tr.hidden = !ok;
      if (ok) tr.querySelector('.num').textContent = ++shown;
    });
    $('empty').hidden = shown > 0;
    $('count').textContent = 'Showing ' + shown + ' of ' + rows.length + ' employees';
  }
  $('search').addEventListener('input', filter);
  filter();
})();
</script>
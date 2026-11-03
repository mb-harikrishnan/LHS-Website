<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* enable / disable switch */
.act{display:flex;align-items:center;gap:10px}
.switch{position:relative;width:44px;height:24px;flex:none}
.switch input{position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer;margin:0;z-index:1}
.switch .track{position:absolute;inset:0;background:#cbd5e1;border-radius:99px;transition:background .2s}
.switch .track::after{content:"";position:absolute;top:3px;left:3px;width:18px;height:18px;background:#fff;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,.3);transition:transform .2s}
.switch input:checked + .track{background:#22c55e}
.switch input:checked + .track::after{transform:translateX(20px)}
.state{font-size:12px;font-weight:700;min-width:62px}
.state.on{color:#15803d}
.state.off{color:#94a3b8}
tr.is-off .v-title{color:#94a3b8}
[hidden]{display:none !important}

.btn-sm{padding:6px 10px;font-size:13px}
.btn-danger{background:#dc2626;color:#fff;border-color:#dc2626}
.act-btns{display:flex;gap:6px}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Role List</h1>
      <p class="page-sub">Manage roles and enable or disable them</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">+ Add Role</button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <input type="text" id="search" placeholder="Search role…" aria-label="Search roles">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:80px">Sl No</th>
            <th>Name</th>
            <th style="width:200px">Status</th>
            <th style="width:160px">Action</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php if (!empty($roles)): $n = 1; foreach ($roles as $r): ?>
            <tr class="<?= $r->status == 1 ? '' : 'is-off' ?>" data-name="<?= strtolower(html_escape($r->role_name)) ?>">
              <td class="num"><?= $n++ ?></td>
              <td class="v-title"><?= html_escape($r->role_name) ?></td>
              <td>
                <div class="act">
                  <label class="switch" title="<?= $r->status == 1 ? 'Click to deactivate' : 'Click to activate' ?>">
                    <input type="checkbox" class="js-toggle" data-id="<?= $r->role_id ?>" <?= $r->status == 1 ? 'checked' : '' ?>>
                    <span class="track"></span>
                  </label>
                  <span class="state <?= $r->status == 1 ? 'on' : 'off' ?>"><?= $r->status == 1 ? 'Active' : 'Inactive' ?></span>
                </div>
              </td>
              <td>
                <div class="act-btns">
                  <button type="button" class="btn btn-sm js-edit"
                          data-id="<?= $r->role_id ?>"
                          data-name="<?= html_escape($r->role_name) ?>">Edit</button>
                  <button type="button" class="btn btn-sm btn-danger js-delete"
                          data-id="<?= $r->role_id ?>"
                          data-name="<?= html_escape($r->role_name) ?>">Delete</button>
                </div>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
      <div class="empty" id="empty" <?= !empty($roles) ? 'hidden' : '' ?>>No roles found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<script>
(function () {
  const $ = id => document.getElementById(id);
  const URLS = {
    save:   "<?= base_url('user_role_save') ?>",
    update: "<?= base_url('user_role_update') ?>",
    del:    "<?= base_url('user_role_delete') ?>",
    status: "<?= base_url('user_role_status') ?>"
  };

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

  function successAndReload(msg) {
    Swal.fire({ icon: 'success', title: msg, timer: 1200, showConfirmButton: false })
        .then(() => location.reload());
  }

  // ADD / EDIT popup (same function for both)
  async function roleForm(id = '', name = '') {
    const result = await Swal.fire({
      title: id ? 'Edit Role' : 'Add Role',
      input: 'text',
      inputValue: name,
      inputLabel: 'Role',
      inputPlaceholder: 'Enter role',
      inputAttributes: { maxlength: 60 },
      showCancelButton: true,
      confirmButtonText: 'Save',
      showLoaderOnConfirm: true,
      allowOutsideClick: () => !Swal.isLoading(),
      preConfirm: async (value) => {
        value = (value || '').trim();
        if (!value) { Swal.showValidationMessage('Please enter a role.'); return false; }
        const r = id
          ? await post(URLS.update, { role_id: id, role_name: value })
          : await post(URLS.save,   { role_name: value });
        if (!r.success) { Swal.showValidationMessage(r.message); return false; }
        return r;
      }
    });
    if (result.isConfirmed) successAndReload(result.value.message);
  }

  $('openAddModal').addEventListener('click', () => roleForm());

  // EDIT / DELETE buttons
  $('rows').addEventListener('click', async e => {
    const edit = e.target.closest('.js-edit');
    if (edit) { roleForm(edit.dataset.id, edit.dataset.name); return; }

    const del = e.target.closest('.js-delete');
    if (del) {
      const c = await Swal.fire({
        title: 'Are you sure?',
        text: 'Delete role "' + del.dataset.name + '"? This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Yes, delete it'
      });
      if (!c.isConfirmed) return;

      const r = await post(URLS.del, { role_id: del.dataset.id });
      r.success ? successAndReload(r.message)
                : Swal.fire({ icon: 'error', title: 'Oops', text: r.message });
    }
  });

  // ACTIVE / INACTIVE toggle
  $('rows').addEventListener('change', async e => {
    const t = e.target.closest('.js-toggle');
    if (!t) return;
    const r = await post(URLS.status, { role_id: t.dataset.id, status: t.checked ? 1 : 0 });
    if (r.success) {
      successAndReload(r.message);
    } else {
      t.checked = !t.checked;
      Swal.fire({ icon: 'error', title: 'Oops', text: r.message });
    }
  });

  // Search + count
  function filter() {
    const q = $('search').value.trim().toLowerCase();
    const rows = [...$('rows').querySelectorAll('tr')];
    let shown = 0;
    rows.forEach(tr => {
      const ok = !q || tr.dataset.name.includes(q);
      tr.hidden = !ok;
      if (ok) tr.querySelector('.num').textContent = ++shown;
    });
    $('empty').hidden = shown > 0;
    $('count').textContent = 'Showing ' + shown + ' of ' + rows.length + ' roles';
  }
  $('search').addEventListener('input', filter);
  filter();
})();
</script>
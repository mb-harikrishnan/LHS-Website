<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.perm-top{display:flex;align-items:center;gap:12px;padding:16px 20px;flex-wrap:wrap}
.perm-top label{font-size:14px;font-weight:700;color:#1e3a8a}
.perm-top select{min-width:240px;padding:10px 12px;border:1px solid #cbd5e1;border-radius:10px;font:inherit;font-size:14px;background:#fff}
.perm-top select:focus{outline:2px solid #6366f1;outline-offset:1px}

.perm-wrap{overflow-x:auto;padding:0 20px}
.perm{width:100%;border-collapse:collapse;min-width:640px}
.perm th{padding:12px 8px;border-bottom:1px solid #e2e8f0;color:#1e3a8a;font-size:15px;font-weight:700;text-align:center;white-space:nowrap}
.perm th:first-child{text-align:left;padding-left:8px}
.perm th label{display:inline-flex;align-items:center;gap:6px;cursor:pointer}
.perm td{padding:11px 8px;border-bottom:1px solid #eef0f4;text-align:center}
.perm td:first-child{text-align:left}
.perm tr.parent td{background:#fafafa}
.perm tr.parent .mn{color:#1e3a8a;font-size:15px;font-weight:600}
.perm tr.child .mn{color:#475569;font-size:15px;padding-left:34px}
.mn{display:inline-flex;align-items:center;gap:10px;cursor:pointer}
.perm input[type=checkbox]{width:16px;height:16px;accent-color:#4f46e5;cursor:pointer;margin:0}
.perm.locked{opacity:.45;pointer-events:none}

.perm-foot{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:16px 20px;border-top:1px solid #e2e8f0;margin-top:8px;flex-wrap:wrap}
.perm-msg{font-size:13px;color:#64748b}
.perm-msg.warn{color:#b45309;font-weight:600}
.perm-btns{display:flex;gap:10px}
.perm-btns .btn:disabled{opacity:.5;cursor:not-allowed}
.hint-row{padding:30px;text-align:center;color:#94a3b8;font-size:14px}
[hidden]{display:none !important}
</style>

<?php
$roles_js = [];
foreach ($roles as $r) {
    $roles_js[] = ['id' => (int) $r->role_id, 'name' => $r->role_name];
}
$menus_js = [];
foreach ($menus as $p) {
    $menus_js[] = ['id' => (int) $p->menu_id, 'name' => $p->display_name, 'parent' => null];
    foreach ($p->children as $c) {
        $menus_js[] = ['id' => (int) $c->menu_id, 'name' => $c->display_name, 'parent' => (int) $p->menu_id];
    }
}
?>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Menu Permission</h1>
      <p class="page-sub">Choose a role and set what it can view, add, edit and delete</p>
    </div>
  </div>

  <div class="card">
    <div class="perm-top">
      <label for="roleSel">Select Role:</label>
      <select id="roleSel"></select>
    </div>

    <div class="perm-wrap">
      <table class="perm locked" id="permTable">
        <thead>
          <tr>
            <th>Menu</th>
            <th><label><input type="checkbox" data-col="v"> View</label></th>
            <th><label><input type="checkbox" data-col="a"> Add</label></th>
            <th><label><input type="checkbox" data-col="e"> Edit</label></th>
            <th><label><input type="checkbox" data-col="d"> Delete</label></th>
          </tr>
        </thead>
        <tbody id="rows"></tbody>
      </table>
    </div>
    <div class="hint-row" id="hint">Select a role to set its permissions.</div>

    <div class="perm-foot">
      <span class="perm-msg" id="msg"></span>
      <div class="perm-btns">
        <button type="button" class="btn" id="resetBtn" disabled>Reset</button>
        <button type="button" class="btn btn-primary" id="saveBtn" disabled>Save Permissions</button>
      </div>
    </div>
  </div>
</main>

<script>
(function () {
  const $ = id => document.getElementById(id);
  const KEYS = ['v', 'a', 'e', 'd'];
  const ROLES = <?= json_encode($roles_js, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
  const MENUS = <?= json_encode($menus_js, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
  const URLS = {
    load: "<?= base_url('get_role_permissions') ?>",
    save: "<?= base_url('save_menu_permissions') ?>"
  };

  const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const blank = () => { const o = {}; MENUS.forEach(m => o[m.id] = { v: false, a: false, e: false, d: false }); return o; };
  const clone = o => JSON.parse(JSON.stringify(o));
  const kidsOf = id => MENUS.filter(m => m.parent === id).map(m => m.id);

  const store = {};      // last saved/loaded permissions per role
  let roleId = null;
  let cur = null;        // working copy

  $('roleSel').innerHTML = '<option value="">-- Select Role --</option>' +
    ROLES.map(r => `<option value="${r.id}">${esc(r.name)}</option>`).join('');

  const dirty = () => roleId && cur && JSON.stringify(cur) !== JSON.stringify(store[roleId] || blank());
  function say(text, cls) { $('msg').textContent = text || ''; $('msg').className = 'perm-msg ' + (cls || ''); }

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

  function setCell(id, key, val) {
    const c = cur[id];
    c[key] = val;
    if (key === 'v' && !val) { c.a = c.e = c.d = false; }
    if (key !== 'v' && val) c.v = true;
  }
  const state = list => {
    const n = list.filter(Boolean).length;
    return n === 0 ? 'none' : n === list.length ? 'all' : 'some';
  };
  function paint(el, st) { if (!el) return; el.checked = st === 'all'; el.indeterminate = st === 'some'; }

  function render() {
    if (!MENUS.length) {
      $('rows').innerHTML = '<tr><td colspan="5" class="hint-row">No active menus found.</td></tr>';
      return;
    }
    $('rows').innerHTML = MENUS.map(m => {
      const isChild = m.parent !== null;
      const cells = KEYS.map(k =>
        `<td><input type="checkbox" data-cell="${m.id}|${k}" ${cur && cur[m.id][k] ? 'checked' : ''} aria-label="${esc(m.name)} ${k}"></td>`).join('');
      return `<tr class="${isChild ? 'child' : 'parent'}">
        <td><label class="mn"><input type="checkbox" data-row="${m.id}" aria-label="Select all for ${esc(m.name)}"> ${esc(m.name)}</label></td>
        ${cells}</tr>`;
    }).join('');
    sync();
  }

  function sync() {
    const on = !!cur;
    $('permTable').classList.toggle('locked', !on);
    $('hint').hidden = on;
    $('saveBtn').disabled = !on || !dirty();
    $('resetBtn').disabled = !on || !dirty();
    if (!on) {
      document.querySelectorAll('[data-col]').forEach(c => { c.checked = false; c.indeterminate = false; });
      return;
    }
    MENUS.forEach(m => {
      KEYS.forEach(k => {
        const el = document.querySelector(`[data-cell="${m.id}|${k}"]`);
        if (el) el.checked = cur[m.id][k];
      });
      const ids = [m.id].concat(kidsOf(m.id));
      const all = [];
      ids.forEach(i => KEYS.forEach(k => all.push(cur[i][k])));
      paint(document.querySelector(`[data-row="${m.id}"]`), state(all));
    });
    KEYS.forEach(k => paint(document.querySelector(`[data-col="${k}"]`), state(MENUS.map(m => cur[m.id][k]))));
  }

  // select role -> load saved permissions from the database
  $('roleSel').addEventListener('change', async () => {
    const newId = $('roleSel').value ? +$('roleSel').value : null;

    if (dirty()) {
      const c = await Swal.fire({
        title: 'Unsaved changes',
        text: 'Discard your changes and switch role?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, discard'
      });
      if (!c.isConfirmed) { $('roleSel').value = roleId || ''; return; }
    }

    roleId = newId;
    say('');
    if (!roleId) { cur = null; render(); return; }

    Swal.fire({ title: 'Loading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    const r = await post(URLS.load, { role_id: roleId });
    Swal.close();

    if (!r.success) {
      Swal.fire({ icon: 'error', title: 'Oops', text: r.message });
      roleId = null; cur = null; $('roleSel').value = ''; render();
      return;
    }

    const saved = blank();
    const p = r.permissions || {};
    MENUS.forEach(m => {
      if (p[m.id]) KEYS.forEach(k => saved[m.id][k] = !!+p[m.id][k]);
    });
    store[roleId] = saved;
    cur = clone(saved);
    render();
  });

  $('permTable').addEventListener('change', e => {
    const t = e.target;
    if (!cur) return;
    if (t.dataset.cell) {
      const p = t.dataset.cell.split('|');
      setCell(+p[0], p[1], t.checked);
    } else if (t.dataset.row) {
      const id = +t.dataset.row, val = t.checked;
      [id].concat(kidsOf(id)).forEach(i => KEYS.forEach(k => setCell(i, k, val)));
    } else if (t.dataset.col) {
      MENUS.forEach(m => setCell(m.id, t.dataset.col, t.checked));
    } else return;
    say(dirty() ? 'Unsaved changes' : '', dirty() ? 'warn' : '');
    sync();
  });

  $('resetBtn').addEventListener('click', () => {
    cur = clone(store[roleId] || blank());
    say('');
    sync();
  });

  // save to the database
  $('saveBtn').addEventListener('click', async () => {
    if (!roleId) return;
    $('saveBtn').disabled = true;
    const r = await post(URLS.save, { role_id: roleId, permissions: JSON.stringify(cur) });
    if (r.success) {
      store[roleId] = clone(cur);
      say('');
      sync();
      Swal.fire({ icon: 'success', title: r.message, timer: 1400, showConfirmButton: false });
    } else {
      sync();
      Swal.fire({ icon: 'error', title: 'Oops', text: r.message });
    }
  });

  window.addEventListener('beforeunload', e => { if (dirty()) { e.preventDefault(); e.returnValue = ''; } });

  render();
})();
</script>
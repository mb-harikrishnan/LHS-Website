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
.perm tr.parent .mn{color:#1e3a8a;font-size:15px}
.perm tr.child .mn{color:#475569;font-size:15px;padding-left:34px}
.mn{display:inline-flex;align-items:center;gap:10px;cursor:pointer}
.perm input[type=checkbox]{width:16px;height:16px;accent-color:#4f46e5;cursor:pointer;margin:0}
.perm.locked{opacity:.45;pointer-events:none}

.perm-foot{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:16px 20px;border-top:1px solid #e2e8f0;margin-top:8px;flex-wrap:wrap}
.perm-msg{font-size:13px;color:#64748b}
.perm-msg.ok{color:#15803d;font-weight:600}
.perm-msg.warn{color:#b45309;font-weight:600}
.perm-btns{display:flex;gap:10px}
.perm-btns .btn:disabled{opacity:.5;cursor:not-allowed}
.hint-row{padding:30px;text-align:center;color:#94a3b8;font-size:14px}
</style>

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

  // Load these from your Role List and Menu List pages / backend later
  const ROLES = [
    { id: 1, name: 'Admin' }, { id: 2, name: 'Teacher' },
    { id: 3, name: 'Accountant' }, { id: 4, name: 'Librarian' }
  ];
  const MENUS = [
    { id: 1, name: 'Teacher Dashboard', parent: null },
    { id: 2, name: 'Mandatory Disclosure', parent: null },
    { id: 3, name: 'Document And Information', parent: 2 },
    { id: 4, name: 'Result & Staff', parent: 2 },
    { id: 5, name: 'Infrastructure video', parent: 2 },
    { id: 6, name: 'Dashboard', parent: null },
    { id: 7, name: 'Students', parent: null },
    { id: 8, name: 'Division', parent: 7 },
    { id: 9, name: 'Class Division Allocation', parent: 7 }
  ];

  const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const blank = () => { const o = {}; MENUS.forEach(m => o[m.id] = { v: false, a: false, e: false, d: false }); return o; };
  const clone = o => JSON.parse(JSON.stringify(o));
  const kidsOf = id => MENUS.filter(m => m.parent === id).map(m => m.id);

  const store = {};          // saved permissions per role id
  let roleId = null;
  let cur = null;            // working copy for the selected role

  $('roleSel').innerHTML = '<option value="">-- Select Role --</option>' +
    ROLES.map(r => `<option value="${r.id}">${esc(r.name)}</option>`).join('');

  const dirty = () => roleId && JSON.stringify(cur) !== JSON.stringify(store[roleId] || blank());

  function say(text, cls) { $('msg').textContent = text || ''; $('msg').className = 'perm-msg ' + (cls || ''); }

  /* ---------- rules ---------- */
  function setCell(id, key, val) {
    const c = cur[id];
    c[key] = val;
    if (key === 'v' && !val) { c.a = c.e = c.d = false; }       // no view = no other access
    if (key !== 'v' && val) c.v = true;                          // add/edit/delete needs view
  }
  const state = list => {                                        // list of booleans -> 'all' | 'some' | 'none'
    const n = list.filter(Boolean).length;
    return n === 0 ? 'none' : n === list.length ? 'all' : 'some';
  };
  function paint(el, st) { el.checked = st === 'all'; el.indeterminate = st === 'some'; }

  /* ---------- render ---------- */
  function render() {
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

  // refresh the row, parent and header "select all" boxes (no full re-render)
  function sync() {
    const on = !!cur;
    $('permTable').classList.toggle('locked', !on);
    $('hint').hidden = on;
    $('saveBtn').disabled = !on || !dirty();
    $('resetBtn').disabled = !on || !dirty();
    if (!on) { document.querySelectorAll('[data-col]').forEach(c => { c.checked = false; c.indeterminate = false; }); return; }

    MENUS.forEach(m => {
      KEYS.forEach(k => {
        const el = document.querySelector(`[data-cell="${m.id}|${k}"]`);
        if (el) el.checked = cur[m.id][k];
      });
      const ids = [m.id].concat(kidsOf(m.id));                  // a parent row also covers its sub-menus
      const all = [];
      ids.forEach(i => KEYS.forEach(k => all.push(cur[i][k])));
      paint(document.querySelector(`[data-row="${m.id}"]`), state(all));
    });
    KEYS.forEach(k => paint(document.querySelector(`[data-col="${k}"]`), state(MENUS.map(m => cur[m.id][k]))));
  }

  /* ---------- events ---------- */
  $('roleSel').addEventListener('change', () => {
    if (dirty() && !confirm('You have unsaved changes. Discard them?')) {
      $('roleSel').value = roleId || '';
      return;
    }
    roleId = $('roleSel').value ? +$('roleSel').value : null;
    cur = roleId ? clone(store[roleId] || blank()) : null;
    // TODO: load from backend, e.g. fetch('/api/roles/' + roleId + '/permissions').then(r => r.json())...
    say('');
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

  $('saveBtn').addEventListener('click', () => {
    store[roleId] = clone(cur);
    // TODO: fetch('/api/roles/' + roleId + '/permissions', {
    //   method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify(cur)
    // })
    say('Permissions saved successfully.', 'ok');
    sync();
  });

  window.addEventListener('beforeunload', e => { if (dirty()) { e.preventDefault(); e.returnValue = ''; } });

  render();
})();
</script>
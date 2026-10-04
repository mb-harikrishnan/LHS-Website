<style>
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:620px;max-height:92vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #e2e8f0}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:#64748b}
.modal-body{padding:24px 20px 12px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid #e2e8f0;background:#f8fafc}

/* label left, field right (like your screenshot) */
.frow{display:grid;grid-template-columns:150px 1fr;gap:6px 16px;align-items:center;margin-bottom:18px}
.frow>label{font-size:14px;font-weight:600;color:#1e3a8a}
.req{color:#dc2626}
.field-input{width:100%;padding:11px 12px;border:1px solid #cbd5e1;border-radius:10px;font:inherit;font-size:14px;background:#fff}
.field-input:focus{outline:2px solid #6366f1;outline-offset:1px}
.field-input:disabled{background:#f1f5f9;cursor:not-allowed}
.frow .hint{grid-column:2;font-size:12px;color:#94a3b8;margin-top:-2px}
.field-error{color:#dc2626;font-size:13px;margin:0 0 12px}

/* list */
.t-menu{font-weight:700;color:#334155;white-space:nowrap}
.sub .t-menu{font-weight:500;padding-left:22px}
.sub td{background:#f8fafc}
.sub .ln{color:#94a3b8;margin-right:6px}
.tg{width:18px;height:18px;border-radius:4px;border:0;background:#1e3a8a;color:#fff;font-size:14px;line-height:1;cursor:pointer;margin-right:8px;display:inline-flex;align-items:center;justify-content:center;vertical-align:middle}
.tg-gap{display:inline-block;width:26px}
.st{font-weight:700;font-size:13px}
.st.on{color:#334155}
.st.off{color:#dc2626}
.sub .st,.sub .dn,.sub .lk{font-weight:500}
.dn,.lk{color:#334155}
.is-off td{opacity:.6}
.is-off td:nth-last-child(-n+2){opacity:1}
.ed{border:1px solid #bfdbfe;background:#eff6ff;color:#2563eb;border-radius:10px;width:58px;height:38px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center}
.ed:hover{background:#dbeafe}
.sw{border:0;border-radius:8px;padding:8px 14px;color:#fff;font:inherit;font-size:13px;font-weight:600;cursor:pointer;min-width:92px}
.sw.en{background:#22a846}.sw.en:hover{background:#1c8f3b}
.sw.dis{background:#dc2626}.sw.dis:hover{background:#b91c1c}
[hidden]{display:none !important}
@media(max-width:560px){.frow{grid-template-columns:1fr}.frow .hint{grid-column:1}}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Menu List</h1>
      <p class="page-sub">Manage menus, sub-menus and their status</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Menu
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search menu name, display name or link…" aria-label="Search menus">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:60px">SL</th>
            <th>Menu Name</th>
            <th>Display Name</th>
            <th>Link</th>
            <th style="width:80px">Order</th>
            <th style="width:90px">Status</th>
            <th style="width:90px;text-align:center">Edit</th>
            <th style="width:130px;text-align:center">Action</th>
          </tr>
        </thead>
        <tbody id="rows"></tbody>
      </table>
      <div class="empty" id="empty" hidden>No menus found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Menu</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <div class="modal-body">
        <div class="frow">
          <label for="mName">Menu Name <span class="req">*</span></label>
          <input id="mName" class="field-input" placeholder="e.g. EXAM_MASTER" maxlength="60">
        </div>
        <div class="frow">
          <label for="mDisp">Display Name <span class="req">*</span></label>
          <input id="mDisp" class="field-input" placeholder="e.g. Exam Master" maxlength="60">
        </div>
        <div class="frow">
          <label for="mLink">Menu Link</label>
          <input id="mLink" class="field-input" placeholder="/exams/master" maxlength="120">
          <span class="hint">Leave empty for a parent menu that only holds sub-menus.</span>
        </div>
        <div class="frow">
          <label for="mParent">Parent Menu</label>
          <select id="mParent" class="field-input"></select>
          <span class="hint" id="parentHint" hidden>This menu has sub-menus, so it must stay a top-level menu.</span>
        </div>
        <div class="frow">
          <label for="mOrder">Display Order <span class="req">*</span></label>
          <input id="mOrder" class="field-input" type="number" min="1" step="1">
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

<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('addModal'), form = $('addForm'), errorEl = $('formError');

  let items = [
    { id:1,  name:'EMPLOYEE_DASHBOARD',    disp:'Teacher Dashboard',        link:'/teacherdashboard',  parent:null, order:2, active:true },
    { id:2,  name:'MANDATORY_DISCLOSURE',  disp:'Mandatory Disclosure',     link:'',                   parent:null, order:2, active:true },
    { id:3,  name:'DOCUMENT_INFORMATION',  disp:'Document And Information', link:'/general_information',parent:2,   order:1, active:true },
    { id:4,  name:'RESULT_STAFF',          disp:'Result & Staff',           link:'/Result_and_Staff',  parent:2,    order:2, active:true },
    { id:5,  name:'INFRASTRUCTURE_VIDEO',  disp:'Infrastructure video',     link:'/infrastructure',    parent:2,    order:3, active:true },
    { id:6,  name:'MAIN_DASHBOARD',        disp:'Dashboard',                link:'/dashboard',         parent:null, order:2, active:true },
    { id:7,  name:'STUDENTS',              disp:'Students',                 link:'',                   parent:null, order:3, active:true },
    { id:8,  name:'STUDENT_LIST',          disp:'Student List',             link:'/students',          parent:7,    order:1, active:true },
    { id:9,  name:'SCHOOL_NEWS',           disp:'School News',              link:'/school_news',       parent:null, order:3, active:true }
  ];
  let nextId = 10, editId = null;
  const expanded = new Set([2]);

  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const byOrder = (a, b) => a.order - b.order || a.id - b.id;
  const kids = id => items.filter(i => i.parent === id).sort(byOrder);
  const hit = (i, q) => [i.name, i.disp, i.link].some(v => v.toLowerCase().includes(q));

  const PEN = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>';

  function row(i, n, isSub, hasKids, open) {
    const toggle = isSub ? '<span class="ln">↳</span>'
      : hasKids ? `<button class="tg" type="button" data-tg="${i.id}" aria-label="${open ? 'Collapse' : 'Expand'} ${esc(i.name)}">${open ? '−' : '+'}</button>`
      : '<span class="tg-gap"></span>';
    return `
      <tr class="${isSub ? 'sub' : ''} ${i.active ? '' : 'is-off'}">
        <td class="num">${isSub ? '' : n}</td>
        <td class="t-menu">${toggle}${esc(i.name)}</td>
        <td class="dn">${esc(i.disp)}</td>
        <td class="lk">${i.link ? esc(i.link) : '-'}</td>
        <td>${i.order}</td>
        <td><span class="st ${i.active ? 'on' : 'off'}">${i.active ? 'Active' : 'Inactive'}</span></td>
        <td style="text-align:center"><button class="ed" type="button" title="Edit" data-edit="${i.id}" aria-label="Edit ${esc(i.name)}">${PEN}</button></td>
        <td style="text-align:center">
          <button class="sw ${i.active ? 'dis' : 'en'}" type="button" data-sw="${i.id}">${i.active ? '✕ Disable' : '✓ Enable'}</button>
        </td>
      </tr>`;
  }

  function render() {
    const q = $('search').value.trim().toLowerCase();
    let html = '', n = 0, shown = 0;
    items.filter(i => !i.parent).sort(byOrder).forEach(p => {
      const ch = kids(p.id);
      let list = ch;
      if (q) {
        const selfHit = hit(p, q);
        list = selfHit ? ch : ch.filter(c => hit(c, q));
        if (!selfHit && !list.length) return;
      }
      n++; shown++;
      const open = q ? list.length > 0 : expanded.has(p.id);
      html += row(p, n, false, ch.length > 0, open);
      if (open) list.forEach(c => { html += row(c, 0, true); shown++; });
    });
    $('rows').innerHTML = html;
    $('empty').hidden = shown > 0;
    $('count').textContent = `Showing ${shown} of ${items.length} menus`;
  }

  /* ---------- modal ---------- */
  function fillParents(selfId, value) {
    const tops = items.filter(i => !i.parent && i.id !== selfId).sort(byOrder);
    $('mParent').innerHTML = '<option value="">-- None (Top Level) --</option>' +
      tops.map(t => `<option value="${t.id}">${esc(t.name)}</option>`).join('');
    $('mParent').value = value ? String(value) : '';
  }
  function nextOrder(parent) {
    return items.filter(i => (i.parent || null) === (parent || null)).reduce((m, i) => Math.max(m, i.order), 0) + 1;
  }
  const showError = msg => { errorEl.textContent = msg; errorEl.hidden = false; };

  function open(item) {
    form.reset();
    errorEl.hidden = true;
    editId = item ? item.id : null;
    $('addTitle').textContent = item ? 'Edit Menu' : 'Add Menu';
    $('saveBtn').textContent = item ? 'Update' : 'Save';
    fillParents(item ? item.id : null, item ? item.parent : null);
    const locked = !!item && kids(item.id).length > 0;
    $('mParent').disabled = locked;
    $('parentHint').hidden = !locked;
    $('mName').value = item ? item.name : '';
    $('mDisp').value = item ? item.disp : '';
    $('mLink').value = item ? item.link : '';
    $('mOrder').value = item ? item.order : nextOrder(null);
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    $('mName').focus();
  }
  function close() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    editId = null;
  }

  $('openAddModal').addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  // suggest the next order when the parent changes (new menu only)
  $('mParent').addEventListener('change', () => {
    if (!editId) $('mOrder').value = nextOrder($('mParent').value ? +$('mParent').value : null);
  });

  form.addEventListener('submit', e => {
    e.preventDefault();
    const name = $('mName').value.trim().toUpperCase().replace(/\s+/g, '_');
    const disp = $('mDisp').value.trim();
    const link = $('mLink').value.trim();
    const parent = $('mParent').value ? +$('mParent').value : null;
    const order = Number($('mOrder').value);

    if (!name) return showError('Please enter the menu name.');
    if (!/^[A-Z0-9_]+$/.test(name)) return showError('Menu name can use only letters, numbers and underscore.');
    if (!disp) return showError('Please enter the display name.');
    if (link && !link.startsWith('/')) return showError('Menu link must start with "/".');
    if (!Number.isInteger(order) || order < 1) return showError('Display order must be a whole number from 1.');
    if (items.some(i => i.id !== editId && i.name === name)) return showError('This menu name already exists.');

    if (editId) {
      // TODO: fetch('/api/menus/' + editId, { method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ name, disp, link, parent, order }) })
      items = items.map(i => i.id === editId ? Object.assign({}, i, { name, disp, link, parent, order }) : i);
    } else {
      // TODO: fetch('/api/menus', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ name, disp, link, parent, order }) })
      items.push({ id: nextId++, name, disp, link, parent, order, active: true });
    }
    if (parent) expanded.add(parent);
    render();
    close();
  });

  /* ---------- list buttons ---------- */
  $('rows').addEventListener('click', e => {
    const tg = e.target.closest('[data-tg]');
    const ed = e.target.closest('[data-edit]');
    const sw = e.target.closest('[data-sw]');
    if (tg) {
      const id = +tg.dataset.tg;
      expanded.has(id) ? expanded.delete(id) : expanded.add(id);
      render();
    }
    if (ed) { const it = items.find(i => i.id === +ed.dataset.edit); if (it) open(it); }
    if (sw) {
      const it = items.find(i => i.id === +sw.dataset.sw);
      if (!it) return;
      it.active = !it.active;
      // disabling a parent also disables its sub-menus
      if (!it.active) items.forEach(c => { if (c.parent === it.id) c.active = false; });
      // TODO: fetch('/api/menus/' + it.id + '/status', { method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ active: it.active }) })
      render();
    }
  });

  $('search').addEventListener('input', render);
  render();
})();
</script>
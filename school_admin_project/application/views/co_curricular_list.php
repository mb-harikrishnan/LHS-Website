
<style>

/* modal */
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:480px;max-height:92vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--slate-200)}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:var(--slate-500)}
.modal-body{padding:20px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid var(--slate-200)}
.field-label{display:block;font-size:13px;font-weight:600;margin:0 0 6px}
.req{color:#dc2626}
.field-input{width:100%;padding:9px 10px;border:1px solid var(--slate-300);border-radius:8px;font:inherit;margin-bottom:16px;background:#fff}
.dropzone{display:flex;flex-direction:column;align-items:center;gap:6px;text-align:center;padding:20px 12px;border:2px dashed var(--slate-300);border-radius:10px;cursor:pointer;color:var(--slate-500);transition:.15s}
.dropzone:hover,.dropzone.drag{border-color:var(--blue);background:#eff6ff}
.dropzone.has-file{border-style:solid;border-color:#16a34a;background:#f0fdf4;color:#166534}
.preview{display:none;width:100%;max-height:180px;object-fit:contain;border-radius:8px}
.has-file .preview{display:block}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}
.field-error[hidden]{display:none}
</style>

<body>
<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Co-Curricular List</h1>
      <p class="page-sub">Sports, arts and activity events with their photos</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Co-Curricular
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search by type or date…" aria-label="Search co-curricular list">
      </div>
      <select class="filter-select" id="typeFilter" aria-label="Filter by type">
        <option value="">All Types</option>
      </select>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th><th>Date</th><th>Type</th><th>Image</th>
            <th style="width:120px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="rows"></tbody>
      </table>
      <div class="empty" id="empty" hidden>No co-curricular entries match. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<!-- Add Modal -->
<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Co-Curricular</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <div class="modal-body">
        <label class="field-label" for="cType">Type <span class="req">*</span></label>
        <select id="cType" class="field-input">
          <option value="">Select type</option>
          <option>Volley ball</option>
          <option>Basket ball</option>
          <option>Football</option>
          <option>Cricket</option>
          <option>Dance</option>
          <option>Music</option>
          <option>Art</option>
          <option value="__other">Other…</option>
        </select>
        <input type="text" id="cOther" class="field-input" placeholder="Enter type name" hidden>

        <label class="field-label" for="cDate">Date <span class="req">*</span></label>
        <input type="date" id="cDate" class="field-input">

        <label class="field-label" for="cImage">Image <span class="req">*</span></label>
        <label class="dropzone" id="dropzone" for="cImage">
          <img class="preview" id="preview" alt="Selected image preview">
          <span id="dropText"><strong>Click to choose</strong> or drag an image here</span>
          <small>JPG, PNG or WEBP, max 5 MB</small>
        </label>
        <input type="file" id="cImage" accept="image/png,image/jpeg,image/webp" hidden>

        <p class="field-error" id="formError" hidden></p>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('addModal'), form = $('addForm'), fileInput = $('cImage');
  const dropzone = $('dropzone'), dropText = $('dropText'), preview = $('preview');
  const errorEl = $('formError'), typeSel = $('cType'), otherInp = $('cOther');
  const MAX = 5 * 1024 * 1024;
  let imageData = '';

  // sample data (placeholder images are inline SVG, replace with your API data)
  const ph = (c, t) => 'data:image/svg+xml;utf8,' + encodeURIComponent(
    `<svg xmlns="http://www.w3.org/2000/svg" width="250" height="164"><rect width="250" height="164" fill="${c}"/><text x="125" y="90" font-family="sans-serif" font-size="22" fill="#fff" text-anchor="middle">${t}</text></svg>`);
  let items = [
    { id: 1, date: '2026-06-23', type: 'Volley ball', img: ph('#b45309', 'Volley ball') },
    { id: 2, date: '2026-06-22', type: 'Basket ball', img: ph('#d97706', 'Basket ball') }
  ];
  let nextId = 3;

  const fmt = d => d.split('-').reverse().join('-'); // dd-mm-yyyy
  const esc = s => s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  function refreshFilter() {
    const cur = $('typeFilter').value;
    const types = [...new Set(items.map(i => i.type))];
    $('typeFilter').innerHTML = '<option value="">All Types</option>' +
      types.map(t => `<option value="${esc(t)}">${esc(t)}</option>`).join('');
    $('typeFilter').value = types.includes(cur) ? cur : '';
  }

  function render() {
    const q = $('search').value.trim().toLowerCase(), tf = $('typeFilter').value;
    const list = items.filter(i =>
      (!tf || i.type === tf) &&
      (!q || i.type.toLowerCase().includes(q) || fmt(i.date).includes(q)));
    $('rows').innerHTML = list.map((i, n) => `
      <tr>
        <td class="num">${n + 1}</td>
        <td>${fmt(i.date)}</td>
        <td><span class="badge">${esc(i.type)}</span></td>
        <td><img class="thumb" src="${i.img}" alt="${esc(i.type)} photo"></td>
        <td><div class="row-actions">
          <button class="icon-btn danger" title="Delete" data-del="${i.id}" aria-label="Delete ${esc(i.type)} entry">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
          </button></div></td>
      </tr>`).join('');
    $('empty').hidden = list.length > 0;
    $('count').textContent = `Showing ${list.length} of ${items.length} entries`;
  }

  const showError = m => { errorEl.textContent = m; errorEl.hidden = false; };
  function resetForm() {
    form.reset(); imageData = '';
    otherInp.hidden = true;
    dropzone.classList.remove('has-file'); preview.removeAttribute('src');
    dropText.innerHTML = '<strong>Click to choose</strong> or drag an image here';
    errorEl.hidden = true;
  }
  const open = () => { modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false'); typeSel.focus(); };
  const close = () => { modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true'); resetForm(); };

  function setFile(file) {
    errorEl.hidden = true;
    if (!/^image\/(png|jpe?g|webp)$/.test(file.type)) { fileInput.value = ''; return showError('Only JPG, PNG or WEBP images are allowed.'); }
    if (file.size > MAX) { fileInput.value = ''; return showError('Image must be 5 MB or smaller.'); }
    const r = new FileReader();
    r.onload = () => {
      imageData = r.result; preview.src = imageData;
      dropzone.classList.add('has-file');
      dropText.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
    };
    r.readAsDataURL(file);
  }

  $('openAddModal').addEventListener('click', open);
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  typeSel.addEventListener('change', () => { otherInp.hidden = typeSel.value !== '__other'; if (!otherInp.hidden) otherInp.focus(); });
  fileInput.addEventListener('change', () => fileInput.files[0] && setFile(fileInput.files[0]));
  ['dragenter', 'dragover'].forEach(ev => dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.add('drag'); }));
  ['dragleave', 'drop'].forEach(ev => dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.remove('drag'); }));
  dropzone.addEventListener('drop', e => {
    const f = e.dataTransfer.files[0];
    if (f) { fileInput.files = e.dataTransfer.files; setFile(f); }
  });

  form.addEventListener('submit', e => {
    e.preventDefault();
    const type = typeSel.value === '__other' ? otherInp.value.trim() : typeSel.value;
    const date = $('cDate').value, file = fileInput.files[0];
    if (!type) return showError('Please select or enter a type.');
    if (!date) return showError('Please choose a date.');
    if (!file || !imageData) return showError('Please choose an image.');

    // TODO: send to your backend, e.g.
    // const data = new FormData(); data.append('type', type); data.append('date', date); data.append('image', file);
    // fetch('/api/co-curricular', { method: 'POST', body: data })
    items.unshift({ id: nextId++, date, type, img: imageData });
    refreshFilter(); render(); close();
  });

  $('rows').addEventListener('click', e => {
    const b = e.target.closest('[data-del]');
    if (!b || !confirm('Delete this entry?')) return;
    items = items.filter(i => i.id !== +b.dataset.del);
    refreshFilter(); render();
  });
  $('search').addEventListener('input', render);
  $('typeFilter').addEventListener('change', render);

  refreshFilter(); render();
})();
</script>

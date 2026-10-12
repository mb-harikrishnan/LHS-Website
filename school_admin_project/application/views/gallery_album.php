<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Same image folder logic as the gallery page
$img_base   = dirname(rtrim(base_url(), '/')) . '/assets/images/gallery/';
$back_url   = site_url('gallery');
$delete_url = site_url('delete_gallery_image');
$upload_url = site_url('save_gallery_images');
?>
<main class="page">
  <div class="page-header">
    <div>
      <a class="back-link" href="<?= $back_url ?>">&larr; All albums</a>
      <h1 class="page-title"><?= html_escape($type->name) ?></h1>
      <p class="page-sub"><?= count($images) ?> <?= count($images) === 1 ? 'photo' : 'photos' ?> in this album</p>
    </div>
    <div class="page-actions">
      <button class="btn btn-primary" type="button" id="openUpload">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        Add Images
      </button>
    </div>
  </div>

  <?php if (empty($images)): ?>
    <div class="album-empty">No images in this album yet. Click <strong>Add Images</strong> to upload.</div>
  <?php else: ?>
  <div class="photo-grid" id="photoGrid">
    <?php foreach ($images as $img): ?>
      <div class="photo-item" data-id="<?= (int) $img->n_slno ?>">
        <img src="<?= html_escape($img_base . $img->c_image) ?>" alt="" loading="lazy" data-zoom>
        <button type="button" class="photo-del" data-delete="<?= (int) $img->n_slno ?>" title="Delete" aria-label="Delete image">&times;</button>
        <?php if (!empty($img->d_date)): ?>
          <div class="photo-date"><?= date('d M Y', strtotime($img->d_date)) ?></div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</main>

<!-- lightbox -->
<div class="lightbox" id="lightbox" hidden>
  <button type="button" class="lightbox-x" aria-label="Close">&times;</button>
  <img src="" alt="">
</div>

<!-- Add Images Modal (album is fixed to this one) -->
<div class="modal-overlay" id="albumModal" aria-hidden="true">
  <div class="modal" style="max-width:640px" role="dialog" aria-modal="true" aria-labelledby="albumModalTitle">
    <div class="modal-head">
      <h2 id="albumModalTitle">Add Images to <?= html_escape($type->name) ?></h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>

    <form id="albumForm" novalidate>
      <div class="modal-body">
        <label class="field-label" for="aFiles">Images <span class="req">*</span></label>
        <label class="dropzone" id="aDrop" for="aFiles">
          <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="1.6"/><path d="m21 15-5-5L5 21"/></svg>
          <span><strong>Click to choose</strong> or drag images here</span>
          <small>JPG, PNG, WEBP · max 5 MB each · up to 30 images</small>
        </label>
        <input type="file" id="aFiles" accept="image/png,image/jpeg,image/webp" multiple hidden>

        <div class="prev-head" id="aCountRow" hidden>
          <span id="aCount"></span>
          <button type="button" class="link-btn" id="aClear">Remove all</button>
        </div>
        <div class="prev-grid" id="aPreview"></div>

        <p class="field-error" id="aError" hidden></p>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary" id="aSubmit">Upload</button>
      </div>
    </form>
  </div>
</div>

<style>
.back-link{display:inline-block;font-size:13px;color:#2563eb;text-decoration:none;margin-bottom:6px}
.back-link:hover{text-decoration:underline}
.photo-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px}
.photo-item{position:relative;aspect-ratio:4/3;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;background:#f1f5f9;transition:opacity .2s,transform .2s}
.photo-item img{width:100%;height:100%;object-fit:cover;display:block;cursor:zoom-in}
.photo-item.removing{opacity:0;transform:scale(.95)}
.photo-del{position:absolute;top:8px;right:8px;width:28px;height:28px;border:0;border-radius:50%;background:rgba(15,23,42,.75);color:#fff;font-size:18px;line-height:1;cursor:pointer;opacity:0;transition:.15s;display:flex;align-items:center;justify-content:center}
.photo-item:hover .photo-del,.photo-del:focus{opacity:1}
.photo-del:hover{background:#dc2626}
@media (hover:none){.photo-del{opacity:1}}
.photo-date{position:absolute;left:0;right:0;bottom:0;padding:14px 10px 6px;background:linear-gradient(transparent,rgba(0,0,0,.6));color:#fff;font-size:11px;pointer-events:none}
.album-empty{padding:40px;text-align:center;color:#64748b;background:#fff;border:1px dashed #cbd5e1;border-radius:12px}
.lightbox{position:fixed;inset:0;background:rgba(15,23,42,.9);z-index:1100;display:flex;align-items:center;justify-content:center;padding:24px}
.lightbox[hidden]{display:none}
.lightbox img{max-width:100%;max-height:100%;border-radius:8px}
.lightbox-x{position:absolute;top:14px;right:20px;background:none;border:0;color:#fff;font-size:34px;cursor:pointer;line-height:1}

/* ---------- modal (same styles as gallery page) ---------- */
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:480px;max-height:92vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #e2e8f0}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:#64748b}
.modal-body{padding:20px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid #e2e8f0}
.field-label{display:block;font-size:13px;font-weight:600;margin:0 0 6px}
.field-label .req{color:#dc2626}
.dropzone{display:flex;flex-direction:column;align-items:center;gap:6px;text-align:center;padding:22px 12px;border:2px dashed #cbd5e1;border-radius:10px;cursor:pointer;color:#64748b;transition:.15s}
.dropzone:hover,.dropzone.drag{border-color:#2563eb;background:#eff6ff}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}
.field-error.ok{color:#059669}
.prev-head{display:flex;justify-content:space-between;align-items:center;margin:14px 0 8px;font-size:13px;font-weight:600}
.link-btn{background:none;border:0;color:#dc2626;font-size:13px;cursor:pointer;padding:0}
.link-btn:hover{text-decoration:underline}
.prev-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:10px}
.prev-item{position:relative;aspect-ratio:1/1;border-radius:10px;overflow:hidden;border:1px solid #e2e8f0;background:#f1f5f9}
.prev-item img{width:100%;height:100%;object-fit:cover;display:block}
.prev-x{position:absolute;top:5px;right:5px;width:22px;height:22px;border:0;border-radius:50%;background:rgba(15,23,42,.75);color:#fff;font-size:15px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center}
.prev-x:hover{background:#dc2626}
.prev-name{position:absolute;left:0;right:0;bottom:0;padding:3px 6px;background:linear-gradient(transparent,rgba(0,0,0,.65));color:#fff;font-size:10px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
</style>


<!-- ===================== photo grid: zoom + delete ===================== -->
<script>
(function () {
  // CSRF (CodeIgniter) - updated after every AJAX response
  const GalleryCsrf = {
    name: '<?= $this->security->get_csrf_token_name() ?>',
    hash: '<?= $this->security->get_csrf_hash() ?>'
  };
  window.GalleryCsrf = GalleryCsrf;   // shared with the upload script below

  const grid = document.getElementById('photoGrid');
  const box = document.getElementById('lightbox'), boxImg = box.querySelector('img');
  const closeBox = () => { box.hidden = true; boxImg.src = ''; };

  box.addEventListener('click', closeBox);
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && !box.hidden) closeBox(); });
  if (!grid) return;

  grid.addEventListener('click', async e => {
    const zoom = e.target.closest('[data-zoom]');
    if (zoom) { boxImg.src = zoom.src; box.hidden = false; return; }

    const btn = e.target.closest('[data-delete]'); if (!btn) return;
    if (!confirm('Delete this image?')) return;

    const body = new FormData();
    body.append('id', btn.dataset.delete);
    body.append(GalleryCsrf.name, GalleryCsrf.hash);
    btn.disabled = true;

    try {
      const res  = await fetch('<?= $delete_url ?>', { method: 'POST', body, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();
      if (json.csrf) GalleryCsrf.hash = json.csrf;
      if (!json.status) { alert(json.msg); btn.disabled = false; return; }

      const item = btn.closest('.photo-item');
      item.classList.add('removing');
      setTimeout(() => { item.remove(); if (!grid.children.length) location.reload(); }, 200);
    } catch (err) {
      alert('Network error. Please try again.');
      btn.disabled = false;
    }
  });
})();
</script>


<!-- ===================== Add Images (this album) ===================== -->
<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('albumModal'), form = $('albumForm');
  const input = $('aFiles'), drop = $('aDrop'), preview = $('aPreview');
  const countRow = $('aCountRow'), countEl = $('aCount'), errorEl = $('aError');
  const submitBtn = $('aSubmit');

  const TYPE_ID = <?= (int) $type->slno ?>;
  const UPLOAD_URL = '<?= $upload_url ?>';
  const MAX_SIZE = 5 * 1024 * 1024, MAX_FILES = 30;
  const OK_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

  let items = [];
  let uid = 0;

  const esc = s => s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const showError = t => { errorEl.classList.remove('ok'); errorEl.textContent = t; errorEl.hidden = false; };
  const showOk    = t => { errorEl.classList.add('ok');    errorEl.textContent = t; errorEl.hidden = false; };

  function render() {
    preview.innerHTML = items.map(it => `
      <div class="prev-item">
        <img src="${it.url}" alt="">
        <button type="button" class="prev-x" data-remove="${it.id}" title="Remove" aria-label="Remove image">&times;</button>
        <div class="prev-name">${esc(it.file.name)}</div>
      </div>`).join('');
    countRow.hidden = items.length === 0;
    countEl.textContent = items.length + (items.length === 1 ? ' image selected' : ' images selected');
  }

  function addFiles(fileList) {
    errorEl.hidden = true;
    const problems = [];
    for (const f of fileList) {
      if (items.length >= MAX_FILES) { problems.push('Maximum ' + MAX_FILES + ' images at a time.'); break; }
      if (!OK_TYPES.includes(f.type)) { problems.push(f.name + ': only JPG, PNG or WEBP images are allowed.'); continue; }
      if (f.size > MAX_SIZE) { problems.push(f.name + ': larger than 5 MB.'); continue; }
      if (items.some(x => x.file.name === f.name && x.file.size === f.size && x.file.lastModified === f.lastModified)) continue;
      items.push({ id: ++uid, file: f, url: URL.createObjectURL(f) });
    }
    render();
    if (problems.length) showError(problems.slice(0, 3).join(' ') + (problems.length > 3 ? ' (+' + (problems.length - 3) + ' more)' : ''));
  }

  function clearItems() {
    items.forEach(it => URL.revokeObjectURL(it.url));
    items = []; render();
  }

  function open() { modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false'); }
  function close() {
    modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true');
    form.reset(); errorEl.hidden = true; clearItems();
  }

  $('openUpload').addEventListener('click', open);
  modal.addEventListener('click', e => { if (e.target === modal || e.target.closest('[data-close]')) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  input.addEventListener('change', () => { addFiles(input.files); input.value = ''; });
  ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('drag'); }));
  ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('drag'); }));
  drop.addEventListener('drop', e => addFiles(e.dataTransfer.files));

  preview.addEventListener('click', e => {
    const btn = e.target.closest('[data-remove]'); if (!btn) return;
    const id = Number(btn.dataset.remove);
    const it = items.find(x => x.id === id);
    if (it) URL.revokeObjectURL(it.url);
    items = items.filter(x => x.id !== id);
    errorEl.hidden = true;
    render();
  });
  $('aClear').addEventListener('click', () => { clearItems(); errorEl.hidden = true; });

  form.addEventListener('submit', async e => {
    e.preventDefault();
    if (!items.length) return showError('Please add at least one image.');

    const data = new FormData();
    data.append('type', TYPE_ID);
    data.append(GalleryCsrf.name, GalleryCsrf.hash);
    items.forEach(it => data.append('images[]', it.file));

    submitBtn.disabled = true;
    submitBtn.textContent = 'Uploading...';
    errorEl.hidden = true;

    try {
      const res  = await fetch(UPLOAD_URL, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();
      if (json.csrf) GalleryCsrf.hash = json.csrf;

      if (json.status) {
        showOk(json.msg);
        setTimeout(() => window.location.reload(), 900);
      } else {
        showError(json.msg || 'Upload failed.');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Upload';
      }
    } catch (err) {
      showError('Something went wrong. Please try again.');
      submitBtn.disabled = false;
      submitBtn.textContent = 'Upload';
    }
  });
})();
</script>
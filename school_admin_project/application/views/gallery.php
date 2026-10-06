<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Public URL of the folder where images are saved.
 * Controller saves to '../assets/images/gallery/' (one level above this admin folder),
 * so we take the parent of base_url(). Change it if your setup is different.
 */
$img_base = dirname(rtrim(base_url(), '/')) . '/assets/images/gallery/';

$gradients = array(
    'linear-gradient(135deg,#3b82f6,#1d4ed8)',
    'linear-gradient(135deg,#059669,#047857)',
    'linear-gradient(135deg,#d97706,#b45309)',
    'linear-gradient(135deg,#7c3aed,#5b21b6)',
    'linear-gradient(135deg,#64748b,#334155)'
);
?>

<!-- Page Content -->
<main class="page">

  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Photo &amp; Event Gallery</h1>
      <p class="page-sub">Campus events, cultural fests, tournaments and celebratory albums</p>
    </div>
    <div class="page-actions">
      <button class="btn btn-primary" type="button" id="openAlbumModal">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        Add Images
      </button>
    </div>
  </div>

  <!-- Gallery Grid (one card per category) -->
  <div class="gallery-grid" id="galleryGrid">
    <?php if (!empty($albums)): ?>
      <?php foreach ($albums as $i => $a): ?>
        <?php
          $has_cover = !empty($a->cover);
          $thumb_style = $has_cover
              ? "background-image:url('" . html_escape($img_base . $a->cover) . "')"
              : 'background:' . $gradients[$i % count($gradients)];
          $total = (int) $a->total;
        ?>
        <a class="gallery-card-link" href="<?= site_url('GalleryController/gallery_album/' . (int) $a->type_id) ?>">
          <div class="gallery-card">
            <div class="gallery-thumb <?= $has_cover ? 'has-img' : '' ?>" style="<?= $thumb_style ?>">
              <?= $has_cover ? '' : html_escape($a->name) ?>
              <span><?= $total ?> <?= $total === 1 ? 'Photo' : 'Photos' ?></span>
            </div>
            <div class="gallery-info">
              <h3><?= html_escape($a->name) ?></h3>
              <p>
                <?= $a->last_date
                    ? 'Last updated ' . date('d M Y', strtotime($a->last_date))
                    : 'No photos added yet' ?>
              </p>
              <div class="gallery-meta">
                <span class="badge badge-purple">Album</span>
                <span class="badge <?= $total > 0 ? 'badge-ok' : 'badge-muted' ?>">
                  <?= $total > 0 ? 'Active' : 'Empty' ?>
                </span>
              </div>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="empty-note">No albums yet. Add event categories first, then upload images.</p>
    <?php endif; ?>
  </div>
</main>


<!-- Add Images Modal -->
<div class="modal-overlay" id="albumModal" aria-hidden="true">
  <div class="modal" style="max-width:640px" role="dialog" aria-modal="true" aria-labelledby="albumModalTitle">
    <div class="modal-head">
      <h2 id="albumModalTitle">Add Images</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>

    <form id="albumForm" novalidate>
      <div class="modal-body">
        <label class="field-label" for="aCategory">Category <span class="req">*</span></label>
        <select id="aCategory" class="field-input">
          <option value="">Select category</option>
          <?php foreach ($types as $t): ?>
            <option value="<?= (int) $t->slno ?>"><?= html_escape($t->name) ?></option>
          <?php endforeach; ?>
        </select>

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
/* ---------- gallery grid ---------- */
.gallery-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px}
.gallery-card-link{text-decoration:none;color:inherit;display:block}
.gallery-card{background:#fff;border:1px solid var(--line);border-radius:16px;overflow:hidden;box-shadow:var(--shadow-soft);transition:transform .15s ease,box-shadow .15s ease}
.gallery-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-lift)}
.gallery-thumb{height:160px;background:linear-gradient(135deg,var(--brand-500),var(--brand-700));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:16px;position:relative;text-align:center;padding:0 12px}
.gallery-thumb span{position:absolute;bottom:10px;right:12px;background:rgba(0,0,0,.4);padding:2px 8px;border-radius:6px;font-size:11px;backdrop-filter:blur(4px)}
.gallery-thumb.has-img{background-size:cover;background-position:center}
.gallery-thumb.has-img::before{content:"";position:absolute;inset:0;background:linear-gradient(transparent 50%,rgba(0,0,0,.45))}
.gallery-thumb.has-img span{z-index:1}
.gallery-info{padding:16px}
.gallery-info h3{font-size:15px;margin:0 0 4px;color:var(--slate-900)}
.gallery-info p{font-size:12.5px;color:var(--slate-500);margin:0 0 12px}
.gallery-meta{display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--slate-100);padding-top:10px}
.empty-note{grid-column:1/-1;text-align:center;color:#64748b;padding:40px 0}

/* ---------- modal ---------- */
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
.field-input{width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:8px;font:inherit;margin-bottom:16px;box-sizing:border-box}
.field-input:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.15)}
.dropzone{display:flex;flex-direction:column;align-items:center;gap:6px;text-align:center;padding:22px 12px;border:2px dashed #cbd5e1;border-radius:10px;cursor:pointer;color:#64748b;transition:.15s}
.dropzone:hover,.dropzone.drag{border-color:#2563eb;background:#eff6ff}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}
.field-error.ok{color:#059669}

/* previews */
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


<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('albumModal'), form = $('albumForm');
  const input = $('aFiles'), drop = $('aDrop'), preview = $('aPreview');
  const countRow = $('aCountRow'), countEl = $('aCount'), errorEl = $('aError');
  const submitBtn = $('aSubmit');

  const MAX_SIZE = 5 * 1024 * 1024, MAX_FILES = 30;
  const OK_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

  // CSRF (CodeIgniter) - updated after every AJAX response
  let csrfName = '<?= $this->security->get_csrf_token_name() ?>';
  let csrfHash = '<?= $this->security->get_csrf_hash() ?>';
  const UPLOAD_URL = '<?= site_url('GalleryController/save_gallery_images') ?>';

  let items = [];   // [{ id, file, url }]
  let uid = 0;

  const esc = s => s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const showError = t => { errorEl.classList.remove('ok'); errorEl.textContent = t; errorEl.hidden = false; };
  const showOk    = t => { errorEl.classList.add('ok');    errorEl.textContent = t; errorEl.hidden = false; };

  function render() {
    preview.innerHTML = items.map((it, i) => `
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

  function open() { modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false'); $('aCategory').focus(); }
  function close() {
    modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true');
    form.reset(); errorEl.hidden = true; clearItems();
  }

  $('openAlbumModal').addEventListener('click', open);
  modal.addEventListener('click', e => { if (e.target === modal || e.target.closest('[data-close]')) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  // choose / drag-drop
  input.addEventListener('change', () => { addFiles(input.files); input.value = ''; });
  ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('drag'); }));
  ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('drag'); }));
  drop.addEventListener('drop', e => addFiles(e.dataTransfer.files));

  // remove one / remove all
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

  // upload
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const type = $('aCategory').value;
    if (!type) return showError('Please select a category.');
    if (!items.length) return showError('Please add at least one image.');

    const data = new FormData();
    data.append('type', type);
    data.append(csrfName, csrfHash);
    items.forEach(it => data.append('images[]', it.file));

    submitBtn.disabled = true;
    submitBtn.textContent = 'Uploading...';
    errorEl.hidden = true;

    try {
      const res  = await fetch(UPLOAD_URL, {
        method: 'POST',
        body: data,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const json = await res.json();
      if (json.csrf) csrfHash = json.csrf;      // refresh token

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
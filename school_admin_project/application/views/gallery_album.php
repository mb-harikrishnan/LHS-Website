<?php defined('BASEPATH') OR exit('No direct script access allowed');

// ---- adjust to match your routes / folders ----
$img_base    = base_url('../assets/images/gallery/');
$back_url    = site_url('gallery');
$delete_url  = site_url('delete_gallery_image');
?>
<main class="page">
  <div class="page-header">
    <div>
      <a class="back-link" href="<?= $back_url ?>">&larr; All albums</a>
      <h1 class="page-title"><?= html_escape($type->name) ?></h1>
      <p class="page-sub"><?= count($images) ?> <?= count($images) === 1 ? 'photo' : 'photos' ?> in this album</p>
    </div>
    <div class="page-actions">
      <button class="btn btn-primary" type="button" data-open-upload>
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
</style>


<script>
(function () {
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
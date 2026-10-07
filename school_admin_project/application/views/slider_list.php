<?php
/* Save as: application/views/slider_list.php
   Receives from controller: $sliders */

$media_base = 'http://localhost:8000/assets/images/gallery/';
$type_label = array('image' => 'Image', 'video' => 'Video', 'link' => 'Video Link');
?>
<style>
/* modal */
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.55);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
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
textarea.field-input{resize:vertical}
.field-note{font-size:12px;color:var(--slate-500);margin:-10px 0 16px}
.field-error{color:#dc2626;font-size:13px;margin:6px 0 0}
.field-error[hidden]{display:none}
.radio-row{display:flex;gap:20px;margin-bottom:12px;font-size:14px}
.radio-row label{display:flex;align-items:center;gap:6px;cursor:pointer}
.preview{display:block;max-width:100%;max-height:150px;border-radius:8px;margin:-6px 0 16px;border:1px solid var(--slate-200)}
.preview[hidden]{display:none}

/* media viewer */
.viewer-box{position:relative;max-width:90vw;max-height:90vh;text-align:center}
.viewer-box img,.viewer-box video{max-width:100%;max-height:80vh;border-radius:10px;box-shadow:0 20px 50px rgba(0,0,0,.5);background:#000}
.viewer-caption{color:#fff;margin-top:10px;font-size:14px}
.viewer-close{position:absolute;top:-14px;right:-14px;width:34px;height:34px;border-radius:50%;border:0;background:#fff;color:#111;font-size:22px;line-height:1;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,.3)}
#viewer{background:rgba(15,23,42,.88);z-index:2000}

/* table media */
.thumb-btn{position:relative;display:block;padding:0;border:0;background:none;cursor:zoom-in;border-radius:6px}
.thumb{width:84px;height:52px;object-fit:cover;border-radius:6px;border:1px solid var(--slate-200);background:#000;display:block;pointer-events:none}
.thumb-btn.vid::after{content:"\25B6";position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;text-shadow:0 1px 4px rgba(0,0,0,.7);pointer-events:none}
.sub{font-size:12px;color:var(--slate-500);margin-top:2px}

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
      <h1 class="page-title">School Slider</h1>
      <p class="page-sub">Manage school slider images and content</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Upload New Image or Video
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search by title, description or date…" aria-label="Search slides">
      </div>
      <select class="filter-select" id="typeFilter" aria-label="Filter by type">
        <option value="">All Types</option>
        <?php foreach ($type_label as $val => $label): ?>
          <option value="<?= $val ?>"><?= $label ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:60px">#</th>
            <th style="width:110px">Date</th>
            <th>Title &amp; Description</th>
            <th>Type</th>
            <th>Media</th>
            <th style="width:110px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php foreach ($sliders as $s):
            $type = $s->c_upload_type;
            $isLink = ($type === 'link');
            $url  = $isLink ? $s->c_file : $media_base . rawurlencode($s->c_file);
            $safeLink = ($isLink && preg_match('#^https?://#i', $s->c_file)) ? $s->c_file : '#';
            $label = isset($type_label[$type]) ? $type_label[$type] : $type;
            $dmy = date('d-m-Y', strtotime($s->d_date));
            $ymd = date('Y-m-d', strtotime($s->d_date));
          ?>
          <tr data-type="<?= html_escape($type) ?>"
              data-title="<?= html_escape($s->c_title) ?>"
              data-desc="<?= html_escape($s->c_description) ?>"
              data-date="<?= $dmy ?>">
            <td class="num"></td>
            <td><?= $dmy ?></td>
            <td>
              <strong><?= html_escape($s->c_title) ?></strong>
              <?php if ($s->c_description !== ''): ?>
                <div class="sub"><?= html_escape($s->c_description) ?></div>
              <?php endif; ?>
            </td>
            <td><span class="badge <?= $type === 'image' ? 'badge-info' : 'badge-purple' ?>"><?= html_escape($label) ?></span></td>
            <td>
              <?php if ($type === 'image'): ?>
                <button type="button" class="thumb-btn" data-media data-kind="image" data-url="<?= html_escape($url) ?>" data-cap="<?= html_escape($s->c_title) ?>" aria-label="View image">
                  <img class="thumb" src="<?= html_escape($url) ?>" alt="">
                </button>
              <?php elseif ($type === 'video'): ?>
                <button type="button" class="thumb-btn vid" data-media data-kind="video" data-url="<?= html_escape($url) ?>" data-cap="<?= html_escape($s->c_title) ?>" aria-label="Play video">
                  <video class="thumb" src="<?= html_escape($url) ?>" preload="metadata" muted></video>
                </button>
              <?php else: ?>
                <a href="<?= html_escape($safeLink) ?>" target="_blank" rel="noopener">Open link</a>
              <?php endif; ?>
            </td>
            <td>
              <div class="cell-actions">
                <button class="act-btn ed" type="button" title="Edit" aria-label="Edit <?= html_escape($s->c_title) ?>"
                        data-edit
                        data-id="<?= (int)$s->n_slno ?>"
                        data-date="<?= $ymd ?>"
                        data-title="<?= html_escape($s->c_title) ?>"
                        data-desc="<?= html_escape($s->c_description) ?>"
                        data-type="<?= html_escape($type) ?>"
                        data-file="<?= html_escape($s->c_file) ?>"
                        data-url="<?= html_escape($url) ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </button>
                <button class="act-btn rm" type="button" title="Delete" aria-label="Delete <?= html_escape($s->c_title) ?>"
                        data-del="<?= (int)$s->n_slno ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v6M14 11v6"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="empty" id="empty" hidden>No slides found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<!-- Add / Edit Modal -->
<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Upload New Slide</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate enctype="multipart/form-data">
      <input type="hidden" id="sId" value="0">
      <div class="modal-body">
        <label class="field-label" for="fDate">Date <span class="req">*</span></label>
        <input type="date" id="fDate" class="field-input">

        <label class="field-label" for="fTitle">Title <span class="req">*</span></label>
        <input type="text" id="fTitle" class="field-input" placeholder="Slide title" maxlength="150">

        <label class="field-label" for="fDesc">Description</label>
        <textarea id="fDesc" class="field-input" rows="3" placeholder="Short description"></textarea>

        <label class="field-label" for="fType">Upload Type</label>
        <select id="fType" class="field-input">
          <option value="image">Image</option>
          <option value="video">Video</option>
        </select>

        <!-- Image -->
        <div id="boxImage">
          <label class="field-label" for="fImage">Image <span class="req">*</span></label>
          <input type="file" id="fImage" class="field-input" accept="image/png,image/jpeg,image/webp">
          <small class="field-note">JPG, PNG or WEBP, max 5 MB</small>
          <img id="imgPreview" class="preview" alt="" hidden>
        </div>

        <!-- Video -->
        <div id="boxVideo" hidden>
          <label class="field-label">Video Source</label>
          <div class="radio-row">
            <label><input type="radio" name="vsrc" value="file" checked> Upload video</label>
            <label><input type="radio" name="vsrc" value="link"> Video link</label>
          </div>
          <div id="boxVFile">
            <input type="file" id="fVideo" class="field-input" accept="video/mp4,video/webm,video/ogg,video/quicktime">
            <small class="field-note">MP4, WEBM, OGG or MOV, max 50 MB</small>
          </div>
          <div id="boxVLink" hidden>
            <input type="url" id="fLink" class="field-input" placeholder="https://www.youtube.com/watch?v=...">
          </div>
        </div>

        <p class="field-note" id="curNote" hidden>The current file is kept unless you choose a new one.</p>
        <p class="field-error" id="formError" hidden></p>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Media Viewer -->
<div class="modal-overlay" id="viewer" aria-hidden="true">
  <div class="viewer-box">
    <button type="button" class="viewer-close" id="viewerClose" aria-label="Close">&times;</button>
    <div id="viewerMedia"></div>
    <div class="viewer-caption" id="viewerCap"></div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('addModal'), form = $('addForm'), tbody = $('rows');
  const fType = $('fType'), fImage = $('fImage'), fVideo = $('fVideo'), fLink = $('fLink');
  const preview = $('imgPreview'), errorEl = $('formError'), saveBtn = $('saveBtn');

  const IMG_EXT = ['jpg', 'jpeg', 'png', 'webp'], VID_EXT = ['mp4', 'webm', 'ogg', 'mov'];
  const IMG_MAX = 5 * 1024 * 1024, VID_MAX = 50 * 1024 * 1024;

  const SAVE_URL   = '<?= site_url('save_slider') ?>';
  const DELETE_URL = '<?= site_url('delete_slider') ?>';
  const CSRF_NAME  = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH  = '<?= $this->security->get_csrf_hash() ?>';

  let editInfo = null;   // the row being edited (null when adding)

  const showError = m => { errorEl.textContent = m; errorEl.hidden = false; };
  const ext = f => f.name.split('.').pop().toLowerCase();
  const today = () => new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 10);
  const vsrc = () => form.querySelector('input[name="vsrc"]:checked').value;

  /* ---------- list: search + type filter ---------- */
  function applyFilter() {
    const q = $('search').value.trim().toLowerCase(), tf = $('typeFilter').value;
    const trs = [...tbody.querySelectorAll('tr')];
    let shown = 0;
    trs.forEach(tr => {
      const d = tr.dataset;
      const ok = (!tf || d.type === tf) &&
                 (!q || d.title.toLowerCase().includes(q) || d.desc.toLowerCase().includes(q) || d.date.includes(q));
      tr.hidden = !ok;
      if (ok) { shown++; tr.querySelector('.num').textContent = shown; }
    });
    $('empty').hidden = shown > 0;
    $('count').textContent = `Showing ${shown} of ${trs.length} slides`;
  }

  /* ---------- form fields ---------- */
  function syncFields() {
    const isImg = fType.value === 'image';
    $('boxImage').hidden = !isImg;
    $('boxVideo').hidden = isImg;
    const isFile = vsrc() === 'file';
    $('boxVFile').hidden = !isFile;
    $('boxVLink').hidden = isFile;
  }

  function resetForm() {
    form.reset(); $('sId').value = 0; editInfo = null;
    preview.hidden = true; preview.removeAttribute('src');
    $('curNote').hidden = true; errorEl.hidden = true;
  }

  function open(edit) {
    resetForm();
    editInfo = edit;
    $('addTitle').textContent = edit ? 'Edit Slide' : 'Upload New Slide';
    $('fDate').value = edit ? edit.date : today();
    if (edit) {
      $('sId').value = edit.id;
      $('fTitle').value = edit.title;
      $('fDesc').value = edit.desc;
      fType.value = edit.type === 'image' ? 'image' : 'video';
      if (edit.type === 'image') {
        preview.src = edit.url; preview.hidden = false;
        $('curNote').hidden = false;
      } else if (edit.type === 'video') {
        $('curNote').hidden = false;
      } else {
        form.querySelector('input[name="vsrc"][value="link"]').checked = true;
        fLink.value = edit.file;
      }
    }
    syncFields();
    modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false'); $('fTitle').focus();
  }
  const close = () => { modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true'); resetForm(); };

  $('openAddModal').addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });

  fType.addEventListener('change', syncFields);
  form.querySelectorAll('input[name="vsrc"]').forEach(r => r.addEventListener('change', syncFields));
  fImage.addEventListener('change', () => {
    if (fImage.files[0]) { preview.src = URL.createObjectURL(fImage.files[0]); preview.hidden = false; }
  });

  /* ---------- save (insert / update) ---------- */
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const id = +$('sId').value, date = $('fDate').value, title = $('fTitle').value.trim();
    const isImg = fType.value === 'image';
    const uploadType = isImg ? 'image' : (vsrc() === 'link' ? 'link' : 'video');

    if (!date)  return showError('Please select a date.');
    if (!title) return showError('Please enter a title.');

    const fd = new FormData();
    fd.append('id', id); fd.append('date', date); fd.append('title', title);
    fd.append('description', $('fDesc').value.trim()); fd.append('upload_type', uploadType);

    if (uploadType === 'image') {
      const f = fImage.files[0];
      if (f) {
        if (!IMG_EXT.includes(ext(f))) return showError('Please choose a valid image (JPG, PNG or WEBP).');
        if (f.size > IMG_MAX) return showError('Image must be 5 MB or smaller.');
        fd.append('image', f);
      } else if (!(id && editInfo && editInfo.type === 'image')) return showError('Please choose an image.');
    } else if (uploadType === 'video') {
      const f = fVideo.files[0];
      if (f) {
        if (!VID_EXT.includes(ext(f))) return showError('Please choose a valid video (MP4, WEBM, OGG or MOV).');
        if (f.size > VID_MAX) return showError('Video must be 50 MB or smaller.');
        fd.append('video', f);
      } else if (!(id && editInfo && editInfo.type === 'video')) return showError('Please choose a video file.');
    } else {
      const link = fLink.value.trim();
      if (!/^https?:\/\/.+/i.test(link)) return showError('Please enter a valid video link (https://...).');
      fd.append('link', link);
    }
    fd.append(CSRF_NAME, CSRF_HASH);

    saveBtn.disabled = true; saveBtn.textContent = 'Saving…';
    try {
      const res = await (await fetch(SAVE_URL, { method: 'POST', body: fd })).json();
      if (res.csrf) CSRF_HASH = res.csrf;
      if (!res.status) return showError(res.msg);
      close();
      Swal.fire({ icon: 'success', title: 'Success', text: res.msg, timer: 1500, showConfirmButton: false })
          .then(() => location.reload());
    } catch (err) {
      showError('Something went wrong. Please try again.');
    } finally { saveBtn.disabled = false; saveBtn.textContent = 'Save'; }
  });

  /* ---------- media viewer ---------- */
  const viewer = $('viewer');
  function openViewer(kind, url, cap) {
    const el = kind === 'video'
      ? Object.assign(document.createElement('video'), { src: url, controls: true, autoplay: true })
      : Object.assign(document.createElement('img'), { src: url, alt: cap || '' });
    $('viewerMedia').replaceChildren(el);
    $('viewerCap').textContent = cap || '';
    viewer.classList.add('open'); viewer.setAttribute('aria-hidden', 'false');
  }
  function closeViewer() {
    viewer.classList.remove('open'); viewer.setAttribute('aria-hidden', 'true');
    $('viewerMedia').replaceChildren();   // also stops any playing video
  }
  $('viewerClose').addEventListener('click', closeViewer);
  viewer.addEventListener('click', e => { if (e.target === viewer) closeViewer(); });

  /* ---------- table clicks: media, edit, delete ---------- */
  tbody.addEventListener('click', e => {
    const m = e.target.closest('[data-media]');
    if (m) return openViewer(m.dataset.kind, m.dataset.url, m.dataset.cap);

    const ed = e.target.closest('[data-edit]');
    if (ed) return open({
      id: ed.dataset.id, date: ed.dataset.date, title: ed.dataset.title, desc: ed.dataset.desc,
      type: ed.dataset.type, file: ed.dataset.file, url: ed.dataset.url
    });

    const del = e.target.closest('[data-del]');
    if (!del) return;
    Swal.fire({
      title: 'Are you sure?', text: 'This slide will be deleted.', icon: 'warning',
      showCancelButton: true, confirmButtonColor: '#dc2626',
      confirmButtonText: 'Yes, delete it', cancelButtonText: 'Cancel'
    }).then(async r => {
      if (!r.isConfirmed) return;
      const fd = new FormData();
      fd.append('id', del.dataset.del); fd.append(CSRF_NAME, CSRF_HASH);
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

  /* ---------- Esc: close whichever is open (viewer first) ---------- */
  document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    if (viewer.classList.contains('open')) closeViewer();
    else if (modal.classList.contains('open')) close();
  });

  $('search').addEventListener('input', applyFilter);
  $('typeFilter').addEventListener('change', applyFilter);
  applyFilter();
})();
</script>
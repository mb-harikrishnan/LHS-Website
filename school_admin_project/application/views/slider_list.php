

      <!-- Page Content -->
      <main class="page">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">School Slider</h1>
            <p class="page-sub">Manage school slider images and content</p>
          </div>
          <div class="page-actions">
            <button class="btn btn-primary" type="button" id="open-upload-modal">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
              Upload New Image Or video </video>
            </button>
          </div>
        </div>

        <!-- Table Card -->
        <div class="card">
          <div class="table-wrap">
                <table class="table">
                <thead>
                    <tr>
                    <th style="width:110px;">Date</th>
                    <th>Title & Description</th>
                    <th>Type</th>
                    <th>Media</th>
                    <th style="width:110px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="slider-body"></tbody>
                </table>
          </div>
        </div>
      </main>



<div class="modal-backdrop" id="upload-modal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="upload-title">
    <div class="modal-head">
      <h2 id="upload-title">Upload New Slide</h2>
      <button type="button" class="modal-close" id="upload-close" aria-label="Close">&times;</button>
    </div>

    <form id="upload-form" novalidate>
      <div class="modal-body">
        <label class="field-label" for="f-date">Date</label>
        <input type="date" id="f-date" class="field" required>

        <label class="field-label" for="f-title">Title</label>
        <input type="text" id="f-title" class="field" placeholder="Slide title" required>

        <label class="field-label" for="f-desc">Description</label>
        <textarea id="f-desc" class="field" rows="3" placeholder="Short description"></textarea>

        <label class="field-label" for="f-type">Upload Type</label>
        <select id="f-type" class="field">
          <option value="image">Image</option>
          <option value="video">Video</option>
        </select>

        <!-- Image -->
        <div id="box-image">
          <label class="field-label" for="f-image">Image</label>
          <input type="file" id="f-image" class="field" accept="image/*">
          <img id="image-preview" class="preview" alt="" hidden>
        </div>

        <!-- Video -->
        <div id="box-video" hidden>
          <label class="field-label">Video Source</label>
          <div class="radio-row">
            <label><input type="radio" name="vsrc" value="file" checked> Upload video</label>
            <label><input type="radio" name="vsrc" value="link"> Video link</label>
          </div>

          <div id="box-vfile">
            <input type="file" id="f-video" class="field" accept="video/*">
          </div>
          <div id="box-vlink" hidden>
            <input type="url" id="f-link" class="field" placeholder="https://www.youtube.com/watch?v=...">
          </div>
        </div>

        <p class="field-error" id="form-error"></p>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn" id="upload-cancel">Cancel</button>
        <button type="submit" class="btn btn-primary" id="save-btn">Save</button>
      </div>
    </form>
  </div>
</div>


<style>
    .modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,.55);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-backdrop.show{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:460px;box-shadow:0 20px 50px rgba(0,0,0,.25);overflow:hidden}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #e2e8f0}
.modal-head h2{font-size:17px;margin:0}
.modal-close{background:none;border:0;font-size:24px;line-height:1;cursor:pointer;color:#64748b}
.modal-body{padding:20px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid #e2e8f0;background:#f8fafc}
.field-label{display:block;font-size:13px;font-weight:600;margin:0 0 6px}
.field{width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px;margin-bottom:16px;background:#fff}
.field:focus{outline:2px solid #6366f1;outline-offset:1px}
.dropzone{display:flex;flex-direction:column;align-items:center;gap:6px;text-align:center;padding:24px 12px;border:2px dashed #cbd5e1;border-radius:10px;cursor:pointer;color:#475569;font-size:14px}
.dropzone small{color:#94a3b8;font-size:12px}
.dropzone:hover,.dropzone.drag{border-color:#6366f1;background:#eef2ff}
.field-error{color:#dc2626;font-size:12px;margin:6px 0 0;min-height:16px}


.modal{max-height:92vh;overflow-y:auto}
textarea.field{resize:vertical;font-family:inherit}
.radio-row{display:flex;gap:20px;margin-bottom:12px;font-size:14px}
.radio-row label{display:flex;align-items:center;gap:6px;cursor:pointer}
.preview{display:block;max-width:100%;max-height:150px;border-radius:8px;margin:-6px 0 16px;border:1px solid #e2e8f0}
.thumb{width:84px;height:52px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;background:#000;display:block}
.empty-row td{text-align:center;color:#94a3b8;padding:28px}
</style>


<script>
(function () {
  var slides = [];
  var nextId = 1;
  var editId = null;

  var $ = function (id) { return document.getElementById(id); };
  var modal = $('upload-modal'), form = $('upload-form'), body = $('slider-body');
  var fType = $('f-type'), fImage = $('f-image'), fVideo = $('f-video'), fLink = $('f-link');
  var preview = $('image-preview'), errBox = $('form-error');

  var IMG_EXT = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
  var VID_EXT = ['mp4', 'webm', 'ogg', 'mov'];
  var IMG_MAX = 5 * 1024 * 1024, VID_MAX = 50 * 1024 * 1024;

  function esc(s) {
    return String(s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }
  function ext(f) { return f.name.split('.').pop().toLowerCase(); }
  function today() { return new Date().toISOString().slice(0, 10); }
  function fmtDate(d) {
    var p = d.split('-');
    return p[2] + '-' + p[1] + '-' + p[0];
  }
  function vsrc() { return form.querySelector('input[name="vsrc"]:checked').value; }

  /* ---------- show / hide fields ---------- */
  function syncFields() {
    var isImg = fType.value === 'image';
    $('box-image').hidden = !isImg;
    $('box-video').hidden = isImg;
    var isFile = vsrc() === 'file';
    $('box-vfile').hidden = !isFile;
    $('box-vlink').hidden = isFile;
  }

  /* ---------- open / close ---------- */
  function openModal(item) {
    form.reset();
    errBox.textContent = '';
    preview.hidden = true;
    editId = item ? item.id : null;
    $('upload-title').textContent = item ? 'Edit Slide' : 'Upload New Slide';

    $('f-date').value = item ? item.date : today();
    $('f-title').value = item ? item.title : '';
    $('f-desc').value = item ? item.desc : '';
    fType.value = item ? item.type : 'image';

    if (item && item.type === 'image' && item.src) {
      preview.src = item.src; preview.hidden = false;
    }
    if (item && item.type === 'video') {
      var r = form.querySelector('input[name="vsrc"][value="' + item.source + '"]');
      r.checked = true;
      if (item.source === 'link') fLink.value = item.src;
    }
    syncFields();
    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');
  }
  function closeModal() {
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
  }

  /* ---------- render list ---------- */
  function mediaCell(s) {
    if (s.type === 'image') return '<img class="thumb" src="' + esc(s.src) + '" alt="">';
    if (s.source === 'file') return '<video class="thumb" src="' + esc(s.src) + '" muted></video>';
    return '<a href="' + esc(s.src) + '" target="_blank" rel="noopener">Open link</a>';
  }
  function render() {
    if (!slides.length) {
      body.innerHTML = '<tr class="empty-row"><td colspan="5">No slides yet. Click "Upload New Image or Video".</td></tr>';
      return;
    }
    body.innerHTML = slides.map(function (s) {
      return '<tr>' +
        '<td>' + fmtDate(s.date) + '</td>' +
        '<td><strong>' + esc(s.title) + '</strong>' +
          '<div style="font-size:12px;color:var(--slate-500);margin-top:2px;">' + esc(s.desc) + '</div></td>' +
        '<td><span class="badge ' + (s.type === 'image' ? 'badge-info' : 'badge-purple') + '">' +
          (s.type === 'image' ? 'Image' : 'Video') + '</span></td>' +
        '<td>' + mediaCell(s) + '</td>' +
        '<td><div class="row-actions" style="justify-content:flex-end;">' +
          '<button class="icon-btn" title="Edit" data-edit="' + s.id + '">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></button>' +
          '<button class="icon-btn" title="Delete" data-del="' + s.id + '">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg></button>' +
        '</div></td></tr>';
    }).join('');
  }

  /* ---------- events ---------- */
  $('open-upload-modal').addEventListener('click', function () { openModal(null); });
  $('upload-close').addEventListener('click', closeModal);
  $('upload-cancel').addEventListener('click', closeModal);
  modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

  fType.addEventListener('change', syncFields);
  form.querySelectorAll('input[name="vsrc"]').forEach(function (r) {
    r.addEventListener('change', syncFields);
  });
  fImage.addEventListener('change', function () {
    if (fImage.files[0]) { preview.src = URL.createObjectURL(fImage.files[0]); preview.hidden = false; }
  });

  body.addEventListener('click', function (e) {
    var ed = e.target.closest('[data-edit]'), del = e.target.closest('[data-del]');
    if (ed) {
      var item = slides.filter(function (s) { return s.id == ed.dataset.edit; })[0];
      if (item) openModal(item);
    }
    if (del && confirm('Delete this slide?')) {
      slides = slides.filter(function (s) { return s.id != del.dataset.del; });
      render();
      // TODO: fetch('/api/slider/' + id, { method: 'DELETE' })
    }
  });

  /* ---------- save ---------- */
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var old = editId ? slides.filter(function (s) { return s.id === editId; })[0] : null;
    var data = {
      id: editId || nextId++,
      date: $('f-date').value,
      title: $('f-title').value.trim(),
      desc: $('f-desc').value.trim(),
      type: fType.value,
      source: 'file',
      src: ''
    };

    if (!data.date) return (errBox.textContent = 'Please select a date.');
    if (!data.title) return (errBox.textContent = 'Please enter a title.');

    if (data.type === 'image') {
      var img = fImage.files[0];
      if (img) {
        if (IMG_EXT.indexOf(ext(img)) === -1) return (errBox.textContent = 'Please choose a valid image (JPG, PNG, WEBP, GIF).');
        if (img.size > IMG_MAX) return (errBox.textContent = 'Image must be under 5 MB.');
        data.src = URL.createObjectURL(img);
      } else if (old && old.type === 'image') {
        data.src = old.src;
      } else return (errBox.textContent = 'Please upload an image.');
    } else {
      data.source = vsrc();
      if (data.source === 'file') {
        var vid = fVideo.files[0];
        if (vid) {
          if (VID_EXT.indexOf(ext(vid)) === -1) return (errBox.textContent = 'Please choose a valid video (MP4, WEBM, OGG, MOV).');
          if (vid.size > VID_MAX) return (errBox.textContent = 'Video must be under 50 MB.');
          data.src = URL.createObjectURL(vid);
        } else if (old && old.type === 'video' && old.source === 'file') {
          data.src = old.src;
        } else return (errBox.textContent = 'Please upload a video file.');
      } else {
        var link = fLink.value.trim();
        if (!/^https?:\/\/.+/i.test(link)) return (errBox.textContent = 'Please enter a valid video link (https://...).');
        data.src = link;
      }
    }

    if (old) slides[slides.indexOf(old)] = data; else slides.unshift(data);

    // TODO: send to backend using FormData
    // var fd = new FormData();
    // fd.append('date', data.date); fd.append('title', data.title);
    // fd.append('description', data.desc); fd.append('type', data.type);
    // image -> fd.append('image', fImage.files[0])
    // video file -> fd.append('video', fVideo.files[0]); video link -> fd.append('link', data.src)
    // fetch('/api/slider', { method: 'POST', body: fd })

    render();
    closeModal();
  });

  render();
})();
</script>
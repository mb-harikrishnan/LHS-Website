<?php
// Existing types = types that already have an active video (from controller)
$existing_types = isset($existing_types) ? $existing_types : array();
$all_taken = true;
foreach ($doc_types as $k => $label) {
    if (!in_array($k, $existing_types)) { $all_taken = false; break; }
}
$csrf_name = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
?>

<main class="page">

  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Infrastructure Video List</h1>
      <p class="page-sub">Upload and manage infrastructure videos</p>
    </div>
    <div class="page-actions">
      <button class="btn btn-primary" type="button" id="openVideoModal" <?= $all_taken ? 'data-all-taken="1"' : '' ?>>
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        Upload Video
      </button>
    </div>
  </div>

  <!-- Table Card -->
  <div class="card">
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>Type</th>
            <th>Video</th>
            <th style="width:120px;text-align:right;">Delete</th>
          </tr>
        </thead>
        <tbody id="facilityBody">
          <?php if (!empty($documents)): foreach ($documents as $d):
            $label = isset($doc_types[$d->c_type]) ? $doc_types[$d->c_type] : $d->c_type;
            $url   = base_url('assets/uploads/videos/' . $d->c_videos); // adjust to your public path
          ?>
            <tr>
              <td><span class="badge badge-purple"><?= html_escape($label) ?></span></td>

              <td>
                <div class="v-thumb" data-view
                     data-url="<?= html_escape($url) ?>"
                     data-title="<?= html_escape($label) ?>">
                  <video src="<?= html_escape($url) ?>#t=0.5" preload="metadata" muted></video>
                </div>
              </td>

              <td>
                <div class="row-actions" style="justify-content:flex-end;">
                  <button type="button" class="icon-btn btn-delete" title="Delete"
                          data-id="<?= (int)$d->n_slno ?>"
                          data-title="<?= html_escape($label) ?>">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="3" style="text-align:center;padding:24px;color:#64748b;">No videos uploaded yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="table-foot">
      <span class="muted">Showing <?= count($documents) ?> video(s)</span>
    </div>
  </div>
</main>

<!-- Hidden delete form (submitted by SweetAlert confirm) -->
<form id="deleteForm" method="post" action="<?= site_url('delete_infrastructure') ?>" style="display:none">
  <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
  <input type="hidden" name="id" id="deleteId" value="">
</form>

<!-- ============ Upload Modal ============ -->
<div class="modal-overlay" id="videoModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true">
    <div class="modal-head">
      <h2>Add Infrastructure Video</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>

    <form id="videoForm" method="post" action="<?= site_url('upload_infrastructure') ?>" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">

      <div class="modal-body">
        <label class="field-label" for="vCategory">Type <span class="req">*</span></label>
        <select id="vCategory" name="document_type" class="field-input">
          <option value="">Select type</option>
          <?php foreach ($doc_types as $key => $label):
            $taken = in_array($key, $existing_types); ?>
            <option value="<?= html_escape($key) ?>" <?= $taken ? 'disabled' : '' ?>>
              <?= html_escape($label) ?><?= $taken ? ' (already uploaded)' : '' ?>
            </option>
          <?php endforeach; ?>
        </select>

        <label class="field-label" for="vFile">Video <span class="req">*</span></label>
        <label class="dropzone" id="vDrop" for="vFile">
          <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="14" height="14" rx="2"/><path d="m22 8-6 4 6 4V8Z"/></svg>
          <span id="vDropText"><strong>Click to choose</strong> or drag a video here</span>
          <small>MP4, AVI, MOV, WMV, FLV, MKV (max 200 MB).</small>
        </label>
        <input type="file" id="vFile" name="document_file" accept="video/*" hidden>

        <video id="vPreview" controls style="display:none;width:100%;margin-top:12px;border-radius:8px;background:#000"></video>
        <p class="field-error" id="vError" hidden></p>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary">Save Video</button>
      </div>
    </form>
  </div>
</div>

<!-- ============ Player Modal ============ -->
<div class="modal-overlay" id="viewModal" aria-hidden="true">
  <div class="modal" style="max-width:820px" role="dialog" aria-modal="true">
    <div class="modal-head">
      <h2 id="viewTitle">Video</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <div class="modal-body">
      <video id="viewPlayer" controls style="width:100%;max-height:70vh;border-radius:8px;background:#000"></video>
    </div>
  </div>
</div>

<style>
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
.field-input{width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:8px;font:inherit;margin-bottom:16px}
.dropzone{display:flex;flex-direction:column;align-items:center;gap:6px;text-align:center;padding:24px 12px;border:2px dashed #cbd5e1;border-radius:10px;cursor:pointer;color:#64748b;transition:.15s}
.dropzone:hover,.dropzone.drag{border-color:#2563eb;background:#eff6ff}
.dropzone.has-file{border-style:solid;border-color:#16a34a;background:#f0fdf4;color:#166534}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}

.v-thumb{position:relative;width:120px;height:68px;border-radius:8px;overflow:hidden;background:#000;cursor:pointer}
.v-thumb video{width:100%;height:100%;object-fit:cover;pointer-events:none}
.v-thumb::after{content:"▶";position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;background:rgba(0,0,0,.25)}
</style>

<!-- SweetAlert2 (remove this line if your header/footer already loads it) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function () {
  const $ = id => document.getElementById(id);
  const videoModal = $('videoModal'), viewModal = $('viewModal');
  const form = $('videoForm'), fileInput = $('vFile'), drop = $('vDrop');
  const dropText = $('vDropText'), preview = $('vPreview'), errorEl = $('vError');
  const player = $('viewPlayer'), typeSelect = $('vCategory');

  const MAX = 200 * 1024 * 1024;
  const VIDEO_EXT = /\.(mp4|avi|mov|wmv|flv|mkv)$/i;

  // Types that already have a video (from PHP)
  const EXISTING = <?= json_encode(array_values($existing_types)) ?>;

  const openM  = m => { m.classList.add('open');    m.setAttribute('aria-hidden', 'false'); };
  const closeM = m => { m.classList.remove('open'); m.setAttribute('aria-hidden', 'true');  };
  const showError = t => { errorEl.textContent = t; errorEl.hidden = false; };

  function resetForm() {
    form.reset();
    drop.classList.remove('has-file');
    dropText.innerHTML = '<strong>Click to choose</strong> or drag a video here';
    errorEl.hidden = true;
    if (preview.src) URL.revokeObjectURL(preview.src);
    preview.removeAttribute('src');
    preview.style.display = 'none';
  }

  function isVideo(f) {
    return f.type.startsWith('video/') || (!f.type && VIDEO_EXT.test(f.name));
  }

  function setFile(file) {
    errorEl.hidden = true;
    if (!isVideo(file)) {
      fileInput.value = '';
      drop.classList.remove('has-file');
      preview.style.display = 'none';
      return showError('Only video files are allowed.');
    }
    if (file.size > MAX) {
      fileInput.value = '';
      return showError('Video must be 200 MB or smaller.');
    }
    drop.classList.add('has-file');
    dropText.textContent = file.name + ' (' + (file.size / 1048576).toFixed(1) + ' MB)';
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
  }

  // ---------- Open upload modal (blocked if every type already exists) ----------
  $('openVideoModal').addEventListener('click', function () {
    if (this.dataset.allTaken) {
      Swal.fire({
        icon: 'info',
        title: 'Already uploaded',
        text: 'A video already exists for every type. Delete one first to upload a new video.'
      });
      return;
    }
    openM(videoModal);
  });

  // ---------- Close modals ----------
  function closeAny(m) {
    closeM(m);
    if (m === videoModal) resetForm();
    if (m === viewModal) { player.pause(); player.removeAttribute('src'); player.load(); }
  }
  document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => {
      if (e.target === m || e.target.closest('[data-close]')) closeAny(m);
    });
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(closeAny);
  });

  // ---------- File input + drag/drop ----------
  fileInput.addEventListener('change', () => fileInput.files[0] && setFile(fileInput.files[0]));
  ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('drag'); }));
  ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('drag'); }));
  drop.addEventListener('drop', e => {
    const f = e.dataTransfer.files[0];
    if (f) { fileInput.files = e.dataTransfer.files; setFile(f); }
  });

  // ---------- Validate, then submit normally to PHP ----------
  form.addEventListener('submit', e => {
    const type = typeSelect.value, file = fileInput.files[0];

    if (!type) { e.preventDefault(); return showError('Please select a type.'); }
    if (EXISTING.indexOf(type) !== -1) {
      e.preventDefault();
      return showError('A video already exists for this type. Delete it first.');
    }
    if (!file) { e.preventDefault(); return showError('Please choose a video file.'); }
    if (!isVideo(file)) { e.preventDefault(); return showError('Only video files are allowed.'); }
    if (file.size > MAX) { e.preventDefault(); return showError('Video must be 200 MB or smaller.'); }

    // all good: let the form post. Show a loader while uploading.
    Swal.fire({
      title: 'Uploading...',
      text: 'Please wait, do not close this page.',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });
  });

  // ---------- Play video ----------
  document.getElementById('facilityBody').addEventListener('click', e => {
    const t = e.target.closest('[data-view]');
    if (!t) return;
    $('viewTitle').textContent = t.dataset.title;
    player.src = t.dataset.url;
    openM(viewModal);
    player.play().catch(() => {});
  });

  // ---------- Delete with SweetAlert confirmation ----------
  document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function () {
      const id = this.dataset.id, title = this.dataset.title;
      Swal.fire({
        title: 'Delete this video?',
        text: '"' + title + '" video will be removed. This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
      }).then(result => {
        if (result.isConfirmed) {
          $('deleteId').value = id;
          $('deleteForm').submit();
        }
      });
    });
  });

  // ---------- Flash messages from controller ----------
  <?php if ($this->session->flashdata('success')): ?>
  Swal.fire({ icon: 'success', title: 'Done', text: <?= json_encode($this->session->flashdata('success')) ?>, timer: 2500, showConfirmButton: false });
  <?php endif; ?>
  <?php if ($this->session->flashdata('error')): ?>
  Swal.fire({ icon: 'error', title: 'Oops', html: <?= json_encode($this->session->flashdata('error')) ?> });
  <?php endif; ?>
})();
</script>
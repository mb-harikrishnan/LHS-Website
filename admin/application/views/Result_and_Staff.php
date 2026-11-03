<?php
// Public URL where uploaded PDFs are served from (adjust if needed)
$doc_base_url = base_url('../assets/uploads/documents/');
?>

<!-- Page Content -->
<main class="page">

  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Documents & Information</h1>
      <p class="page-sub">Mandatory disclosures, CBSE affiliation documents and official declarations</p>
    </div>
    <div class="page-actions">
      <button class="btn btn-primary" type="button" id="openUploadModal">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        Upload Document
      </button>
    </div>
  </div>

  <!-- Table Card -->
  <div class="card">
    <div class="table-toolbar">
      <!-- Filter form (no delete-form class here) -->
      <form method="post" action="<?= site_url('Result_and_Staff'); ?>" style="margin:0;">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <select class="filter-select" name="type" onchange="this.form.submit()" aria-label="Filter by category">
          <option value="">All</option>
          <?php foreach ($doc_types as $key => $label): ?>
            <option value="<?= $key; ?>" <?= ($this->input->post('type') == $key) ? 'selected' : ''; ?>>
              <?= $label; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </form>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Type</th>
            <th>PDF</th>
            <th>View</th>
            <th style="text-align:right;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($documents)): $i = 1; foreach ($documents as $row): ?>
            <tr>
              <td><?= $i++; ?></td>
              <td><?= date('d M Y', strtotime($row->d_date)); ?></td>
              <td>
                <span class="badge badge-info">
                  <?= isset($doc_types[$row->c_type]) ? $doc_types[$row->c_type] : $row->c_type; ?>
                </span>
              </td>
              <td>
                <code style="font-size:12px;background:var(--slate-100);padding:2px 6px;border-radius:4px;">
                  <?= $row->c_document; ?>
                </code>
              </td>
              <td>
                <a class="btn" href="<?= $doc_base_url . $row->c_document; ?>" target="_blank" rel="noopener">View</a>
              </td>
              <td>
                <div class="row-actions" style="justify-content:flex-end;">
                  <!-- Delete form: has delete-form class, NO onsubmit confirm -->
                  <form method="post" action="<?= site_url('delete_Result_and_Staff'); ?>" class="delete-form" style="margin:0;">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="id" value="<?= (int)$row->n_slno; ?>">
                    <button type="submit" class="icon-btn" title="Delete">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; else: ?>
            <tr>
              <td colspan="6" style="text-align:center;padding:24px;">No documents found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="table-foot">
      <span class="muted">Showing <?= count($documents); ?> document<?= count($documents) == 1 ? '' : 's'; ?></span>
    </div>
  </div>
</main>


<!-- ///////////////////////////////   MODAL  //////////////////////// -->

<!-- Upload Modal -->
<div class="modal-overlay" id="uploadModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="uploadModalTitle">
    <div class="modal-head">
      <h2 id="uploadModalTitle">Upload Document</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>

    <form id="uploadForm" method="post" enctype="multipart/form-data"
          action="<?= site_url('upload_Result_and_Staff'); ?>" novalidate>
      <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

      <div class="modal-body">
        <label class="field-label" for="docCategory">Category <span class="req">*</span></label>
        <select id="docCategory" name="document_type" class="field-input" required>
          <option value="">Select category</option>
          <?php foreach ($doc_types as $key => $label): ?>
            <option value="<?= $key; ?>"><?= $label; ?></option>
          <?php endforeach; ?>
        </select>

        <label class="field-label" for="docFile">File (PDF only) <span class="req">*</span></label>
        <label class="dropzone" id="dropzone" for="docFile">
          <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m17 8-5-5-5 5"/><path d="M12 3v12"/></svg>
          <span id="dropText"><strong>Click to choose</strong> or drag a file here</span>
          <small>PDF only, max 10 MB</small>
        </label>
        <input type="file" id="docFile" name="document_file" accept=".pdf,application/pdf" hidden>

        <p class="field-error" id="uploadError" hidden></p>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary">Upload</button>
      </div>
    </form>
  </div>
</div>


<style>
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:480px;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--slate-200,#e2e8f0)}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:var(--slate-500,#64748b)}
.modal-body{padding:20px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid var(--slate-200,#e2e8f0)}
.field-label{display:block;font-size:13px;font-weight:600;margin:0 0 6px}
.field-label .req{color:#dc2626}
.field-input{width:100%;padding:9px 10px;border:1px solid var(--slate-300,#cbd5e1);border-radius:8px;font:inherit;margin-bottom:16px}
.dropzone{display:flex;flex-direction:column;align-items:center;gap:6px;text-align:center;padding:24px 12px;border:2px dashed var(--slate-300,#cbd5e1);border-radius:10px;cursor:pointer;color:var(--slate-500,#64748b);transition:.15s}
.dropzone:hover,.dropzone.drag{border-color:#2563eb;background:#eff6ff}
.dropzone.has-file{border-style:solid;border-color:#16a34a;background:#f0fdf4;color:#166534}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}
.swal2-container{z-index:2000 !important}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  // Categories that already have an active PDF (from PHP)
  const existingTypes = <?= json_encode(isset($existing_types) ? $existing_types : array()); ?>;

  // Server flash messages shown as SweetAlert
  const flashError   = <?= json_encode(strip_tags((string)$this->session->flashdata('error'))); ?>;
  const flashSuccess = <?= json_encode(strip_tags((string)$this->session->flashdata('success'))); ?>;

  const modal = document.getElementById('uploadModal');
  const form = document.getElementById('uploadForm');
  const fileInput = document.getElementById('docFile');
  const dropzone = document.getElementById('dropzone');
  const dropText = document.getElementById('dropText');
  const errorEl = document.getElementById('uploadError');
  const categoryEl = document.getElementById('docCategory');
  const MAX = 10 * 1024 * 1024;
  const okExt = /\.pdf$/i;

  const open = () => { modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false'); };
  const close = () => {
    modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true');
    form.reset(); reset();
  };
  const reset = () => {
    dropzone.classList.remove('has-file');
    dropText.innerHTML = '<strong>Click to choose</strong> or drag a file here';
    errorEl.hidden = true;
  };
  const showError = m => { errorEl.textContent = m; errorEl.hidden = false; };

  // Error swal: category already has a document
  function alreadyExists(label) {
    Swal.fire({
      icon: 'error',
      title: 'Document already exists',
      html: 'A document already exists for <b>' + label + '</b>.<br>Delete it first, then upload the new one.',
      confirmButtonText: 'OK',
      confirmButtonColor: '#2563eb'
    });
  }

  function setFile(file) {
    errorEl.hidden = true;
    if (!okExt.test(file.name)) { fileInput.value = ''; return showError('Only PDF files are allowed.'); }
    if (file.size > MAX) { fileInput.value = ''; return showError('File must be 10 MB or smaller.'); }
    dropzone.classList.add('has-file');
    dropText.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
  }

  document.getElementById('openUploadModal').addEventListener('click', open);
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && !Swal.isVisible()) close(); });

  fileInput.addEventListener('change', () => fileInput.files[0] && setFile(fileInput.files[0]));

  ['dragenter', 'dragover'].forEach(ev => dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.add('drag'); }));
  ['dragleave', 'drop'].forEach(ev => dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.remove('drag'); }));
  dropzone.addEventListener('drop', e => {
    const f = e.dataTransfer.files[0];
    if (f) { fileInput.files = e.dataTransfer.files; setFile(f); }
  });

  // Error swal as soon as an existing category is selected
  categoryEl.addEventListener('change', () => {
    if (existingTypes.includes(categoryEl.value)) {
      const label = categoryEl.selectedOptions[0].text;
      categoryEl.value = '';
      alreadyExists(label);
    }
  });

  // Validate, then submit normally to CodeIgniter
  form.addEventListener('submit', e => {
    const category = categoryEl.value;
    const file = fileInput.files[0];

    if (!category) { e.preventDefault(); return showError('Please select a category.'); }

    if (existingTypes.includes(category)) {
      e.preventDefault();
      return alreadyExists(categoryEl.selectedOptions[0].text);
    }

    if (!file) { e.preventDefault(); return showError('Please choose a PDF file.'); }
  });

  // Small "Are you sure?" swal on delete
  document.querySelectorAll('.delete-form').forEach(f => {
    f.addEventListener('submit', e => {
      e.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Are you sure?',
        text: 'This document will be deleted.',
        width: 340,
        showCancelButton: true,
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        reverseButtons: true
      }).then(r => { if (r.isConfirmed) f.submit(); });
    });
  });

  // Show server messages (after redirect)
  if (flashError)   Swal.fire({ icon: 'error', title: 'Error', text: flashError, confirmButtonColor: '#2563eb' });
  if (flashSuccess) Swal.fire({ icon: 'success', title: 'Done', text: flashSuccess, timer: 1800, showConfirmButton: false });
})();
</script>
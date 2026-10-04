

      <!-- Page Content -->
      <main class="page">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">School Downloads & Forms</h1>
            <p class="page-sub">Public forms, admission brochures, route guides and certificates</p>
          </div>
          <div class="page-actions">
            <button class="btn btn-primary" type="button" id="open-upload-modal">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
              Upload New Form
            </button>
          </div>
        </div>

        <!-- Table Card -->
        <div class="card">
          <div class="table-wrap">
            <table class="table">
              <thead>
                <tr>
                  <th>Form Title & Description</th>
                  <th>Category</th>
                  <th>File Name</th>
                  <th>Format</th>
                  <th>Status</th>
                  <th style="width:110px;text-align:right;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <strong>Admission Application Form 2026–27</strong>
                    <div style="font-size:12px;color:var(--slate-500);margin-top:2px;">Complete admission form for Grades 1 through 11.</div>
                  </td>
                  <td><span class="badge badge-info">Admission</span></td>
                  <td><code style="font-size:12px;background:var(--slate-100);padding:2px 6px;border-radius:4px;">application-form.pdf</code></td>
                  <td><strong>PDF</strong></td>
                  <td><span class="badge badge-ok">Active</span></td>
                  <td>
                    <div class="row-actions" style="justify-content:flex-end;">
                      <button class="icon-btn" title="Download">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <strong>Transfer Certificate (TC) Request</strong>
                    <div style="font-size:12px;color:var(--slate-500);margin-top:2px;">Official application for requesting school leaving TC.</div>
                  </td>
                  <td><span class="badge badge-purple">Certificates</span></td>
                  <td><code style="font-size:12px;background:var(--slate-100);padding:2px 6px;border-radius:4px;">tc-request.pdf</code></td>
                  <td><strong>PDF</strong></td>
                  <td><span class="badge badge-ok">Active</span></td>
                  <td>
                    <div class="row-actions" style="justify-content:flex-end;">
                      <button class="icon-btn" title="Download">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <strong>Approved Holiday List 2026</strong>
                    <div style="font-size:12px;color:var(--slate-500);margin-top:2px;">Gazetted and school vacation calendar for the calendar year.</div>
                  </td>
                  <td><span class="badge badge-green">General</span></td>
                  <td><code style="font-size:12px;background:var(--slate-100);padding:2px 6px;border-radius:4px;">holidays-2026.pdf</code></td>
                  <td><strong>PDF</strong></td>
                  <td><span class="badge badge-ok">Active</span></td>
                  <td>
                    <div class="row-actions" style="justify-content:flex-end;">
                      <button class="icon-btn" title="Download">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <strong>Transport Route Guide & Timetable</strong>
                    <div style="font-size:12px;color:var(--slate-500);margin-top:2px;">Bus routes, pickup locations and driver contact numbers.</div>
                  </td>
                  <td><span class="badge badge-amber">Transport</span></td>
                  <td><code style="font-size:12px;background:var(--slate-100);padding:2px 6px;border-radius:4px;">routes.xlsx</code></td>
                  <td><strong>Excel</strong></td>
                  <td><span class="badge badge-ok">Active</span></td>
                  <td>
                    <div class="row-actions" style="justify-content:flex-end;">
                      <button class="icon-btn" title="Download">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>




      <!-- Upload Modal -->
<div class="modal-backdrop" id="upload-modal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="upload-title">
    <div class="modal-head">
      <h2 id="upload-title">Upload New Form</h2>
      <button type="button" class="modal-close" id="upload-close" aria-label="Close">&times;</button>
    </div>

    <form id="upload-form" enctype="multipart/form-data">
      <div class="modal-body">
        <label class="field-label" for="class-select">Class</label>
        <select id="class-select" name="class" class="field" required>
          <option value="">Select class</option>
          <option value="all">All Classes</option>
          <option value="1">Grade 1</option>
          <option value="2">Grade 2</option>
          <option value="3">Grade 3</option>
          <option value="4">Grade 4</option>
          <option value="5">Grade 5</option>
          <option value="6">Grade 6</option>
          <option value="7">Grade 7</option>
          <option value="8">Grade 8</option>
          <option value="9">Grade 9</option>
          <option value="10">Grade 10</option>
          <option value="11">Grade 11</option>
        </select>

        <label class="field-label" for="file-input">Document / PDF</label>
        <label class="dropzone" id="dropzone" for="file-input">
          <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m17 8-5-5-5 5"/><path d="M12 3v12"/></svg>
          <span id="file-name">Click to choose or drag &amp; drop a file</span>
          <small>PDF, DOC, DOCX, XLS, XLSX (max 10 MB)</small>
        </label>
        <input type="file" id="file-input" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" hidden required>
        <p class="field-error" id="file-error"></p>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn" id="upload-cancel">Cancel</button>
        <button type="submit" class="btn btn-primary">Upload</button>
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
</style>



<script>
    var modal = document.getElementById('upload-modal');
var openBtn = document.getElementById('open-upload-modal');
var form = document.getElementById('upload-form');
var fileInput = document.getElementById('file-input');
var fileName = document.getElementById('file-name');
var fileError = document.getElementById('file-error');
var dropzone = document.getElementById('dropzone');
var defaultText = fileName.textContent;
var MAX = 10 * 1024 * 1024;
var ALLOWED = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];

function openModal() { modal.classList.add('show'); modal.setAttribute('aria-hidden', 'false'); }
function closeModal() {
  modal.classList.remove('show');
  modal.setAttribute('aria-hidden', 'true');
  form.reset();
  fileName.textContent = defaultText;
  fileError.textContent = '';
}

function checkFile(file) {
  var ext = file.name.split('.').pop().toLowerCase();
  if (ALLOWED.indexOf(ext) === -1) return 'Only PDF, DOC, DOCX, XLS or XLSX files are allowed.';
  if (file.size > MAX) return 'File is larger than 10 MB.';
  return '';
}

function showFile(file) {
  var err = checkFile(file);
  fileError.textContent = err;
  if (err) { fileInput.value = ''; fileName.textContent = defaultText; return; }
  fileName.textContent = file.name;
}

openBtn.addEventListener('click', openModal);
document.getElementById('upload-close').addEventListener('click', closeModal);
document.getElementById('upload-cancel').addEventListener('click', closeModal);
modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

fileInput.addEventListener('change', function () {
  if (fileInput.files[0]) showFile(fileInput.files[0]);
});

['dragenter', 'dragover'].forEach(function (ev) {
  dropzone.addEventListener(ev, function (e) { e.preventDefault(); dropzone.classList.add('drag'); });
});
['dragleave', 'drop'].forEach(function (ev) {
  dropzone.addEventListener(ev, function (e) { e.preventDefault(); dropzone.classList.remove('drag'); });
});
dropzone.addEventListener('drop', function (e) {
  if (e.dataTransfer.files[0]) {
    fileInput.files = e.dataTransfer.files;
    showFile(e.dataTransfer.files[0]);
  }
});

form.addEventListener('submit', function (e) {
  e.preventDefault();
  var data = new FormData(form);
  // TODO: send to your backend, e.g.
  // fetch('/api/forms/upload', { method: 'POST', body: data }).then(...)
  console.log('Class:', data.get('class'), 'File:', data.get('file').name);
  closeModal();
});
</script>
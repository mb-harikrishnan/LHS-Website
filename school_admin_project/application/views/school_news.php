
      <!-- Page Content -->
      <main class="page">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">School News & Notices</h1>
            <p class="page-sub">Manage and publish official school announcements, circulars and campus events</p>
          </div>
          <div class="page-actions">
            <button class="btn btn-primary" type="button" id="openNewsModal">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
              Publish News
            </button>
          </div>
        </div>

        <!-- Table Card -->
        <div class="card">
          <!-- Toolbar -->
          <div class="table-toolbar">
            <div class="search-box">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
              <input type="text" placeholder="Search news by title or content…" aria-label="Search news">
            </div>

            <select class="filter-select" aria-label="Filter by category">
              <option value="">All Categories</option>
              <option value="sports">Sports</option>
              <option value="academics">Academics</option>
              <option value="events">Events</option>
              <option value="notices">Notices</option>
            </select>

            <select class="filter-select" aria-label="Filter by status">
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>

          <!-- Table -->
          <div class="table-wrap">
            <table class="table">
              <thead>
                <tr>
                  <th style="width:70px;">Cover</th>
                  <th>Article Title & Summary</th>
                  <th>Category</th>
                  <th>Published Date</th>
                  <th>Views</th>
                  <th>Status</th>
                  <th style="width:110px;text-align:right;">Actions</th>
                </tr>
              </thead>
              <tbody id="newsBody">
                <tr>
                  <td>
                    <div style="width:54px;height:40px;border-radius:8px;background:var(--brand-100);color:var(--brand-700);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:11px;">
                      SPORTS
                    </div>
                  </td>
                  <td>
                    <strong>Annual Sports Meet 2026 Announced</strong>
                    <div style="font-size:12px;color:var(--slate-500);margin-top:2px;">Three-day sports festival beginning February 12 across all grades.</div>
                  </td>
                  <td><span class="badge badge-purple">Sports</span></td>
                  <td>Oct 01, 2026</td>
                  <td><strong>428</strong> views</td>
                  <td><span class="badge badge-ok">Active</span></td>
                  <td>
                    <div class="row-actions" style="justify-content:flex-end;">
                      <button class="icon-btn" title="Edit Article">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.8 2.8 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3Z"/></svg>
                      </button>
                      <button class="icon-btn danger" title="Delete">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div style="width:54px;height:40px;border-radius:8px;background:var(--emerald-100);color:var(--emerald-700);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:11px;">
                      CBSE
                    </div>
                  </td>
                  <td>
                    <strong>CBSE Board Exam Schedule Released</strong>
                    <div style="font-size:12px;color:var(--slate-500);margin-top:2px;">Practical and theory timetable for Grades 10 and 12 are available.</div>
                  </td>
                  <td><span class="badge badge-green">Academics</span></td>
                  <td>Sep 24, 2026</td>
                  <td><strong>892</strong> views</td>
                  <td><span class="badge badge-ok">Active</span></td>
                  <td>
                    <div class="row-actions" style="justify-content:flex-end;">
                      <button class="icon-btn" title="Edit Article">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.8 2.8 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3Z"/></svg>
                      </button>
                      <button class="icon-btn danger" title="Delete">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div style="width:54px;height:40px;border-radius:8px;background:var(--amber-100);color:var(--amber-700);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:11px;">
                      EXPO
                    </div>
                  </td>
                  <td>
                    <strong>Science Exhibition — Project Registration Open</strong>
                    <div style="font-size:12px;color:var(--slate-500);margin-top:2px;">Students of Grades 6–11 can register individual or team projects.</div>
                  </td>
                  <td><span class="badge badge-amber">Events</span></td>
                  <td>Sep 18, 2026</td>
                  <td><strong>315</strong> views</td>
                  <td><span class="badge badge-ok">Active</span></td>
                  <td>
                    <div class="row-actions" style="justify-content:flex-end;">
                      <button class="icon-btn" title="Edit Article">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.8 2.8 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3Z"/></svg>
                      </button>
                      <button class="icon-btn danger" title="Delete">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div style="width:54px;height:40px;border-radius:8px;background:var(--slate-100);color:var(--slate-700);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:11px;">
                      NOTIC
                    </div>
                  </td>
                  <td>
                    <strong>New Library Timings from Next Week</strong>
                    <div style="font-size:12px;color:var(--slate-500);margin-top:2px;">Library will remain open until 5:30 PM on weekdays with new catalogue.</div>
                  </td>
                  <td><span class="badge badge-gray">Notices</span></td>
                  <td>Sep 10, 2026</td>
                  <td><strong>142</strong> views</td>
                  <td><span class="badge badge-muted">Inactive</span></td>
                  <td>
                    <div class="row-actions" style="justify-content:flex-end;">
                      <button class="icon-btn" title="Edit Article">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.8 2.8 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3Z"/></svg>
                      </button>
                      <button class="icon-btn danger" title="Delete">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Table Footer -->
          <div class="table-foot">
            <span class="muted">Showing 4 news announcements</span>
            <div class="pagination">
              <button class="pg-btn" disabled>Prev</button>
              <button class="pg-btn active">1</button>
              <button class="pg-btn" disabled>Next</button>
            </div>
          </div>
        </div>
      </main>

   


      <!-- Add News Modal -->


<div class="modal-overlay" id="newsModal" aria-hidden="true">
  <div class="modal" style="max-width:600px" role="dialog" aria-modal="true" aria-labelledby="newsModalTitle">
    <div class="modal-head">
      <h2 id="newsModalTitle">Publish News</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>

    <form id="newsForm" novalidate>
      <div class="modal-body">
        <label class="field-label" for="nTitle">Title <span class="req">*</span></label>
        <input id="nTitle" class="field-input" type="text" maxlength="120" placeholder="e.g. Annual Sports Meet 2026 Announced">

        <div class="field-row">
          <div>
            <label class="field-label" for="nCategory">Category <span class="req">*</span></label>
            <select id="nCategory" class="field-input">
              <option value="">Select category</option>
              <option value="sports">Sports</option>
              <option value="academics">Academics</option>
              <option value="events">Events</option>
              <option value="notices">Notices</option>
            </select>
          </div>
          <div>
            <label class="field-label" for="nDate">Published Date <span class="req">*</span></label>
            <input id="nDate" class="field-input" type="date">
          </div>
        </div>

        <label class="field-label" for="nSummary">Short Summary <span class="req">*</span></label>
        <input id="nSummary" class="field-input" type="text" maxlength="160" placeholder="One line shown in the list">

        <label class="field-label" for="nContent">Full Content</label>
        <textarea id="nContent" class="field-input" rows="5" placeholder="Write the complete news or notice here…"></textarea>

        <label class="field-label" for="nCover">Cover Image <small style="font-weight:400;color:#64748b">(optional, JPG/PNG/WEBP, max 2 MB)</small></label>
        <div class="cover-row">
          <img id="nCoverPreview" alt="" style="display:none">
          <input type="file" id="nCover" accept="image/png,image/jpeg,image/webp">
        </div>

        <label class="field-label" style="margin-top:16px">Status</label>
        <div class="status-row">
          <label><input type="radio" name="nStatus" value="active" checked> Active</label>
          <label><input type="radio" name="nStatus" value="inactive"> Inactive</label>
        </div>

        <p class="field-error" id="nError" hidden></p>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary">Publish</button>
      </div>
    </form>
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
.field-input{width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:8px;font:inherit;margin-bottom:16px;box-sizing:border-box}
textarea.field-input{resize:vertical}
.field-input:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.15)}
.field-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.cover-row{display:flex;align-items:center;gap:12px}
.cover-row img{width:72px;height:54px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0}
.status-row{display:flex;gap:20px;font-size:14px}
.status-row label{display:flex;align-items:center;gap:6px;cursor:pointer}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}
@media (max-width:520px){.field-row{grid-template-columns:1fr}}
</style>



<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('newsModal'), form = $('newsForm'), body = $('newsBody');
  const coverInput = $('nCover'), coverPreview = $('nCoverPreview'), errorEl = $('nError');
  const MAX_IMG = 2 * 1024 * 1024;

  const CATS = {
    sports:    { label: 'Sports',    badge: 'badge-purple', tag: 'SPORTS', bg: 'var(--brand-100)',   fg: 'var(--brand-700)' },
    academics: { label: 'Academics', badge: 'badge-green',  tag: 'ACAD',   bg: 'var(--emerald-100)', fg: 'var(--emerald-700)' },
    events:    { label: 'Events',    badge: 'badge-amber',  tag: 'EVENT',  bg: 'var(--amber-100)',   fg: 'var(--amber-700)' },
    notices:   { label: 'Notices',   badge: 'badge-gray',   tag: 'NOTICE', bg: 'var(--slate-100)',   fg: 'var(--slate-700)' }
  };

  const esc = s => s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const showError = t => { errorEl.textContent = t; errorEl.hidden = false; };
  const fmtDate = v => new Date(v + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });

  function open() {
    $('nDate').value = new Date().toISOString().slice(0, 10);   // default: today
    modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false');
    $('nTitle').focus();
  }
  function close() {
    modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true');
    form.reset(); errorEl.hidden = true;
    if (coverPreview.src) URL.revokeObjectURL(coverPreview.src);
    coverPreview.removeAttribute('src'); coverPreview.style.display = 'none';
  }

  $('openNewsModal').addEventListener('click', open);
  modal.addEventListener('click', e => { if (e.target === modal || e.target.closest('[data-close]')) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  // cover image preview + validation (images only)
  coverInput.addEventListener('change', () => {
    errorEl.hidden = true;
    const f = coverInput.files[0];
    if (!f) { coverPreview.style.display = 'none'; return; }
    if (!/^image\/(png|jpe?g|webp)$/.test(f.type)) {
      coverInput.value = ''; coverPreview.style.display = 'none';
      return showError('Cover must be a JPG, PNG or WEBP image.');
    }
    if (f.size > MAX_IMG) {
      coverInput.value = ''; coverPreview.style.display = 'none';
      return showError('Cover image must be 2 MB or smaller.');
    }
    coverPreview.src = URL.createObjectURL(f);
    coverPreview.style.display = 'block';
  });

  form.addEventListener('submit', e => {
    e.preventDefault();
    const title = $('nTitle').value.trim(), cat = $('nCategory').value, date = $('nDate').value;
    const summary = $('nSummary').value.trim(), content = $('nContent').value.trim();
    const status = form.querySelector('input[name="nStatus"]:checked').value;
    const cover = coverInput.files[0];

    if (!title)   return showError('Please enter the news title.');
    if (!cat)     return showError('Please select a category.');
    if (!date)    return showError('Please choose the published date.');
    if (!summary) return showError('Please enter a short summary.');

    // TODO: send to your backend
    // const data = new FormData();
    // data.append('title', title); data.append('category', cat); data.append('date', date);
    // data.append('summary', summary); data.append('content', content); data.append('status', status);
    // if (cover) data.append('cover', cover);
    // fetch('/api/news', { method: 'POST', body: data })

    addRow({ title, cat, date, summary, status, coverUrl: cover ? URL.createObjectURL(cover) : '' });
    close();
  });

  function addRow({ title, cat, date, summary, status, coverUrl }) {
    const c = CATS[cat];
    const coverHtml = coverUrl
      ? `<img src="${coverUrl}" alt="" style="width:54px;height:40px;object-fit:cover;border-radius:8px;display:block">`
      : `<div style="width:54px;height:40px;border-radius:8px;background:${c.bg};color:${c.fg};display:flex;align-items:center;justify-content:center;font-weight:700;font-size:11px;">${c.tag}</div>`;
    const statusHtml = status === 'active'
      ? '<span class="badge badge-ok">Active</span>'
      : '<span class="badge badge-muted">Inactive</span>';

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${coverHtml}</td>
      <td>
        <strong>${esc(title)}</strong>
        <div style="font-size:12px;color:var(--slate-500);margin-top:2px;">${esc(summary)}</div>
      </td>
      <td><span class="badge ${c.badge}">${c.label}</span></td>
      <td>${fmtDate(date)}</td>
      <td><strong>0</strong> views</td>
      <td>${statusHtml}</td>
      <td>
        <div class="row-actions" style="justify-content:flex-end;">
          <button class="icon-btn" title="Edit Article">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.8 2.8 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3Z"/></svg>
          </button>
          <button class="icon-btn danger" title="Delete" data-delete>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
          </button>
        </div>
      </td>`;
    body.prepend(tr);   // newest first
  }

  // delete (works for new and existing rows)
  body.addEventListener('click', e => {
    const btn = e.target.closest('.icon-btn.danger, [data-delete]');
    if (btn && confirm('Delete this news article?')) btn.closest('tr').remove();
  });
})();
</script>
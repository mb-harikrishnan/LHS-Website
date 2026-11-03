<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">School News &amp; Notices</h1>
      <p class="page-sub">Manage and publish official school announcements, circulars and campus events</p>
    </div>
    <div class="page-actions">
      <button class="btn btn-primary" type="button" id="openNewsModal">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        Publish News
      </button>
    </div>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="newsSearch" placeholder="Search news by title or content…" aria-label="Search news">
      </div>
      <select class="filter-select" id="statusFilter" aria-label="Filter by status">
        <option value="">All Status</option>
        <option value="Y">Active</option>
        <option value="N">Inactive</option>
      </select>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:60px;">#</th>
            <th>Title</th>
            <th>Published Date</th>
            <th>Details</th>
            <th>Status</th>
            <th style="width:110px;text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody id="newsBody">
          <?php if (!empty($news)): $i = 1; foreach ($news as $n): ?>
            <tr data-status="<?= $n->c_status ?>">
              <td><?= $i++ ?></td>
              <td><strong><?= html_escape($n->c_title) ?></strong></td>
              <td><?= date('M d, Y', strtotime($n->d_date)) ?></td>
              <td>
                <?php if (trim((string) $n->c_news) !== ''): ?>
                  <button type="button" class="btn btn-sm btn-view"
                          data-title="<?= html_escape($n->c_title) ?>"
                          data-date="<?= date('M d, Y', strtotime($n->d_date)) ?>"
                          data-news="<?= html_escape($n->c_news) ?>">
                    View News
                  </button>
                <?php else: ?>
                  <span class="muted">—</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($n->c_status === 'Y'): ?>
                  <span class="badge badge-ok">Active</span>
                <?php else: ?>
                  <span class="badge badge-muted">Inactive</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="row-actions" style="justify-content:flex-end;">
                  <button class="icon-btn btn-edit" title="Edit Article"
                          data-id="<?= $n->n_slno ?>"
                          data-title="<?= html_escape($n->c_title) ?>"
                          data-news="<?= html_escape($n->c_news) ?>"
                          data-date="<?= $n->d_date ?>"
                          data-status="<?= $n->c_status ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.8 2.8 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3Z"/></svg>
                  </button>
                  <button class="icon-btn danger btn-delete" title="Delete" data-id="<?= $n->n_slno ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; else: ?>
            <tr id="emptyRow"><td colspan="6" style="text-align:center;padding:24px" class="muted">No news announcements yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="table-foot">
      <span class="muted">Showing <span id="rowCount"><?= count($news) ?></span> news announcements</span>
    </div>
  </div>
</main>

<!-- ============ Add / Edit Modal ============ -->
<div class="modal-overlay" id="newsModal" aria-hidden="true">
  <div class="modal" style="max-width:600px" role="dialog" aria-modal="true" aria-labelledby="newsModalTitle">
    <div class="modal-head">
      <h2 id="newsModalTitle">Publish News</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>

    <form id="newsForm" novalidate>
      <input type="hidden" id="nId" value="0">
      <div class="modal-body">
        <label class="field-label" for="nTitle">Title <span class="req">*</span></label>
        <input id="nTitle" class="field-input" type="text" maxlength="120" placeholder="e.g. Annual Sports Meet 2026 Announced">

        <label class="field-label" for="nDate">Published Date <span class="req">*</span></label>
        <input id="nDate" class="field-input" type="date">

        <label class="field-label" for="nContent">Full Content</label>
        <textarea id="nContent" class="field-input" rows="6" placeholder="Write the complete news or notice here…"></textarea>

        <label class="field-label">Status</label>
        <div class="status-row">
          <label><input type="radio" name="nStatus" value="Y" checked> Active</label>
          <label><input type="radio" name="nStatus" value="N"> Inactive</label>
        </div>

        <p class="field-error" id="nError" hidden></p>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary" id="nSubmit">Publish</button>
      </div>
    </form>
  </div>
</div>

<!-- ============ View News Modal ============ -->
<div class="modal-overlay" id="viewModal" aria-hidden="true">
  <div class="modal" style="max-width:640px" role="dialog" aria-modal="true" aria-labelledby="vTitle">
    <div class="modal-head">
      <h2 id="vTitle"></h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <div class="modal-body">
      <p class="muted" id="vDate" style="margin:0 0 14px;font-size:13px"></p>
      <div id="vNews" style="white-space:pre-wrap;line-height:1.65;font-size:14px"></div>
    </div>
    <div class="modal-foot">
      <button type="button" class="btn" data-close>Close</button>
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
.field-input{width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:8px;font:inherit;margin-bottom:16px;box-sizing:border-box}
textarea.field-input{resize:vertical}
.field-input:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.15)}
.status-row{display:flex;gap:20px;font-size:14px}
.status-row label{display:flex;align-items:center;gap:6px;cursor:pointer}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}
.btn-sm{padding:5px 12px;font-size:12px}
</style>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function () {
  const $ = id => document.getElementById(id);
  const baseUrl = '<?= rtrim(site_url(), "/") ?>';
  let csrfName  = '<?= $this->security->get_csrf_token_name() ?>';
  let csrfHash  = '<?= $this->security->get_csrf_hash() ?>';

  const newsModal = $('newsModal'), viewModal = $('viewModal');
  const form = $('newsForm'), body = $('newsBody'), errorEl = $('nError');

  const showError = t => { errorEl.textContent = t; errorEl.hidden = false; };
  const openM  = m => { m.classList.add('open');    m.setAttribute('aria-hidden', 'false'); };
  const closeM = m => { m.classList.remove('open'); m.setAttribute('aria-hidden', 'true'); };

  // success popup, then reload the list
  function successAlert(text) {
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: text,
      timer: 1500,
      showConfirmButton: false
    }).then(() => location.reload());
  }

  function post(url, obj) {
    const fd = new FormData();
    Object.keys(obj).forEach(k => fd.append(k, obj[k]));
    fd.append(csrfName, csrfHash);
    return fetch(baseUrl + '/' + url, { method: 'POST', body: fd })
      .then(r => r.json())
      .then(res => { if (res.csrf) csrfHash = res.csrf; return res; });
  }

  /* ---------- open add modal ---------- */
  $('openNewsModal').addEventListener('click', () => {
    form.reset(); errorEl.hidden = true;
    $('nId').value = 0;
    $('nDate').value = new Date().toISOString().slice(0, 10);
    $('newsModalTitle').textContent = 'Publish News';
    $('nSubmit').textContent = 'Publish';
    openM(newsModal); $('nTitle').focus();
  });

  /* ---------- close modals ---------- */
  document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => {
      if (e.target === m || e.target.closest('[data-close]')) closeM(m);
    });
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeM(newsModal); closeM(viewModal); }
  });

  /* ---------- row buttons (view / edit / delete) ---------- */
  body.addEventListener('click', e => {
    const view = e.target.closest('.btn-view');
    const edit = e.target.closest('.btn-edit');
    const del  = e.target.closest('.btn-delete');

    if (view) {
      $('vTitle').textContent = view.dataset.title;
      $('vDate').textContent  = 'Published on ' + view.dataset.date;
      $('vNews').textContent  = view.dataset.news;
      openM(viewModal);
    }

    if (edit) {
      form.reset(); errorEl.hidden = true;
      $('nId').value      = edit.dataset.id;
      $('nTitle').value   = edit.dataset.title;
      $('nDate').value    = edit.dataset.date;
      $('nContent').value = edit.dataset.news;
      form.querySelector('input[name="nStatus"][value="' + edit.dataset.status + '"]').checked = true;
      $('newsModalTitle').textContent = 'Edit News';
      $('nSubmit').textContent = 'Update';
      openM(newsModal); $('nTitle').focus();
    }

    if (del) {
      Swal.fire({
        title: 'Are you sure?',
        text: 'This news article will be deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
      }).then(result => {
        if (!result.isConfirmed) return;

        post('delete_news', { id: del.dataset.id })
          .then(res => {
            if (res.status) successAlert('News deleted successfully.');
            else Swal.fire({ icon: 'error', title: 'Error', text: res.msg });
          })
          .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Server error. Please try again.' }));
      });
    }
  });

  /* ---------- save (add / update) ---------- */
  form.addEventListener('submit', e => {
    e.preventDefault();
    const title = $('nTitle').value.trim(), date = $('nDate').value;
    if (!title) return showError('Please enter the news title.');
    if (!date)  return showError('Please choose the published date.');

    const isEdit = parseInt($('nId').value, 10) > 0;

    $('nSubmit').disabled = true;
    post('save_news', {
      id: $('nId').value,
      title: title,
      date: date,
      description: $('nContent').value.trim(),
      status: form.querySelector('input[name="nStatus"]:checked').value
    }).then(res => {
      $('nSubmit').disabled = false;
      if (res.status) {
        closeM(newsModal);
        successAlert(isEdit ? 'News updated successfully.' : 'News published successfully.');
      } else {
        showError(res.msg);
      }
    }).catch(() => {
      $('nSubmit').disabled = false;
      showError('Server error. Please try again.');
    });
  });

  /* ---------- search + status filter ---------- */
  function applyFilter() {
    const q = $('newsSearch').value.toLowerCase(), st = $('statusFilter').value;
    let shown = 0;
    body.querySelectorAll('tr[data-status]').forEach(tr => {
      const vb = tr.querySelector('.btn-view');
      const text = (tr.textContent + ' ' + (vb ? vb.dataset.news : '')).toLowerCase();
      const ok = text.includes(q) && (!st || tr.dataset.status === st);
      tr.style.display = ok ? '' : 'none';
      if (ok) shown++;
    });
    $('rowCount').textContent = shown;
  }
  $('newsSearch').addEventListener('input', applyFilter);
  $('statusFilter').addEventListener('change', applyFilter);
})();
</script>
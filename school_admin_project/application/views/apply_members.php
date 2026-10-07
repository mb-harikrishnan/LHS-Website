<?php
/* Save as: application/views/members_area/apply_members.php
   Receives from controller: $applications */

// folder where resumes are stored (change to your real resume folder URL)
$resume_base = 'http://localhost:8000/assets/resumes/';

// distinct job titles for the filter dropdown
$jobs = array();
foreach ($applications as $a) {
    $jobs[] = !empty($a->job_title) ? $a->job_title : 'Job #' . $a->n_job_id;
}
$jobs = array_values(array_unique($jobs));
sort($jobs);
?>
<style>
/* action buttons */
.act-btn{display:inline-flex;align-items:center;gap:6px;height:34px;padding:0 12px;border-radius:8px;font:inherit;font-size:13px;font-weight:600;line-height:1;cursor:pointer;text-decoration:none;white-space:nowrap;border:1px solid transparent;transition:background .15s,color .15s,border-color .15s,box-shadow .15s,transform .05s}
.act-btn svg{width:15px;height:15px;flex:none}
.act-btn:active{transform:translateY(1px)}
.act-btn:focus-visible{outline:2px solid #93c5fd;outline-offset:2px}

.act-btn.dl{background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe}
.act-btn.dl:hover{background:#dbeafe;border-color:#93c5fd}

.act-btn.rm{background:#fef2f2;color:#b91c1c;border-color:#fecaca}
.act-btn.rm:hover{background:#dc2626;color:#fff;border-color:#dc2626;box-shadow:0 4px 10px rgba(220,38,38,.25)}
</style>

<main class="page">
  <h1 class="page-title">Job Applications</h1>
  <p class="page-sub">Candidates who applied for open vacancies</p>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search by name, email or mobile…" aria-label="Search applications">
      </div>
      <select class="filter-select" id="jobFilter" aria-label="Filter by job">
        <option value="">All Jobs</option>
        <?php foreach ($jobs as $j): ?>
          <option value="<?= html_escape($j) ?>"><?= html_escape($j) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr id="head">
            <th class="sortable" data-key="id"># <span class="arrows"><i class="up"></i><i class="down"></i></span></th>
            <th class="sortable" data-key="date">Date <span class="arrows"><i class="up"></i><i class="down"></i></span></th>
            <th class="sortable" data-key="job">Job <span class="arrows"><i class="up"></i><i class="down"></i></span></th>
            <th class="sortable" data-key="name">Name <span class="arrows"><i class="up"></i><i class="down"></i></span></th>
            <th>Email</th>
            <th>Mobile No</th>
            <th>Resume</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php foreach ($applications as $a):
            $job = !empty($a->job_title) ? $a->job_title : 'Job #' . $a->n_job_id;
          ?>
          <tr data-id="<?= (int)$a->n_slno ?>"
              data-date="<?= date('Y-m-d', strtotime($a->d_date)) ?>"
              data-job="<?= html_escape($job) ?>"
              data-name="<?= html_escape($a->c_name) ?>"
              data-email="<?= html_escape($a->c_email) ?>"
              data-mobile="<?= html_escape($a->n_mobile) ?>">
            <td class="num"></td>
            <td><?= date('d-m-Y', strtotime($a->d_date)) ?></td>
            <td class="job"><?= html_escape($job) ?></td>
            <td><?= html_escape($a->c_name) ?></td>
            <td><?= html_escape($a->c_email) ?></td>
            <td><?= html_escape($a->n_mobile) ?></td>
            <td>
              <?php if (!empty($a->c_resume)): ?>
                <a class="act-btn dl" href="<?= html_escape($resume_base . rawurlencode($a->c_resume)) ?>" target="_blank" rel="noopener" download>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12m0 0-4-4m4 4 4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/></svg>
                  Download
                </a>
              <?php else: ?>
                &mdash;
              <?php endif; ?>
            </td>
            <td>
              <button class="act-btn rm" type="button" data-del="<?= (int)$a->n_slno ?>" aria-label="Delete application of <?= html_escape($a->c_name) ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v6M14 11v6"/></svg>
                Delete
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="empty" id="empty" hidden>No applications match your search.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  const $ = id => document.getElementById(id);
  const tbody = $('rows');

  const DELETE_URL = '<?= site_url('delete_application') ?>';
  const CSRF_NAME  = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH  = '<?= $this->security->get_csrf_hash() ?>';

  let sortKey = 'date', sortDir = 'desc';   // matches the order sent by the controller

  /* ---------- filter + renumber ---------- */
  function applyFilter() {
    const q = $('search').value.trim().toLowerCase(), jf = $('jobFilter').value;
    const trs = [...tbody.querySelectorAll('tr')];
    let shown = 0;
    trs.forEach(tr => {
      const d = tr.dataset;
      const ok = (!jf || d.job === jf) &&
                 (!q || [d.name, d.email, d.mobile].some(v => v.toLowerCase().includes(q)));
      tr.hidden = !ok;
      if (ok) { shown++; tr.querySelector('.num').textContent = shown; }
    });
    $('empty').hidden = shown > 0;
    $('count').textContent = `Showing ${shown} of ${trs.length} applications`;
  }

  /* ---------- sort ---------- */
  function applySort() {
    const trs = [...tbody.querySelectorAll('tr')];
    trs.sort((a, b) => {
      let x = a.dataset[sortKey], y = b.dataset[sortKey];
      if (sortKey === 'id') { x = +x; y = +y; }
      else { x = x.toLowerCase(); y = y.toLowerCase(); }
      return (x > y ? 1 : x < y ? -1 : 0) * (sortDir === 'asc' ? 1 : -1);
    });
    trs.forEach(tr => tbody.appendChild(tr));
    document.querySelectorAll('#head th.sortable').forEach(th => {
      th.classList.remove('asc', 'desc');
      if (th.dataset.key === sortKey) th.classList.add(sortDir);
    });
    applyFilter();
  }

  $('head').addEventListener('click', e => {
    const th = e.target.closest('th.sortable'); if (!th) return;
    sortDir = (sortKey === th.dataset.key && sortDir === 'asc') ? 'desc' : 'asc';
    sortKey = th.dataset.key;
    applySort();
  });

  /* ---------- delete ---------- */
  tbody.addEventListener('click', e => {
    const del = e.target.closest('[data-del]');
    if (!del) return;
    Swal.fire({
      title: 'Are you sure?', text: 'This application will be deleted.', icon: 'warning',
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

  $('search').addEventListener('input', applyFilter);
  $('jobFilter').addEventListener('change', applyFilter);
  applySort();
})();
</script>
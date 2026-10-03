
<style>

.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:500px;max-height:92vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--slate-200)}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:var(--slate-500)}
.modal-body{padding:20px}
.detail{display:grid;grid-template-columns:110px 1fr;gap:10px 12px;margin:0;font-size:14px}
.detail dt{color:var(--slate-500);font-weight:600}
.detail dd{margin:0;overflow-wrap:anywhere}
.modal-foot{display:flex;justify-content:flex-end;padding:14px 20px;border-top:1px solid var(--slate-200)}
.btn{padding:9px 16px;border:1px solid var(--slate-300);background:#fff;border-radius:8px;font:inherit;font-weight:500;cursor:pointer}
</style>
</head>
<body>
<main class="page">
  <h1 class="page-title">Job Applications</h1>
  <p class="page-sub">Candidates who applied for open vacancies</p>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search by name, email or mobile…" aria-label="Search applications">
      </div>
      <select class="filter-select" id="jobFilter" aria-label="Filter by job"><option value="">All Jobs</option></select>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr id="head">
            <th class="sortable" data-key="no"># <span class="arrows"><i class="up"></i><i class="down"></i></span></th>
            <th class="sortable" data-key="date">Date <span class="arrows"><i class="up"></i><i class="down"></i></span></th>
            <th class="sortable" data-key="job">Job <span class="arrows"><i class="up"></i><i class="down"></i></span></th>
            <th class="sortable" data-key="name">Name <span class="arrows"><i class="up"></i><i class="down"></i></span></th>
            <th>Email</th>
            <th>Mobile No</th>
            <th>View</th>
            <th>Delete</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="rows"></tbody>
      </table>
      <div class="empty" id="empty" hidden>No applications match your search.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<div class="modal-overlay" id="viewModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="viewTitle">
    <div class="modal-head">
      <h2 id="viewTitle">Application details</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <div class="modal-body"><dl class="detail" id="detail"></dl></div>
    <div class="modal-foot"><button class="btn" type="button" data-close>Close</button></div>
  </div>
</div>

<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('viewModal');
  // sample data: replace with your API response
  let items = [
    { id: 1, date: '2026-06-24', job: 'PGT Mathematics Teacher', name: 'Anjali Nair', email: 'anjali.nair@example.com', mobile: '9847012345', status: 'Pending', note: 'M.Sc. Maths, B.Ed., 4 years CBSE experience.' },
    { id: 2, date: '2026-06-23', job: 'Office Assistant', name: 'Rahul Menon', email: 'rahul.menon@example.com', mobile: '9946098765', status: 'Shortlisted', note: 'B.Com graduate, proficient in MS Office.' },
    { id: 3, date: '2026-06-21', job: 'PGT Mathematics Teacher', name: 'Divya S', email: 'divya.s@example.com', mobile: '9895011223', status: 'Rejected', note: 'Fresher, B.Ed. in progress.' }
  ];
  let sortKey = 'date', sortDir = 'desc';
  const fmt = d => d.split('-').reverse().join('-');
  const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  function refreshJobs() {
    const cur = $('jobFilter').value, jobs = [...new Set(items.map(i => i.job))];
    $('jobFilter').innerHTML = '<option value="">All Jobs</option>' + jobs.map(j => `<option value="${esc(j)}">${esc(j)}</option>`).join('');
    $('jobFilter').value = jobs.includes(cur) ? cur : '';
  }

  function render() {
    const q = $('search').value.trim().toLowerCase(), jf = $('jobFilter').value;
    let list = items.filter(i => (!jf || i.job === jf) &&
      (!q || [i.name, i.email, i.mobile].some(v => v.toLowerCase().includes(q))));
    const key = sortKey === 'no' ? 'id' : sortKey;
    list.sort((a, b) => (a[key] > b[key] ? 1 : a[key] < b[key] ? -1 : 0) * (sortDir === 'asc' ? 1 : -1));

    $('rows').innerHTML = list.map((i, n) => `
      <tr>
        <td class="num">${n + 1}</td>
        <td>${fmt(i.date)}</td>
        <td class="job">${esc(i.job)}</td>
        <td>${esc(i.name)}</td>
        <td>${esc(i.email)}</td>
        <td>${esc(i.mobile)}</td>
        <td><button class="pill view" data-view="${i.id}">View</button></td>
        <td><button class="pill del" data-del="${i.id}">Delete</button></td>
        <td><select class="status ${i.status}" data-status="${i.id}" aria-label="Status for ${esc(i.name)}">
          ${['Pending', 'Shortlisted', 'Rejected'].map(s => `<option${s === i.status ? ' selected' : ''}>${s}</option>`).join('')}
        </select></td>
      </tr>`).join('');
    $('empty').hidden = list.length > 0;
    $('count').textContent = `Showing ${list.length} of ${items.length} applications`;
    document.querySelectorAll('#head th.sortable').forEach(th => {
      th.classList.remove('asc', 'desc');
      if (th.dataset.key === sortKey) th.classList.add(sortDir);
    });
  }

  $('head').addEventListener('click', e => {
    const th = e.target.closest('th.sortable'); if (!th) return;
    sortDir = (sortKey === th.dataset.key && sortDir === 'asc') ? 'desc' : 'asc';
    sortKey = th.dataset.key; render();
  });

  $('rows').addEventListener('click', e => {
    const v = e.target.closest('[data-view]'), d = e.target.closest('[data-del]');
    if (v) {
      const i = items.find(x => x.id === +v.dataset.view);
      $('detail').innerHTML = [['Date', fmt(i.date)], ['Job', i.job], ['Name', i.name], ['Email', i.email], ['Mobile no', i.mobile], ['Status', i.status], ['Details', i.note]]
        .map(([k, val]) => `<dt>${k}</dt><dd>${esc(val)}</dd>`).join('');
      modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false');
    }
    if (d && confirm('Delete this application?')) {
      // TODO: fetch('/api/applications/' + d.dataset.del, { method: 'DELETE' })
      items = items.filter(x => x.id !== +d.dataset.del); refreshJobs(); render();
    }
  });

  $('rows').addEventListener('change', e => {
    const s = e.target.closest('[data-status]'); if (!s) return;
    // TODO: fetch('/api/applications/' + s.dataset.status, { method: 'PATCH', body: ... })
    items.find(x => x.id === +s.dataset.status).status = s.value; render();
  });

  const close = () => { modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true'); };
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });
  $('search').addEventListener('input', render);
  $('jobFilter').addEventListener('change', render);

  refreshJobs(); render();
})();
</script>

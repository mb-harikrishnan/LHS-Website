<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Admission Enquiries</h1>
      <p class="page-sub">Enquiries submitted from the website admission form</p>
    </div>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <input type="text" id="search" placeholder="Search student, parent, phone, class…" aria-label="Search enquiries">
      </div>
    </div>

    <div class="table-wrap">
      <table class="table" id="admTable">
        <thead>
          <tr>
            <th style="width:60px">SL</th>
            <th>Student</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Class</th>
            <th>Parent</th>
            <th>Phone</th>
            <th style="width:90px">Status</th>
            <th style="width:100px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if (!empty($enquiries)): ?>
          <?php foreach ($enquiries as $i => $r): ?>
            <?php $is_new = (strcasecmp($r->status, 'New') === 0); ?>
            <tr data-id="<?= (int) $r->id ?>"
                data-student="<?= html_escape($r->student_name) ?>"
                data-dob="<?= $r->dob ? date('d M Y', strtotime($r->dob)) : '' ?>"
                data-gender="<?= html_escape($r->gender) ?>"
                data-class="<?= html_escape($r->class_applied) ?>"
                data-school="<?= html_escape($r->prev_school) ?>"
                data-parent="<?= html_escape($r->parent_name) ?>"
                data-phone="<?= html_escape($r->phone) ?>"
                data-email="<?= html_escape($r->email) ?>"
                data-address="<?= html_escape($r->address) ?>">
              <td><?= $i + 1 ?></td>
              <td><?= html_escape($r->student_name) ?></td>
              <td><?= $r->dob ? date('d M Y', strtotime($r->dob)) : '-' ?></td>
              <td><?= html_escape($r->gender) ?></td>
              <td><?= html_escape($r->class_applied) ?></td>
              <td><?= html_escape($r->parent_name) ?></td>
              <td><?= html_escape($r->phone) ?></td>
              <td>
                <span class="badge status-badge <?= $is_new ? 'badge-warn' : 'badge-ok' ?>">
                  <?= $is_new ? 'New' : 'Read' ?>
                </span>
              </td>
              <td style="text-align:right">
                <button type="button" class="btn btn-sm btn-primary" data-read>View</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="9" style="text-align:center;color:#64748b;padding:30px">No enquiries yet.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<style>
.badge-warn{background:#fef3c7;color:#b45309}
.badge-ok{background:#d1fae5;color:#047857}
tr.is-new td{font-weight:600}
.swal-detail{text-align:left;font-size:14px;line-height:1.8}
.swal-detail .addr{margin-top:8px;padding:10px 12px;background:#f1f5f9;border-radius:8px;white-space:pre-wrap;word-break:break-word}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  let csrfName = '<?= $this->security->get_csrf_token_name() ?>';
  let csrfHash = '<?= $this->security->get_csrf_hash() ?>';
  const READ_URL = '<?= site_url('read_admission') ?>';

  const table = document.getElementById('admTable');
  const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  // bold the new rows on load
  table.querySelectorAll('tbody tr').forEach(tr => {
    const b = tr.querySelector('.status-badge');
    if (b && b.textContent.trim() === 'New') tr.classList.add('is-new');
  });

  // search
  document.getElementById('search').addEventListener('input', e => {
    const q = e.target.value.toLowerCase().trim();
    table.querySelectorAll('tbody tr[data-id]').forEach(tr => {
      tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  });

  // View -> show details -> mark Read
  table.addEventListener('click', async e => {
    const btn = e.target.closest('[data-read]'); if (!btn) return;
    const tr = btn.closest('tr');
    const d  = tr.dataset;

    await Swal.fire({
      title: 'Admission Enquiry',
      html: '<div class="swal-detail">'
          + '<div><b>Student:</b> '        + esc(d.student) + '</div>'
          + '<div><b>DOB:</b> '            + esc(d.dob)     + '</div>'
          + '<div><b>Gender:</b> '         + esc(d.gender)  + '</div>'
          + '<div><b>Class applied:</b> '  + esc(d.class)   + '</div>'
          + '<div><b>Previous school:</b> '+ esc(d.school)  + '</div>'
          + '<div><b>Parent:</b> '         + esc(d.parent)  + '</div>'
          + '<div><b>Phone:</b> '          + esc(d.phone)   + '</div>'
          + '<div><b>Email:</b> '          + esc(d.email)   + '</div>'
          + '<div class="addr"><b>Address:</b> ' + esc(d.address) + '</div>'
          + '</div>',
      confirmButtonText: 'Close',
      confirmButtonColor: '#2563eb'
    });

    // already read? nothing more to do
    const badge = tr.querySelector('.status-badge');
    if (badge.textContent.trim() === 'Read') return;

    const body = new FormData();
    body.append('id', d.id);
    body.append(csrfName, csrfHash);

    try {
      const res  = await fetch(READ_URL, { method: 'POST', body, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();
      if (json.csrf) csrfHash = json.csrf;

      if (json.status) {
        badge.textContent = 'Read';
        badge.classList.remove('badge-warn');
        badge.classList.add('badge-ok');
        tr.classList.remove('is-new');
      } else {
        Swal.fire({ icon: 'error', title: 'Oops', text: json.msg });
      }
    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Network error', text: 'Please try again.' });
    }
  });
})();
</script>
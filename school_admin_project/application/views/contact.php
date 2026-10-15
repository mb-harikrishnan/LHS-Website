<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Contact Us Messages</h1>
      <p class="page-sub">Messages sent from the website contact form</p>
    </div>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <input type="text" id="search" placeholder="Search name, mobile, email, message…" aria-label="Search messages">
      </div>
    </div>

    <div class="table-wrap">
      <table class="table" id="contactTable">
        <thead>
          <tr>
            <th style="width:60px">SL</th>
            <th style="width:110px">Date</th>
            <th>Name</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Message</th>
            <th style="width:100px">Status</th>
            <th style="width:110px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if (!empty($contacts)): ?>
          <?php foreach ($contacts as $i => $c): ?>
            <?php $unread = ($c->c_status === 'Y'); ?>
            <tr data-id="<?= (int) $c->n_slno ?>"
                data-name="<?= html_escape($c->c_name) ?>"
                data-mobile="<?= html_escape($c->n_mobile) ?>"
                data-email="<?= html_escape($c->c_email) ?>"
                data-comment="<?= html_escape($c->c_comment) ?>"
                data-date="<?= $c->d_date ? date('d M Y', strtotime($c->d_date)) : '' ?>">
              <td><?= $i + 1 ?></td>
              <td><?= $c->d_date ? date('d M Y', strtotime($c->d_date)) : '-' ?></td>
              <td><?= html_escape($c->c_name) ?></td>
              <td><?= html_escape($c->n_mobile) ?></td>
              <td><?= html_escape($c->c_email) ?></td>
              <td class="msg-cell"><?= html_escape($c->c_comment) ?></td>
              <td>
                <span class="badge status-badge <?= $unread ? 'badge-warn' : 'badge-ok' ?>">
                  <?= $unread ? 'Unread' : 'Read' ?>
                </span>
              </td>
              <td style="text-align:right">
                <button type="button" class="btn btn-sm btn-primary" data-read>View</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="8" style="text-align:center;color:#64748b;padding:30px">No messages yet.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<style>
.msg-cell{max-width:320px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.badge-warn{background:#fef3c7;color:#b45309}
.badge-ok{background:#d1fae5;color:#047857}
tr.is-unread td{font-weight:600}
.swal-detail{text-align:left;font-size:14px;line-height:1.7}
.swal-detail .msg{margin-top:10px;padding:10px 12px;background:#f1f5f9;border-radius:8px;white-space:pre-wrap;word-break:break-word}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  let csrfName = '<?= $this->security->get_csrf_token_name() ?>';
  let csrfHash = '<?= $this->security->get_csrf_hash() ?>';
  const READ_URL = '<?= site_url('read_contact') ?>';

  const table = document.getElementById('contactTable');
  const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  // bold the unread rows on load
  table.querySelectorAll('tbody tr').forEach(tr => {
    const b = tr.querySelector('.status-badge');
    if (b && b.textContent.trim() === 'Unread') tr.classList.add('is-unread');
  });

  // search
  document.getElementById('search').addEventListener('input', e => {
    const q = e.target.value.toLowerCase().trim();
    table.querySelectorAll('tbody tr[data-id]').forEach(tr => {
      tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  });

  // View / Read
  table.addEventListener('click', async e => {
    const btn = e.target.closest('[data-read]'); if (!btn) return;
    const tr = btn.closest('tr');
    const d = tr.dataset;

    // show the full message
    await Swal.fire({
      title: 'Contact Message',
      html: '<div class="swal-detail">'
          + '<div><b>Name:</b> '   + esc(d.name)   + '</div>'
          + '<div><b>Mobile:</b> ' + esc(d.mobile) + '</div>'
          + '<div><b>Email:</b> '  + esc(d.email)  + '</div>'
          + '<div><b>Date:</b> '   + esc(d.date)   + '</div>'
          + '<div class="msg">'    + esc(d.comment) + '</div>'
          + '</div>',
      confirmButtonText: 'Close',
      confirmButtonColor: '#2563eb'
    });

    // already read? nothing more to do
    const badge = tr.querySelector('.status-badge');
    if (badge.textContent.trim() === 'Read') return;

    // mark as read (status Y -> N), row stays in the list
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
        tr.classList.remove('is-unread');
      } else {
        Swal.fire({ icon: 'error', title: 'Oops', text: json.msg });
      }
    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Network error', text: 'Please try again.' });
    }
  });
})();
</script>
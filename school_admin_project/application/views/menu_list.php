<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.frow{display:grid;grid-template-columns:140px 1fr;gap:4px 14px;align-items:center;margin-bottom:14px;text-align:left}
.frow>label{font-size:14px;font-weight:600;color:#1e3a8a}
.frow .hint{grid-column:2;font-size:12px;color:#94a3b8}
.sw-input{width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:10px;font:inherit;font-size:14px;background:#fff;box-sizing:border-box}
.sw-input:disabled{background:#f1f5f9;cursor:not-allowed}

.t-menu{font-weight:700;color:#334155;white-space:nowrap}
.sub .t-menu{font-weight:500;padding-left:22px}
.sub td{background:#f8fafc}
.sub .ln{color:#94a3b8;margin-right:6px}
.tg{width:18px;height:18px;border-radius:4px;border:0;background:#1e3a8a;color:#fff;font-size:14px;line-height:1;cursor:pointer;margin-right:8px;display:inline-flex;align-items:center;justify-content:center;vertical-align:middle}
.tg-gap{display:inline-block;width:26px}
.st{font-weight:700;font-size:13px}
.st.on{color:#334155}
.st.off{color:#dc2626}
.sub .st,.sub .dn,.sub .lk{font-weight:500}
.dn,.lk{color:#334155}
.is-off td{opacity:.6}
.is-off td:nth-last-child(-n+2){opacity:1}
.ed{border:1px solid #bfdbfe;background:#eff6ff;color:#2563eb;border-radius:10px;width:58px;height:38px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center}
.ed:hover{background:#dbeafe}
.sw{border:0;border-radius:8px;padding:8px 14px;color:#fff;font:inherit;font-size:13px;font-weight:600;cursor:pointer;min-width:92px}
.sw.en{background:#22a846}.sw.en:hover{background:#1c8f3b}
.sw.dis{background:#dc2626}.sw.dis:hover{background:#b91c1c}
.del{border:1px solid #fecaca;background:#fef2f2;color:#dc2626;border-radius:8px;padding:8px 12px;font:inherit;font-size:13px;font-weight:600;cursor:pointer}
.del:hover{background:#fee2e2}
.acts{display:flex;gap:6px;justify-content:center}
[hidden]{display:none !important}
@media(max-width:560px){.frow{grid-template-columns:1fr}.frow .hint{grid-column:1}}
</style>

<?php
$PEN = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>';

// small helper to print one table row
function menu_row($m, $n, $is_sub, $has_kids, $PEN) {
    $on = ($m->status == 1);
    ?>
    <tr class="<?= $is_sub ? 'sub' : 'parent' ?> <?= $on ? '' : 'is-off' ?>"
        data-id="<?= $m->menu_id ?>"
        data-parent="<?= (int) $m->parent_menu_id ?>"
        data-text="<?= html_escape(strtolower($m->menu_name . ' ' . $m->display_name . ' ' . $m->menu_link)) ?>">
      <td class="num"><?= $is_sub ? '' : $n ?></td>
      <td class="t-menu">
        <?php if ($is_sub): ?>
          <span class="ln">↳</span>
        <?php elseif ($has_kids): ?>
          <button class="tg" type="button" data-tg="<?= $m->menu_id ?>" aria-label="Expand">+</button>
        <?php else: ?>
          <span class="tg-gap"></span>
        <?php endif; ?>
        <?= html_escape($m->menu_name) ?>
      </td>
      <td class="dn"><?= html_escape($m->display_name) ?></td>
      <td class="lk"><?= $m->menu_link ? html_escape($m->menu_link) : '-' ?></td>
      <td><?= (int) $m->display_order ?></td>
      <td><span class="st <?= $on ? 'on' : 'off' ?>"><?= $on ? 'Active' : 'Inactive' ?></span></td>
      <td style="text-align:center">
        <button class="ed" type="button" title="Edit"
                data-edit="<?= $m->menu_id ?>"
                data-name="<?= html_escape($m->menu_name) ?>"
                data-disp="<?= html_escape($m->display_name) ?>"
                data-link="<?= html_escape($m->menu_link) ?>"
                data-parentid="<?= (int) $m->parent_menu_id ?>"
                data-order="<?= (int) $m->display_order ?>"
                data-kids="<?= $has_kids ? 1 : 0 ?>"><?= $PEN ?></button>
      </td>
      <td>
        <div class="acts">
          <button class="sw <?= $on ? 'dis' : 'en' ?>" type="button" data-sw="<?= $m->menu_id ?>" data-to="<?= $on ? 0 : 1 ?>">
            <?= $on ? '✕ Disable' : '✓ Enable' ?>
          </button>
          <button class="del" type="button" data-del="<?= $m->menu_id ?>" data-name="<?= html_escape($m->menu_name) ?>">Delete</button>
        </div>
      </td>
    </tr>
    <?php
}
?>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Menu List</h1>
      <p class="page-sub">Manage menus, sub-menus and their status</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">+ Add Menu</button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <input type="text" id="search" placeholder="Search menu name, display name or link…" aria-label="Search menus">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:60px">SL</th>
            <th>Menu Name</th>
            <th>Display Name</th>
            <th>Link</th>
            <th style="width:80px">Order</th>
            <th style="width:90px">Status</th>
            <th style="width:90px;text-align:center">Edit</th>
            <th style="width:250px;text-align:center">Action</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php
          $n = 0;
          $parents_js = [];
          if (!empty($tree)):
            foreach ($tree as $p):
              $n++;
              $parents_js[] = ['id' => (int) $p->menu_id, 'name' => $p->menu_name];
              menu_row($p, $n, false, !empty($p->children), $PEN);
              foreach ($p->children as $c) {
                  menu_row($c, 0, true, false, $PEN);
              }
            endforeach;
          endif;
          ?>
        </tbody>
      </table>
      <div class="empty" id="empty" <?= !empty($tree) ? 'hidden' : '' ?>>No menus found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<script>
(function () {
  const $ = id => document.getElementById(id);
  const PARENTS = <?= json_encode($parents_js) ?>;
  const URLS = {
    save:   "<?= base_url('menu_save') ?>",
    update: "<?= base_url('menu_update') ?>",
    del:    "<?= base_url('menu_delete') ?>",
    status: "<?= base_url('menu_status') ?>"
  };
  const expanded = new Set();
  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  async function post(url, data) {
    const fd = new FormData();
    Object.keys(data).forEach(k => fd.append(k, data[k]));
    // If CSRF is enabled, uncomment:
    // fd.append('<?= $this->security->get_csrf_token_name() ?>', '<?= $this->security->get_csrf_hash() ?>');
    try {
      const res = await fetch(url, { method: 'POST', body: fd });
      return await res.json();
    } catch (err) {
      return { success: false, message: 'Server error. Please try again.' };
    }
  }

  function successAndReload(msg) {
    Swal.fire({ icon: 'success', title: msg, timer: 1200, showConfirmButton: false })
        .then(() => location.reload());
  }

  /* ---------- ADD / EDIT popup ---------- */
  async function menuForm(item) {
    item = item || null;
    const id = item ? item.id : '';

    const parentOpts = '<option value="">-- None (Top Level) --</option>' +
      PARENTS.filter(p => String(p.id) !== String(id))
             .map(p => `<option value="${p.id}">${esc(p.name)}</option>`).join('');

    const html = `
      <div class="frow"><label>Menu Name <span style="color:#dc2626">*</span></label>
        <input id="fName" class="sw-input" placeholder="e.g. EXAM_MASTER" maxlength="60"></div>
      <div class="frow"><label>Display Name <span style="color:#dc2626">*</span></label>
        <input id="fDisp" class="sw-input" placeholder="e.g. Exam Master" maxlength="60"></div>
      <div class="frow"><label>Menu Link</label>
        <input id="fLink" class="sw-input" placeholder="/exams/master" maxlength="120">
        <span class="hint">Leave empty for a parent menu that only holds sub-menus.</span></div>
      <div class="frow"><label>Parent Menu</label>
        <select id="fParent" class="sw-input">${parentOpts}</select>
        <span class="hint" id="fParentHint" hidden>This menu has sub-menus, so it must stay top-level.</span></div>
      <div class="frow"><label>Display Order</label>
        <input id="fOrder" class="sw-input" type="number" min="1" step="1" placeholder="Auto">
        <span class="hint">Leave empty to add at the end.</span></div>`;

    const result = await Swal.fire({
      title: item ? 'Edit Menu' : 'Add Menu',
      html: html,
      width: 640,
      showCancelButton: true,
      confirmButtonText: item ? 'Update' : 'Save',
      showLoaderOnConfirm: true,
      allowOutsideClick: () => !Swal.isLoading(),
      didOpen: () => {
        if (item) {
          $('fName').value   = item.name;
          $('fDisp').value   = item.disp;
          $('fLink').value   = item.link;
          $('fParent').value = item.parentid ? String(item.parentid) : '';
          $('fOrder').value  = item.order;
          if (item.kids) { $('fParent').disabled = true; $('fParentHint').hidden = false; }
        }
        $('fName').focus();
      },
      preConfirm: async () => {
        const name  = $('fName').value.trim();
        const disp  = $('fDisp').value.trim();
        const link  = $('fLink').value.trim();
        const order = $('fOrder').value.trim();

        if (!name) { Swal.showValidationMessage('Please enter the menu name.'); return false; }
        if (!/^[A-Za-z0-9_ ]+$/.test(name)) { Swal.showValidationMessage('Menu name can use only letters, numbers and underscore.'); return false; }
        if (!disp) { Swal.showValidationMessage('Please enter the display name.'); return false; }
        if (link && link.charAt(0) !== '/') { Swal.showValidationMessage('Menu link must start with "/".'); return false; }
        if (order && (!/^\d+$/.test(order) || +order < 1)) { Swal.showValidationMessage('Display order must be a whole number from 1.'); return false; }

        const payload = {
          menu_name: name,
          display_name: disp,
          menu_link: link,
          parent_menu_id: $('fParent').value,
          display_order: order
        };
        let r;
        if (item) { payload.menu_id = id; r = await post(URLS.update, payload); }
        else      { r = await post(URLS.save, payload); }

        if (!r.success) { Swal.showValidationMessage(r.message); return false; }
        return r;
      }
    });
    if (result.isConfirmed) successAndReload(result.value.message);
  }

  $('openAddModal').addEventListener('click', () => menuForm(null));

  /* ---------- table buttons ---------- */
  $('rows').addEventListener('click', async e => {
    const tg  = e.target.closest('[data-tg]');
    const ed  = e.target.closest('[data-edit]');
    const sw  = e.target.closest('[data-sw]');
    const del = e.target.closest('[data-del]');

    if (tg) {
      const id = tg.dataset.tg;
      expanded.has(id) ? expanded.delete(id) : expanded.add(id);
      applyView();
      return;
    }

    if (ed) {
      menuForm({
        id: ed.dataset.edit, name: ed.dataset.name, disp: ed.dataset.disp, link: ed.dataset.link,
        parentid: +ed.dataset.parentid, order: ed.dataset.order, kids: ed.dataset.kids === '1'
      });
      return;
    }

    if (sw) {
      const to = +sw.dataset.to;
      const c = await Swal.fire({
        title: to ? 'Enable this menu?' : 'Disable this menu?',
        text: to ? '' : 'Its sub-menus (if any) will also be disabled.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: to ? 'Yes, enable' : 'Yes, disable',
        confirmButtonColor: to ? '#22a846' : '#dc2626'
      });
      if (!c.isConfirmed) return;
      const r = await post(URLS.status, { menu_id: sw.dataset.sw, status: to });
      r.success ? successAndReload(r.message)
                : Swal.fire({ icon: 'error', title: 'Oops', text: r.message });
      return;
    }

    if (del) {
      const c = await Swal.fire({
        title: 'Are you sure?',
        text: 'Delete menu "' + del.dataset.name + '"? This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Yes, delete it'
      });
      if (!c.isConfirmed) return;
      const r = await post(URLS.del, { menu_id: del.dataset.del });
      r.success ? successAndReload(r.message)
                : Swal.fire({ icon: 'error', title: 'Oops', text: r.message });
    }
  });

  /* ---------- expand / collapse + search ---------- */
  function applyView() {
    const q = $('search').value.trim().toLowerCase();
    const all = [...$('rows').querySelectorAll('tr')];
    const parents = all.filter(tr => tr.classList.contains('parent'));
    let shown = 0, n = 0;

    parents.forEach(p => {
      const pid = p.dataset.id;
      const kids = all.filter(tr => tr.classList.contains('sub') && tr.dataset.parent === pid);
      const selfHit = !q || p.dataset.text.includes(q);
      const kidHits = q ? kids.filter(k => k.dataset.text.includes(q)) : kids;
      const visibleParent = selfHit || kidHits.length > 0;

      p.hidden = !visibleParent;
      if (visibleParent) { n++; shown++; p.querySelector('.num').textContent = n; }

      const open = q ? visibleParent : expanded.has(pid);
      const btn = p.querySelector('.tg');
      if (btn) btn.textContent = open ? '−' : '+';

      kids.forEach(k => {
        const show = visibleParent && open && (!q || selfHit || k.dataset.text.includes(q));
        k.hidden = !show;
        if (show) shown++;
      });
    });

    $('empty').hidden = shown > 0;
    $('count').textContent = 'Showing ' + shown + ' of ' + all.length + ' menus';
  }
  $('search').addEventListener('input', applyView);
  applyView();
})();
</script>
<?php /* Receives: $year (amId, amYear) or null, $classes (cmId, cmName, total) */ ?>
<style>
.da-chip{display:inline-block;padding:6px 14px;border-radius:999px;font-size:13px;font-weight:600;background:#dcfce7;color:#15803d;margin:0 0 16px}
.da-warn{color:#b45309;background:#fffbeb;border:1px solid #fde68a;padding:10px 14px;border-radius:8px;font-size:13px;margin:12px 0}
.da-card{padding:16px}

.da-filters{display:flex;gap:14px;flex-wrap:wrap}
.da-f{display:flex;flex-direction:column;gap:4px;min-width:180px}
.da-f label{font-size:12px;font-weight:600;color:#475569}
.da-f select{padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font:inherit;background:#fff}
.da-f select:focus{outline:2px solid #6366f1;outline-offset:1px}

.da-bar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin:16px 0 10px}
.da-counts{display:flex;gap:8px;flex-wrap:wrap}
.cnt{background:#f1f5f9;border-radius:999px;padding:4px 12px;font-size:12px;color:#334155}
.cnt b{margin-right:4px}
.cnt.un{background:#fffbeb;color:#b45309}
.cnt.tc{background:#fef2f2;color:#b91c1c}
.da-actions{display:flex;gap:8px;flex-wrap:wrap}

.da-wrap{overflow:auto;max-height:65vh;border:1px solid #e2e8f0;border-radius:10px}
.da-table{width:100%;border-collapse:collapse;font-size:14px}
.da-table th{position:sticky;top:0;z-index:1;background:#f8fafc;text-align:left;padding:10px 12px;font-size:12px;
             text-transform:uppercase;letter-spacing:.3px;color:#475569;border-bottom:1px solid #e2e8f0;white-space:nowrap}
.da-table td{padding:8px 12px;border-bottom:1px solid #f1f5f9;vertical-align:middle}
.da-table tr:hover td{background:#fafafa}
.da-table .c{text-align:center;width:70px}
.da-table .n{width:48px;color:#94a3b8}
.da-table input[type=checkbox]{width:18px;height:18px;cursor:pointer;accent-color:#4f46e5}
.da-table td.tcc input{accent-color:#dc2626}
.da-table th.tch{color:#b91c1c}
tr.is-tc td{background:#fef2f2;color:#94a3b8}
tr.is-tc .nm{text-decoration:line-through}
.g{display:inline-block;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;background:#f1f5f9;color:#64748b}
.g0{background:#fdf2f8;color:#db2777}
.g1{background:#eff6ff;color:#2563eb}
.mut{color:#94a3b8}
.da-empty{padding:40px 0;text-align:center;color:#94a3b8;font-size:14px}
.da-foot{font-size:12px;color:#64748b;margin-top:10px}
[hidden]{display:none !important}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Division Allocation</h1>
      <p class="page-sub">Assign the students of a class to its divisions</p>
    </div>
  </div>

  <?php if (!$year): ?>
    <div class="da-warn">
      No active academic year. A year must be marked current (<b>amIsCurrent = 1</b>) and not yet promoted (<b>c_status = 0</b>).
    </div>
  <?php else: ?>
    <span class="da-chip">Academic year: <?= html_escape($year->amYear) ?></span>

    <div class="card da-card">
      <div class="da-filters">
        <div class="da-f">
          <label for="fClass">Class</label>
          <select id="fClass">
            <option value="">Select class</option>
            <?php foreach ($classes as $c): ?>
              <option value="<?= (int)$c->cmId ?>" data-name="<?= html_escape($c->cmName) ?>">
                <?= html_escape($c->cmName) ?> (<?= (int)$c->total ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="da-f">
          <label for="fGender">Gender</label>
          <select id="fGender">
            <option value="all">All (girls first, then boys)</option>
            <option value="0">Girls only</option>
            <option value="1">Boys only</option>
          </select>
        </div>
        <div class="da-f">
          <label for="fDiv">Division</label>
          <select id="fDiv">
            <option value="all">All divisions</option>
          </select>
        </div>
      </div>

      <div class="da-bar" id="bar" hidden>
        <div class="da-counts" id="counts"></div>
        <div class="da-actions">
          <button type="button" class="btn" id="shuffleBtn">Shuffle &amp; fill</button>
          <button type="button" class="btn" id="resetBtn">Reset</button>
          <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
        </div>
      </div>

      <div class="da-warn" id="warn" hidden></div>

      <div class="da-wrap" id="wrap" hidden>
        <table class="da-table">
          <thead id="thead"></thead>
          <tbody id="tbody"></tbody>
        </table>
      </div>

      <div class="da-empty" id="empty">Select a class to see its students.</div>
      <div class="da-foot" id="foot" hidden></div>
    </div>
  <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  var $ = function (id) { return document.getElementById(id); };
  if (!$('fClass')) return;

  var LIST_URL  = '<?= site_url('division_students') ?>';
  var SAVE_URL  = '<?= site_url('division_allocation_save') ?>';
  var CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
  var CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';

  var divisions = [];      // [{id, name}]
  var students  = [];      // [{id, adm, name, gender, divName, orig, sel, tc}]
  var byId      = {};
  var classId   = '';      // class shown now
  var pinned    = null;    // after a shuffle: the ids that stay on screen until a filter is changed

  /* ---------- helpers ---------- */
  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (ch) {
      return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[ch];
    });
  }
  function notify(type, msg) {
    if (window.Swal) Swal.fire({ icon: type, text: msg });
    else alert(msg);
  }
  function ask(title, text, yes) {
    if (window.Swal) {
      return Swal.fire({ title: title, text: text, icon: 'question', showCancelButton: true,
                         confirmButtonText: yes, cancelButtonText: 'Cancel' })
                 .then(function (r) { return r.isConfirmed; });
    }
    return Promise.resolve(confirm(title + '\n\n' + text));
  }
  function isDirty() {
    return students.some(function (s) { return s.tc || s.sel !== s.orig; });
  }
  function glabel(g) { return g === 0 ? 'Girl' : (g === 1 ? 'Boy' : '-'); }

  /* ---------- screen state ---------- */
  function showNothing(msg) {
    divisions = []; students = []; byId = {};
    $('bar').hidden = true; $('wrap').hidden = true; $('foot').hidden = true; $('warn').hidden = true;
    $('empty').textContent = msg; $('empty').hidden = false;
    fillDivFilter();
  }

  function fillDivFilter() {
    var h = '<option value="all">All divisions</option><option value="none">Unassigned</option>';
    divisions.forEach(function (d) { h += '<option value="' + d.id + '">' + esc(d.name) + '</option>'; });
    $('fDiv').innerHTML = h;
  }

  /* ---------- load a class ---------- */
  async function loadClass(id) {
    classId = id;
    pinned = null;
    if (!id) return showNothing('Select a class to see its students.');

    $('empty').textContent = 'Loading…'; $('empty').hidden = false;
    $('bar').hidden = true; $('wrap').hidden = true; $('foot').hidden = true;

    try {
      var res = await (await fetch(LIST_URL + '?class_id=' + encodeURIComponent(id))).json();
      if (res.csrf) CSRF_HASH = res.csrf;
      if (!res.status) { notify('error', res.msg); return showNothing(res.msg); }

      divisions = res.divisions;
      var allowed = {};
      divisions.forEach(function (d) { allowed[d.id] = true; });

      students = res.students.map(function (s) {
        var cur = allowed[s.div] ? s.div : 0;
        return { id: s.id, adm: s.adm, name: s.name, gender: s.gender, divName: s.divName,
                 orig: cur, sel: cur, tc: false };
      });
      byId = {};
      students.forEach(function (s) { byId[s.id] = s; });

      // keep the class label count in step with the list
      var opt = $('fClass').querySelector('option[value="' + id + '"]');
      if (opt) opt.textContent = opt.dataset.name + ' (' + students.length + ')';

      fillDivFilter();
      $('fGender').value = 'all';

      var w = $('warn');
      if (!divisions.length) {
        w.textContent = 'No divisions are allocated to this class in the class division allocation table.';
        w.hidden = false;
      } else { w.hidden = true; }

      $('shuffleBtn').disabled = !divisions.length;
      render(); updateCounts();
    } catch (e) {
      notify('error', 'Could not load the students.');
      showNothing('Could not load the students.');
    }
  }

  /* ---------- the students that are on screen (pinned list after a shuffle, otherwise the filters) ---------- */
  function visibleList() {
    var gf = $('fGender').value, df = $('fDiv').value;
    return students.filter(function (s) {
      if (pinned) return pinned[s.id] === true;
      if (gf !== 'all' && String(s.gender) !== gf) return false;
      if (df === 'none') return s.sel === 0;
      if (df !== 'all')  return s.sel === Number(df);
      return true;
    });
  }

  /* ---------- render ---------- */
  function render() {

    var head = '<tr><th class="n">#</th><th>Admission No</th><th>Name</th><th>Gender</th><th>Current Division</th>';
    divisions.forEach(function (d) { head += '<th class="c">' + esc(d.name) + '</th>'; });
    head += '<th class="c tch">TC</th></tr>';
    $('thead').innerHTML = head;

    var list = visibleList();

    var h = '', n = 0;
    list.forEach(function (s) {
      h += '<tr data-id="' + s.id + '"' + (s.tc ? ' class="is-tc"' : '') + '>' +
           '<td class="n">' + (++n) + '</td>' +
           '<td>' + esc(s.adm) + '</td>' +
           '<td class="nm">' + esc(s.name) + '</td>' +
           '<td><span class="g g' + s.gender + '">' + glabel(s.gender) + '</span></td>' +
           '<td>' + (s.divName ? esc(s.divName) : '<span class="mut">&mdash;</span>') + '</td>';
      divisions.forEach(function (d) {
        h += '<td class="c"><input type="checkbox" data-div="' + d.id + '"' +
             (s.sel === d.id ? ' checked' : '') + (s.tc ? ' disabled' : '') + '></td>';
      });
      h += '<td class="c tcc"><input type="checkbox" data-tc' + (s.tc ? ' checked' : '') + '></td></tr>';
    });
    $('tbody').innerHTML = h;

    $('bar').hidden  = false;
    $('wrap').hidden = list.length === 0;
    $('empty').textContent = 'No students match the selected filters.';
    $('empty').hidden = list.length > 0;
    $('foot').hidden = false;
    $('foot').textContent = 'Showing ' + list.length + ' of ' + students.length + ' students' +
                            (pinned ? ' (shuffled students; change a filter to refresh the view)' : '');
  }

  function updateCounts() {
    var c = {}, un = 0, tc = 0;
    divisions.forEach(function (d) { c[d.id] = 0; });
    students.forEach(function (s) {
      if (s.tc) { tc++; return; }
      if (s.sel && c[s.sel] !== undefined) c[s.sel]++; else un++;
    });
    var h = '';
    divisions.forEach(function (d) { h += '<span class="cnt"><b>' + esc(d.name) + '</b>' + c[d.id] + '</span>'; });
    h += '<span class="cnt un"><b>Unassigned</b>' + un + '</span>';
    if (tc) h += '<span class="cnt tc"><b>TC</b>' + tc + '</span>';
    $('counts').innerHTML = h;
  }

  /* ---------- ticks (one division per student) ---------- */
  $('tbody').addEventListener('change', function (e) {
    var tr = e.target.closest('tr');
    if (!tr) return;
    var s = byId[tr.dataset.id];
    if (!s) return;

    if (e.target.hasAttribute('data-tc')) {
      s.tc = e.target.checked;
      tr.classList.toggle('is-tc', s.tc);
      tr.querySelectorAll('[data-div]').forEach(function (c) { c.disabled = s.tc; });
    } else if (e.target.getAttribute('data-div')) {
      s.sel = e.target.checked ? Number(e.target.getAttribute('data-div')) : 0;
      tr.querySelectorAll('[data-div]').forEach(function (c) {
        c.checked = (Number(c.getAttribute('data-div')) === s.sel);
      });
    }
    updateCounts();
  });

  /* ---------- shuffle: works on the students that are shown ----------
     The shown students are mixed up and dealt one by one across ALL divisions
     (girls first, then boys), so every division gets a similar number and mix. */
  function shuffleArr(a) {
    for (var i = a.length - 1; i > 0; i--) {
      var j = Math.floor(Math.random() * (i + 1)), t = a[i]; a[i] = a[j]; a[j] = t;
    }
    return a;
  }
  $('shuffleBtn').addEventListener('click', function () {
    if (!divisions.length) return;

    var shown  = visibleList();
    var target = shown.filter(function (s) { return !s.tc; });
    if (!target.length) return notify('info', 'There are no students to shuffle with the current filters.');

    ask('Shuffle and fill?',
        target.length + ' student(s) shown (TC excluded) will be shuffled and spread evenly across ' +
        divisions.length + ' division(s). Nothing is saved until you click Save.', 'Yes, shuffle')
      .then(function (ok) {
        if (!ok) return;

        var keep = {};
        shown.forEach(function (s) { keep[s.id] = true; });

        var girls  = shuffleArr(target.filter(function (s) { return s.gender === 0; }));
        var boys   = shuffleArr(target.filter(function (s) { return s.gender === 1; }));
        var others = target.filter(function (s) { return s.gender !== 0 && s.gender !== 1; });

        // deal across the divisions, starting from a random one
        var i = Math.floor(Math.random() * divisions.length);
        girls.concat(boys, others).forEach(function (s) {
          s.sel = divisions[i % divisions.length].id;
          i++;
        });

        pinned = keep;          // the same students stay on screen so the result can be seen
        render(); updateCounts();
      });
  });

  /* ---------- reset to what is saved ---------- */
  $('resetBtn').addEventListener('click', function () {
    students.forEach(function (s) { s.sel = s.orig; s.tc = false; });
    pinned = null;
    render(); updateCounts();
  });

  /* ---------- save ---------- */
  $('saveBtn').addEventListener('click', function () {
    var changed = students.filter(function (s) { return !s.tc && s.sel !== s.orig; });
    var tc      = students.filter(function (s) { return s.tc; });

    if (!changed.length && !tc.length) return notify('info', 'Nothing has changed.');

    var text = changed.length + ' division change(s)';
    if (tc.length) text += ' and ' + tc.length + ' student(s) marked TC. TC students become inactive and leave this list';
    text += '.';

    ask('Save changes?', text, 'Yes, save').then(async function (ok) {
      if (!ok) return;

      var assign = {};
      changed.forEach(function (s) { assign[s.id] = s.sel; });

      var fd = new FormData();
      fd.append('class_id', classId);
      fd.append('assign', JSON.stringify(assign));
      fd.append('tc', JSON.stringify(tc.map(function (s) { return s.id; })));
      fd.append(CSRF_NAME, CSRF_HASH);

      $('saveBtn').disabled = true;
      try {
        var res = await (await fetch(SAVE_URL, { method: 'POST', body: fd })).json();
        if (res.csrf) CSRF_HASH = res.csrf;
        if (!res.status) { notify('error', res.msg); }
        else { notify('success', res.msg); await loadClass(classId); }
      } catch (e) {
        notify('error', 'Something went wrong. Please try again.');
      }
      $('saveBtn').disabled = false;
    });
  });

  /* ---------- filters ---------- */
  $('fGender').addEventListener('change', function () { pinned = null; if (students.length) render(); });
  $('fDiv').addEventListener('change',    function () { pinned = null; if (students.length) render(); });

  $('fClass').addEventListener('change', function () {
    var next = this.value, self = this;
    if (isDirty()) {
      ask('Discard changes?', 'You have unsaved changes for this class.', 'Yes, discard').then(function (ok) {
        if (ok) loadClass(next); else self.value = classId;
      });
    } else {
      loadClass(next);
    }
  });
})();
</script>
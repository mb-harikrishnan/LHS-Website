/* Leave form shared by Leave Requests (office) and My Attendance (staff). Needs StoreUI.
   opt: ids of staff (optional), type, from, to, half, reason, info, err; checkUrl; self (bool) */
window.LeaveForm = function (opt) {
  const $ = id => document.getElementById(id), U = StoreUI;
  const el = {staff: opt.staff ? $(opt.staff) : null, type: $(opt.type), from: $(opt.from), to: $(opt.to), half: $(opt.half),
              reason: $(opt.reason), info: $(opt.info), err: $(opt.err)};
  let seq = 0;
  const fmt = n => { n = +n; return Number.isInteger(n) ? String(n) : n.toFixed(1); };

  function halfSync() {
    if (el.half.checked) { el.to.value = el.from.value; el.to.disabled = true; } else { el.to.disabled = false; }
  }

  async function check() {
    halfSync();
    const from = el.from.value, to = el.to.value || from;
    el.info.textContent = '';
    if (!from || (el.staff && !el.staff.value) || !el.type.value) return;
    if (to < from) { el.info.innerHTML = '<span class="bad-txt">The end date is before the start date.</span>'; return; }
    const q = new URLSearchParams({type: el.type.value, from, to, half: el.half.checked ? '1' : '0'});
    if (el.staff) q.append('staff', el.staff.value);
    if (opt.self) q.append('self', '1');
    const n = ++seq;
    let res;
    try { res = await (await fetch(opt.checkUrl + '?' + q)).json(); } catch (e) { return; }
    if (n !== seq || !res.status) return;
    let t = `<strong>${fmt(res.days)}</strong> working day(s)` + (res.days ? '' : ' — these dates are holidays or weekly offs');
    const b = res.balance;
    if (b) {
      if (b.entitled === null) t += ' · no yearly limit for this leave';
      else {
        const left = b.balance - b.pending;
        t += ` · balance <strong>${fmt(b.balance)}</strong> of ${fmt(b.entitled)}` + (b.pending ? ` (${fmt(b.pending)} waiting for approval)` : '');
        if (res.days > left + 0.001) t += ' · <span class="bad-txt">not enough balance</span>';
      }
    }
    el.info.innerHTML = t;
  }
  ['change', 'input'].forEach(ev => [el.staff, el.type, el.from, el.to, el.half].forEach(x => x && x.addEventListener(ev, check)));

  function error(msg) { el.err.textContent = msg; el.err.hidden = false; }

  return {
    reset() {
      if (el.staff) el.staff.value = '';
      el.type.value = ''; el.from.value = ''; el.to.value = ''; el.half.checked = false; el.reason.value = '';
      el.info.textContent = ''; el.err.hidden = true; halfSync();
    },
    error,
    data() {
      el.err.hidden = true;
      if (el.staff && !el.staff.value) { error('Select the staff member.'); return null; }
      if (!el.type.value) { error('Select the leave type.'); return null; }
      if (!el.from.value) { error('Enter the leave date.'); return null; }
      const to = el.half.checked ? el.from.value : (el.to.value || el.from.value);
      if (to < el.from.value) { error('The end date is before the start date.'); return null; }
      const fd = new FormData();
      if (el.staff) fd.append('staff', el.staff.value);
      fd.append('type', el.type.value); fd.append('from', el.from.value); fd.append('to', to);
      fd.append('half', el.half.checked ? '1' : '0'); fd.append('reason', el.reason.value.trim());
      return fd;
    },
  };
};

/* School store screens: shared helpers + line-item editor (needs SweetAlert2) */
window.StoreUI = (function () {
  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c]));
  const inr = n => Number(n || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
  const qty = n => { n = Number(n || 0); return Number.isInteger(n) ? String(n) : n.toFixed(2); };
  const r2 = n => Math.round((+n || 0) * 100) / 100;
  const isNum = v => /^\d{1,8}(\.\d{1,2})?$/.test(String(v).trim());
  let csrf = {name: '', hash: ''};

  function init(c) { csrf = c; }

  async function post(url, fd) {
    fd.append(csrf.name, csrf.hash);
    const res = await (await fetch(url, {method: 'POST', body: fd})).json();
    if (res.csrf) csrf.hash = res.csrf;
    return res;
  }

  function modal(id) {
    const m = document.getElementById(id);
    const hide = () => m.classList.remove('open');
    m.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', hide));
    m.addEventListener('click', e => { if (e.target === m) hide(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') hide(); });
    return {el: m, show: () => m.classList.add('open'), hide};
  }

  const ok = (msg, reload = true) => Swal.fire({icon: 'success', title: 'Done', text: msg, timer: 1400, showConfirmButton: false})
    .then(() => { if (reload) location.reload(); });
  const err = msg => Swal.fire({icon: 'error', title: 'Error', text: msg});

  async function askReason(title) {
    const r = await Swal.fire({title, input: 'text', inputLabel: 'Reason', icon: 'warning', showCancelButton: true,
      confirmButtonColor: '#dc2626', confirmButtonText: 'Yes, cancel it', cancelButtonText: 'Back',
      inputValidator: v => !v.trim() ? 'Enter the reason.' : undefined});
    return r.isConfirmed ? r.value.trim() : null;
  }

  /* item picker: <input list> + <datalist>; calls onPick(item) */
  function picker(input, items, onPick, showStock) {
    const dl = document.createElement('datalist');
    dl.id = input.id + '_list';
    const label = i => `${i.code} — ${i.name}${showStock ? ' · stock ' + qty(i.stock) : ''}`;
    dl.innerHTML = items.map(i => `<option value="${esc(label(i))}"></option>`).join('');
    input.after(dl);
    input.setAttribute('list', dl.id);
    const find = v => {
      v = v.trim().toLowerCase();
      if (!v) return null;
      return items.find(i => label(i).toLowerCase() === v) || items.find(i => i.code.toLowerCase() === v) || null;
    };
    const pick = () => { const it = find(input.value); if (it) { onPick(it); input.value = ''; } };
    input.addEventListener('change', pick);
    input.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); pick(); } });
  }

  /**
   * Line editor
   *   mode 'sale'     : rate = sale price, tax % from item (read only), stock checked
   *   mode 'purchase' : rate = purchase price, tax % editable
   *   mode 'kit'      : qty only
   */
  function lines(opt) {
    const tbody = opt.tbody, items = opt.items, byId = {};
    items.forEach(i => byId[i.id] = i);
    const mode = opt.mode;

    function add(id, q, rate) {
      const it = byId[id]; if (!it) return;
      const ex = tbody.querySelector(`tr[data-id="${id}"]`);
      if (ex) {   // same item again: add to quantity
        const qi = ex.querySelector('.l-qty'); qi.value = r2((+qi.value || 0) + (+q || 1)); calc(); return ex;
      }
      const tr = document.createElement('tr');
      tr.dataset.id = id;
      const r = rate != null ? rate : (mode === 'purchase' ? it.cost : it.sale);
      tr.innerHTML = `<td><strong>${esc(it.name)}</strong><div class="small muted">${esc(it.code)} · ${esc(it.cat || '')}${mode !== 'purchase' ? ' · stock ' + qty(it.stock) : ''}</div></td>
        <td class="num"><input class="cell-input l-qty" inputmode="decimal" value="${r2(q || 1)}" style="width:80px"></td>
        ${mode === 'kit' ? '' : `<td class="num"><input class="cell-input l-rate" inputmode="decimal" value="${r2(r)}"></td>
        <td class="num">${mode === 'purchase' ? `<input class="cell-input l-tax" inputmode="decimal" value="${r2(it.tax)}" style="width:64px">` : `<span class="l-tax-ro">${qty(it.tax)}</span>`}</td>
        <td class="num l-amt"></td>`}
        <td><button type="button" class="act-btn rm l-del" aria-label="Remove">✕</button></td>`;
      tbody.appendChild(tr);
      calc();
      return tr;
    }

    function read() {
      return Array.from(tbody.rows).map(tr => {
        const it = byId[tr.dataset.id];
        const q = tr.querySelector('.l-qty').value.trim();
        const rate = tr.querySelector('.l-rate') ? tr.querySelector('.l-rate').value.trim() : '0';
        const tax = tr.querySelector('.l-tax') ? tr.querySelector('.l-tax').value.trim() : String(it.tax);
        return {tr, it, qty: q, rate, tax};
      });
    }

    function calc() {
      let sub = 0, tax = 0;
      read().forEach(l => {
        const amt = r2((+l.qty || 0) * (+l.rate || 0)), t = r2(amt * (+l.tax || 0) / 100);
        sub += amt; tax += t;
        const cell = l.tr.querySelector('.l-amt');
        if (cell) cell.textContent = inr(amt + t);
        l.tr.classList.toggle('row-warn', mode === 'sale' && (+l.qty || 0) > l.it.stock);
      });
      if (opt.onChange) opt.onChange({sub: r2(sub), tax: r2(tax), count: tbody.rows.length});
    }

    /* validation + FormData fields item[] qty[] rate[] tax[] */
    function fill(fd) {
      const ls = read();
      if (!ls.length) return 'Add at least one item.';
      for (const l of ls) {
        if (!isNum(l.qty) || +l.qty <= 0) return 'Enter the quantity for ' + l.it.name + '.';
        if (mode !== 'kit' && !isNum(l.rate)) return 'Enter a valid rate for ' + l.it.name + '.';
        if (mode === 'purchase' && (!isNum(l.tax) || +l.tax > 100)) return 'Tax % for ' + l.it.name + ' must be 0 - 100.';
        if (mode === 'sale' && +l.qty > l.it.stock) return l.it.name + ': only ' + qty(l.it.stock) + ' in stock.';
      }
      ls.forEach(l => {
        fd.append('item[]', l.it.id); fd.append('qty[]', l.qty);
        if (mode !== 'kit') fd.append('rate[]', l.rate);
        if (mode === 'purchase') fd.append('tax[]', l.tax);
      });
      return '';
    }

    tbody.addEventListener('input', calc);
    tbody.addEventListener('click', e => { if (e.target.closest('.l-del')) { e.target.closest('tr').remove(); calc(); } });
    return {add, calc, fill, clear: () => { tbody.innerHTML = ''; calc(); }, count: () => tbody.rows.length};
  }

  return {esc, inr, qty, r2, isNum, init, post, modal, ok, err, askReason, picker, lines};
})();

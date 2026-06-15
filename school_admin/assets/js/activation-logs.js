const MOCK_RECEIPT_SVG = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='300' height='400' viewBox='0 0 300 400'><rect width='100%' height='100%' fill='%23f2ede4'/><text x='20' y='40' font-family='Courier' font-size='16' font-weight='bold' fill='%231b3a2d'>SBI BANK TRANSFER</text><line x1='20' y1='60' x2='280' y2='60' stroke='%231b3a2d' stroke-width='2'/><text x='20' y='100' font-family='Courier' font-size='12' fill='%234a5c52'>Ref UTR: UTRADV1287410</text><text x='20' y='130' font-family='Courier' font-size='12' fill='%234a5c52'>Date: 04 June 2026</text><text x='20' y='160' font-family='Courier' font-size='12' fill='%234a5c52'>From: Radha Madhav (RM-0001)</text><text x='20' y='190' font-family='Courier' font-size='12' fill='%234a5c52'>To: Radha Madhav Growth Pvt Ltd</text><text x='20' y='220' font-family='Courier' font-size='12' fill='%234a5c52'>Amount: INR 14,160.00</text><rect x='20' y='260' width='260' height='80' fill='%231b3a2d' rx='6'/><text x='60' y='305' font-family='Courier' font-size='16' font-weight='bold' fill='white'>PAYMENT SUCCESS</text></svg>";

const defaultActivations = [
  {
    orderId: 'RM-ACT-93821',
    packageName: 'Starter Package',
    amount: 7080,
    paymentMode: 'Online (Razorpay)',
    utr: 'MOCK-RZP-9281742',
    date: '2026-06-08',
    status: 'Active',
    receipt: ''
  },
  {
    orderId: 'RM-ACT-12984',
    packageName: 'Advanced Package',
    amount: 14160,
    paymentMode: 'Bank Transfer',
    utr: 'UTRADV1287410',
    date: '2026-06-04',
    status: 'Pending Approval',
    receipt: MOCK_RECEIPT_SVG
  }
];

const ACT_COLUMN_LABELS = ['SLNO', 'Order ID', 'Package Details', 'Amount Paid', 'Payment Mode', 'Transaction Ref', 'Purchase Date', 'Status', 'Receipt File'];

let activationsList = [];
let actDataTable = null;

function loadActivationsList() {
  try {
    const raw = localStorage.getItem('rm_activations');
    if (raw) {
      activationsList = JSON.parse(raw);
    } else {
      activationsList = [...defaultActivations];
      localStorage.setItem('rm_activations', JSON.stringify(activationsList));
    }
  } catch (e) {
    activationsList = [...defaultActivations];
  }
}

function formatDisplayDate(iso) {
  if (!iso) return '—';
  const d = new Date(iso + 'T00:00:00');
  return d.toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' });
}

function buildActivationRow(r, index) {
  let statusClass = 'pending';
  if (r.status === 'Active') statusClass = 'active';
  else if (r.status === 'Rejected') statusClass = 'inactive';

  const receiptBtn = r.receipt 
    ? `<button class="btn btn-ghost btn-sm" onclick="viewReceipt(${index})" style="padding: 6px 12px; font-size:11.5px;">View Receipt</button>` 
    : `<span style="color:var(--text-light); font-size:11.5px;">—</span>`;

  return [
    `<span style="font-weight:600; color:var(--text);">${index + 1}</span>`,
    `<span style="font-weight:600; color:var(--text);">${r.orderId}</span>`,
    r.packageName,
    `₹${r.amount.toLocaleString('en-IN')}`,
    r.paymentMode,
    `<span style="font-family:monospace;">${r.utr || '—'}</span>`,
    formatDisplayDate(r.date),
    `<span class="status-pill ${statusClass}">${r.status}</span>`,
    receiptBtn
  ];
}

function getActTableOptions() {
  return {
    data: activationsList.map((r, i) => buildActivationRow(r, i)),
    pageLength: 10,
    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'All']],
    order: [[0, 'desc']], // Sort by new entries first
    autoWidth: false,
    responsive: false,
    dom: '<"dt-toolbar"lf>rt<"dt-footer"ip>',
    language: {
      search: '',
      searchPlaceholder: 'Search orders…',
      lengthMenu: 'Show _MENU_ entries',
      info: 'Showing _START_ to _END_ of _TOTAL_ entries',
      infoEmpty: 'Showing 0 to 0 of 0 entries',
      infoFiltered: '(filtered from _MAX_ total entries)',
      zeroRecords: 'No matching orders found',
      paginate: { first: '«', last: '»', next: 'Next', previous: 'Previous' }
    },
    columnDefs: [{
      targets: '_all',
      createdCell: function(td, cellData, _rowData, _row, col) {
        td.setAttribute('data-label', ACT_COLUMN_LABELS[col]);
        td.innerHTML = cellData;
      }
    }]
  };
}

function refreshActTable() {
  const empty = document.getElementById('emptyActivationState');
  const wrap = document.getElementById('activationTableWrap');

  if (!activationsList.length) {
    empty.style.display = 'block';
    wrap.style.display = 'none';
    if (actDataTable) {
      actDataTable.clear().draw();
    }
    return;
  }

  empty.style.display = 'none';
  wrap.style.display = 'block';

  if (!actDataTable) {
    actDataTable = $('#activationLogsTable').DataTable(getActTableOptions());
    return;
  }

  actDataTable.clear();
  actDataTable.rows.add(activationsList.map((r, i) => buildActivationRow(r, i)));
  actDataTable.draw();
}

function viewReceipt(index) {
  const item = activationsList[index];
  if (!item || !item.receipt) return;

  const modal = document.getElementById('receiptModal');
  const img = document.getElementById('receiptModalImg');
  const details = document.getElementById('receiptModalDetails');

  img.src = item.receipt;
  details.textContent = `Order ID: ${item.orderId} · Ref UTR: ${item.utr || '—'} · Date: ${formatDisplayDate(item.date)}`;

  modal.classList.add('show');
}

function closeReceiptModal() {
  document.getElementById('receiptModal').classList.remove('show');
}

// Close Modal when clicking outside the modal dialog box
document.addEventListener('click', e => {
  const modal = document.getElementById('receiptModal');
  if (e.target === modal) {
    closeReceiptModal();
  }
});

document.addEventListener('DOMContentLoaded', () => {
  loadActivationsList();
  refreshActTable();
});

if (document.readyState === 'complete' || document.readyState === 'interactive') {
  loadActivationsList();
  refreshActTable();
}

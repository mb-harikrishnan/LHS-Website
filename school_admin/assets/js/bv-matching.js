// ═══ DUMMY BV MATCHING DATA ═══
const allBVData = [
  { slno: 1, date: '2026-06-08', leftBv: 1200, rightBv: 1000, pairBv: 1000, leftCarryBv: 200, rightCarryBv: 0 },
  { slno: 2, date: '2026-06-07', leftBv: 800, rightBv: 1500, pairBv: 800, leftCarryBv: 0, rightCarryBv: 700 },
  { slno: 3, date: '2026-06-05', leftBv: 1500, rightBv: 1500, pairBv: 1500, leftCarryBv: 0, rightCarryBv: 0 },
  { slno: 4, date: '2026-06-04', leftBv: 600, rightBv: 900, pairBv: 600, leftCarryBv: 0, rightCarryBv: 300 },
  { slno: 5, date: '2026-06-02', leftBv: 2000, rightBv: 1800, pairBv: 1800, leftCarryBv: 200, rightCarryBv: 0 },
  { slno: 6, date: '2026-05-30', leftBv: 1000, rightBv: 1200, pairBv: 1000, leftCarryBv: 0, rightCarryBv: 200 },
  { slno: 7, date: '2026-05-28', leftBv: 1200, rightBv: 800, pairBv: 800, leftCarryBv: 400, rightCarryBv: 0 },
  { slno: 8, date: '2026-05-25', leftBv: 1500, rightBv: 2000, pairBv: 1500, leftCarryBv: 0, rightCarryBv: 500 },
  { slno: 9, date: '2026-05-22', leftBv: 800, rightBv: 800, pairBv: 800, leftCarryBv: 0, rightCarryBv: 0 },
  { slno: 10, date: '2026-05-20', leftBv: 3000, rightBv: 2500, pairBv: 2500, leftCarryBv: 500, rightCarryBv: 0 },
  { slno: 11, date: '2026-05-15', leftBv: 1000, rightBv: 1200, pairBv: 1000, leftCarryBv: 0, rightCarryBv: 200 },
  { slno: 12, date: '2026-05-10', leftBv: 1800, rightBv: 1500, pairBv: 1500, leftCarryBv: 300, rightCarryBv: 0 },
  { slno: 13, date: '2026-05-05', leftBv: 2500, rightBv: 3000, pairBv: 2500, leftCarryBv: 0, rightCarryBv: 500 },
  { slno: 14, date: '2026-05-01', leftBv: 1200, rightBv: 1200, pairBv: 1200, leftCarryBv: 0, rightCarryBv: 0 }
];

const BV_COLUMN_LABELS = ['SLNO', 'Date', 'Left BV', 'Right BV', 'Pair BV', 'Left Carry BV', 'Right Carry BV'];

let filteredBVData = [...allBVData];
let bvDataTable = null;

function formatDisplayDate(iso) {
  const d = new Date(iso + 'T00:00:00');
  return d.toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' });
}

function formatBVValue(val) {
  return val.toLocaleString('en-IN');
}

function buildBVRow(r) {
  return [
    `<span style="font-weight:600; color:var(--text);">${r.slno}</span>`,
    formatDisplayDate(r.date),
    `<span style="font-weight:600; color:var(--text);">${formatBVValue(r.leftBv)}</span>`,
    `<span style="font-weight:600; color:var(--text);">${formatBVValue(r.rightBv)}</span>`,
    `<span style="font-weight:700; color:#3ba588;">${formatBVValue(r.pairBv)}</span>`,
    `<span style="color:var(--text-mid);">${formatBVValue(r.leftCarryBv)}</span>`,
    `<span style="color:var(--text-mid);">${formatBVValue(r.rightCarryBv)}</span>`
  ];
}

function getBVTableOptions() {
  return {
    data: filteredBVData.map(buildBVRow),
    pageLength: 10,
    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'All']],
    order: [[0, 'asc']], // Sort by serial number ascending
    autoWidth: false,
    responsive: false,
    dom: '<"dt-toolbar"lf>rt<"dt-footer"ip>',
    language: {
      search: '',
      searchPlaceholder: 'Search…',
      lengthMenu: 'Show _MENU_ entries',
      info: 'Showing _START_ to _END_ of _TOTAL_ entries',
      infoEmpty: 'Showing 0 to 0 of 0 entries',
      infoFiltered: '(filtered from _MAX_ total entries)',
      zeroRecords: 'No matching records found',
      paginate: { first: '«', last: '»', next: 'Next', previous: 'Previous' }
    },
    columnDefs: [{
      targets: '_all',
      createdCell: function(td, cellData, _rowData, _row, col) {
        td.setAttribute('data-label', BV_COLUMN_LABELS[col]);
        td.innerHTML = cellData;
      }
    }]
  };
}

function initBVDataTable() {
  if (bvDataTable) return;
  bvDataTable = $('#bvMatchingDataTable').DataTable(getBVTableOptions());
}

function refreshBVDataTable() {
  const emptyState = document.getElementById('emptyBVState');
  const tableWrap = document.getElementById('bvTableWrap');

  if (!filteredBVData.length) {
    emptyState.style.display = 'block';
    tableWrap.style.display = 'none';
    if (bvDataTable) {
      bvDataTable.clear().draw();
    }
    return;
  }

  emptyState.style.display = 'none';
  tableWrap.style.display = 'block';

  if (!bvDataTable) {
    initBVDataTable();
    return;
  }

  bvDataTable.clear();
  bvDataTable.rows.add(filteredBVData.map(buildBVRow));
  bvDataTable.draw();
}

function filterBVData() {
  const from = document.getElementById('fromDate').value;
  const to = document.getElementById('toDate').value;

  if (!from || !to) {
    if (typeof showToast === 'function') {
      showToast('Please select both From Date and To Date.', 'red');
    } else {
      alert('Please select both From Date and To Date.');
    }
    return;
  }
  if (from > to) {
    if (typeof showToast === 'function') {
      showToast('From Date cannot be after To Date.', 'red');
    } else {
      alert('From Date cannot be after To Date.');
    }
    return;
  }

  filteredBVData = allBVData.filter(r => r.date >= from && r.date <= to);
  refreshBVDataTable();
  
  if (typeof showToast === 'function') {
    showToast(`Filtered ${filteredBVData.length} record(s).`, 'green');
  }
}

// Add page theme modification when JS loads
document.addEventListener('DOMContentLoaded', () => {
  document.body.classList.add('page-bv-matching');
  
  // Initialize table
  filterBVData();
});

// Fallback in case DOMContentLoaded has already fired
if (document.readyState === 'complete' || document.readyState === 'interactive') {
  document.body.classList.add('page-bv-matching');
  filterBVData();
}

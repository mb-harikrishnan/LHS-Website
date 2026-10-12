// ═══ DUMMY REPORT DATA ═══
const allReports = [
  { date:'2026-06-05', id:'RPT-1042', type:'sales',      typeLabel:'Product Sales', description:'June product sales batch',     member:'Anita Sharma',   amount:82400,  flow:'credit', status:'completed' },
  { date:'2026-06-04', id:'RPT-1041', type:'commission', typeLabel:'Commission',    description:'May cycle team commission',    member:'Rakesh Gupta',   amount:12450,  flow:'credit', status:'completed' },
  { date:'2026-06-03', id:'RPT-1040', type:'expense',    typeLabel:'Expense',       description:'Training materials purchase',  member:'—',              amount:3200,   flow:'debit',  status:'completed' },
  { date:'2026-06-02', id:'RPT-1039', type:'bonus',      typeLabel:'Bonus',         description:'Diamond level performance bonus',member:'Meena Patel',    amount:18000,  flow:'credit', status:'completed' },
  { date:'2026-06-01', id:'RPT-1038', type:'expense',    typeLabel:'Expense',       description:'Promotional event setup',      member:'—',              amount:6800,   flow:'debit',  status:'completed' },
  { date:'2026-05-28', id:'RPT-1037', type:'sales',      typeLabel:'Product Sales', description:'End of May sales',             member:'Kavita Singh',   amount:56200,  flow:'credit', status:'completed' },
  { date:'2026-05-25', id:'RPT-1036', type:'commission', typeLabel:'Commission',    description:'Regional commission payout',   member:'Suresh Yadav',   amount:9800,   flow:'credit', status:'completed' },
  { date:'2026-05-20', id:'RPT-1035', type:'bonus',      typeLabel:'Bonus',         description:'Gold leader monthly bonus',    member:'Laxmi Nair',     amount:7500,   flow:'credit', status:'completed' },
  { date:'2026-05-15', id:'RPT-1034', type:'expense',    typeLabel:'Expense',       description:'Office supplies',              member:'—',              amount:2400,   flow:'debit',  status:'completed' },
  { date:'2026-05-10', id:'RPT-1033', type:'sales',      typeLabel:'Product Sales', description:'Mid-May sales drive',          member:'Geeta Rao',      amount:41500,  flow:'credit', status:'completed' },
  { date:'2026-05-05', id:'RPT-1032', type:'commission', typeLabel:'Commission',    description:'April cycle commission',       member:'Harish Bhatt',   amount:6200,   flow:'credit', status:'pending'   },
  { date:'2026-04-28', id:'RPT-1031', type:'sales',      typeLabel:'Product Sales', description:'April closing sales',          member:'Pradeep Kumar',  amount:38900,  flow:'credit', status:'completed' },
  { date:'2026-04-20', id:'RPT-1030', type:'expense',    typeLabel:'Expense',       description:'Travel & logistics',           member:'—',              amount:5100,   flow:'debit',  status:'completed' },
  { date:'2026-04-12', id:'RPT-1029', type:'bonus',      typeLabel:'Bonus',         description:'Silver tier achievement bonus',  member:'Mohan Lal',      amount:5000,   flow:'credit', status:'completed' },
  { date:'2026-03-30', id:'RPT-1028', type:'sales',      typeLabel:'Product Sales', description:'Q1 closing sales',             member:'Vijay Tiwari',   amount:71200,  flow:'credit', status:'completed' },
];

const REPORT_COLUMN_LABELS = ['Date', 'Report ID', 'Type', 'Description', 'Member', 'Amount', 'Status'];

let filteredReports = [...allReports];
let reportChartInstance = null;
let reportDataTable = null;

function formatINR(n) {
  const abs = Math.abs(n);
  if (abs >= 100000) return '₹' + (abs / 100000).toFixed(2) + 'L';
  if (abs >= 1000) return '₹' + abs.toLocaleString('en-IN');
  return '₹' + abs.toLocaleString('en-IN');
}

function formatDisplayDate(iso) {
  const d = new Date(iso + 'T00:00:00');
  return d.toLocaleDateString('en-IN', { day:'numeric', month:'short', year:'numeric' });
}

function buildReportRow(r) {
  const statusClass = r.status === 'completed' ? 'active' : 'pending';
  return [
    formatDisplayDate(r.date),
    `<span class="report-id-cell">${r.id}</span>`,
    `<span class="report-type ${r.type}">${r.typeLabel}</span>`,
    r.description,
    r.member,
    `<span class="amount-cell ${r.flow}">${r.flow === 'credit' ? '+' : '−'}${formatINR(r.amount)}</span>`,
    `<span class="status-pill ${statusClass}">${r.status.charAt(0).toUpperCase() + r.status.slice(1)}</span>`
  ];
}

function getDataTableOptions() {
  return {
    data: filteredReports.map(buildReportRow),
    pageLength: 10,
    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'All']],
    order: [[0, 'desc']],
    autoWidth: false,
    responsive: false,
    dom: '<"dt-toolbar"lf>rt<"dt-footer"ip>',
    language: {
      search: '',
      searchPlaceholder: 'Search reports…',
      lengthMenu: 'Show _MENU_',
      info: 'Showing _START_–_END_ of _TOTAL_ records',
      infoEmpty: 'No records to show',
      infoFiltered: '(filtered from _MAX_ total)',
      zeroRecords: 'No matching reports found',
      paginate: { first: '«', last: '»', next: '›', previous: '‹' }
    },
    columnDefs: [{
      targets: '_all',
      createdCell: function(td, cellData, _rowData, _row, col) {
        td.setAttribute('data-label', REPORT_COLUMN_LABELS[col]);
        td.innerHTML = cellData;
      }
    }]
  };
}

function initReportDataTable() {
  if (reportDataTable) return;
  reportDataTable = $('#reportsDataTable').DataTable(getDataTableOptions());
}

function refreshReportDataTable() {
  const empty = document.getElementById('emptyReports');
  const tableWrap = document.getElementById('reportTableWrap');

  if (!filteredReports.length) {
    empty.style.display = 'block';
    tableWrap.style.display = 'none';
    document.getElementById('tableBadge').textContent = '0 records';
    if (reportDataTable) {
      reportDataTable.clear().draw();
    }
    return;
  }

  empty.style.display = 'none';
  tableWrap.style.display = 'block';
  document.getElementById('tableBadge').textContent = filteredReports.length + ' records';

  if (!reportDataTable) {
    initReportDataTable();
    return;
  }

  reportDataTable.clear();
  reportDataTable.rows.add(filteredReports.map(buildReportRow));
  reportDataTable.draw();
}

function filterReports() {
  const from = document.getElementById('fromDate').value;
  const to = document.getElementById('toDate').value;
  const type = document.getElementById('reportType').value;

  if (!from || !to) {
    showToast('Please select both From Date and To Date.', 'red');
    return;
  }
  if (from > to) {
    showToast('From Date cannot be after To Date.', 'red');
    return;
  }

  filteredReports = allReports.filter(r => {
    const inRange = r.date >= from && r.date <= to;
    const typeMatch = type === 'all' || r.type === type;
    return inRange && typeMatch;
  });

  refreshReportDataTable();
  updateReportSummary();
  buildReportChart();
  updateFilterMeta(from, to, type);
}

function resetFilters() {
  document.getElementById('fromDate').value = '2026-05-01';
  document.getElementById('toDate').value = '2026-06-08';
  document.getElementById('reportType').value = 'all';
  filteredReports = allReports.filter(r => r.date >= '2026-05-01' && r.date <= '2026-06-08');
  refreshReportDataTable();
  updateReportSummary();
  buildReportChart();
  updateFilterMeta('2026-05-01', '2026-06-08', 'all');
  showToast('Filters reset to default range.', 'green');
}

function updateFilterMeta(from, to, type) {
  const typeLabel = type === 'all' ? 'all types' : type;
  document.getElementById('filterMeta').textContent =
    'Showing ' + filteredReports.length + ' record(s) from ' + formatDisplayDate(from) + ' to ' + formatDisplayDate(to) + ' (' + typeLabel + ')';
  document.getElementById('chartRangeLabel').textContent =
    formatDisplayDate(from) + ' – ' + formatDisplayDate(to);
}

function updateReportSummary() {
  const income = filteredReports.filter(r => r.flow === 'credit').reduce((s, r) => s + r.amount, 0);
  const expense = filteredReports.filter(r => r.flow === 'debit').reduce((s, r) => s + r.amount, 0);
  const net = income - expense;

  document.getElementById('reportCount').textContent = filteredReports.length;
  document.getElementById('reportCountSub').textContent = 'In selected range';
  document.getElementById('totalIncome').textContent = formatINR(income);
  document.getElementById('totalExpense').textContent = formatINR(expense);
  document.getElementById('netBalance').textContent = formatINR(net);
}

function buildReportChart() {
  const canvas = document.getElementById('reportChart');
  if (!canvas) return;

  const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(27,58,45,0.06)';
  const textColor = isDark ? '#5E8070' : '#8A9E92';

  const byMonth = {};
  filteredReports.forEach(r => {
    const key = r.date.slice(0, 7);
    if (!byMonth[key]) byMonth[key] = { income: 0, expense: 0 };
    if (r.flow === 'credit') byMonth[key].income += r.amount;
    else byMonth[key].expense += r.amount;
  });

  const labels = Object.keys(byMonth).sort().map(k => {
    const [y, m] = k.split('-');
    return new Date(y, m - 1).toLocaleDateString('en-IN', { month:'short', year:'numeric' });
  });
  const incomeData = Object.keys(byMonth).sort().map(k => Math.round(byMonth[k].income / 1000));
  const expenseData = Object.keys(byMonth).sort().map(k => Math.round(byMonth[k].expense / 1000));

  const ctx = canvas.getContext('2d');
  if (reportChartInstance) reportChartInstance.destroy();

  reportChartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels.length ? labels : ['No data'],
      datasets: [
        {
          label: 'Income (₹K)',
          data: incomeData.length ? incomeData : [0],
          backgroundColor: isDark ? 'rgba(74,124,89,0.7)' : 'rgba(45,90,64,0.75)',
          borderRadius: 6,
        },
        {
          label: 'Expense (₹K)',
          data: expenseData.length ? expenseData : [0],
          backgroundColor: isDark ? 'rgba(231,76,60,0.6)' : 'rgba(231,76,60,0.75)',
          borderRadius: 6,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          labels: { color: textColor, font: { family: 'Outfit', size: 11 } }
        }
      },
      scales: {
        x: { grid: { display: false }, ticks: { color: textColor, font: { family: 'Outfit', size: 10 } } },
        y: {
          grid: { color: gridColor },
          ticks: { color: textColor, font: { family: 'Outfit', size: 10 }, callback: v => '₹' + v + 'K' }
        }
      }
    }
  });
}

function rebuildReportChart() {
  if (document.getElementById('reportChart')) setTimeout(buildReportChart, 50);
}

function exportReports() {
  if (!filteredReports.length) {
    showToast('No data to export.', 'red');
    return;
  }
  const headers = ['Date', 'Report ID', 'Type', 'Description', 'Member', 'Amount', 'Status'];
  const rows = filteredReports.map(r => [
    r.date, r.id, r.typeLabel, r.description, r.member,
    (r.flow === 'credit' ? '' : '-') + r.amount, r.status
  ]);
  const csv = [headers, ...rows].map(row => row.map(c => '"' + String(c).replace(/"/g, '""') + '"').join(',')).join('\n');
  const blob = new Blob([csv], { type: 'text/csv' });
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = 'radha-madhav-reports.csv';
  a.click();
  showToast('Report exported successfully!', 'gold');
}

function initReportsPage() {
  filteredReports = allReports.filter(r => r.date >= '2026-05-01' && r.date <= '2026-06-08');
  refreshReportDataTable();
  updateReportSummary();
  buildReportChart();
  updateFilterMeta('2026-05-01', '2026-06-08', 'all');
}

if (document.getElementById('reportsDataTable')) {
  initReportsPage();
}

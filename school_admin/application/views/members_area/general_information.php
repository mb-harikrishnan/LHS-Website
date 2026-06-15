    <?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
$pageStylesheets = [
  'https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css',
];
$pageHeadScripts = [
  'https://code.jquery.com/jquery-3.7.1.min.js'
];
$pageScripts = [
  'https://cdn.datatables.net/2.1.8/js/dataTables.min.js'
];



?>



    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/reports.js">
    
    
    <!-- PAGE CONTENT -->
      <div class="page-header">
        <div class="page-eyebrow">
          <div class="eyebrow-pulse"></div>
          Financial Reports
        </div>
        <h1 class="page-title">Business <em>Reports</em></h1>
        <p class="page-sub">Filter and view sales, commission, bonus, and expense reports by date range.</p>
      </div>

      <!-- Summary Cards -->
      <div class="report-summary">
        <div class="stat-card">
          <div class="stat-header">
            <div class="stat-icon green">
              <svg viewBox="0 0 24 24" fill="none" stroke="#1B3A2D" stroke-width="2" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
              </svg>
            </div>
          </div>
          <div class="stat-value" id="reportCount">0</div>
          <div class="stat-label">Total Records</div>
          <div class="stat-sub" id="reportCountSub">In selected range</div>
        </div>
        <div class="stat-card">
          <div class="stat-header">
            <div class="stat-icon gold">
              <svg viewBox="0 0 24 24" fill="none" stroke="#B8962E" stroke-width="2" stroke-linecap="round">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
              </svg>
            </div>
          </div>
          <div class="stat-value" id="totalIncome">â‚¹0</div>
          <div class="stat-label">Total Income</div>
          <div class="stat-sub">Sales + Commission + Bonus</div>
        </div>
        <div class="stat-card">
          <div class="stat-header">
            <div class="stat-icon blue">
              <svg viewBox="0 0 24 24" fill="none" stroke="#2980b9" stroke-width="2" stroke-linecap="round">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                <polyline points="17 6 23 6 23 12"/>
              </svg>
            </div>
          </div>
          <div class="stat-value" id="totalExpense">â‚¹0</div>
          <div class="stat-label">Total Expenses</div>
          <div class="stat-sub">Operational & event costs</div>
        </div>
        <div class="stat-card">
          <div class="stat-header">
            <div class="stat-icon purple">
              <svg viewBox="0 0 24 24" fill="none" stroke="#8e44ad" stroke-width="2" stroke-linecap="round">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
              </svg>
            </div>
          </div>
          <div class="stat-value" id="netBalance">â‚¹0</div>
          <div class="stat-label">Net Balance</div>
          <div class="stat-sub">Income minus expenses</div>
        </div>
      </div>

      <!-- Reports Table Card -->
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
            </svg>
            Report Records
            <span class="card-badge" id="tableBadge">0 records</span>
          </div>
          <button class="card-action" onclick="exportReports()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="7 10 12 15 17 10"/>
              <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Export CSV
          </button>
        </div>

        <!-- Date Filter -->
        <div class="filter-bar">
          <div class="filter-group">
            <label for="fromDate">From Date</label>
            <input type="date" id="fromDate" value="2026-05-01">
          </div>
          <div class="filter-group">
            <label for="toDate">To Date</label>
            <input type="date" id="toDate" value="2026-06-08">
          </div>
          <div class="filter-group">
            <label for="reportType">Report Type</label>
            <select class="form-select" id="reportType" style="padding:9px 12px">
              <option value="all">All Types</option>
              <option value="sales">Product Sales</option>
              <option value="commission">Commission</option>
              <option value="bonus">Bonus</option>
              <option value="expense">Expense</option>
            </select>
          </div>
          <div class="filter-actions">
            <button class="btn btn-primary" onclick="filterReports()">Apply Filter</button>
            <button class="btn btn-ghost" onclick="resetFilters()">Reset</button>
          </div>
        </div>

        <p class="report-meta" style="padding:12px 24px 0" id="filterMeta">Showing all reports</p>

        <div class="report-table-wrap" id="reportTableWrap">
          <table class="report-table display" id="reportsDataTable" style="width:100%">
            <thead>
              <tr>
                <th>Date</th>
                <th>Report ID</th>
                <th>Type</th>
                <th>Description</th>
                <th>Member</th>
                <th>Amount</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="empty-reports" id="emptyReports" style="display:none">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
          </svg>
          <p>No reports found for the selected date range.</p>
        </div>
      </div>

      <!-- Chart -->
      <div class="card" style="margin-top:20px">
        <div class="card-head">
          <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
              <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            Income vs Expense Trend
          </div>
          <span class="card-badge" id="chartRangeLabel">May â€“ Jun 2026</span>
        </div>
        <div class="chart-wrap" style="height:280px">
          <canvas id="reportChart"></canvas>
        </div>
      </div>


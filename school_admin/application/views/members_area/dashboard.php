      <!-- Welcome Widget -->
      <div class="welcome-widget">
        <div class="welcome-widget-bg">
          <div class="welcome-deco w1"></div>
          <div class="welcome-deco w2"></div>
          <div class="welcome-deco w3"></div>
        </div>
        <div class="welcome-widget-inner">
          <div class="welcome-widget-left">
            <div class="welcome-badge">
              <div class="eyebrow-pulse"></div>
              Live Overview · June 2026
            </div>
            <p class="welcome-greeting" id="welcomeGreeting">Good morning</p>
            <h1 class="welcome-title">Welcome back, <em>Radha Madhav</em></h1>
            <p class="welcome-sub">Your network is growing strong — 3 new members joined this week and revenue is up 14.2%.</p>
            <div class="welcome-target">
              <div class="welcome-target-head">
                <span>Monthly revenue target</span>
                <strong id="welcomeTargetPct">78%</strong>
              </div>
              <div class="welcome-target-bar">
                <div class="welcome-target-fill" style="width:78%"></div>
              </div>
              <span class="welcome-target-sub">₹4,09,344 of ₹5,24,800 achieved</span>
            </div>
            <div class="welcome-quick-btns">
              <button class="btn btn-gold btn-sm" onclick="window.location='profile.php'">My Profile</button>
            </div>
          </div>
          <div class="welcome-widget-right">
            <div class="welcome-avatar-ring">
              <div class="welcome-avatar">RM</div>
              <span class="welcome-rank">Diamond Admin</span>
            </div>
            <div class="welcome-datetime">
              <span class="welcome-date" id="welcomeDate"></span>
              <span class="welcome-clock" id="welcomeClock"></span>
            </div>
            <div class="welcome-mini-stats">
              <div class="welcome-mini-stat">
                <span class="wms-val">12</span>
                <span class="wms-lbl">Team</span>
              </div>
              <div class="welcome-mini-stat">
                <span class="wms-val">#7</span>
                <span class="wms-lbl">Rank</span>
              </div>
              <div class="welcome-mini-stat">
                <span class="wms-val">75%</span>
                <span class="wms-lbl">KYC</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Profile Cards Section -->
      <section class="profile-cards-section">
        <div class="section-head">
          <div>
            <h2 class="section-title">Your <em>Profile</em> Snapshot</h2>
            <p class="section-sub">Quick access to your account details and verification status</p>
          </div>
          <a href="profile.php" class="section-link">
            Manage Profile
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
          </a>
        </div>
        <div class="profile-cards-grid">
          <a href="profile.php" class="profile-card personal">
            <div class="pc-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div class="pc-body">
              <h3>Personal Details</h3>
              <p>Radha Madhav · RM-0001</p>
              <span class="pc-status complete">Complete</span>
            </div>
            <div class="pc-arrow">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </a>
          <a href="profile.php#panel-kyc" class="profile-card kyc" onclick="event.preventDefault();window.location='profile.php';">
            <div class="pc-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div class="pc-body">
              <h3>KYC Verification</h3>
              <p>Identity verified · Bank pending</p>
              <span class="pc-status progress">75% Complete</span>
            </div>
            <div class="pc-progress-ring">
              <svg viewBox="0 0 44 44"><circle cx="22" cy="22" r="18" fill="none" stroke="rgba(27,58,45,0.1)" stroke-width="4"/><circle cx="22" cy="22" r="18" fill="none" stroke="var(--gold)" stroke-width="4" stroke-dasharray="113" stroke-dashoffset="28" stroke-linecap="round" transform="rotate(-90 22 22)"/></svg>
              <span>75%</span>
            </div>
          </a>

          <a href="profile.php" class="profile-card pan">
            <div class="pc-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div class="pc-body">
              <h3>PAN Card</h3>
              <p>ABCPR1234D</p>
              <span class="pc-status verified">Verified</span>
            </div>
            <div class="pc-check">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
          </a>
        </div>
      </section>

      <!-- ─── STATS GRID ─── -->
      <div class="stats-grid">
        <!-- Total Revenue -->
        <div class="stat-card">
          <div class="stat-header">
            <div class="stat-icon green">
              <svg viewBox="0 0 24 24" fill="none" stroke="#1B3A2D" stroke-width="2" stroke-linecap="round">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
              </svg>
            </div>
            <div class="stat-trend up">▲ 14.2%</div>
          </div>
          <div class="stat-value" id="totalRevenue">₹5,24,800</div>
          <div class="stat-label">Total Revenue — June</div>
          <div class="stat-sub">Compared to ₹4,59,400 last month</div>
        </div>

        <!-- Active Members -->
        <div class="stat-card">
          <div class="stat-header">
            <div class="stat-icon gold">
              <svg viewBox="0 0 24 24" fill="none" stroke="#B8962E" stroke-width="2" stroke-linecap="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
            </div>
            <div class="stat-trend up">▲ 3 new</div>
          </div>
          <div class="stat-value" id="memberCount">12</div>
          <div class="stat-label">Active Team Members</div>
          <div class="stat-sub">2 pending approvals · 1 inactive</div>
        </div>

        <!-- Network Rank -->
        <div class="stat-card">
          <div class="stat-header">
            <div class="stat-icon blue">
              <svg viewBox="0 0 24 24" fill="none" stroke="#2980b9" stroke-width="2" stroke-linecap="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
              </svg>
            </div>
            <div class="stat-trend up">▲ 2 ranks</div>
          </div>
          <div class="stat-value">#7</div>
          <div class="stat-label">Network Rank</div>
          <div class="stat-sub">Zone rank improved from #9</div>
        </div>

        <!-- Points Balance -->
        <div class="stat-card">
          <div class="stat-header">
            <div class="stat-icon purple">
              <svg viewBox="0 0 24 24" fill="none" stroke="#8e44ad" stroke-width="2" stroke-linecap="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
              </svg>
            </div>
            <div class="stat-trend up">▲ 1,200 pts</div>
          </div>
          <div class="stat-value">8,540</div>
          <div class="stat-label">Loyalty Points</div>
          <div class="stat-sub">Redeemable up to ₹8,540</div>
        </div>
      </div>

      <!-- ─── MAIN GRID ─── -->
      <div class="main-grid">

        <!-- Left Column: Team Table + Chart -->
        <div style="display:flex;flex-direction:column;gap:20px">

          <!-- Team Members Card -->
          <div class="card">
            <div class="card-head">
              <div class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                  <circle cx="9" cy="7" r="4"/>
                  <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                </svg>
                Team Members
                <span class="card-badge" id="tableCount">12 members</span>
              </div>
              <span></span>
            </div>
            <div class="table-search">
              <input type="text" id="tableSearch" placeholder="Search by name, ID, or role…" oninput="filterTable(this.value)">
            </div>
            <div style="overflow-x:auto">
              <table class="team-table">
                <thead>
                  <tr>
                    <th>Member</th>
                    <th>Level</th>
                    <th>Joined</th>
                    <th>Downline</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="memberTableBody">
                  <!-- Rows injected by JS -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- Growth Chart Card -->
          <div class="card">
            <div class="card-head">
              <div class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                  <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                Weekly Growth Trends
              </div>
              <span class="card-badge">June 2026</span>
            </div>
            <div class="chart-wrap">
              <canvas id="growthChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Right Column: Income + Activity -->
        <div style="display:flex;flex-direction:column;gap:20px">

          <!-- Income Card -->
          <div class="card">
            <div class="card-head">
              <div class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                  <line x1="12" y1="1" x2="12" y2="23"/>
                  <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                Transactions
              </div>
              <span></span>
            </div>
            <div class="income-list" id="incomeList">
              <!-- Injected by JS -->
            </div>
          </div>

          <!-- Activity -->
          <div class="card">
            <div class="card-head">
              <div class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                  <circle cx="12" cy="12" r="10"/>
                  <polyline points="12 6 12 12 16 14"/>
                </svg>
                Recent Activity
              </div>
            </div>
            <div class="activity-list" id="activityList">
              <!-- Injected by JS -->
            </div>
          </div>
        </div>

      </div><!-- /main-grid -->

      <!-- ─── BOTTOM GRID ─── -->
      <div class="bottom-grid">

        <!-- Quick Actions -->
        <div class="card">
          <div class="card-head">
            <div class="card-title">Quick Actions</div>
          </div>
          <div class="quick-actions">

            <button class="qa-btn">
              <div class="qa-icon b">
                <svg viewBox="0 0 24 24" fill="none" stroke="#2980b9" stroke-width="2" stroke-linecap="round">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                  <polyline points="14 2 14 8 20 8"/>
                </svg>
              </div>
              <div>
                <span class="qa-label">Generate Report</span>
                <span class="qa-sub">Export team analytics</span>
              </div>
            </button>

            <button class="qa-btn">
              <div class="qa-icon p">
                <svg viewBox="0 0 24 24" fill="none" stroke="#8e44ad" stroke-width="2" stroke-linecap="round">
                  <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
              </div>
              <div>
                <span class="qa-label">View Analytics</span>
                <span class="qa-sub">Deep growth insights</span>
              </div>
            </button>
          </div>
        </div>

        <!-- Referral Link -->
        <div class="card">
          <div class="card-head">
            <div class="card-title">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round">
                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
              </svg>
              Referral Link
            </div>
            <span class="card-badge">Share &amp; Earn</span>
          </div>
          <div style="padding:20px 24px">
            <p style="font-size:13px;color:var(--text-sub);margin:0 0 14px">Share your unique referral link to invite new members to your team and earn referral bonuses.</p>
            <div class="referral-link-box" id="referralLinkBox">
              <div class="referral-link-url" id="referralLinkUrl">https://radhmadhav.com/join?ref=RM-0001</div>
              <button class="referral-copy-btn" id="referralCopyBtn" onclick="copyReferralLink()" title="Copy referral link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="15" height="15"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                Copy
              </button>
            </div>
            <div class="referral-actions">
              <button class="referral-share-btn" onclick="shareReferral('whatsapp')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="14" height="14"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                WhatsApp
              </button>
              <button class="referral-share-btn" onclick="shareReferral('telegram')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="14" height="14"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Telegram
              </button>
              <button class="referral-share-btn" onclick="shareReferral('sms')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="14" height="14"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                SMS
              </button>
            </div>
            <div class="referral-stats">
              <div class="referral-stat">
                <span class="referral-stat-val">8</span>
                <span class="referral-stat-lbl">Referrals Sent</span>
              </div>
              <div class="referral-stat">
                <span class="referral-stat-val">5</span>
                <span class="referral-stat-lbl">Joined</span>
              </div>
              <div class="referral-stat">
                <span class="referral-stat-val">₹2,500</span>
                <span class="referral-stat-lbl">Bonus Earned</span>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-head">
            <div class="card-title">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
              </svg>
              Top Performers
            </div>
            <span class="card-badge">This Month</span>
          </div>
          <div style="padding:16px 24px;display:flex;flex-direction:column;gap:12px" id="topPerformersList">
            <!-- Injected by JS -->
          </div>
        </div>

      </div><!-- /bottom-grid -->

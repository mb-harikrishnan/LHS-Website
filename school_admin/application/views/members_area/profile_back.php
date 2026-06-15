<?php
$pageTitle = 'My Profile';
$breadcrumb = 'My Profile';
$activePage = 'profile';
$showGlobalSearch = false;

?>

<script src="<?php echo JS_PATH ?>profile.js"></script>


<div class="page-header">
  <div class="page-eyebrow">
    <div class="eyebrow-pulse"></div>
    Member Profile
  </div>
  <h1 class="page-title">My <em>Profile</em></h1>
  <p class="page-sub">Manage your personal details, KYC verification, and PAN card information.</p>
</div>

<!-- Profile Hero Banner -->
<div class="profile-hero">
  <div class="profile-hero-bg">
    <div class="profile-hero-deco p1"></div>
    <div class="profile-hero-deco p2"></div>
    <div class="profile-hero-deco p3"></div>
  </div>
  <div class="profile-hero-inner">
    <div class="profile-hero-left">
      <div class="profile-avatar-wrap">
        <div class="profile-avatar" id="profileAvatar">RM</div>
        <button class="profile-avatar-edit" onclick="document.getElementById('avatarInput').click()" title="Change photo">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
        </button>
        <input type="file" id="avatarInput" accept="image/*" hidden onchange="previewAvatar(this)">
      </div>
      <div class="profile-hero-info">
        <h2 class="profile-hero-name">Radha Madhav</h2>
        <div class="profile-hero-meta">
          <span class="profile-id-badge">RM-0001</span>
          <span class="profile-role-badge">Admin · Diamond</span>
        </div>
        <p class="profile-hero-since">Member since January 2024 · Mumbai, Maharashtra</p>
      </div>
    </div>
    <div class="profile-hero-right">
      <div class="profile-kyc-ring">
        <svg viewBox="0 0 120 120" class="kyc-progress-svg">
          <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,0.12)" stroke-width="8"/>
          <circle cx="60" cy="60" r="52" fill="none" stroke="url(#kycGrad)" stroke-width="8"
            stroke-linecap="round" stroke-dasharray="326.7" stroke-dashoffset="81.7"
            transform="rotate(-90 60 60)" id="kycProgressRing"/>
          <defs>
            <linearGradient id="kycGrad" x1="0%" y1="0%" x2="100%" y2="0%">
              <stop offset="0%" stop-color="#D4AF5A"/>
              <stop offset="100%" stop-color="#4A7C59"/>
            </linearGradient>
          </defs>
        </svg>
        <div class="kyc-ring-label">
          <span class="kyc-ring-pct" id="kycPercent">75%</span>
          <span class="kyc-ring-text">KYC Complete</span>
        </div>
      </div>
      <div class="profile-hero-stats">
        <div class="profile-stat-chip verified">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          PAN Verified
        </div>
        <div class="profile-stat-chip pending">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Bank Pending
        </div>
      </div>
    </div>
  </div>
</div>

<?php foreach($user_details as $value){ 
  
  $sql = "SELECT name FROM country_states WHERE code='$value->C_STATE'";
  $qry = $this->db->query($sql);
  $STATE =$qry->row()->name;
  
  ?>



<!-- Mobile / top tab bar -->
<div class="profile-tabs-bar" role="tablist" aria-label="Profile sections">
  <button type="button" class="profile-tab-btn active" data-panel="personal" role="tab" aria-selected="true">Personal</button>
  <button type="button" class="profile-tab-btn" data-panel="kyc" role="tab" aria-selected="false">KYC</button>
  <button type="button" class="profile-tab-btn" data-panel="pan" role="tab" aria-selected="false">PAN</button>
</div>

<!-- Profile Layout -->
<div class="profile-layout">

  <!-- Tree Navigation -->
  <nav class="profile-nav" aria-label="Profile sections">
    <div class="profile-nav-head">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Profile Sections
    </div>
    <ul class="profile-tree">
      <li class="profile-tree-item active" data-panel="personal">
        <button type="button" class="profile-tree-btn">
          <span class="tree-icon personal">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </span>
          Personal Details
        </button>
      </li>
      <li class="profile-tree-group">
        <span class="profile-tree-branch">├─</span>
        <ul class="profile-tree-sub">
          <li class="profile-tree-item" data-panel="kyc">
            <button type="button" class="profile-tree-btn">
              <span class="tree-icon kyc">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              </span>
              KYC Verification
              <?php if($value->C_BANK_APPROVEL=='Y'){?>
              <span class="tree-badge verified">Verified</span>
              <?php }else{?>
              <span class="tree-badge verified">Not Verified</span>
              <?php }?>
            </button>
          </li>
          <li class="profile-tree-item" data-panel="pan">
            <button type="button" class="profile-tree-btn">
              <span class="tree-icon pan">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              </span>
              PAN Card Details
                <?php if($value->C_PAN_APPROVEL=='Y'){?>
              <span class="tree-badge verified">Verified</span>
              <?php }else{?>
              <span class="tree-badge verified">Not Verified</span>
              <?php }?>
            </button>
          </li>
        </ul>
      </li>
    </ul>
  </nav>

  <!-- Content Panels -->
  <div class="profile-panels">

    <!-- Personal Details -->
    <section class="profile-panel active" id="panel-personal">
      <div class="profile-panel-head">
        <div>
          <h3 class="profile-panel-title">Personal Details</h3>
          <p class="profile-panel-sub">Your basic contact and address information</p>
        </div>
        <div class="profile-panel-actions">
          <button class="btn btn-ghost btn-sm" id="editToggleBtn" onclick="toggleProfileEdit()">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" width="14" height="14"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit
          </button>
          <button class="btn btn-primary btn-sm" onclick="saveProfileSection('personal')">Save Changes</button>
        </div>
      </div>
      <form class="profile-form" id="personalForm">
        <div class="profile-form-grid">
          <div class="pf-field">
            <label for="pfFullName">Full Name</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input" type="text" id="pfFullName" value="<?php echo $value->C_FNAME ; ?>" required>
            </div>
          </div>
          <div class="pf-field">
            <label for="pfEmail">Email Address</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input" type="email" id="pfEmail" value="<?php echo $value->C_EMAIL ; ?>" required>
            </div>
          </div>
          <div class="pf-field">
            <label for="pfPhone">Mobile Number</label>
            <div class="pf-input-wrap">
              <span class="pf-input-icon">+91</span>
              <input class="form-input pf-input pf-has-icon" type="tel" id="pfPhone" value="98765 43210" maxlength="11" value="<?php echo $value->C_MOBILE ; ?>" required>
            </div>
          </div>
          <div class="pf-field">
            <label for="pfDob">Date of Birth</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input" type="date" id="pfDob" value="<?php echo $value->C_DOB ; ?>" required>
            </div>
          </div>
          <div class="pf-field">
            <label for="pfGender">Gender</label>
          
             <div class="pf-input-wrap">
              <select class="form-select pf-input" id="pfGender" value="<?php echo $value->C_GENDER ; ?>">
                <option value="female" selected>Female</option>
                <option value="male">Male</option>
                <option value="other">Other</option>
              </select>
            </div> 
          </div>
          <div class="pf-field">
            <label for="pfMemberId">Member ID</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input pf-readonly" type="text" id="pfMemberId" value="<?php echo $this->session->userdata('c_username') ; ?>" readonly>
              <button type="button" class="pf-copy-btn" onclick="copyToClipboard('pfMemberId')" title="Copy ID">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
              </button>
            </div>
          </div>
          <div class="pf-field full">
            <label for="pfAddress">Residential Address</label>
            <div class="pf-input-wrap">
              <textarea class="form-input pf-textarea pf-input" id="pfAddress" rows="3" maxlength="200" oninput="updateCharCount(this, 'pfAddressCount')"><?php echo $value->C_ADDRESS ; ?></textarea>
              <span class="pf-char-count" id="pfAddressCount">0 / 200</span>
            </div>
          </div>
          <div class="pf-field">
            <label for="pfCity">City</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input" type="text" id="pfCity" value="<?php echo $value->C_CITY ;?>">
            </div>
          </div>
          <div class="pf-field">
            <label for="pfState">State</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input" type="text" id="pfState" value="<?php echo $STATE; ?>">
            </div>
          </div>
          <div class="pf-field">
            <label for="pfPincode">Pincode</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input" type="text" id="pfPincode" value="<?php echo $value->C_ZIP_CODE ; ?>" maxlength="6" oninput="validatePincode(this)">
              <span class="pf-field-hint" id="pfPincodeHint"></span>
            </div>
          </div>
        </div>
      </form>
    </section>

    <!-- KYC -->
    <section class="profile-panel" id="panel-kyc">
      <div class="profile-panel-head">
        <div>
          <h3 class="profile-panel-title">KYC Verification</h3>
          <p class="profile-panel-sub">Know Your Customer compliance and bank details</p>
        </div>
        <span class="kyc-status-banner verified">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          KYC Approved
        </span>
      </div>

      <div class="kyc-timeline">
        <div class="kyc-step done">
          <div class="kyc-step-dot"><svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="kyc-step-body"><strong>Identity Submitted</strong><span>PAN uploaded · 12 Jan 2024</span></div>
        </div>
        <div class="kyc-step done">
          <div class="kyc-step-dot"><svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="kyc-step-body"><strong>Documents Verified</strong><span>Verified by compliance team · 15 Jan 2024</span></div>
        </div>
        <div class="kyc-step active">
          <div class="kyc-step-dot">3</div>
          <div class="kyc-step-body"><strong>Bank Account Linking</strong><span>Awaiting verification · In progress</span></div>
        </div>
        <div class="kyc-step">
          <div class="kyc-step-dot">4</div>
          <div class="kyc-step-body"><strong>Full KYC Complete</strong><span>All checks passed</span></div>
        </div>
      </div>

      <form class="profile-form" id="kycForm">
        <h4 class="pf-section-label">Bank Account Details</h4>
        <div class="profile-form-grid">
          <div class="pf-field">
            <label for="kycAccountName">Account Holder Name</label>
            <input class="form-input" type="text" id="kycAccountName" value="Radha Madhav">
          </div>
          <div class="pf-field">
            <label for="kycBankName">Bank Name</label>
            <input class="form-input" type="text" id="kycBankName" value="State Bank of India">
          </div>
          <div class="pf-field">
            <label for="kycAccountNo">Account Number</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input" type="password" id="kycAccountNo" value="1234567890" data-masked="**** **** 7890">
              <button type="button" class="pf-toggle-btn" onclick="toggleFieldVisibility('kycAccountNo', this)" title="Show/Hide">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>
          <div class="pf-field">
            <label for="kycIfsc">IFSC Code</label>
            <input class="form-input" type="text" id="kycIfsc" value="SBIN0001234">
          </div>
          <div class="pf-field">
            <label for="kycBranch">Branch</label>
            <input class="form-input" type="text" id="kycBranch" value="Andheri West, Mumbai">
          </div>
          <div class="pf-field">
            <label for="kycAccountType">Account Type</label>
            <select class="form-select" id="kycAccountType">
              <option value="savings" selected>Savings</option>
              <option value="current">Current</option>
            </select>
          </div>
        </div>
        <div class="pf-actions">
          <button type="button" class="btn btn-primary" onclick="saveProfileSection('kyc')">Update Bank Details</button>
        </div>
      </form>
    </section>

    <!-- PAN -->
    <section class="profile-panel" id="panel-pan">
      <div class="profile-panel-head">
        <div>
          <h3 class="profile-panel-title">PAN Card Details</h3>
          <p class="profile-panel-sub">Permanent Account Number for tax and payout compliance</p>
        </div>
        <span class="kyc-status-banner verified">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          Verified
        </span>
      </div>

      <div class="doc-preview-card pan-card">
        <div class="doc-card-header">
          <span class="pan-dept">Income Tax Department</span>
          <span class="pan-govt">Govt. of India</span>
        </div>
        <div class="doc-card-body pan-body">
          <div class="pan-number-display" id="panDisplay">ABCPR1234D</div>
          <div class="doc-card-fields pan-fields">
            <div class="doc-field"><span class="doc-label">Name</span><span class="doc-value">Radha Madhav</span></div>
            <div class="doc-field"><span class="doc-label">Father's Name</span><span class="doc-value">Madhav Kumar Sharma</span></div>
            <div class="doc-field"><span class="doc-label">Date of Birth</span><span class="doc-value">15 / 03 / 1985</span></div>
          </div>
        </div>
      </div>

      <form class="profile-form" id="panForm">
        <div class="profile-form-grid">
          <div class="pf-field">
            <label for="panNumber">PAN Number</label>
            <input class="form-input" type="text" id="panNumber" value="ABCPR1234D" maxlength="10" oninput="formatPan(this)" placeholder="ABCDE1234F" style="text-transform:uppercase">
          </div>
          <div class="pf-field">
            <label for="panName">Name on PAN</label>
            <input class="form-input" type="text" id="panName" value="Radha Madhav">
          </div>
          <div class="pf-field">
            <label for="panFather">Father's Name</label>
            <input class="form-input" type="text" id="panFather" value="Madhav Kumar Sharma">
          </div>
          <div class="pf-field">
            <label for="panDob">Date of Birth</label>
            <input class="form-input" type="date" id="panDob" value="1985-03-15">
          </div>
        </div>

        <h4 class="pf-section-label">PAN Card Upload</h4>
        <div class="doc-upload-grid single">
          <label class="doc-upload-box wide" id="panCardBox">
            <input type="file" accept="image/*,.pdf" hidden onchange="previewDoc(this, 'panCardBox', 'panCardPreview')">
            <div class="doc-upload-inner" id="panCardPreview">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              <span>Upload PAN Card</span>
              <small>Click to upload front side · JPG, PNG or PDF</small>
            </div>
          </label>
        </div>
        <div class="pf-actions">
          <button type="button" class="btn btn-primary" onclick="saveProfileSection('pan')">Save PAN Details</button>
        </div>
      </form>
    </section>

  </div><!-- /profile-panels -->
</div><!-- /profile-layout -->

<?php } ?>


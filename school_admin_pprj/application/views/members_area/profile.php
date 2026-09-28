<?php
$pageTitle = 'My Profile';
$breadcrumb = 'My Profile';
$activePage = 'profile';
$showGlobalSearch = false;
$pageScripts = ['assets/js/profile.js'];


?>

<div class="page-header">
  <div class="page-eyebrow">
    <div class="eyebrow-pulse"></div>
    Member Profile
  </div>
  <h1 class="page-title">My <em>Profile</em></h1>
  <p class="page-sub">Manage your personal details, KYC verification, and PAN card information.</p>
</div>
<?php foreach($user_details as $value){ 
  
  $sql = "SELECT name FROM country_states WHERE code='$value->C_STATE'";
  $qry = $this->db->query($sql);
  $STATE =$qry->row()->name;


$firstName = $value->C_FNAME;

// Take first 2 letters
$initials = strtoupper(substr($firstName, 0, 2));

  
  ?>
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
        <div class="profile-avatar" id="profileAvatar">
           <?php if(!empty($value->c_profile_photo)) { ?>
               <img src="<?php echo base_url('assets/images/'.$value->c_profile_photo); ?>" 
                     id="profilePreview"
                     style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
              <?php } else { ?>

                <?php echo $initials; ?>

            <?php } ?>
        </div>
        <button class="profile-avatar-edit" onclick="document.getElementById('avatarInput').click()" title="Change photo">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
        </button>
        <input type="file" id="avatarInput"   accept="image/*" hidden onchange="previewAvatar(this)">
      </div>
      <div class="profile-hero-info">
        <h2 class="profile-hero-name"><?php echo $value->C_FNAME ; ?></h2>
        <div class="profile-hero-meta">
          <span class="profile-id-badge"><?php echo $value->c_username ; ?></span>
          <span class="profile-role-badge">    <?= ($value->c_distributor_active == 'Y') ? 'Active' : 'Not Active'; ?></span>
        </div>
        <p class="profile-hero-since">
            Member since <?= date('F Y', strtotime($value->D_JOIN)); ?> · 
            <?= $value->C_CITY; ?>, <?= $STATE; ?>
        </p>     
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
        <?php if($value->C_PAN_APPROVEL=='Y'){  ?>
        <div class="profile-stat-chip verified">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          PAN Verified
        </div>
        <?php }else{?>
        <div class="profile-stat-chip pending">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          PAN Pending
        </div>
        <?php } ?>
        <?php if($value->C_BANK_APPROVEL=='Y'){  ?>
        <div class="profile-stat-chip verified">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          Bank Verified
        </div>
        <?php }else{?>
        <div class="profile-stat-chip pending">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Bank Pending
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

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
              <?php }?>            </button>
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
    <form class="profile-form" id="personalForm" method="POST" action="<?php echo base_url('edit_profile') ; ?>">

    <section class="profile-panel active" id="panel-personal">
      <div class="profile-panel-head">
        <div>
          <h3 class="profile-panel-title">Personal Details</h3>
          <p class="profile-panel-sub">Your basic contact and address information</p>
        </div>

         <div class="profile-panel-actions">
          <button type="button" class="btn btn-ghost btn-sm" id="editToggleBtn" onclick="toggleProfileEdit()">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" width="14" height="14"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit
          </button>
          <!-- <button class="btn btn-primary btn-sm" onclick="saveProfileSection('personal')">Save Changes</button> -->
           <button type="submit" class="btn btn-primary btn-sm">
              Save Changes
          </button>
        </div>
        
      </div>

     
        <div class="profile-form-grid">
          <div class="pf-field">
            <label for="pfFullName">Full Name</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input" type="text" id="pfFullName" name="pfFullName" value="<?php echo $value->C_FNAME ; ?>" required>
            </div>
          </div>
          <div class="pf-field">
            <label for="pfEmail">Email Address</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input" type="email" id="pfEmail" name="pfEmail" value="<?php echo $value->C_EMAIL ; ?>" required>
            </div>
          </div>
          <div class="pf-field">
            <label for="pfPhone">Mobile Number</label>
            <div class="pf-input-wrap">
              <span class="pf-input-icon">+91</span>
              <input class="form-input pf-input pf-has-icon" type="tel" id="pfPhone" name="pfPhone" value="<?php echo $value->C_MOBILE ; ?>" maxlength="11"  required>
            </div>
          </div>
          <div class="pf-field">
            <label for="pfDob">Date of Birth</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input" type="date" id="pfDob" name="pfDob" value="<?php echo $value->C_DOB ; ?>">
            </div>
          </div>
          <div class="pf-field">
            <label for="pfGender">Gender</label>
            <div class="pf-input-wrap">
              <select class="form-select pf-input" id="pfGender" name="pfGender" >
                <option value="female" selected>Female</option>
                <option value="male">Male</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>
          <div class="pf-field">
            <label for="pfMemberId">Member ID</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input pf-readonly" type="text" id="pfMemberId" name="pfMemberId" value="<?php echo $value->c_username ; ?>" readonly>
              <button type="button" class="pf-copy-btn" onclick="copyToClipboard('pfMemberId')" title="Copy ID">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
              </button>
            </div>
          </div>
          <div class="pf-field full">
            <label for="pfAddress">Residential Address</label>
            <div class="pf-input-wrap">
              <textarea class="form-input pf-textarea pf-input"  name="pfAddress" id="pfAddress" rows="3" maxlength="200" oninput="updateCharCount(this, 'pfAddressCount')"><?php echo $value->C_ADDRESS ; ?></textarea>
              <span class="pf-char-count" name="pfAddressCount" id="pfAddressCount">0 / 200</span>
            </div>
          </div>
          <div class="pf-field">
            <label for="pfCity">City</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input" type="text" id="pfCity" name="pfCity" value="<?php echo $value->C_CITY ; ?>">
            </div>
          </div>
        
            <div class="pf-field">
                <label for="pfState">State</label>

                <div class="pf-input-wrap">

                    <select class="form-select pf-input" id="pfState" name="pfState" required>

                        <option value="">Select State</option>

                        <?php

                        $states = $this->db->query("SELECT * FROM country_states ORDER BY name ASC")->result();

                        foreach($states as $state)
                        {
                        ?>

                            <option value="<?php echo $state->code; ?>"
                                <?php if($value->C_STATE == $state->code){ echo 'selected'; } ?>>

                                <?php echo $state->name; ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>
            </div>


          <div class="pf-field">
            <label for="pfPincode">Pincode</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input" type="text" id="pfPincode" name="pfPincode" value="<?php echo $value->C_ZIP_CODE ; ?>" maxlength="6" oninput="validatePincode(this)">
              <span class="pf-field-hint" id="pfPincodeHint"></span>
            </div>
          </div>
        </div>
     
    </section>
     </form>

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

      <!-- <div class="kyc-timeline">
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
      </div> -->

      
        <div class="kyc-timeline">

            <div class="kyc-step ">
                <div class="kyc-step-dot">1
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>

                <div class="kyc-step-body">
                    <strong>Identity Submitted</strong>
                    <span>PAN uploaded · 12 Jan 2024</span>
                </div>
            </div>

            <div class="kyc-step ">
                <div class="kyc-step-dot">2
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>

                <div class="kyc-step-body">
                    <strong>Documents Verified</strong>
                    <span>Verified by compliance team · 15 Jan 2024</span>
                </div>
            </div>

            <!-- ACTIVE STEP -->
            <div class="kyc-step ">
                <div class="kyc-step-dot">3</div>

                <div class="kyc-step-body">
                    <strong>Bank Account Linking</strong>
                    <span>Awaiting verification · In progress</span>
                </div>
            </div>

            <div class="kyc-step">
                <div class="kyc-step-dot">4</div>

                <div class="kyc-step-body">
                    <strong>Full KYC Complete</strong>
                    <span>Pending verification</span>
                </div>
            </div>

        </div>

               <?php
                  $bank_readonly = ($value->C_BANK_APPROVEL == 'Y' || $value->C_BANK_APPROVEL == 'P') ? 'readonly' : '';
                ?>

      <form class="profile-form" id="kycForm" method="POST" action="<?php echo base_url('update_bank_details') ; ?>" >
        <h4 class="pf-section-label">Bank Account Details</h4>
        <div class="profile-form-grid">
          <div class="pf-field">
            <label for="kycAccountName">Account Holder Name</label>
            <input class="form-input" type="text" id="kycAccountName" <?php echo $bank_readonly; ?> name="kycAccountName" value="<?php echo $value->C_ACC_HOLDER ; ?>">
          </div>
          <div class="pf-field">
            <label for="kycBankName">Bank Name</label>
            <input class="form-input" type="text" id="kycBankName" name="kycBankName" <?php echo $bank_readonly; ?> value="<?php echo $value->C_BANK ; ?>">
          </div>
          <div class="pf-field">
            <label for="kycAccount_No">Account Number</label>
            <div class="pf-input-wrap">
              <input class="form-input pf-input" type="text" id="kycAccount_No" name="kycAccount_No" <?php echo $bank_readonly; ?> value="<?php echo $value->C_ACC_NO ; ?>" >
           
            </div>
          </div>
          <div class="pf-field">
            <label for="kycIfsc">IFSC Code</label>
            <input class="form-input" type="text" id="kycIfsc" name="kycIfsc" <?php echo $bank_readonly; ?> value="<?php echo $value->C_IFC_CODE ; ?>">
          </div>
          <div class="pf-field">
            <label for="kycBranch">Branch</label>
            <input class="form-input" type="text" id="kycBranch" name="kycBranch" <?php echo $bank_readonly; ?> value="<?php echo $value->C_BRANCH ; ?>">
          </div>
            <!-- <div class="pf-field">
              <label for="kycAccountType">Account Type</label>
              <select class="form-select" id="kycAccountType" name="kycAccountType" value="<?php echo $value->C_ACC_TYPE ; ?>" >
                <option value="savings" selected>Savings</option>
                <option value="current">Current</option>
              </select>
          </div> -->
          <div class="pf-field">

            <?php $acc_type = strtolower(trim($value->C_ACC_TYPE ?? '')); ?>

        <label for="kycAccountType">Account Type</label>

        <select class="form-select" id="kycAccountType" name="kycAccountType" <?php echo $bank_readonly; ?>>

            <option value="">Select Account Type</option>

            <option value="savings"
                <?php echo ($acc_type == 'savings') ? 'selected' : ''; ?>>
                Savings
            </option>

            <option value="current"
                <?php echo ($acc_type == 'current') ? 'selected' : ''; ?>>
                Current
            </option>

        </select>
        </div>
        <?php if($value->C_BANK_APPROVEL=='R'||$value->C_BANK_APPROVEL=='N'){?>
        <div class="pf-actions">
          <!-- <button type="button" class="btn btn-primary" onclick="saveProfileSection('kyc')">Update Bank Details</button> -->
           <button type="submit" class="btn btn-primary">
              Save Changes
          </button>
        </div>
        <?php }?>
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
          <div class="pan-number-display" id="panDisplay"><?php echo $value->C_PAN ;?></div>
          <div class="doc-card-fields pan-fields">
            <div class="doc-field"><span class="doc-label">Name</span><span class="doc-value"><?php echo $value->C_PAN_NAME ; ?></span></div>
            <div class="doc-field"><span class="doc-label">Father's Name</span><span class="doc-value"><?php echo $value->C_FATHER ; ?></span></div>
            <div class="doc-field"><span class="doc-label">Date of Birth</span><span class="doc-value"><?php echo $value->C_DOB ; ?></span></div>
          </div>
        </div>
      </div>
<!-- 
      <form class="profile-form" id="panForm">
        <div class="profile-form-grid">
          <div class="pf-field">
            <label for="panNumber">PAN Number</label>
            <input class="form-input" type="text" id="panNumber" name="panNumber" value="<?php echo $value->C_PAN ; ?>" maxlength="10" oninput="formatPan(this)" placeholder="ABCDE1234F" style="text-transform:uppercase">
          </div>
          <div class="pf-field">
            <label for="panName">Name on PAN</label>
            <input class="form-input" type="text" id="panName" name="panName" value="<?php $value->C_PAN_NAME ; ?>">
          </div>
          <div class="pf-field">
            <label for="panFather">Father's Name</label>
            <input class="form-input" type="text" id="panFather" name="panFather" value="<?php echo $value->C_FATHER ;?>">
          </div>
          <div class="pf-field">
            <label for="panDob">Date of Birth</label>
            <input class="form-input" type="date" id="panDob" name="panDob" value="<?php echo $value->C_DOB ; ?>">
          </div>
        </div>

        <h4 class="pf-section-label">PAN Card Upload</h4>
        <div class="doc-upload-grid single">
          <label class="doc-upload-box wide" id="panCardBox">
            <input type="file" accept="image/*,.pdf" hidden onchange="previewDoc(this, 'panCardBox', 'panCardPreview')">
            <div class="doc-upload-inner" id="panCardPreview">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              <span>Upload PAN Card</span>
              <small>Click to upload front side · JPG, PNG </small>
            </div>
          </label>
        </div>
        <div class="pf-actions">
          <button type="button" class="btn btn-primary" onclick="saveProfileSection('pan')">Save PAN Details</button>
        </div>
      </form> -->
               <?php
                  $is_readonly = ($value->C_PAN_APPROVEL == 'Y' || $value->C_PAN_APPROVEL == 'P') ? 'readonly' : '';
                ?>

                <form class="profile-form" id="panForm" enctype="multipart/form-data" method="POST" action="<?php echo base_url('update_pan')?>" >
            
            <div class="profile-form-grid">

              <div class="pf-field">
                <label for="panNumber">PAN Number</label>
                <input class="form-input"
                      type="text"
                      id="panNumber"
                      name="panNumber"
                      value="<?php echo $value->C_PAN ; ?>"
                      maxlength="10"
                      oninput="formatPan(this)"
                      placeholder="ABCDE1234F"
                      style="text-transform:uppercase"  <?php echo $is_readonly; ?>>
              </div>

              <div class="pf-field">
                <label for="panName">Name on PAN</label>
                <input class="form-input"
                      type="text"
                      id="panName"
                      name="panName"
                      value="<?php echo $value->C_PAN_NAME ; ?>"  <?php echo $is_readonly; ?>>
              </div>

              <div class="pf-field">
                <label for="panFather">Father's Name</label>
                <input class="form-input"
                      type="text"
                      id="panFather"
                      name="panFather"
                      value="<?php echo $value->C_FATHER ; ?>"  <?php echo $is_readonly; ?>>
              </div>

              <div class="pf-field">
                <label for="panDob">Date of Birth</label>
                <input class="form-input"
                      type="date"
                      id="panDob"
                      name="panDob"
                      value="<?php echo $value->C_DOB ; ?>"  <?php echo $is_readonly; ?>>
              </div>

            </div>

            <h4 class="pf-section-label">PAN Card Upload</h4>

            <div class="doc-upload-grid single">

              <?php
                $pan_status = $value->C_PAN_APPROVEL;
                $pan_image  = $value->C_PANCARD_FILE;
              ?>

              <?php if($pan_status == 'N' || empty($pan_image)) { ?>

                <!-- Upload Option -->

                <label class="doc-upload-box wide" id="panCardBox">

                  <input type="file"
                        name="panImage"
                        id="panImage"
                        accept="image/*,.pdf"
                        hidden
                        onchange="previewDoc(this, 'panCardBox', 'panCardPreview')">

                  <div class="doc-upload-inner" id="panCardPreview">

                    <svg viewBox="0 0 24 24"
                        fill="none"
                        stroke-width="1.5"
                        stroke-linecap="round">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                      <polyline points="14 2 14 8 20 8"/>
                    </svg>

                    <span>Upload PAN Card</span>
                    <small>Click to upload front side · JPG, PNG</small>

                  </div>

                </label>

                <?php if($pan_status == 'R') { ?>
                  <div class="doc-status rejected">
                    REJECTED
                  </div>
                <?php } ?>

              <?php } else { ?>

                <!-- Preview Image -->

                <div class="doc-upload-box wide uploaded-preview">

                  <img src="<?php echo base_url('assets/uploads/pan/'.$pan_image); ?>"
                      class="doc-preview-img">

                  <?php if($pan_status == 'P') { ?>

                    <div class="doc-status pending">
                      PENDING
                    </div>

                  <?php } elseif($pan_status == 'Y') { ?>

                    <div class="doc-status approved">
                      APPROVED
                    </div>

                  <?php } ?>

                </div>

              <?php } ?>

            </div>

            <?php if($pan_status=='R' || $pan_status == 'N'){?>

            <div class="pf-actions">
              <button type="submit" class="btn btn-primary">
                Save PAN Details
              </button>
            </div>
            <?php }?>

          </form>
    </section>

  </div><!-- /profile-panels -->
</div><!-- /profile-layout -->

<?php }?>


<style>
label.error{
    color:red !important;
    font-size:13px;
    margin-top:5px;
    display:block;
}

input.error,
select.error,
textarea.error{
    border:1px solid red !important;
    box-shadow:none !important;
}
</style>

<script src="<?php echo JS_PATH ?>jquery-3.6.0.min.js"></script>

<script src="<?php echo JS_PATH ?>profile.js"></script>
<script src="<?php echo JS_PATH ?>jquery.validate.min.js"></script>
<script src="<?php echo JS_PATH ?>additional-methods.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




<script>

$('#avatarInput').on('change', function () {

    let file = this.files[0];

    if(file)
    {

        // Preview
        let reader = new FileReader();

        reader.onload = function(e){

            $('#profileAvatar').html(
                '<img src="'+e.target.result+'" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">'
            );

            $('.sb-avatar').html(
                '<img src="'+e.target.result+'" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">'
            );
            $('.tb-avatar').html(
                '<img src="'+e.target.result+'" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">'
            );

           

        }

        reader.readAsDataURL(file);

        // FormData
        let formData = new FormData();

        formData.append('profile_photo', file);

        $.ajax({

            url: "<?php echo base_url('update_profile_photo'); ?>",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',

           success: function(res){

    if(res.status == 'success')
    {

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Profile photo updated successfully',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });

    }
    else
    {

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: res.message,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });

    }

},

            error:function(xhr){

                console.log(xhr.responseText);

            }

        });

    }

});

</script>




<script>
  $('#personalForm').validate({

    rules:
    {
        pfFullName:
        {
            required:true
        },

        pfEmail:
        {
            required:true,
            email:true
        },

        pfPhone:
        {
            required:true,
            digits:true,
            minlength:10,
            maxlength:10
        },

        pfDob:
        {
            required:true
        },

        pfGender:
        {
            required:true
        },

        pfAddress:
        {
            required:true
        },

        pfCity:
        {
            required:true
        },

        pfState:
        {
            required:true
        },

        pfPincode:
        {
            required:true,
            digits:true,
            minlength:6,
            maxlength:6
        }
    },

  
       messages:
    {
        pfFullName:
        {
            required:"Please enter full name"
        },

        pfEmail:
        {
            required:"Please enter email address",
            email:"Enter valid email address"
        },

        pfPhone:
        {
            required:"Please enter mobile number",
            digits:"Only numbers allowed",
            maxlength:"Mobile number must be 10 digits"
        },

        pfDob:
        {
            required:"Please select date of birth"
        },

        pfGender:
        {
            required:"Please select gender"
        },

        pfAddress:
        {
            required:"Please enter address"
        },

        pfCity:
        {
            required:"Please enter city"
        },

        pfState:
        {
            required:"Please enter state"
        },

        pfPincode:
        {
            required:"Please enter pincode",
            digits:"Only numbers allowed",
            minlength:"Pincode must be 6 digits",
            maxlength:"Pincode must be 6 digits"
        }
    
    },

    submitHandler:function(form)
    {

        let dob = $('#pfDob').val();

        let birthDate = new Date(dob);

        let today = new Date();

        let age = today.getFullYear() - birthDate.getFullYear();

        let monthDiff = today.getMonth() - birthDate.getMonth();

        if(monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate()))
        {
            age--;
        }

        if(age < 18)
        {
          Swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'error',
              title: 'Age Restriction',
              text: 'Age must be 18 or above',
              showConfirmButton: false,
              timer: 2500,
              timerProgressBar: true,
              width: '350px'
          });

            return false;
        }

        form.submit();
    }

});
</script>

<style>.kyc-step.done .kyc-step-dot
{
    background:#16a34a;
    color:#fff;
}

.kyc-step.done svg
{
    display:block;
    stroke:#fff;
}

.kyc-step.done .kyc-step-dot
{
    font-size:0;
}

.kyc-step.active .kyc-step-dot
{
    background:#2563eb;
    color:#fff;
}

.kyc-step svg
{
    width:14px;
    height:14px;
    display:none;
}</style>

<!-- bank  -->

<script>

function updateKycTimeline()
{
    let step1 = false;
    let step2 = false;
    let step3 = false;
    let step4 = false;

    // GET VALUES
    let accountName = $('#kycAccountName').val().trim();
    let bankName    = $('#kycBankName').val().trim();
    let accountNo   = $('#kycAccount_No').val().trim();
    let ifsc        = $('#kycIfsc').val().trim();
    let branch      = $('#kycBranch').val().trim();
    let accType     = $('#kycAccountType').val();

    // VALIDATIONS
    let ifscPattern = /^[A-Z]{4}0[A-Z0-9]{6}$/;

    // STEP 1
    if(accountName !== '')
    {
        step1 = true;
    }

    // STEP 2
    if(bankName !== '' && accountNo.length >= 9)
    {
        step2 = true;
    }

    // STEP 3
    if(ifscPattern.test(ifsc) && branch !== '' && accType !== '')
    {
        step3 = true;
    }

    // STEP 4
    if(step1 && step2 && step3)
    {
        step4 = true;
    }

    // REMOVE ALL CLASSES
    $('.kyc-step').removeClass('done active');

    // STEP 1 UI
    if(step1)
    {
        $('.kyc-step').eq(0).addClass('done');
    }
    else
    {
        $('.kyc-step').eq(0).addClass('active');
        return;
    }

    // STEP 2 UI
    if(step2)
    {
        $('.kyc-step').eq(1).addClass('done');
    }
    else
    {
        $('.kyc-step').eq(1).addClass('active');
        return;
    }

    // STEP 3 UI
    if(step3)
    {
        $('.kyc-step').eq(2).addClass('done');
    }
    else
    {
        $('.kyc-step').eq(2).addClass('active');
        return;
    }

    // STEP 4 UI
    if(step4)
    {
        $('.kyc-step').eq(3).addClass('done');
    }
}

$(document).ready(function(){

    // INITIAL LOAD
    updateKycTimeline();

    // ON INPUT CHANGE
    $('#kycForm input, #kycForm select').on('keyup change', function(){
        updateKycTimeline();
    });

});

$('#kycForm').validate({

    rules:
    {
        kycAccountName:
        {
            required:true,
            minlength:3
        },

        kycBankName:
        {
            required:true
        },

        kycAccount_No:
        {
            required:true,
            digits:true,
            minlength:9,
            maxlength:18
        },

        kycIfsc:
        {
            required:true,
            pattern:/^[A-Z]{4}0[A-Z0-9]{6}$/
        },

        kycBranch:
        {
            required:true
        },

        kycAccountType:
        {
            required:true
        }
    },

    messages:
    {
        kycAccountName:
        {
            required:"Please enter account holder name",
            minlength:"Minimum 3 characters required"
        },

        kycBankName:
        {
            required:"Please enter bank name"
        },

        kycAccount_No:
        {
            required:"Please enter account number",
            digits:"Only numbers allowed",
            minlength:"Account number must be minimum 9 digits",
            maxlength:"Account number maximum 18 digits"
        },

        kycIfsc:
        {
            required:"Please enter IFSC code",
            pattern:"Enter valid IFSC code"
        },

        kycBranch:
        {
            required:"Please enter branch name"
        },

        kycAccountType:
        {
            required:"Please select account type"
        }
    },

    submitHandler:function(form)
    {
        // form.submit();
          $.ajax({

            url: $(form).attr('action'),
            type:'POST',
            data: $(form).serialize(),
            dataType:'json',

            success:function(res)
            {

                if(res.status == 'success')
                {
                    Swal.fire({
                        icon:'success',
                        title:'Success',
                        text:'Bank details updated successfully',
                        confirmButtonColor:'#3085d6'
                            }).then(() => {

                        location.reload();

                    });
                }
                else
                {
                    Swal.fire({
                        icon:'error',
                        title:'Error',
                        text:res.message
                    });
                }

            },

            error:function()
            {
                Swal.fire({
                    icon:'error',
                    title:'Error',
                    text:'Something went wrong'
                });
            }

        });

        return false;
    
    }
});

</script>

<style>

.validation-error{
    color:red;
    font-size:13px;
    margin-top:4px;
    display:block;
}

.doc-preview-img{
    width:100%;
    height:220px;
    object-fit:cover;
    border-radius:12px;
}

.doc-status{
    margin-top:10px;
    text-align:center;
    font-weight:700;
    padding:8px;
    border-radius:8px;
    font-size:14px;
}

.doc-status.pending{
    background:#fff3cd;
    color:#856404;
}

.doc-status.approved{
    background:#d4edda;
    color:#155724;
}

.doc-status.rejected{
    background:#f8d7da;
    color:#721c24;
}



input[readonly] {
    background: #f5f5f5;
    cursor: not-allowed;
}

</style>


<script>

$('#panForm').validate({

    rules:
    {
        panNumber:
        {
            required:true,
            minlength:10,
            maxlength:10,
            pattern:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/
        },

        panName:
        {
            required:true,
            minlength:3
        },

        panFather:
        {
            required:true,
            minlength:3
        },

        panDob:
        {
            required:true
        },

        panImage:
        {
            required:function()
            {
                return $('#panImage').length > 0;
            },
            extension:"jpg|jpeg|png|pdf"
        }
    },

    messages:
    {
        panNumber:
        {
            required:"Please enter PAN number",
            minlength:"PAN number must be 10 characters",
            maxlength:"PAN number must be 10 characters",
            pattern:"Enter valid PAN number"
        },

        panName:
        {
            required:"Please enter name on PAN",
            minlength:"Minimum 3 characters required"
        },

        panFather:
        {
            required:"Please enter father's name",
            minlength:"Minimum 3 characters required"
        },

        panDob:
        {
            required:"Please select date of birth"
        },

        panImage:
        {
            required:"Please upload PAN card",
            extension:"Only JPG, PNG and PDF allowed"
        }
    },

    errorElement:'span',
    errorClass:'validation-error',

    submitHandler:function(form)
    {

        var formData = new FormData(form);

        $.ajax({

            url:"<?php echo base_url('update_pan'); ?>",
            type:"POST",
            data:formData,
            processData:false,
            contentType:false,
            dataType:'json',

            success:function(res)
            {

                if(res.status == 'success')
                {

                    Swal.fire({

                        icon:'success',
                        title:'Success',
                        text:'PAN details updated successfully',
                        timer:2000,
                        showConfirmButton:false

                    }).then(() => {

                        location.reload();

                    });

                }
                else
                {

                    Swal.fire({

                        icon:'error',
                        title:'Error',
                        text:res.message

                    });

                }

            }

        });

    }

});


function formatPan(input)
{
    input.value = input.value.toUpperCase();
}

</script>

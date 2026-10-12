let profileEditMode = false;

const PROFILE_PANELS = ['personal', 'kyc', 'aadhar', 'pan'];

function switchProfilePanel(panelId, btn) {
  if (!PROFILE_PANELS.includes(panelId)) return;

  document.querySelectorAll('.profile-panel').forEach(function(p) {
    p.classList.remove('active');
  });
  document.querySelectorAll('.profile-tree-item').forEach(function(i) {
    i.classList.remove('active');
  });
  document.querySelectorAll('.profile-tab-btn').forEach(function(t) {
    t.classList.remove('active');
    t.setAttribute('aria-selected', 'false');
  });

  const panel = document.getElementById('panel-' + panelId);
  if (panel) panel.classList.add('active');

  const treeItem = document.querySelector('.profile-tree-item[data-panel="' + panelId + '"]');
  if (treeItem) treeItem.classList.add('active');

  const tabBtn = document.querySelector('.profile-tab-btn[data-panel="' + panelId + '"]');
  if (tabBtn) {
    tabBtn.classList.add('active');
    tabBtn.setAttribute('aria-selected', 'true');
  }

  if (btn && btn.classList && btn.classList.contains('profile-tree-btn')) {
    const item = btn.closest('.profile-tree-item');
    if (item) item.classList.add('active');
  }

  if (history.replaceState) {
    history.replaceState(null, '', '#' + panelId);
  } else {
    window.location.hash = panelId;
  }
}

function bindProfileTabs() {
  document.querySelectorAll('.profile-tree-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const item = this.closest('.profile-tree-item');
      const panelId = item && item.dataset.panel;
      if (panelId) switchProfilePanel(panelId, this);
    });
  });

  document.querySelectorAll('.profile-tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const panelId = this.dataset.panel;
      if (panelId) switchProfilePanel(panelId, this);
    });
  });
}

function formatAadhar(input) {
  let val = input.value.replace(/\D/g, '').slice(0, 12);
  val = val.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
  input.value = val;

  const display = document.getElementById('aadharDisplay');
  if (display && val.length >= 4) {
    const masked = val.replace(/\d(?=\d{4})/g, 'X');
    display.textContent = masked || 'XXXX XXXX XXXX';
  }
}

function formatPan(input) {
  input.value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 10);
  const display = document.getElementById('panDisplay');
  if (display) display.textContent = input.value || 'XXXXXXXXXX';
}

function formatPhone(input) {
  input.value = input.value.replace(/\D/g, '').slice(0, 10);
  if (input.value.length === 10) {
    input.value = input.value.replace(/(\d{5})(\d{5})/, '$1 $2');
  }
}

function validatePincode(input) {
  input.value = input.value.replace(/\D/g, '').slice(0, 6);
  const hint = document.getElementById('pfPincodeHint');
  if (!hint) return;
  if (input.value.length === 0) {
    hint.textContent = '';
    hint.className = 'pf-field-hint';
  } else if (input.value.length === 6) {
    hint.textContent = 'Valid pincode format';
    hint.className = 'pf-field-hint valid';
  } else {
    hint.textContent = 'Enter 6-digit pincode';
    hint.className = 'pf-field-hint invalid';
  }
}

function updateCharCount(textarea, countId) {
  const el = document.getElementById(countId);
  if (el) el.textContent = textarea.value.length + ' / ' + (textarea.maxLength || 200);
}

function copyToClipboard(fieldId) {
  const field = document.getElementById(fieldId);
  if (!field) return;
  navigator.clipboard.writeText(field.value).then(function() {
    showToast('Member ID copied!', 'green');
  }).catch(function() {
    showToast('Could not copy', 'red');
  });
}

function toggleFieldVisibility(fieldId, btn) {
  const field = document.getElementById(fieldId);
  if (!field) return;
  const isHidden = field.type === 'password';
  field.type = isHidden ? 'text' : 'password';
  if (!isHidden && field.dataset.masked) {
    field.value = field.dataset.masked;
  } else if (isHidden) {
    field.value = field.value.replace(/\*/g, '') || '1234567890';
  }
  btn.classList.toggle('active', isHidden);
}

function toggleProfileEdit() {
  profileEditMode = !profileEditMode;
  const form = document.getElementById('personalForm');
  const btn = document.getElementById('editToggleBtn');
  if (form) form.classList.toggle('pf-locked', !profileEditMode);
  if (btn) {
    btn.innerHTML = profileEditMode
      ? '<svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" width="14" height="14"><polyline points="20 6 9 17 4 12"/></svg> Done'
      : '<svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" width="14" height="14"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit';
  }
  if (profileEditMode) showToast('Edit mode enabled', 'gold');
}

function previewAvatar(input) {
  const file = input.files && input.files[0];
  if (!file || !file.type.startsWith('image/')) return;

  const reader = new FileReader();
  reader.onload = function(e) {
    const avatar = document.getElementById('profileAvatar');
    avatar.style.backgroundImage = 'url(' + e.target.result + ')';
    avatar.style.backgroundSize = 'cover';
    avatar.style.backgroundPosition = 'center';
    avatar.textContent = '';
    showToast('Profile photo updated!', 'green');
  };
  reader.readAsDataURL(file);
}

function previewDoc(input, boxId, previewId) {
  const file = input.files && input.files[0];
  if (!file) return;

  const preview = document.getElementById(previewId);
  const box = document.getElementById(boxId);
  if (!preview || !box) return;

  box.classList.add('has-file');

  if (file.type.startsWith('image/')) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.innerHTML =
        '<img src="' + e.target.result + '" alt="Document preview">' +
        '<span>' + file.name + '</span>' +
        '<small>Click to replace</small>';
    };
    reader.readAsDataURL(file);
  } else {
    preview.innerHTML =
      '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>' +
      '<span>' + file.name + '</span>' +
      '<small>PDF uploaded · Click to replace</small>';
  }
}

function saveProfileSection(section) {
  const labels = {
    personal: 'Personal details',
    kyc: 'KYC & bank details',
    aadhar: 'Aadhar details',
    pan: 'PAN card details'
  };

  if (section === 'personal') {
    const pincode = document.getElementById('pfPincode');
    if (pincode && pincode.value.length > 0 && pincode.value.length !== 6) {
      showToast('Please enter a valid 6-digit pincode.', 'red');
      return;
    }
    profileEditMode = false;
    const form = document.getElementById('personalForm');
    if (form) form.classList.add('pf-locked');
    const btn = document.getElementById('editToggleBtn');
    if (btn) btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" width="14" height="14"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit';
  }

  showToast((labels[section] || 'Profile') + ' saved successfully!', 'green');
}

function initProfilePage() {
  bindProfileTabs();

  const hash = window.location.hash.replace('#', '');
  if (hash && PROFILE_PANELS.includes(hash)) {
    switchProfilePanel(hash, null);
  }

  const aadharInput = document.getElementById('aadharNumber');
  if (aadharInput) formatAadhar(aadharInput);

  const panInput = document.getElementById('panNumber');
  if (panInput) formatPan(panInput);

  const address = document.getElementById('pfAddress');
  if (address) updateCharCount(address, 'pfAddressCount');

  const pincode = document.getElementById('pfPincode');
  if (pincode) validatePincode(pincode);

  const personalForm = document.getElementById('personalForm');
  if (personalForm) personalForm.classList.add('pf-locked');


}

if (document.getElementById('panel-personal')) {
  initProfilePage();
}

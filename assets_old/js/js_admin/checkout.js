let currentCartItem = null;
let receiptBase64 = '';
let selectedMethod = 'online'; // 'online' or 'offline'

// Toggle transaction password visibility
function toggleTxnPw(btn) {
  const input = btn.closest('.txn-pw-wrap').querySelector('input');
  const isHidden = input.type === 'password';
  input.type = isHidden ? 'text' : 'password';
  const eyePath = isHidden
    ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
    : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
  btn.querySelector('svg').innerHTML = eyePath;
}

function loadCheckoutDetails() {
  const stored = sessionStorage.getItem('rm_pending_purchase');
  if (!stored) {
    alert('No package in cart. Redirecting to packages.');
    window.location.href = 'purchase-package.php';
    return;
  }

  try {
    currentCartItem = JSON.parse(stored);
  } catch (e) {
    window.location.href = 'purchase-package.php';
    return;
  }

  const subtotal = currentCartItem.price;
  const tax = Math.round(subtotal * 0.18);
  const total = subtotal + tax;

  // Update summary table
  document.getElementById('summaryPkgName').textContent = currentCartItem.name;
  document.getElementById('summaryPkgPrice').textContent = `₹${subtotal.toLocaleString('en-IN')}`;
  document.getElementById('summarySubtotal').textContent = `₹${subtotal.toLocaleString('en-IN')}`;
  document.getElementById('summaryTax').textContent = `₹${tax.toLocaleString('en-IN')}`;
  document.getElementById('summaryGrandTotal').textContent = `₹${total.toLocaleString('en-IN')}`;

  // Update Razorpay modal amount
  document.getElementById('rzpAmountVal').textContent = `₹${total.toLocaleString('en-IN')}`;
}

function selectPaymentMethod(type) {
  selectedMethod = type;
  document.querySelectorAll('.payment-pill').forEach(p => p.classList.remove('selected'));
  document.querySelectorAll('.payment-section-content').forEach(s => s.classList.remove('active'));

  if (type === 'online') {
    document.querySelectorAll('.payment-pill')[0].classList.add('selected');
    document.getElementById('online-payment-section').classList.add('active');
  } else {
    document.querySelectorAll('.payment-pill')[1].classList.add('selected');
    document.getElementById('offline-payment-section').classList.add('active');
  }
}

// ════ MOCK RAZORPAY CODE ════
function triggerRazorpay() {
  const backdrop = document.getElementById('rzpBackdrop');
  backdrop.classList.add('show');
  document.getElementById('rzpPaymentArea').style.display = 'none';
}

function selectRzpMethod(method) {
  document.getElementById('rzpPaymentArea').style.display = 'block';
  const label = document.getElementById('rzpFieldLabel');
  const input = document.getElementById('rzpFieldInput');

  if (method === 'card') {
    label.textContent = 'Card Number';
    input.placeholder = '4111 2222 3333 4444';
  } else {
    label.textContent = 'UPI ID';
    input.placeholder = 'username@okaxis';
  }
}

function simulateRzpSuccess() {
  const backdrop = document.getElementById('rzpBackdrop');
  backdrop.classList.remove('show');

  const subtotal = currentCartItem.price;
  const tax = Math.round(subtotal * 0.18);
  const total = subtotal + tax;
  const orderId = 'RM-ACT-' + Math.floor(Math.random() * 90000 + 10000);
  const dateStr = new Date().toISOString().split('T')[0];

  const newActivation = {
    orderId: orderId,
    packageName: currentCartItem.name,
    amount: total,
    paymentMode: 'Online (Razorpay)',
    utr: 'MOCK-RZP-' + Math.floor(Math.random() * 9000000 + 1000000),
    date: dateStr,
    status: 'Active',
    receipt: ''
  };

  // Add to activations log list
  saveActivationLog(newActivation);

  // Add to transaction logs (so it appears on main dashboard)
  saveTransactionHistory(currentCartItem.name, total);

  // Clear cart
  sessionStorage.removeItem('rm_pending_purchase');

  if (typeof showToast === 'function') {
    showToast('Payment successful! Package activated.', 'green');
  }

  setTimeout(() => {
    window.location.href = 'activation-logs.php';
  }, 1000);
}

// ════ OFFLINE TRANSFER RECEIPT CODE ════
function handleReceiptFile(files) {
  if (!files || !files.length) return;
  const file = files[0];

  // Validate file size (max 5MB)
  if (file.size > 5 * 1024 * 1024) {
    alert('File is too large. Max size is 5MB.');
    return;
  }

  const reader = new FileReader();
  reader.onload = function (e) {
    receiptBase64 = e.target.result;
    
    // Display thumbnail preview
    document.getElementById('uploadPreviewImg').src = receiptBase64;
    document.getElementById('uploadPreviewName').textContent = file.name;
    document.getElementById('uploadPreviewWrap').style.display = 'block';
    
    if (typeof showToast === 'function') {
      showToast('Receipt file loaded successfully.', 'green');
    }
  };
  reader.readAsDataURL(file);
}

function submitOfflineReceipt() {
  const utr = document.getElementById('utrNumber').value.trim();
  const date = document.getElementById('utrDate').value;
  const txnPw = document.getElementById('txnPassword') ? document.getElementById('txnPassword').value.trim() : 'skip';

  if (!utr) {
    alert('Please enter your Transaction Reference / UTR Number.');
    return;
  }
  if (!date) {
    alert('Please select the Transaction Date.');
    return;
  }
  if (document.getElementById('txnPassword') && !txnPw) {
    alert('Please enter your Transaction Password for verification.');
    document.getElementById('txnPassword').focus();
    return;
  }
  if (!receiptBase64) {
    alert('Please upload a screenshot of your bank transfer receipt.');
    return;
  }

  const subtotal = currentCartItem.price;
  const tax = Math.round(subtotal * 0.18);
  const total = subtotal + tax;
  const orderId = 'RM-ACT-' + Math.floor(Math.random() * 90000 + 10000);

  const newActivation = {
    orderId: orderId,
    packageName: currentCartItem.name,
    amount: total,
    paymentMode: 'Bank Transfer',
    utr: utr,
    date: date,
    status: 'Pending Approval',
    receipt: receiptBase64
  };

  // Add to activations log list
  saveActivationLog(newActivation);

  // Add to transaction logs (so it appears on main dashboard as pending/credited)
  saveTransactionHistory(currentCartItem.name + ' (Pending)', total);

  // Clear cart
  sessionStorage.removeItem('rm_pending_purchase');

  if (typeof showToast === 'function') {
    showToast('Receipt submitted. Awaiting approval.', 'gold');
  }

  setTimeout(() => {
    window.location.href = 'activation-logs.php';
  }, 1000);
}

// ════ HELPER PERSISTENCE FUNCTIONS ════
function saveActivationLog(activationObj) {
  let activations = [];
  try {
    const raw = localStorage.getItem('rm_activations');
    if (raw) activations = JSON.parse(raw);
  } catch (e) {
    activations = [];
  }
  activations.unshift(activationObj);
  localStorage.setItem('rm_activations', JSON.stringify(activations));
}

function saveTransactionHistory(pkgName, amount) {
  // Read existing transactions from localStorage
  let transactions = [];
  try {
    const raw = localStorage.getItem('rm_transactions');
    if (raw) {
      transactions = JSON.parse(raw);
    } else {
      // Fallback to defaults
      transactions = [
        { name: 'Product Sales — May', date: '5 June 2026', amount: 82400, type: 'credit', icon: 'g' },
        { name: 'Team Commission', date: '4 June 2026', amount: 12450, type: 'credit', icon: 'gold' },
        { name: 'Training Materials', date: '3 June 2026', amount: -3200, type: 'debit', icon: 'b' },
        { name: 'Bonus — Diamond Level', date: '2 June 2026', amount: 18000, type: 'credit', icon: 'gold' },
        { name: 'Promotional Event Cost', date: '1 June 2026', amount: -6800, type: 'debit', icon: 'b' }
      ];
    }
  } catch (e) {
    transactions = [];
  }

  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  const today = new Date();
  const dateStr = `${today.getDate()} ${months[today.getMonth()]} ${today.getFullYear()}`;

  const newTx = {
    name: 'Activation — ' + pkgName,
    date: dateStr,
    amount: amount,
    type: 'credit',
    icon: 'gold'
  };

  transactions.unshift(newTx);
  localStorage.setItem('rm_transactions', JSON.stringify(transactions));
}

// Close Modal when clicking outside
document.addEventListener('click', e => {
  const backdrop = document.getElementById('rzpBackdrop');
  if (e.target === backdrop) {
    backdrop.classList.remove('show');
  }
});

// Drag and drop events setup
document.addEventListener('DOMContentLoaded', () => {
  loadCheckoutDetails();

  const dropzone = document.getElementById('receiptDropzone');
  if (dropzone) {
    ['dragenter', 'dragover'].forEach(eventName => {
      dropzone.addEventListener(eventName, e => {
        e.preventDefault();
        dropzone.classList.add('dragover');
      }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
      dropzone.addEventListener(eventName, e => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
      }, false);
    });

    dropzone.addEventListener('drop', e => {
      const dt = e.dataTransfer;
      const files = dt.files;
      handleReceiptFile(files);
    }, false);
  }
});

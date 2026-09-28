let currentCart = null;

function addToCart(name, price, pkgId) {
  // Highlight selected package card
  const card = document.getElementById(pkgId);
  if (card) {
    card.style.borderColor = 'var(--gold)';
  }

  currentCart = { name, price };
  updateCartUI();

  // Change select button styling
  const btn = document.getElementById('addCartBtn');
  if (btn) {
    btn.textContent = 'Package Selected';
    btn.style.backgroundColor = 'var(--green-soft)';
    btn.style.borderColor = 'var(--green-soft)';
  }

  if (typeof showToast === 'function' && sessionStorage.getItem('rm_toast_shown') !== 'true') {
    showToast(`${name} added to cart!`, 'green');
    sessionStorage.setItem('rm_toast_shown', 'true');
  }
}

function updateCartUI() {
  const container = document.getElementById('cartItemsContainer');
  const badge = document.getElementById('cartBadgeCount');
  const subtotalEl = document.getElementById('cartSubtotal');
  const taxEl = document.getElementById('cartTax');
  const totalEl = document.getElementById('cartGrandTotal');
  const checkoutBtn = document.getElementById('checkoutBtn');

  if (!container) return;

  if (!currentCart) {
    container.innerHTML = `
      <div style="padding:40px 20px; text-align:center; color:var(--text-light); font-size:12.5px; line-height:1.5;">
        Your cart is empty.<br>Please select a package to activate your account.
      </div>
    `;
    badge.textContent = '0 items';
    subtotalEl.textContent = '₹0';
    taxEl.textContent = '₹0';
    totalEl.textContent = '₹0';
    checkoutBtn.disabled = true;
    
    const btn = document.getElementById('addCartBtn');
    if (btn) {
      btn.textContent = 'Select & Add to Cart';
      btn.style.backgroundColor = '#3ba588';
      btn.style.borderColor = '#3ba588';
    }
    return;
  }

  container.innerHTML = `
    <div class="cart-item-row">
      <div>
        <div class="cart-item-name">${currentCart.name}</div>
        <div style="font-size:10.5px; color:var(--text-light); margin-top:2px;">Account Activation Package</div>
      </div>
      <div style="text-align:right;">
        <div class="cart-item-price">₹${currentCart.price.toLocaleString('en-IN')}</div>
        <button onclick="removeFromCart()" style="background:none; border:none; color:#e74c3c; font-size:11px; cursor:pointer; margin-top:6px; font-weight:600; font-family:'Outfit',sans-serif;">Remove</button>
      </div>
    </div>
  `;

  const subtotal = currentCart.price;
  const tax = Math.round(subtotal * 0.18);
  const total = subtotal + tax;

  badge.textContent = '1 item';
  subtotalEl.textContent = `₹${subtotal.toLocaleString('en-IN')}`;
  taxEl.textContent = `₹${tax.toLocaleString('en-IN')}`;
  totalEl.textContent = `₹${total.toLocaleString('en-IN')}`;
  checkoutBtn.disabled = false;
}

function removeFromCart() {
  currentCart = null;
  const card = document.getElementById('pkg-starter');
  if (card) {
    card.style.borderColor = 'var(--border)';
  }
  updateCartUI();
  
  if (typeof showToast === 'function') {
    showToast('Item removed from cart.', 'gold');
  }
}

function goToCheckout() {
  if (!currentCart) return;
  sessionStorage.setItem('rm_pending_purchase', JSON.stringify(currentCart));
  window.location.href = 'checkout.php';
}

document.addEventListener('DOMContentLoaded', () => {
  sessionStorage.removeItem('rm_toast_shown');
  // Auto-select the single ₹6,000 package on page load for immediate checkout readiness
  addToCart('Activation Package', 6000, 'pkg-starter');
});

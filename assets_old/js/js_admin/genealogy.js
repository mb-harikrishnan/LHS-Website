// ═══════════════════════════════════════════════════════
//  GENEALOGY BOARD PLAN — JavaScript
//  Radha Madhav Growth Portal
// ═══════════════════════════════════════════════════════

// ─── Member data ───────────────────────────────────────
const treeMembers = {
  1:  { name:'Radha Madhav',  id:'RM-0001', level:1, role:'Admin · Diamond', joined:'Jan 2024', downline:12, income:'₹48,000', status:'root',   avClass:'root-av' },
  2:  { name:'Anita Sharma',  id:'RM-0002', level:2, role:'Diamond Leader',  joined:'Jan 2024', downline:8,  income:'₹18,400', status:'active', avClass:'av-c1' },
  3:  { name:'Rakesh Gupta',  id:'RM-0003', level:2, role:'Gold Leader',     joined:'Mar 2024', downline:6,  income:'₹12,200', status:'active', avClass:'av-c2' },
  4:  { name:'Meena Patel',   id:'RM-0004', level:3, role:'Regional Head',   joined:'Feb 2024', downline:4,  income:'₹9,600',  status:'active', avClass:'av-c3' },
  5:  { name:'Suresh Yadav',  id:'RM-0005', level:3, role:'Silver Manager',  joined:'May 2024', downline:3,  income:'₹5,800',  status:'active', avClass:'av-c4' },
  6:  { name:'Kavita Singh',  id:'RM-0006', level:3, role:'Team Leader',     joined:'Jun 2024', downline:2,  income:'₹4,200',  status:'active', avClass:'av-c5' },
  7:  { name:'Mohan Lal',     id:'RM-0007', level:3, role:'Senior Associate',joined:'Aug 2024', downline:2,  income:'₹3,900',  status:'active', avClass:'av-c1' },
  8:  { name:'Sunita Devi',   id:'RM-0008', level:4, role:'Associate',       joined:'Oct 2024', downline:0,  income:'₹1,800',  status:'active', avClass:'av-c2' },
  9:  { name:'Pradeep Kumar', id:'RM-0009', level:4, role:'Junior Member',   joined:'Nov 2024', downline:0,  income:'₹1,800',  status:'active', avClass:'av-c3' },
  10: { name:'Laxmi Nair',    id:'RM-0010', level:4, role:'Team Leader',     joined:'Dec 2024', downline:0,  income:'₹1,800',  status:'active', avClass:'av-c4' },
  11: { name:'Vijay Tiwari',  id:'RM-0011', level:4, role:'Associate',       joined:'Jan 2025', downline:0,  income:'₹1,800',  status:'active', avClass:'av-c5' },
  12: { name:'Geeta Rao',     id:'RM-0012', level:4, role:'Senior Associate',joined:'Feb 2025', downline:0,  income:'₹1,800',  status:'active', avClass:'av-c1' },
};

// ─── Connection map (parent → children) ────────────────
const connections = {
  1: [2, 3],
  2: [4, 5],
  3: [6, 7],
  4: [8, 9],
  5: [10, 11],
  6: [12],
  7: [],   // open 13,14,15
};

// ─── Zoom State ─────────────────────────────────────────
let currentZoom = 1;

function zoomTree(delta) {
  currentZoom = Math.min(1.6, Math.max(0.4, currentZoom + delta));
  document.getElementById('treeCanvas').style.transform = `scale(${currentZoom})`;
  document.getElementById('zoomLabel').textContent = Math.round(currentZoom * 100) + '%';
}
function resetZoom() {
  currentZoom = 1;
  document.getElementById('treeCanvas').style.transform = 'scale(1)';
  document.getElementById('zoomLabel').textContent = '100%';
}

// ─── Level Filter ───────────────────────────────────────
function filterLevel(btn, level) {
  document.querySelectorAll('.lvl-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.tree-level').forEach(lvl => {
    if (level === 'all') {
      lvl.style.opacity = '1';
      lvl.style.pointerEvents = '';
    } else {
      const lvlNum = parseInt(lvl.dataset.level);
      lvl.style.opacity = lvlNum === parseInt(level) ? '1' : '0.18';
      lvl.style.pointerEvents = lvlNum === parseInt(level) ? '' : 'none';
    }
  });
}

// ─── SVG Connector Lines ────────────────────────────────
function drawConnectors() {
  const svg = document.getElementById('treeSvg');
  const canvasRect = document.getElementById('treeCanvas').getBoundingClientRect();
  svg.innerHTML = '';

  // Define gradient & glow filter
  svg.innerHTML = `
    <defs>
      <linearGradient id="lineGrad1" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#D4AF5A" stop-opacity="0.9"/>
        <stop offset="100%" stop-color="#4A7C59" stop-opacity="0.6"/>
      </linearGradient>
      <linearGradient id="lineGrad2" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#4A7C59" stop-opacity="0.9"/>
        <stop offset="100%" stop-color="#2980b9" stop-opacity="0.6"/>
      </linearGradient>
      <linearGradient id="lineGrad3" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#2980b9" stop-opacity="0.9"/>
        <stop offset="100%" stop-color="#8e44ad" stop-opacity="0.6"/>
      </linearGradient>
      <filter id="lineGlow">
        <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
        <feMerge><feMergeNode in="coloredBlur"/><feMergeNode in="SourceGraphic"/></feMerge>
      </filter>
    </defs>`;

  const gradMap = { 1:'lineGrad1', 2:'lineGrad2', 3:'lineGrad3' };

  Object.entries(connections).forEach(([parentId, childIds]) => {
    if (!childIds.length) return;
    const parentEl = document.getElementById('node-' + parentId);
    if (!parentEl) return;
    const parentAvatar = parentEl.querySelector('.node-avatar');
    if (!parentAvatar) return;

    const pr = parentAvatar.getBoundingClientRect();
    const px = pr.left + pr.width / 2 - canvasRect.left;
    const py = pr.top  + pr.height     - canvasRect.top;

    childIds.forEach(childId => {
      const childEl = document.getElementById('node-' + childId);
      if (!childEl) return;
      const childAvatar = childEl.querySelector('.node-avatar');
      if (!childAvatar) return;

      const cr = childAvatar.getBoundingClientRect();
      const cx = cr.left + cr.width / 2 - canvasRect.left;
      const cy = cr.top              - canvasRect.top;

      const parentMember = treeMembers[parseInt(parentId)];
      const gradId = gradMap[parentMember ? parentMember.level : 1] || 'lineGrad1';
      const midY = (py + cy) / 2;

      const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      path.setAttribute('d', `M ${px} ${py} C ${px} ${midY}, ${cx} ${midY}, ${cx} ${cy}`);
      path.setAttribute('fill', 'none');
      path.setAttribute('stroke', `url(#${gradId})`);
      path.setAttribute('stroke-width', '2.5');
      path.setAttribute('stroke-linecap', 'round');
      path.setAttribute('filter', 'url(#lineGlow)');
      path.setAttribute('opacity', '0.85');

      // Animate dashes
      const len = path.getTotalLength ? 200 : 200;
      path.style.strokeDasharray  = len;
      path.style.strokeDashoffset = len;
      path.style.animation = `dashDraw 0.8s ease forwards ${0.15 * childId}s`;

      svg.appendChild(path);
    });
  });
}

// Inject keyframe for dash animation into document
(function injectDashKeyframe() {
  const style = document.createElement('style');
  style.textContent = `
    @keyframes dashDraw {
      to { stroke-dashoffset: 0; }
    }
  `;
  document.head.appendChild(style);
})();

// ─── Node Modal ──────────────────────────────────────────
function openNodeModal(id) {
  const m = treeMembers[id];
  if (!m) return;

  // Avatar
  const nmAvatar = document.getElementById('nmAvatar');
  nmAvatar.className = 'nm-avatar ' + m.avClass;
  nmAvatar.innerHTML = `<svg viewBox="0 0 48 48" fill="none">
    <circle cx="24" cy="16" r="10" fill="rgba(255,255,255,0.9)"/>
    <path d="M4 44c0-11.046 8.954-20 20-20s20 8.954 20 20" fill="rgba(255,255,255,0.7)"/>
  </svg>`;

  document.getElementById('nmName').textContent = m.name;
  document.getElementById('nmId').textContent   = m.id;

  const badge = document.getElementById('nmLevelBadge');
  const levelColors = ['','root-badge','direct-badge','',''];
  badge.textContent = 'Level ' + m.level;
  badge.style.cssText = `
    background:${['','linear-gradient(90deg,#D4AF5A,#B8962E)','var(--green-pale)','rgba(41,128,185,0.15)','rgba(142,68,173,0.15)'][m.level]};
    color:${['','#fff','var(--green-soft)','#2980b9','#8e44ad'][m.level]};
    border:1px solid ${['','transparent','var(--border)','rgba(41,128,185,0.3)','rgba(142,68,173,0.3)'][m.level]};
  `;

  document.getElementById('nmStats').innerHTML = `
    <div class="nm-stat">
      <span class="nm-stat-val">${m.level}</span>
      <span class="nm-stat-lbl">Board Level</span>
    </div>
    <div class="nm-stat">
      <span class="nm-stat-val">${m.downline}</span>
      <span class="nm-stat-lbl">Downline</span>
    </div>
    <div class="nm-stat">
      <span class="nm-stat-val">${m.income}</span>
      <span class="nm-stat-lbl">Income</span>
    </div>
    <div class="nm-stat">
      <span class="nm-stat-val">${m.joined}</span>
      <span class="nm-stat-lbl">Joined</span>
    </div>
  `;

  document.getElementById('nodeModalOverlay').classList.add('show');
}
function closeNodeModal() {
  document.getElementById('nodeModalOverlay').classList.remove('show');
}

// ─── Join Slot Modal ─────────────────────────────────────
let currentSlot = null;
function openJoinModal(pos) {
  currentSlot = pos;
  document.getElementById('joinModalSub').innerHTML =
    `Position <strong>#${pos}</strong> is open. Share your referral link to fill this slot and unlock board bonus.`;
  document.getElementById('joinReferralUrl').textContent =
    `https://radhmadhav.com/join?ref=RM-0001&pos=${pos}`;
  document.getElementById('joinModalOverlay').classList.add('show');
}
function closeJoinModal() {
  document.getElementById('joinModalOverlay').classList.remove('show');
}
function copyJoinLink() {
  const url = document.getElementById('joinReferralUrl').textContent.trim();
  navigator.clipboard.writeText(url).then(() => {
    if (typeof showToast === 'function') showToast('Position link copied!', 'gold');
  }).catch(() => {});
}

// ─── Export Tree as Image ─────────────────────────────────
function exportTree() {
  if (typeof showToast === 'function') showToast('Preparing board plan export…', 'green');
  setTimeout(() => {
    window.print();
  }, 400);
}

// ─── Drag to Pan ─────────────────────────────────────────
(function initDragPan() {
  const wrap = document.getElementById('treeCanvasWrap');
  if (!wrap) return;
  let dragging = false, startX, startY, scrollLeft, scrollTop;
  wrap.addEventListener('mousedown', e => {
    if (e.target.closest('.tree-node')) return;
    dragging = true;
    startX = e.pageX - wrap.offsetLeft;
    startY = e.pageY - wrap.offsetTop;
    scrollLeft = wrap.scrollLeft;
    scrollTop  = wrap.scrollTop;
    wrap.style.userSelect = 'none';
  });
  document.addEventListener('mouseup', () => { dragging = false; wrap.style.userSelect = ''; });
  document.addEventListener('mousemove', e => {
    if (!dragging) return;
    const x = e.pageX - wrap.offsetLeft;
    const y = e.pageY - wrap.offsetTop;
    wrap.scrollLeft = scrollLeft - (x - startX);
    wrap.scrollTop  = scrollTop  - (y - startY);
  });
})();

// ─── Init ─────────────────────────────────────────────────
window.addEventListener('load', () => {
  // Draw after layout is stable
  setTimeout(() => {
    drawConnectors();
    // Redraw on resize
    window.addEventListener('resize', () => {
      setTimeout(drawConnectors, 100);
    });
  }, 300);
});

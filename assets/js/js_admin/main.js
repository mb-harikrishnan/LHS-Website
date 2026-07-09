// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
// DATA STORE (with localStorage persistence)
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
const STORAGE_KEYS = { members: 'rm_members', transactions: 'rm_transactions', activity: 'rm_activity', darkMode: 'rm_dark' };

const defaultMembers = [
  { name:'Anita Sharma',    id:'RM-0002', role:'Diamond Leader',  level:'Diamond', joined:'Jan 2024', downline:18, status:'active',   avatarClass:'c1' },
  { name:'Rakesh Gupta',    id:'RM-0003', role:'Gold Leader',     level:'Gold',    joined:'Mar 2024', downline:12, status:'active',   avatarClass:'c2' },
  { name:'Meena Patel',     id:'RM-0004', role:'Regional Head',   level:'Diamond', joined:'Feb 2024', downline:22, status:'active',   avatarClass:'c3' },
  { name:'Suresh Yadav',    id:'RM-0005', role:'Silver Manager',  level:'Silver',  joined:'May 2024', downline:7,  status:'active',   avatarClass:'c4' },
  { name:'Kavita Singh',    id:'RM-0006', role:'Team Leader',     level:'Gold',    joined:'Jun 2024', downline:9,  status:'active',   avatarClass:'c1' },
  { name:'Mohan Lal',       id:'RM-0007', role:'Senior Associate',level:'Silver',  joined:'Aug 2024', downline:4,  status:'active',   avatarClass:'c5' },
  { name:'Sunita Devi',     id:'RM-0008', role:'Associate',       level:'Bronze',  joined:'Oct 2024', downline:2,  status:'pending',  avatarClass:'c2' },
  { name:'Pradeep Kumar',   id:'RM-0009', role:'Junior Member',   level:'Bronze',  joined:'Nov 2024', downline:0,  status:'active',   avatarClass:'c3' },
  { name:'Laxmi Nair',      id:'RM-0010', role:'Team Leader',     level:'Gold',    joined:'Dec 2024', downline:6,  status:'active',   avatarClass:'c4' },
  { name:'Vijay Tiwari',    id:'RM-0011', role:'Associate',       level:'Silver',  joined:'Jan 2025', downline:3,  status:'inactive', avatarClass:'c5' },
  { name:'Geeta Rao',       id:'RM-0012', role:'Senior Associate',level:'Silver',  joined:'Feb 2025', downline:5,  status:'active',   avatarClass:'c1' },
  { name:'Harish Bhatt',    id:'RM-0013', role:'Junior Member',   level:'Bronze',  joined:'Mar 2025', downline:1,  status:'active',   avatarClass:'c2' },
];
const defaultTransactions = [
  { name:'Product Sales â€” May',   date:'5 June 2026',  amount:82400,  type:'credit', icon:'g'    },
  { name:'Team Commission',        date:'4 June 2026',  amount:12450,  type:'credit', icon:'gold' },
  { name:'Training Materials',     date:'3 June 2026',  amount:-3200,  type:'debit',  icon:'b'    },
  { name:'Bonus â€” Diamond Level',  date:'2 June 2026',  amount:18000,  type:'credit', icon:'gold' },
  { name:'Promotional Event Cost', date:'1 June 2026',  amount:-6800,  type:'debit',  icon:'b'    },
];
const defaultActivity = [
  { icon:'user-plus', iconClass:'green', title:'New Member Joined',         detail:'Harish Bhatt (RM-0013) registered as Bronze member.',      time:'2h ago'    },
  { icon:'dollar',    iconClass:'gold',  title:'Commission Credited',        detail:'â‚¹12,450 commission for May 2026 cycle.',                   time:'5h ago'    },
  { icon:'target',    iconClass:'blue',  title:'Monthly Target Achieved',    detail:'June revenue target of â‚¹5L crossed ahead of schedule.',    time:'Yesterday' },
  { icon:'star',      iconClass:'gold',  title:'Rank Upgrade',               detail:'Anita Sharma upgraded from Gold to Diamond Leader.',       time:'2 days ago' },
  { icon:'users',     iconClass:'green', title:'Team Milestone Reached',     detail:'Team crossed 12 active members for the first time.',      time:'3 days ago' },
];

function loadData(key, def) {
  try { const v = localStorage.getItem(key); return v ? JSON.parse(v) : def; }
  catch { return def; }
}
function saveData(key, val) { try { localStorage.setItem(key, JSON.stringify(val)); } catch {} }

let members     = loadData(STORAGE_KEYS.members, defaultMembers);
let transactions= loadData(STORAGE_KEYS.transactions, defaultTransactions);
let activities  = loadData(STORAGE_KEYS.activity, defaultActivity);
let darkMode    = localStorage.getItem(STORAGE_KEYS.darkMode) === 'true';
let notifCount  = 3;

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
// SIDEBAR
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function toggleSidebar() {
  const sb = document.getElementById('sidebar');
  const ov = document.getElementById('overlay');
  const main = document.getElementById('main');
  if (window.innerWidth <= 768) {
    sb.classList.toggle('mobile-open');
    ov.classList.toggle('show');
  } else {
    sb.classList.toggle('hidden');
    main.classList.toggle('full');
  }
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('mobile-open');
  document.getElementById('overlay').classList.remove('show');
}
function setActive(el) {
  document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
  el.classList.add('active');
}
function toggleNav(el) {
  const isOpen = el.classList.contains('open');
  document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active','open'));
  document.querySelectorAll('.sub-nav').forEach(s => s.classList.remove('open'));
  if (!isOpen) {
    el.classList.add('active','open');
    const idx = Array.from(el.parentElement.querySelectorAll('.nav-item')).filter(n=>n.querySelector('.nav-arrow')).indexOf(el);
    const subnavs = document.querySelectorAll('.sub-nav');
    if (subnavs[idx]) subnavs[idx].classList.add('open');
  }
}
function setSubActive(el) {
  document.querySelectorAll('.sub-item').forEach(i => i.classList.remove('active'));
  el.classList.add('active');
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
// DARK MODE
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function applyDarkMode() {
  document.documentElement.setAttribute('data-theme', darkMode ? 'dark' : 'light');
  const tog = document.getElementById('darkToggle');
  if (darkMode) tog.classList.add('on'); else tog.classList.remove('on');
}
function toggleDarkMode() {
  darkMode = !darkMode;
  localStorage.setItem(STORAGE_KEYS.darkMode, darkMode);
  applyDarkMode();
  rebuildChart();
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
// NOTIFICATIONS
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
let notifOpen = false;
function toggleNotif() {
  notifOpen = !notifOpen;
  document.getElementById('notifPanel').classList.toggle('show', notifOpen);
}
function markAllRead() {
  document.querySelectorAll('.notif-item.unread').forEach(i => i.classList.remove('unread'));
  notifCount = 0;
  document.getElementById('notifCount').textContent = '0';
  document.getElementById('notifDot').style.display = 'none';
}
function addNotification(title, detail) {
  const list = document.getElementById('notifList');
  const item = document.createElement('div');
  item.className = 'notif-item unread';
  item.innerHTML = `<div class="notif-dot-item"></div>
    <div class="notif-item-text">
      <strong>${title}</strong>
      <span>${detail} · just now</span>
    </div>`;
  list.prepend(item);
  notifCount++;
  document.getElementById('notifCount').textContent = notifCount;
  document.getElementById('notifDot').style.display = '';
}
document.addEventListener('click', e => {
  const panel = document.getElementById('notifPanel');
  const btn = document.getElementById('notifBtn');
  if (notifOpen && !panel.contains(e.target) && !btn.contains(e.target)) {
    notifOpen = false;
    panel.classList.remove('show');
  }
});

// ═══════════════════════════════════════════
// REFERRAL LINK
// ═══════════════════════════════════════════
function copyReferralLink() {
  const url = document.getElementById('referralLinkUrl').textContent.trim();
  const btn = document.getElementById('referralCopyBtn');
  navigator.clipboard.writeText(url).then(() => {
    btn.classList.add('copied');
    btn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="15" height="15"><polyline points="20 6 9 17 4 12"/></svg> Copied!`;
    showToast('Referral link copied to clipboard!', 'gold');
    setTimeout(() => {
      btn.classList.remove('copied');
      btn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="15" height="15"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg> Copy`;
    }, 2500);
  }).catch(() => {
    // Fallback for older browsers
    const ta = document.createElement('textarea');
    ta.value = url;
    ta.style.position = 'fixed'; ta.style.opacity = '0';
    document.body.appendChild(ta);
    ta.select();
    document.execCommand('copy');
    document.body.removeChild(ta);
    showToast('Referral link copied!', 'gold');
  });
}

function shareReferral(platform) {
  const url = encodeURIComponent(document.getElementById('referralLinkUrl').textContent.trim());
  const msg = encodeURIComponent('Join my team on Radha Madhav Growth! Use my referral link to register: ');
  let shareUrl = '';
  if (platform === 'whatsapp') {
    shareUrl = `https://wa.me/?text=${msg}${url}`;
  } else if (platform === 'telegram') {
    shareUrl = `https://t.me/share/url?url=${url}&text=${msg}`;
  } else if (platform === 'sms') {
    shareUrl = `sms:?body=${msg}${url}`;
  }
  if (shareUrl) window.open(shareUrl, '_blank');
  showToast(`Opening ${platform.charAt(0).toUpperCase()+platform.slice(1)} to share…`, 'green');
}

// ═══════════════════════════════════════════
// TOAST
// ═══════════════════════════════════════════
function showToast(msg, type = 'green') {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round">
    <polyline points="20 6 9 17 4 12"/></svg>${msg}`;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.animation = 'toastOut 0.3s ease forwards';
    setTimeout(() => toast.remove(), 300);
  }, 3200);
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
// MODALS
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•


// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
// MEMBERS
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
const avatarClasses = ['c1','c2','c3','c4','c5'];
function getInitials(name) { return name.split(' ').map(w => w[0]).slice(0,2).join('').toUpperCase(); }
function renderMembers(list) {
  const tbody = document.getElementById('memberTableBody');
  tbody.innerHTML = '';
  if (!list.length) {
    tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;padding:32px;color:var(--text-light)">No members found.</td></tr>`;
    return;
  }
  list.forEach(m => {
    const tr = document.createElement('tr');
    tr.setAttribute('data-name', m.name.toLowerCase());
    tr.setAttribute('data-id', m.id.toLowerCase());
    tr.setAttribute('data-role', (m.role||'').toLowerCase());
    tr.innerHTML = `
      <td>
        <div class="member-cell">
          <div class="member-av ${m.avatarClass}">${getInitials(m.name)}</div>
          <div>
            <div class="member-name">${m.name}</div>
            <div class="member-id">${m.id} Â· ${m.role}</div>
          </div>
        </div>
      </td>
      <td>${m.level}</td>
      <td>${m.joined}</td>
      <td style="font-weight:600;color:var(--text)">${m.downline}</td>
      <td><span class="status-pill ${m.status}">${m.status.charAt(0).toUpperCase()+m.status.slice(1)}</span></td>`;
    tbody.appendChild(tr);
  });
}
function filterTable(q) {
  const rows = document.querySelectorAll('#memberTableBody tr[data-name]');
  const val = q.toLowerCase().trim();
  rows.forEach(r => {
    const match = r.dataset.name.includes(val) || r.dataset.id.includes(val) || r.dataset.role.includes(val);
    r.style.display = match ? '' : 'none';
  });
}
function globalSearchFilter(q) {
  const tableSearch = document.getElementById('tableSearch');
  if (!tableSearch) return;
  tableSearch.value = q;
  filterTable(q);
}
function addMember() {
  const name   = document.getElementById('memberName').value.trim();
  const id     = document.getElementById('memberId').value.trim();
  const role   = document.getElementById('memberRole').value.trim();
  const level  = document.getElementById('memberLevel').value;
  const status = document.getElementById('memberStatus').value;
  if (!name || !id) { showToast('Please fill in Name and Member ID.', 'red'); return; }
  const now = new Date();
  const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  const joined = `${months[now.getMonth()]} ${now.getFullYear()}`;
  const avClass = avatarClasses[members.length % avatarClasses.length];
  const newMember = { name, id, role: role||'Member', level, joined, downline:0, status, avatarClass: avClass };
  members.unshift(newMember);
  saveData(STORAGE_KEYS.members, members);
  renderMembers(members);
  updateCounts();
  closeModal('addMemberModal');
  ['memberName','memberId','memberRole'].forEach(i => document.getElementById(i).value='');
  showToast(`${name} added to the team!`, 'green');
  addNotification('New Member Added', `${name} (${id}) enrolled as ${level} member`);
  addActivityItem({icon:'user-plus', iconClass:'green', title:'New Member Joined', detail:`${name} (${id}) registered as ${level} member.`, time:'Just now'});
}
function updateCounts() {
  const active = members.filter(m => m.status === 'active').length;
  document.getElementById('memberCount').textContent = active;
  document.getElementById('memberBadge').textContent = members.length;
  document.getElementById('tableCount').textContent = `${members.length} members`;
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
// TRANSACTIONS
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function formatINR(n) {
  const abs = Math.abs(n);
  if (abs >= 100000) return `â‚¹${(abs/100000).toFixed(1)}L`;
  if (abs >= 1000) return `â‚¹${(abs/1000).toFixed(1)}K`;
  return `â‚¹${abs.toLocaleString('en-IN')}`;
}
function renderTransactions() {
  const list = document.getElementById('incomeList');
  list.innerHTML = '';
  transactions.slice(0,6).forEach(tx => {
    const div = document.createElement('div');
    div.className = 'income-item';
    div.innerHTML = `
      <div class="income-icon ${tx.icon}">
        <svg viewBox="0 0 24 24" fill="none" stroke="${tx.icon==='gold'?'var(--gold)':tx.icon==='g'?'var(--green)':'#2980b9'}" stroke-width="2" stroke-linecap="round">
          <line x1="12" y1="1" x2="12" y2="23"/>
          <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
        </svg>
      </div>
      <div class="income-info">
        <div class="income-name">${tx.name}</div>
        <div class="income-date">${tx.date}</div>
      </div>
      <div class="income-amount ${tx.type === 'credit' ? 'credit' : 'debit'}">
        ${tx.type==='credit'?'+':'âˆ’'}${formatINR(tx.amount)}
      </div>`;
    list.appendChild(div);
  });
}
function addTransaction() {
  const name   = document.getElementById('txName').value.trim();
  const amount = parseFloat(document.getElementById('txAmount').value);
  const type   = document.getElementById('txType').value;
  if (!name || !amount || amount <= 0) { showToast('Please fill in all transaction fields.', 'red'); return; }
  const now = new Date();
  const d = `${now.getDate()} ${['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][now.getMonth()]} ${now.getFullYear()}`;
  const tx = { name, date: d, amount: type === 'debit' ? -amount : amount, type, icon: type === 'credit' ? 'gold' : 'b' };
  transactions.unshift(tx);
  saveData(STORAGE_KEYS.transactions, transactions);
  renderTransactions();
  updateRevenue();
  closeModal('incomeModal');
  document.getElementById('txName').value='';
  document.getElementById('txAmount').value='';
  showToast(`Transaction recorded: ${formatINR(amount)} ${type}`, 'gold');
  addNotification('Transaction Recorded', `${name} â€” ${type === 'credit' ? '+' : 'âˆ’'}${formatINR(amount)}`);
  addActivityItem({icon:'dollar', iconClass:'gold', title:'Transaction Recorded', detail:`${name}: ${type === 'credit'?'+':'âˆ’'}${formatINR(amount)}`, time:'Just now'});
}
function updateRevenue() {
  const total = transactions.filter(t => t.type === 'credit').reduce((s,t) => s + Math.abs(t.amount), 0);
  const formatted = total >= 100000 ? `â‚¹${(total/100000).toFixed(1)}L` : `â‚¹${total.toLocaleString('en-IN')}`;
  document.getElementById('totalRevenue').textContent = formatted;
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
// ACTIVITY
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
const iconSVGs = {
  'user-plus': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="14" height="14"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>`,
  'dollar':    `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="14" height="14"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>`,
  'target':    `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="14" height="14"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>`,
  'star':      `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="14" height="14"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`,
  'users':     `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="14" height="14"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>`
};
function renderActivity() {
  const list = document.getElementById('activityList');
  list.innerHTML = '';
  activities.slice(0,5).forEach((a, idx) => {
    const div = document.createElement('div');
    div.className = 'activity-item';
    div.innerHTML = `
      <div class="activity-dot ${a.iconClass}">${iconSVGs[a.icon]||''}</div>
      <div class="activity-text">
        <strong>${a.title}</strong>
        <p>${a.detail}</p>
      </div>
      <div class="activity-time">${a.time}</div>`;
    list.appendChild(div);
  });
}
function addActivityItem(item) {
  activities.unshift(item);
  if (activities.length > 20) activities.pop();
  saveData(STORAGE_KEYS.activity, activities);
  renderActivity();
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
// TOP PERFORMERS
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function renderTopPerformers() {
  const sorted = [...members].filter(m=>m.status==='active').sort((a,b)=>b.downline-a.downline).slice(0,4);
  const max = sorted[0]?.downline || 1;
  const list = document.getElementById('topPerformersList');
  list.innerHTML = '';
  sorted.forEach((m, i) => {
    const pct = Math.round((m.downline / max) * 100);
    const medals = ['ðŸ¥‡','ðŸ¥ˆ','ðŸ¥‰','ðŸŽ–ï¸'];
    const div = document.createElement('div');
    div.style.cssText = 'display:flex;flex-direction:column;gap:4px';
    div.innerHTML = `
      <div style="display:flex;align-items:center;gap:10px;justify-content:space-between">
        <div style="display:flex;align-items:center;gap:8px">
          <span style="font-size:14px">${medals[i]}</span>
          <div class="member-av ${m.avatarClass}" style="width:26px;height:26px;font-size:10px">${getInitials(m.name)}</div>
          <div>
            <div style="font-size:12.5px;font-weight:600;color:var(--text)">${m.name}</div>
            <div style="font-size:10.5px;color:var(--text-light)">${m.level} Â· ${m.downline} downline</div>
          </div>
        </div>
        <span style="font-size:12px;font-weight:700;color:var(--gold)">${pct}%</span>
      </div>
      <div style="height:4px;background:var(--border);border-radius:99px;overflow:hidden">
        <div style="height:100%;width:${pct}%;background:linear-gradient(90deg,var(--green),var(--gold));border-radius:99px;transition:width 0.6s ease"></div>
      </div>`;
    list.appendChild(div);
  });
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
// CHART
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
let chartInstance = null;
function buildChart() {
  const canvas = document.getElementById('growthChart');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  const green = isDark ? '#4A7C59' : '#2D5A40';
  const gold  = '#B8962E';
  const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(27,58,45,0.06)';
  const textColor = isDark ? '#5E8070' : '#8A9E92';

  const gradGreen = ctx.createLinearGradient(0,0,0,200);
  gradGreen.addColorStop(0, isDark ? 'rgba(74,124,89,0.4)' : 'rgba(45,90,64,0.25)');
  gradGreen.addColorStop(1, 'rgba(74,124,89,0)');
  const gradGold = ctx.createLinearGradient(0,0,0,200);
  gradGold.addColorStop(0, 'rgba(184,150,46,0.25)');
  gradGold.addColorStop(1, 'rgba(184,150,46,0)');

  if (chartInstance) chartInstance.destroy();
  chartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Week 1','Week 2','Week 3','Week 4','Week 5'],
      datasets: [
        {
          label: 'Revenue (â‚¹K)',
          data: [82, 97, 88, 115, 143],
          borderColor: green, backgroundColor: gradGreen,
          borderWidth: 2.5, pointBackgroundColor: green,
          pointBorderColor: isDark?'#1E2E26':'#fff',
          pointBorderWidth: 2, pointRadius: 5,
          fill: true, tension: 0.4,
        },
        {
          label: 'New Members',
          data: [2, 3, 1, 4, 2],
          borderColor: gold, backgroundColor: gradGold,
          borderWidth: 2, pointBackgroundColor: gold,
          pointBorderColor: isDark?'#1E2E26':'#fff',
          pointBorderWidth: 2, pointRadius: 5,
          fill: true, tension: 0.4,
          yAxisID: 'y2',
        }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: {
          labels: { color: textColor, font: { family: 'Outfit', size: 11 }, boxWidth: 12, padding: 16 }
        },
        tooltip: {
          backgroundColor: isDark?'#1E2E26':'#fff',
          titleColor: isDark?'#E8F0EC':'#1A2820',
          bodyColor: textColor,
          borderColor: isDark?'#2A4035':'#DDE8DF',
          borderWidth: 1,
          padding: 12,
          titleFont: { family:'Playfair Display', size:13 },
          bodyFont: { family:'Outfit', size:11 }
        }
      },
      scales: {
        x: { grid: { color: gridColor }, ticks: { color: textColor, font:{family:'Outfit',size:10} } },
        y: {
          position: 'left', grid: { color: gridColor },
          ticks: { color: textColor, font:{family:'Outfit',size:10}, callback: v => `â‚¹${v}K` }
        },
        y2: {
          position: 'right', grid: { display: false },
          ticks: { color: gold, font:{family:'Outfit',size:10}, stepSize:1 }
        }
      }
    }
  });
}
function rebuildChart() {
  if (document.getElementById('growthChart')) setTimeout(buildChart, 50);
  if (typeof rebuildReportChart === 'function') rebuildReportChart();
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
// LIVE CLOCK
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function updateClock() {
  const now = new Date();
  const fmt = now.toLocaleTimeString('en-IN', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
  const el = document.getElementById('liveTime');
  if (el) el.textContent = fmt;

  const welcomeClock = document.getElementById('welcomeClock');
  if (welcomeClock) welcomeClock.textContent = now.toLocaleTimeString('en-IN', {hour:'2-digit',minute:'2-digit'});
}

function initWelcomeWidget() {
  const greetingEl = document.getElementById('welcomeGreeting');
  const dateEl = document.getElementById('welcomeDate');
  if (!greetingEl && !dateEl) return;

  const now = new Date();
  const hour = now.getHours();
  let greeting = 'Good evening';
  if (hour < 12) greeting = 'Good morning';
  else if (hour < 17) greeting = 'Good afternoon';
  if (greetingEl) greetingEl.textContent = greeting;

  if (dateEl) {
    dateEl.textContent = now.toLocaleDateString('en-IN', {
      weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    });
  }
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
// INIT
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
(function init() {
  applyDarkMode();
  if (document.getElementById('memberTableBody')) {
    renderMembers(members);
    updateCounts();
    renderTransactions();
    updateRevenue();
    renderActivity();
    renderTopPerformers();
  }
  if (document.getElementById('growthChart')) setTimeout(buildChart, 100);
  if (typeof initReportsPage === 'function') initReportsPage();
  if (typeof initChangePasswordPage === 'function') initChangePasswordPage();
  initWelcomeWidget();
  updateClock();
  setInterval(updateClock, 1000);
})();

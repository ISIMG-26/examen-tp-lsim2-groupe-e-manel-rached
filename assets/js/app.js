// ── API Configuration ──────────────────────────────────────
const API = {
  base: 'http://localhost/covoiturage-api',
  auth: {
    login:    '/auth/login.php',
    register: '/auth/register.php',
    profile:  '/auth/getProfile.php',
  },
  rides: {
    get:      '/rides/getRides.php',
    post:     '/rides/post.php',
    book:     '/rides/bookRide.php',
    seats:    '/rides/getAvailableSeats.php',
    bookings: '/rides/getBookings.php',
    userRides:'/rides/getUserRides.php',
  }
};

function apiUrl(path) { return API.base + path; }

// Session helpers 
const Session = {
  get()    { try { return JSON.parse(localStorage.getItem('covoit_user') || 'null'); } catch { return null; } },
  set(u)   { localStorage.setItem('covoit_user', JSON.stringify(u)); },
  clear()  { localStorage.removeItem('covoit_user'); },
  isAuth() { return !!this.get(); },
  id()     { return this.get()?.id || null; },
  name()   { return this.get()?.full_name || 'Utilisateur'; },
};

//  Fetch wrapper 
async function apiFetch(path, options = {}) {
  const url = apiUrl(path);
  const defaults = { headers: { 'Content-Type': 'application/json' } };
  const cfg = { ...defaults, ...options };
  if (cfg.body && typeof cfg.body === 'object') cfg.body = JSON.stringify(cfg.body);
  try {
    const res  = await fetch(url, cfg);
    const data = await res.json();
    return data;
  } catch (err) {
    console.error('apiFetch error:', err);
    return { success: false, message: 'Erreur réseau. Vérifiez votre connexion.' };
  }
}

//  Toast notifications 
function showToast(message, type = 'info', duration = 3500) {
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
  }
  const icons = { success: '✅', error: '❌', info: 'ℹ️' };
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<span>${icons[type] || 'ℹ️'}</span><span>${message}</span>`;
  container.appendChild(toast);
  setTimeout(() => {
    toast.classList.add('hide');
    setTimeout(() => toast.remove(), 350);
  }, duration);
}

// Format helpers 
function fmtDate(d) {
  if (!d) return '';
  const dt = new Date(d.replace(' ', 'T'));
  if (isNaN(dt)) return d;
  return dt.toLocaleDateString('fr-TN', { day:'2-digit', month:'short', year:'numeric' });
}
function fmtTime(t) {
  if (!t) return '';
  return t.length >= 5 ? t.substring(0, 5) : t;
}
function fmtPrice(p) {
  return parseFloat(p || 0).toFixed(2) + ' TND';
}
function escHtml(s) {
  const d = document.createElement('div');
  d.textContent = s || '';
  return d.innerHTML;
}
function initials(name) {
  if (!name) return '?';
  return name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
}

// Navigation — all pages at same level 
function buildNav(activePage) {
  const user   = Session.get();
  const isAuth = !!user;

  const links = isAuth ? [
    { href: 'index.html',        label: '🏠 Accueil',     key: 'home'     },
    { href: 'myrides.html',      label: '🚗 Mes trajets', key: 'myrides'  },
    { href: 'publish.html',      label: '➕ Publier',     key: 'publish'   },
    { href: 'profile.html',      label: '👤 Profil',      key: 'profile'   },
  ] : [
    { href: 'index.html',        label: '🏠 Accueil',     key: 'home'     },
    { href: 'login.html',        label: '🔑 Connexion',   key: 'login'    },
    { href: 'register.html',     label: '📝 S\'inscrire', key: 'register' },
  ];

  const navEl = document.getElementById('main-nav');
  if (!navEl) return;

  navEl.innerHTML = `
    <nav class="navbar">
      <div class="nav-inner">
        <a class="nav-logo" href="index.html">
          <span class="logo-icon">🚗</span>
          <span>Covoiturage TN</span>
        </a>
        <div class="nav-links">
          ${links.map(l =>
            `<a href="${l.href}" class="${l.key === activePage ? 'active' : ''}">${l.label}</a>`
          ).join('')}
        </div>
        <div class="nav-actions">
          ${isAuth
            ? `<div class="nav-user">
                 <div class="nav-avatar">${initials(user.full_name)}</div>
               </div>
               <button class="btn btn-outline btn-sm" onclick="logout()">Déconnexion</button>`
            : `<a href="login.html" class="btn btn-primary btn-sm">Connexion</a>`
          }
        </div>
        <button class="nav-hamburger" onclick="toggleMobileNav()" aria-label="Menu">
          <span></span><span></span><span></span>
        </button>
      </div>
      <div class="nav-mobile" id="nav-mobile">
        ${links.map(l =>
          `<a href="${l.href}" class="${l.key === activePage ? 'active' : ''}">${l.label}</a>`
        ).join('')}
        ${isAuth
          ? `<button class="btn btn-outline btn-sm" onclick="logout()"
               style="margin-top:8px;width:100%">Déconnexion</button>`
          : ''}
      </div>
    </nav>`;
}

function toggleMobileNav() {
  document.getElementById('nav-mobile')?.classList.toggle('open');
}

function logout() {
  Session.clear();
  window.location.href = 'login.html';
}

//  Auth guard 
function requireAuth() {
  if (!Session.isAuth()) {
    window.location.href = 'login.html';
    return false;
  }
  return true;
}

//  City coordinates (mirrors MapActivity.java) 
const CITY_COORDS = {
  'tunis':    [36.8065, 10.1815],
  'sfax':     [34.7398, 10.7600],
  'sousse':   [35.8256, 10.6369],
  'monastir': [35.7643, 10.8113],
  'gabes':    [33.8828, 10.0982],
  'bizerte':  [37.2746, 9.8739],
  'nabeul':   [36.4511, 10.7356],
  'kairouan': [35.6781, 10.0963],
  'gafsa':    [34.4234, 8.7843],
  'djerba':   [33.8627, 10.8585],
  'medenine': [33.3549, 10.4975],
  'mahdia':   [35.5047, 11.0622],
};
function getCityCoords(name) {
  return CITY_COORDS[(name || '').toLowerCase()] || [36.8065, 10.1815];
}

//  Modal helpers 
function openModal(id)  { document.getElementById(id)?.classList.add('open'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('open'); }

document.addEventListener('click', e => {
  if (e.target.classList.contains('modal-overlay')) e.target.classList.remove('open');
});

//  Validators 
const Validators = {
  required: v => (v + '').trim() !== '',
  email:    v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v),
  minLen:   (v, n) => (v + '').trim().length >= n,
  phone:    v => /^\d{8}$/.test((v + '').trim()),
  positive: v => parseFloat(v) > 0,
  nonNeg:   v => parseFloat(v) >= 0,
};

// Shared ride card builder 
function buildRideCard(ride, onClick, mode = 'search') {
  const seats      = parseInt(ride.available_seats ?? ride.seats ?? 0);
  const seatsBooked= parseInt(ride.seats_booked ?? 0);
  const price      = parseFloat(ride.price ?? 0);

  let seatsBadge;
  if (mode === 'myrides-booked') {
    seatsBadge = `<span class="badge badge-blue">👨‍👩‍👧 ${seatsBooked} réservé${seatsBooked>1?'s':''}</span>`;
  } else if (seats <= 0) {
    seatsBadge = `<span class="badge badge-red">❌ Complet</span>`;
  } else {
    seatsBadge = `<span class="badge badge-blue">💺 ${seats} place${seats>1?'s':''}</span>`;
  }

  const statusBadge = ride.status === 'active'
    ? `<span class="badge badge-green">Actif</span>`
    : ride.status === 'full'
    ? `<span class="badge badge-red">Complet</span>`
    : '';

  return `
    <article class="ride-card" tabindex="0" role="button"
             onclick="${onClick}" onkeydown="if(event.key==='Enter')${onClick}">
      <div class="ride-card-header">
        <div class="ride-route">
          <div class="ride-route-line">
            <div class="dot"></div>
            <div class="route-line"></div>
            <div class="dot dest"></div>
          </div>
          <div class="ride-cities">
            <span class="ride-city">${escHtml(ride.from_city || ride.from || '')}</span>
            <span class="ride-city">${escHtml(ride.to_city   || ride.to   || '')}</span>
          </div>
        </div>
        <div class="ride-price">${fmtPrice(price)}</div>
      </div>
      <div class="ride-card-footer">
        <span class="ride-meta-item">📅 ${fmtDate(ride.ride_date || ride.date)}</span>
        <span class="ride-meta-item">⏰ ${fmtTime(ride.ride_time || ride.time)}</span>
        <span class="ride-meta-item">👤 ${escHtml(ride.driver_name || ride.driverName || '')}</span>
        ${seatsBadge}
        ${statusBadge}
      </div>
    </article>`;
}

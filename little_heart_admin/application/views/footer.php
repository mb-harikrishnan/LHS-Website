    </div><!-- /main -->


<style>
    .sidebar {
    background: linear-gradient(180deg, #1e1b4b, #312e81);
}

/* Grid effect */
.sidebar-grid {
    background-image:
        linear-gradient(rgb(255 255 255 / 4%) 1px, transparent 1px),
        linear-gradient(90deg, rgb(255 255 255 / 4%) 1px, transparent 1px);
}

/* Sidebar circles */
.sidebar::after,
.sidebar::before {
    background: rgba(255, 255, 255, 0.05);
}

/* User box */
.sidebar-user {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(10px);
}

/* Username */
.user-name {
    color: #fff;
}

/* Section title */
.nav-section-label {
    color: #c7d2fe;
    font-weight: 700;
}

/* Menu items */
.nav-item {
    color: #fff;
    font-weight: 700;
    transition: 0.3s ease;
}

/* Active menu */
.nav-item.active {
    background: linear-gradient(90deg, #6366f1, #8b5cf6);
    color: white;
    border-left: 3px solid #c4b5fd;
    box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
}

/* Hover effect */
.nav-item:hover {
    background: rgba(255,255,255,0.08);
    color: #c4b5fd;
    transform: translateX(3px);
}

/* Dropdown */
.nav-dropdown-items {
    background: rgba(255,255,255,0.06);
    border-radius: 14px;
}

.nav-dropdown-items a {
    color: #e5e7eb;
    font-weight: 600;
}

.nav-dropdown-items a:hover {
    color: #c4b5fd;
    background: rgba(255,255,255,0.08);
    padding-left: 32px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Copy Address
    window.copyAddr = function (btn) {
        navigator.clipboard.writeText('0x79dffca3f3382888eb136c5b9fb0482f18382772')
        .then(() => {
            btn.textContent = 'âœ… Copied!';
            setTimeout(() => { btn.textContent = 'ðŸ“‹ COPY'; }, 2000);
        });
    };

 

    // Gauge Animation
    const fill = document.querySelector('path[stroke="url(#gaugeGrad)"]');
    if (fill) {
        setTimeout(() => {
            fill.style.strokeDashoffset = '182';
        }, 300);
    }

    // Live Ticker
    const tickers = document.querySelectorAll('.tick-chg');
    if (tickers.length > 0) {
        setInterval(() => {
            tickers.forEach(t => {
                let v = parseFloat(t.textContent.replace('%','')) || 0;
                let d = (Math.random() - 0.48) * 0.15;
                let n = (v + d).toFixed(2);

                t.textContent = (n >= 0 ? '+' : '') + n + '%';
                t.className = 'tick-chg' + (n < 0 ? ' dn' : '');
            });
        }, 3000);
    }

    // Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('openSidebar');
    const closeBtn = document.getElementById('closeSidebar');

    if (openBtn && closeBtn && sidebar) {
        openBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.add('show');
        });

        closeBtn.addEventListener('click', () => {
            sidebar.classList.remove('show');
        });

        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992 &&
                !sidebar.contains(e.target) &&
                !openBtn.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    }

    // Notification Dropdown
    const notifyTrigger = document.getElementById('notifyTrigger');
    const notifyBox = document.getElementById('notifyBox');

    if (notifyTrigger && notifyBox) {
        notifyTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            notifyBox.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!notifyTrigger.contains(e.target)) {
                notifyBox.classList.remove('show');
            }
        });
    }

    // Sidebar Dropdown
    document.querySelectorAll('.nav-item.has-dropdown').forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            this.classList.toggle('dropdown-active');
        });
    });

});
</script>
    
    
    <script>
document.addEventListener("DOMContentLoaded", function () {

    const navItems = document.querySelectorAll(".nav-item");
    const dropdownTriggers = document.querySelectorAll(".has-dropdown");
    const dropdownLinks = document.querySelectorAll(".nav-dropdown-items a");

    // ðŸ”¹ Active class for main nav items
    navItems.forEach(item => {
        item.addEventListener("click", function () {

            // Remove all active
            navItems.forEach(i => i.classList.remove("active"));

            // Add active to clicked
            this.classList.add("active");
        });
    });

    // ðŸ”¹ Dropdown toggle
    dropdownTriggers.forEach(trigger => {
        trigger.addEventListener("click", function (e) {
            e.stopPropagation();

            const dropdownId = this.getAttribute("data-dropdown");
            const dropdown = document.getElementById("dropdown-" + dropdownId);

            // Close other dropdowns
            document.querySelectorAll(".nav-dropdown-items").forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove("open");
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle("open");
        });
    });

    // ðŸ”¹ Active state for dropdown links
    dropdownLinks.forEach(link => {
        link.addEventListener("click", function () {

            // Remove all active
            navItems.forEach(i => i.classList.remove("active"));

            // Set parent active
            const parentItem = this.closest(".nav-item-wrapper").querySelector(".nav-item");
            parentItem.classList.add("active");
        });
    });

});
</script>


<script>
    document.querySelectorAll('.has-dropdown').forEach(item => {
    item.addEventListener('click', function() {
        const dropdownId = this.getAttribute('data-dropdown');
        const dropdownMenu = document.getElementById('dropdown-' + dropdownId);
        
        // Toggle the display
        if (dropdownMenu.style.display === 'flex') {
            dropdownMenu.style.display = 'none';
            this.classList.remove('active');
        } else {
            dropdownMenu.style.display = 'flex';
            this.classList.add('active');
        }
    });
});
</script>


    
</body>
</html>

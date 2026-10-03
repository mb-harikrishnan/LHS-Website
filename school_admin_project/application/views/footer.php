
      <!-- Footer -->
      <footer class="page-foot">
        © 2026 Vidyodaya Public School — Admin Panel
      </footer>
    </div>
  </div>

  <!-- Pure UI Interactivity Script (No auth-blocking, no dynamic rendering needed) -->
  <script>
    (function () {
      // Submenus
      document.querySelectorAll('[data-submenu]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var grp = btn.closest('.side-group');
          grp.classList.toggle('open');
        });
      });

      // Mobile drawer
      var toggle = document.getElementById('menu-toggle');
      var drawer = document.getElementById('drawer');
      var backdrop = document.getElementById('drawer-backdrop');
      var closeBtn = document.getElementById('drawer-close');
      if (toggle && drawer && backdrop) {
        toggle.addEventListener('click', function () {
          drawer.classList.add('open');
          backdrop.classList.add('show');
        });
        function closeDrawer() {
          drawer.classList.remove('open');
          backdrop.classList.remove('show');
        }
        backdrop.addEventListener('click', closeDrawer);
        if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
      }

      // Profile dropdown
      var pbtn = document.getElementById('profile-btn');
      var pdd = document.getElementById('profile-dropdown');
      if (pbtn && pdd) {
        pbtn.addEventListener('click', function (e) {
          e.stopPropagation();
          pdd.classList.toggle('open');
        });
        document.addEventListener('click', function () {
          pdd.classList.remove('open');
        });
      }
    })();
  </script>
</body>
</html>

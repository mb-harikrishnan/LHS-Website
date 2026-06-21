    <!-- Page Footer (inside page-content) -->
    <footer class="page-footer">
      <div class="footer-brand">
        <svg width="16" height="16" viewBox="0 0 80 80" fill="none">
          <circle cx="40" cy="40" r="36" fill="rgba(184,150,46,0.2)" stroke="rgba(184,150,46,0.4)" stroke-width="1"/>
          <path d="M40 20 C36 28 28 32 20 32 C28 32 32 40 28 48 C34 42 40 44 40 44" fill="rgba(184,150,46,0.7)"/>
          <path d="M40 20 C44 28 52 32 60 32 C52 32 48 40 52 48 C46 42 40 44 40 44" fill="rgba(184,150,46,0.7)"/>
        </svg>
        Little Flower School
      </div>
      <span>Â© 2026  Little Flower School. All rights reserved.</span>
      <span id="liveTime" style="font-family:'Playfair Display',serif;font-style:italic;color:var(--gold)"></span>
    </footer>

    </div><!-- /page-content -->

  </main><!-- /main -->
</div><!-- /layout -->

<script src="assets/js/main.js"></script>
<?php if (!empty($pageScripts)) { foreach ($pageScripts as $script) { ?>
<script src="<?php echo htmlspecialchars($script); ?>"></script>
<?php } } ?>
</body>
</html>

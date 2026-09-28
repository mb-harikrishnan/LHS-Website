    <!-- Page Footer (inside page-content) -->
    <footer class="page-footer">
      <div class="footer-brand">
        <svg width="16" height="16" viewBox="0 0 80 80" fill="none">
          <circle cx="40" cy="40" r="36" fill="rgba(37,99,235,0.08)" stroke="rgba(37,99,235,0.25)" stroke-width="1"/>
          <path d="M40 20 C36 28 28 32 20 32 C28 32 32 40 28 48 C34 42 40 44 40 44" fill="rgba(37,99,235,0.55)"/>
          <path d="M40 20 C44 28 52 32 60 32 C52 32 48 40 52 48 C46 42 40 44 40 44" fill="rgba(37,99,235,0.55)"/>
        </svg>
        Little Heart School
      </div>
      <span>&copy; <?php echo date('Y'); ?> Little Heart School. All rights reserved.</span>
      <span id="liveTime" style="font-style:italic;color:var(--brand-600);font-weight:500"></span>
    </footer>

    </div><!-- /page-content -->

  </main><!-- /main -->
</div><!-- /layout -->

<script src="<?php echo base_url()?>assets/js/main.js"></script>
<?php if (!empty($pageScripts)) { foreach ($pageScripts as $script) { ?>
<script src="<?php echo htmlspecialchars($script); ?>"></script>
<?php } } ?>
</body>
</html>

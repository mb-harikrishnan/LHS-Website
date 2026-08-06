<?php
$pageTitle = 'Change Password';
$breadcrumb = 'Change Password';
$activePage = 'change-password';
$showGlobalSearch = false;
$pageScripts = ['assets/js/change-password.js'];



?>




<div class="pw-page-wrap">
  <div class="pw-hero-grid">



    <!-- Right: Form Card -->
    <div class="pw-form-card">
      <h3 class="pw-form-title">Set New Password</h3>
      <p class="pw-form-sub">Enter your current password, then choose a new secure password.</p>

      <div class="pw-success-banner" id="pwSuccessBanner">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
        <span>Password updated successfully! Please use your new password next time you sign in.</span>
      </div>

      <form id="changePasswordForm" method="POST" action="<?php echo base_url('change_old_password');?>" >

          <div class="pw-field">
            <label for="currentPassword">Current Password</label>
            <div class="pw-input-wrap">
              <input type="password" id="currentPassword" name="currentPassword" placeholder="Enter current password" autocomplete="current-password" >
              <button type="button" class="pw-toggle"  aria-label="Show password">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            <?php echo form_error('currentPassword','<span class="text-danger">','</span>'); ?>
          </div>

        <div class="pw-field">
          <label for="newPassword">New Password</label>
          <div class="pw-input-wrap">
            <input type="password" id="newPassword" name="newPassword" placeholder="Create a strong password" autocomplete="new-password"  oninput="checkPasswordStrength()">
            <button type="button" class="pw-toggle"  aria-label="Show password">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
            <?php echo form_error('newPassword','<span class="text-danger">','</span>'); ?>
          </div>
          
        </div>

        <div class="pw-field">
          <label for="confirmPassword">Confirm New Password</label>
          <div class="pw-input-wrap">
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter new password" autocomplete="new-password"  oninput="checkPasswordMatch()">
            <button type="button" class="pw-toggle" onclick="togglePw('confirmPassword', this)" aria-label="Show password">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
            <?php echo form_error('confirmPassword','<span class="text-danger">','</span>'); ?>

        </div>

        

        <div class="pw-actions">
          <button type="button" class="btn btn-ghost" onclick="resetPasswordForm()">Cancel</button>
          <button type="submit" class="btn btn-gold" id="submitPwBtn">
            Update Password
          </button>
        </div>

      </form>
    </div>

  </div>
</div>

<style>
.validation-error{
    color:red;
    font-size:13px;
    margin-top:5px;
    display:block;
}




.pw-strength-bar{
    display:flex;
    gap:6px;
    margin-top:10px;
}

.pw-strength-seg{
    height:6px;
    flex:1;
    background:#e5e5e5;
    border-radius:20px;
    transition:0.4s ease;
}

/* WEAK */
.pw-strength-seg.weak{
    background:#ff4d4f;
}

/* MEDIUM */
.pw-strength-seg.medium{
    background:#faad14;
}

/* STRONG */
.pw-strength-seg.strong{
    background:#52c41a;
}

#strengthText{
    font-size:13px;
    font-weight:600;
}

#strengthText.weak{
    color:#ff4d4f;
}

#strengthText.medium{
    color:#faad14;
}

#strengthText.strong{
    color:#52c41a;
}


	.text-danger{
    color:red !important;
    font-size:13px;
}
</style>


<script src="<?php echo JS_PATH ?>jquery-3.6.0.min.js"></script>
<script src="<?php echo JS_PATH ?>jquery.validate.min.js"></script>
<script src="<?php echo JS_PATH ?>additional-methods.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
$(document).ready(function () {

    // PASSWORD TOGGLE
    $(".pw-toggle").click(function () {

        let input = $(this).siblings("input");

        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }

    });

    // VALIDATION
    $("#changePasswordForm").validate({

        rules: {

            currentPassword: {
                required: true,
                remote: {
                    url: "<?php echo base_url('check_current_password'); ?>",
                    type: "post",
                    data: {
                        currentPassword: function () {
                            return $("#currentPassword").val();
                        }
                    }
                }
            },

            newPassword: {
                required: true,
                minlength: 8,
                pwcheck: true
            },

            confirmPassword: {
                required: true,
                equalTo: "#newPassword"
            }

        },

        messages: {

            currentPassword: {
                required: "Please enter current password",
                remote: "Current password is incorrect"
            },

            newPassword: {
                required: "Please enter new password",
                minlength: "Password must contain minimum 8 characters",
                pwcheck: "Password must contain uppercase, lowercase, number and special character"
            },

            confirmPassword: {
                required: "Please confirm your password",
                equalTo: "Passwords do not match"
            }

        },

        errorElement: "span",
        errorClass: "validation-error",

        submitHandler: function (form) {
            form.submit();
        }

    });

   

});



// PASSWORD MATCH
function checkPasswordMatch() {

    let newPassword = $("#newPassword").val();
    let confirmPassword = $("#confirmPassword").val();

    if (newPassword !== "" && newPassword === confirmPassword) {
        $("#chk-match").addClass("active");
    } else {
        $("#chk-match").removeClass("active");
    }

}


// RESET FORM
function resetPasswordForm() {

    $("#changePasswordForm")[0].reset();

    $(".pw-check-item").removeClass("active");
    $(".pw-strength-seg").removeClass("active");

    $("#strengthText").text("");

}
</script>



<?php if($this->session->flashdata('success')) { ?>

<script>
Swal.fire({
    toast: true,
    position: 'top-end', // upper right corner
    icon: 'success',
    title: '<?php echo $this->session->flashdata('success'); ?>',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});
</script>

<?php } ?>


<?php if($this->session->flashdata('error')) { ?>

<script>
Swal.fire({
    toast: true,
    position: 'top-end', // upper right corner
    icon: 'error',
    title: '<?php echo $this->session->flashdata('error'); ?>',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});
</script>

<?php } ?>


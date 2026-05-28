<?php 
$pageTitle = "Change Password";
include 'header.php'; 
?>
<?php include 'sidebar.php'; ?>

<div class="main">
    <?php include 'topbar.php'; ?>

    <div class="content">
        <div class="password-container">
            <!-- Main Grid -->
            <div class="card password-card">
                <div class="card-accent-top"></div>
                <div class="card-pad">
                    <div class="password-layout">
                        
                        <!-- Left: Form -->
                        <div class="password-form-side">
                            <div class="form-header">
                                <div class="lock-icon">🔒</div>
                                <h2>Security Credentials</h2>
                                <p>Update your password to keep your account secure</p>
                            </div>

                            <form action="#" method="POST" class="auth-form">
                                <div class="form-group">
                                    <label>Old Password*</label>
                                    <div class="input-wrapper">
                                        <input type="password" id="old_pass" placeholder="Enter current password" required>
                                        <button type="button" class="pass-toggle" onclick="togglePass('old_pass')">
                                            <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>New Password*</label>
                                    <div class="input-wrapper">
                                        <input type="password" id="new_pass" placeholder="Create new password" required>
                                        <button type="button" class="pass-toggle" onclick="togglePass('new_pass')">
                                            <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Confirm Password*</label>
                                    <div class="input-wrapper">
                                        <input type="password" id="conf_pass" placeholder="Re-type new password" required>
                                        <button type="button" class="pass-toggle" onclick="togglePass('conf_pass')">
                                            <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-buttons">
                                    <button type="submit" class="btn-submit">Update Password</button>
                                    <button type="button" class="btn-cancel" onclick="window.history.back()">Cancel</button>
                                </div>
                            </form>
                        </div>

                        <!-- Right: Guidelines -->
                        <div class="password-guide-side">
                            <div class="guide-box">
                                <h3 class="guide-title">Password Guidelines</h3>
                                <ul class="guide-list">
                                    <li>
                                        <div class="check-icon">✓</div>
                                        <span>Must be at least 8 characters.</span>
                                    </li>
                                    <li>
                                        <div class="check-icon">✓</div>
                                        <span>Include uppercase, lowercase and numbers.</span>
                                    </li>
                                    <li>
                                        <div class="check-icon">✓</div>
                                        <span>Confirm password must match exactly.</span>
                                    </li>
                                    <li>
                                        <div class="check-icon">✓</div>
                                        <span>Avoid using common passwords (e.g., "123456").</span>
                                    </li>
                                    <li>
                                        <div class="check-icon">✓</div>
                                        <span>Change your password regularly for security.</span>
                                    </li>
                                </ul>
                                
                                <div class="security-tip">
                                    <div class="tip-icon">💡</div>
                                    <p>Tip: A strong password combined with 2FA provides the best protection for your assets.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .password-container {
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .password-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border-radius: 24px;
            border: 1px solid var(--term-border);
            box-shadow: 0 25px 50px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .password-layout {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 40px;
            padding: 20px;
        }

        /* Form Side */
        .form-header {
            margin-bottom: 30px;
        }

        .lock-icon {
            font-size: 32px;
            margin-bottom: 12px;
            filter: drop-shadow(0 4px 10px rgba(22, 163, 74, 0.2));
        }

        .form-header h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--gray900);
            letter-spacing: -0.5px;
        }

        .form-header p {
            font-size: 13px;
            color: var(--gray500);
            margin-top: 4px;
        }

        .auth-form .form-group {
            margin-bottom: 24px;
        }

        .auth-form label {
            display: block;
            font-size: 10px;
            font-weight: 800;
            color: var(--gray400);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 8px;
            padding-left: 2px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper input {
            width: 100%;
            height: 52px;
            background: white;
            border: 1.5px solid var(--gray200);
            border-radius: 14px;
            padding: 0 50px 0 18px;
            font-size: 15px;
            color: var(--gray800);
            transition: all 0.3s;
            outline: none;
        }

        .input-wrapper input:focus {
            border-color: var(--g500);
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
        }

        .pass-toggle {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: var(--gray400);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .pass-toggle:hover {
            color: var(--g600);
        }

        .form-buttons {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--g600), var(--g800));
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(22, 163, 74, 0.2);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(22, 163, 74, 0.3);
        }

        .btn-cancel {
            background: white;
            color: var(--gray600);
            border: 1.5px solid var(--gray200);
            padding: 14px 28px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: var(--gray50);
            border-color: var(--gray300);
        }

        /* Guide Side */
        .password-guide-side {
            background: var(--g50);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid var(--g100);
        }

        .guide-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            font-weight: 800;
            color: var(--gray800);
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .guide-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .guide-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 13px;
            color: var(--gray600);
            line-height: 1.4;
        }

        .check-icon {
            width: 18px;
            height: 18px;
            background: var(--g500);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            flex-shrink: 0;
            margin-top: 1px;
            box-shadow: 0 3px 8px rgba(34, 197, 94, 0.3);
        }

        .security-tip {
            margin-top: 30px;
            padding-top: 24px;
            border-top: 1px dashed var(--g200);
            display: flex;
            gap: 12px;
        }

        .tip-icon {
            font-size: 18px;
        }

        .security-tip p {
            font-size: 12px;
            color: var(--gray500);
            font-style: italic;
        }

        @media (max-width: 992px) {
            .password-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        function togglePass(id) {
            const input = document.getElementById(id);
            const btn = event.currentTarget;
            if (input.type === "password") {
                input.type = "text";
                btn.style.color = "var(--g600)";
            } else {
                input.type = "password";
                btn.style.color = "var(--gray400)";
            }
        }
    </script>

    <?php include 'footer.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login</title>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap"
        rel="stylesheet">
    <style>
     :root {
    --green-50: #fff7ed;
    --green-100: #ffedd5;
    --green-200: #fed7aa;
    --green-300: #fdba74;
    --green-400: #fb923c;
    --green-500: #f97316;
    --green-600: #ea580c;
    --green-700: #c2410c;
    --green-800: #9a3412;
    --green-900: #7c2d12;

    --emerald-400: #fb923c;
    --emerald-500: #f97316;
    --emerald-600: #ea580c;

    --gold: #d97706;
    --gold-light: #fbbf24;

    --white: #ffffff;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-700: #334155;
    --gray-800: #1e293b;

    --shadow-green: 0 4px 24px rgba(249, 115, 22, 0.18);

    --shadow-card:
        0 8px 48px rgba(234, 88, 12, 0.12),
        0 2px 8px rgba(234, 88, 12, 0.08);
}

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--green-50);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }

        /* === BACKGROUND === */
        .bg-layer {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 0%, #ea580c(134, 239, 172, 0.35) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 85% 100%, #ea580c(74, 222, 128, 0.2) 0%, transparent 55%),
                radial-gradient(ellipse 50% 50% at 50% 50%, rgba(240, 253, 244, 1) 0%, rgba(220, 252, 231, 0.8) 100%);
        }

        .grid-overlay {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(22, 163, 74, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(22, 163, 74, 0.06) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        
        @keyframes floatCandle {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            15% {
                opacity: 1;
            }

            80% {
                opacity: 0.6;
            }

            100% {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        /* === LAYOUT === */
        .page {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            gap: 28px;
        }

        /* === LOGO === */
        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            animation: fadeDown 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .logo-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--green-600) 0%, var(--green-500) 50%, var(--emerald-400) 100%);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(22, 163, 74, 0.35), 0 2px 8px rgba(22, 163, 74, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.25);
            position: relative;
            overflow: hidden;
        }

        .logo-icon::before {
            content: '';
            position: absolute;
            top: -20px;
            left: -20px;
            right: -20px;
            height: 50%;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 50%;
        }

        .logo-letters {
            font-family: 'DM Serif Display', serif;
            font-size: 26px;
            color: white;
            letter-spacing: -1px;
            position: relative;
            z-index: 1;
        }

        .logo-name {
            font-family: 'DM Serif Display', serif;
            font-size: 13px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--green-700);
            font-weight: 400;
        }

        /* === CARD === */
        .card {
            width: 100%;
            max-width: 440px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 24px;
            border: 1px solid rgba(134, 239, 172, 0.4);
            box-shadow: var(--shadow-card), inset 0 1px 0 rgba(255, 255, 255, 0.9);
            padding: 40px 40px 36px;
            animation: fadeUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.1s both;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--green-400), var(--emerald-500), var(--green-600));
            border-radius: 24px 24px 0 0;
        }

        /* Subtle corner leaf decoration */
        .card::after {
            content: '';
            position: absolute;
            bottom: -40px;
            right: -40px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(134, 239, 172, 0.15) 0%, transparent 70%);
        }

        .card-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .card-title {
            font-family: 'DM Serif Display', serif;
            font-size: 26px;
            color: var(--gray-800);
            font-weight: 400;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .card-sub {
            margin-top: 6px;
            font-size: 13.5px;
            color: var(--gray-400);
            font-weight: 400;
        }

        /* === MARKET TICKER === */
        .ticker-bar {
            display: flex;
            gap: 16px;
            overflow: hidden;
            margin-bottom: 28px;
            padding: 10px 14px;
            background: var(--green-50);
            border: 1px solid var(--green-200);
            border-radius: 10px;
        }

        .ticker-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .ticker-label {
            font-weight: 600;
            color: var(--gray-700);
        }

        .ticker-price {
            color: var(--gray-500);
        }

        .ticker-change {
            font-weight: 600;
            color: var(--green-600);
        }

        .ticker-change.down {
            color: #dc2626;
        }

        .ticker-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--green-400);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(0.8);
            }
        }

        /* === FORM === */
        .form-group {
            margin-bottom: 16px;
        }

        .input-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--green-700);
            margin-bottom: 7px;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: var(--green-500);
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }

        .input-field {
            width: 100%;
            padding: 13px 14px 13px 42px;
            border: 1.5px solid var(--green-200);
            border-radius: 12px;
            background: var(--white);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--gray-800);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            box-shadow: 0 1px 4px rgba(22, 163, 74, 0.06);
        }

        .input-field::placeholder {
            color: var(--gray-300);
        }

        .input-field:focus {
            border-color: var(--green-500);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.12), 0 1px 4px rgba(22, 163, 74, 0.06);
            background: var(--green-50);
        }

        .eye-btn {
            position: absolute;
            right: 13px;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--gray-400);
            padding: 4px;
            display: flex;
            transition: color 0.2s;
        }

        .eye-btn:hover {
            color: var(--green-600);
        }

        /* === OPTIONS ROW === */
        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 18px 0 22px;
        }

        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .checkbox-wrap input[type="checkbox"] {
            appearance: none;
            width: 17px;
            height: 17px;
            border: 1.5px solid var(--green-300);
            border-radius: 5px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .checkbox-wrap input[type="checkbox"]:checked {
            background: var(--green-500);
            border-color: var(--green-500);
        }

        .checkbox-wrap input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1px;
            width: 6px;
            height: 10px;
            border: 2px solid white;
            border-top: none;
            border-left: none;
            transform: rotate(45deg);
        }

        .checkbox-label {
            font-size: 13px;
            color: var(--gray-500);
            font-weight: 400;
        }

        .forgot-link {
            font-size: 13px;
            font-weight: 600;
            color: var(--green-600);
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: var(--green-700);
        }

        /* === SUCCESS / ERROR BANNERS === */
        .banner {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 11px 14px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 18px;
        }

        .banner.success {
            background: linear-gradient(135deg, rgba(220, 252, 231, 0.9), rgba(187, 247, 208, 0.7));
            border: 1px solid var(--green-300);
            color: var(--green-700);
        }

        .banner svg {
            flex-shrink: 0;
        }

        /* === LOGIN BUTTON === */
        .login-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--green-500) 0%, var(--green-600) 50%, var(--emerald-600) 100%);
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.22, 1, 0.36, 1);
            box-shadow: 0 4px 20px rgba(22, 163, 74, 0.35), 0 1px 4px rgba(22, 163, 74, 0.2);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, transparent 50%);
            border-radius: inherit;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(22, 163, 74, 0.4), 0 2px 8px rgba(228, 135, 135, 0.2);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .btn-arrow {
            transition: transform 0.2s;
        }

        .login-btn:hover .btn-arrow {
            transform: translateX(4px);
        }

        /* === DIVIDER === */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--green-100);
        }

        .divider-text {
            font-size: 11px;
            color: var(--gray-400);
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* === SOCIAL BUTTONS === */
        .social-row {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
        }

        .social-btn {
            flex: 1;
            padding: 10px;
            border: 1.5px solid var(--green-200);
            border-radius: 10px;
            background: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--gray-600);
            transition: all 0.2s;
        }

        .social-btn:hover {
            border-color: var(--green-400);
            background: var(--green-50);
            color: var(--green-700);
        }

        /* === SIGNUP LINK === */
        .signup-row {
            text-align: center;
            font-size: 13.5px;
            color: var(--gray-400);
        }

        .signup-link {
            font-weight: 700;
            color: var(--green-600);
            text-decoration: none;
            margin-left: 5px;
            transition: color 0.2s;
        }

        .signup-link:hover {
            color: var(--green-700);
        }

        /* === STATS BAR === */
        .stats-bar {
            display: flex;
            gap: 24px;
            padding: 0 4px;
            animation: fadeUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.3s both;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-family: 'DM Serif Display', serif;
            font-size: 18px;
            color: var(--green-700);
            letter-spacing: -0.5px;
        }

        .stat-label {
            font-size: 10.5px;
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
            font-weight: 500;
        }

        .stat-divider {
            width: 1px;
            background: var(--green-200);
            align-self: stretch;
        }

        /* === FOOTER === */
        .footer {
            position: relative;
            z-index: 1;
            text-align: center;
            font-size: 11.5px;
            color: var(--gray-400);
            padding: 16px;
            animation: fadeUp 0.6s 0.5s both;
        }

        /* === ANIMATIONS === */
        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mini sparkline decoration */
        .sparkline {
            position: absolute;
            bottom: 28px;
            right: 28px;
            opacity: 0.15;
            pointer-events: none;
        }

        /* Loading state */
        .login-btn.loading {
            pointer-events: none;
        }

        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        .login-btn.loading .spinner {
            display: block;
        }

        .login-btn.loading .btn-text {
            display: none;
        }

        .login-btn.loading .btn-arrow {
            display: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .error {
    color: red;
    font-size: 13px;
    margin-top: 5px;
    display: block;
}
    </style>
</head>

<body>

    <!-- Background layers -->
    <div class="bg-layer"></div>
    <div class="grid-overlay"></div>
    <!-- <div class="candles-bg" id="candlesBg"></div> -->

    <div class="page">

        <!-- Logo -->
        <div class="logo-wrap">
            <img src="<?php echo base_url(); ?>/assets/images/school_logo.png" alt="School Logo">
        </div>

        <form method="post" action="<?php echo base_url('login_check'); ?>">

            <!-- Card -->
            <div class="card">

    

                <div class="card-header">
                    <h1 class="card-title">Login to Your Account</h1>
                </div>

                <!-- Live ticker -->



                <!-- Username -->
                <div class="form-group">
                    <label class="input-label" for="username">Username</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        <input class="input-field" type="text" id="username" name="username" placeholder="Enter your username"
                            autocomplete="off">
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="input-label" for="password">Password</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                        <input class="input-field" type="password" id="password" name="password" placeholder="Enter your password"
                            autocomplete="current-password">
                        <button class="eye-btn" id="eyeBtn" type="button" onclick="togglePassword()">
                            <svg id="eyeIcon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Options row -->
                <div class="options-row">
                    <!-- <label class="checkbox-wrap">
                        <input type="checkbox" id="remember">
                        <span class="checkbox-label">Remember Device</span>
                    </label> -->
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>

                <!-- Login button -->
                <button class="login-btn" id="loginBtn" onclick="handleLogin()">
                    <span class="spinner" id="spinner"></span>
                    <span class="btn-text">LOGIN</span>
                    <svg class="btn-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </button>

                <?php if($this->session->flashdata('error')): ?>
                    <p style="color:red;">
                        <?php echo $this->session->flashdata('error'); ?>
                    </p>
                <?php endif; ?>



            </div>

        </form>

        <!-- Footer -->
        <footer class="footer">
            Copyright &copy; 2026 Little Hearts. All rights reserved.
        </footer>

       


<script src="<?php echo JS_PATH ?>jquery-3.6.0.min.js"></script>
<script src="<?php echo JS_PATH ?>jquery.validate.min.js"></script>



<script>
$(document).ready(function () {

    $("form").validate({

        rules: {
            username: {
                required: true,
                remote: {
                    url: "<?php echo base_url('check_username'); ?>",
                    type: "post",
                    data: {
                        username: function () {
                            return $("#username").val();
                        }
                    }
                }
            },
            password: {
                required: true
            }
        },

        messages: {
            username: {
                required: "Please enter username",
                remote: "Username does not exist"
            },
            password: {
                required: "Please enter password"
            }
        },

        errorPlacement: function(error, element) {
            error.addClass('error'); // add custom class
            error.insertAfter(element.closest('.input-wrap')); // show below input box
        },

        submitHandler: function(form) {
            form.submit(); // ✅ submit only if valid
        }

    });

});
</script>






































</body>

</html>
<?php $page_title = 'Apply — ' . (isset($job->c_title) ? $job->c_title : 'Careers'); ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <title>Little Hearts | Apply</title>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/edua-icons.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/animate.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/owl.carousel.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/owl.transitions.css') ?> ">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/cubeportfolio.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/settings.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootsnav.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/loader.css') ?>">
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.png') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito+Sans:opsz,wght@6..12,400;6..12,600;6..12,700;6..12,800;6..12,900&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           LITTLE HEARTS — APPLY FOR JOB (friendly & user friendly)
           ============================================================ */
        :root {
            --a-navy: #0b2447;
            --a-navy2: #123a6b;
            --a-gold: #f5a623;
            --a-gold2: #e2911a;
            --a-ink: #22314a;
            --a-muted: #5b6b84;
            --a-paper: #f6f9ff;
            --a-line: #e3ecf7;
            --a-r: 22px;
            --a-sh: 0 16px 36px rgba(11, 36, 71, .10);
        }

        body { font-family: 'Nunito Sans', sans-serif; color: var(--a-ink); background: var(--a-paper); }

        .apply-section {
            padding: 60px 0 90px;
            background:
                radial-gradient(circle at 10% 8%, rgba(47,111,237,.08) 0, transparent 28%),
                radial-gradient(circle at 92% 90%, rgba(245,166,35,.10) 0, transparent 30%),
                radial-gradient(rgba(11,36,71,.05) 1.3px, transparent 1.3px);
            background-size: auto, auto, 26px 26px;
            min-height: 70vh;
        }

        .back-btn {
            display: inline-flex; align-items: center; gap: 9px;
            background: #fff;
            color: var(--a-navy) !important;
            padding: 11px 24px;
            border-radius: 50px;
            font-size: 14px; font-weight: 800;
            text-decoration: none !important;
            border: 2px solid var(--a-line);
            box-shadow: 0 4px 12px rgba(11,36,71,.06);
            transition: .25s cubic-bezier(.34,1.56,.64,1);
        }
        .back-btn:hover { background: var(--a-navy); color: #fff !important; border-color: var(--a-navy); transform: translateX(-4px); }

        .apply_wrapper {
            background: #fff;
            border-radius: 26px;
            overflow: hidden;
            box-shadow: 0 26px 60px rgba(11,36,71,.16);
            margin-top: 22px;
        }

        /* ---------- LEFT: job info panel ---------- */
        .job_details {
            background:
                radial-gradient(circle at 85% 12%, rgba(245,166,35,.16) 0, transparent 38%),
                radial-gradient(circle at 12% 90%, rgba(47,111,237,.2) 0, transparent 42%),
                linear-gradient(150deg, #081a34 0%, var(--a-navy) 55%, #143a70 100%);
            color: #fff;
            padding: 52px 46px;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        .job_details::before {
            content: ""; position: absolute; right: -60px; bottom: -60px;
            width: 220px; height: 220px; border-radius: 50%;
            border: 2px dashed rgba(245,166,35,.3);
            animation: aFloat 8s ease-in-out infinite;
        }
        .job_details::after {
            content: ""; position: absolute; left: -30px; top: 40px;
            width: 90px; height: 90px; border-radius: 50%;
            border: 2px dashed rgba(255,255,255,.15);
        }
        @keyframes aFloat { 0%,100% { transform: translateY(0) rotate(0) } 50% { transform: translateY(-14px) rotate(6deg) } }

        .job-tag {
            display: inline-flex; align-items: center; gap: 9px;
            background: rgba(245,166,35,.15);
            border: 1px solid rgba(245,166,35,.45);
            color: var(--a-gold);
            padding: 9px 20px; border-radius: 50px;
            font-size: 12px; font-weight: 800; letter-spacing: 1.4px; text-transform: uppercase;
            margin-bottom: 24px; position: relative; z-index: 1;
        }
        .job_title {
            font-family: 'Fredoka', cursive !important;
            font-size: 36px; font-weight: 700; line-height: 1.3;
            margin-bottom: 22px; color: #fff !important;
            position: relative; z-index: 1; word-break: break-word;
        }
        .job-desc {
            font-size: 15px; line-height: 2; color: rgba(255,255,255,.85);
            text-align: justify; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;
            position: relative; z-index: 1;
        }
        .job-desc p { margin-bottom: 14px; }

        /* school info chips inside the gradient panel */
        .job-points { margin-top: 30px; position: relative; z-index: 1; }
        .job-points li {
            list-style: none;
            display: flex; align-items: flex-start; gap: 12px;
            color: rgba(255,255,255,.85); font-size: 14px; font-weight: 600;
            margin-bottom: 13px;
        }
        .job-points li i {
            width: 26px; height: 26px; flex: none;
            border-radius: 50%;
            background: rgba(245,166,35,.18);
            color: var(--a-gold);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; margin-top: 1px;
        }

        /* ---------- RIGHT: application form ---------- */
        .application_form { padding: 48px 46px; }

        .form-title {
            font-family: 'Fredoka', cursive !important;
            font-size: 30px; font-weight: 700; color: var(--a-navy);
            margin-bottom: 6px;
        }
        .form-sub { color: var(--a-muted); font-size: 14.5px; margin-bottom: 30px; }

        .field-label {
            display: block;
            font-size: 13px; font-weight: 800; letter-spacing: .4px;
            color: var(--a-navy); margin-bottom: 8px;
        }
        .field-label .req { color: #e0475f; }

        .application_form .form-control {
            height: 54px;
            border-radius: 14px;
            border: 2px solid var(--a-line);
            box-shadow: none !important;
            font-size: 15px;
            margin-bottom: 20px;
            padding: 0 18px;
            color: var(--a-ink);
            background: #f9fbfe;
            transition: border-color .25s ease, background .25s ease, box-shadow .25s ease;
        }
        .application_form .form-control:focus {
            border-color: var(--a-gold);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(245,166,35,.14) !important;
        }
        input[type="file"].form-control { padding-top: 13px; }

        .file-hint { font-size: 12.5px; color: var(--a-muted); margin: -12px 0 20px 4px; }
        .file-hint i { color: var(--a-gold2); margin-right: 5px; }

        .submit_btn {
            width: 100%;
            height: 56px;
            border: none;
            border-radius: 50px;
            background: linear-gradient(135deg, var(--a-gold), var(--a-gold2));
            color: var(--a-navy);
            font-size: 16px; font-weight: 800; letter-spacing: .4px;
            box-shadow: 0 8px 0 #c47f10;
            transition: transform .2s cubic-bezier(.34,1.56,.64,1), box-shadow .2s ease;
        }
        .submit_btn:hover { transform: translateY(-3px); box-shadow: 0 11px 0 #c47f10; background: linear-gradient(135deg, var(--a-gold), var(--a-gold2)); color: var(--a-navy); }
        .submit_btn:active { transform: translateY(2px); box-shadow: 0 4px 0 #c47f10; }
        .submit_btn[disabled] { opacity: .7; transform: none; }

        .form-trust {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 18px; font-size: 12.5px; color: var(--a-muted);
        }
        .form-trust i { color: #2fb380; }

        .swal2-container { z-index: 99999; }
        .swal2-popup.swal2-toast { margin-bottom: 80px !important; }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 991px) {
            .job_details, .application_form { padding: 36px 26px; }
            .job_title { font-size: 28px; }
        }
        @media (max-width: 767px) {
            .apply-section { padding: 34px 0 60px; }
            .application_form { padding: 30px 20px; }
            .job_title { font-size: 24px; }
            .form-title { font-size: 24px; }
            .submit_btn { height: 52px; }
        }
    </style>
</head>

<body>

<section class="apply_section apply-section">
    <div class="container">

        <a href="<?php echo base_url('vaccancy'); ?>" class="back-btn">
            <i class="fa fa-arrow-left"></i> Back to Openings
        </a>

        <div class="apply_wrapper">
            <div class="row">

                <!-- LEFT SIDE: job info -->
                <div class="col-md-6">
                    <div class="job_details wow fadeInUp">

                        <span class="job-tag"><i class="fa fa-graduation-cap"></i> Career Opportunity</span>

                        <h1 class="job_title">
                            <?php echo isset($job->c_title) ? $job->c_title : ''; ?>
                        </h1>

                        <div class="job-desc">
                            <?php echo isset($job->c_description) ? nl2br($job->c_description) : ''; ?>
                        </div>

                        <ul class="job-points">
                            <li><i class="fa fa-map-marker"></i> Little Hearts School, Kizhakkepram, N. Paravur</li>
                            <li><i class="fa fa-clock-o"></i> Full-time · CBSE curriculum</li>
                            <li><i class="fa fa-users"></i> Friendly team &amp; supportive leadership</li>
                            <li><i class="fa fa-leaf"></i> Green, calm campus environment</li>
                        </ul>

                    </div>
                </div>

                <!-- RIGHT SIDE: application form -->
                <div class="col-md-6">

                    <div class="application_form wow fadeInUp">

                        <h2 class="form-title">Apply Now</h2>
                        <p class="form-sub">Fill in your details below — it takes less than a minute.</p>

                        <form id="jobForm" enctype="multipart/form-data">

                            <input type="hidden" name="vacancy_id" value="<?php echo isset($job->n_slno) ? $job->n_slno : ''; ?>">

                            <label class="field-label" for="ajName">Full Name <span class="req">*</span></label>
                            <input type="text" id="ajName" name="name" class="form-control" placeholder="e.g. Anitha R Menon" required>

                            <label class="field-label" for="ajMobile">Contact Number <span class="req">*</span></label>
                            <input type="text" id="ajMobile" name="mobile" class="form-control" placeholder="10-digit mobile number" required>

                            <label class="field-label" for="ajEmail">Email Address <span class="req">*</span></label>
                            <input type="email" id="ajEmail" name="email" class="form-control" placeholder="you@example.com" required>

                            <label class="field-label" for="ajResume">Resume / CV <span class="req">*</span></label>
                            <input type="file" id="ajResume" name="resume" class="form-control" required>
                            <div class="file-hint"><i class="fa fa-info-circle"></i> PDF or Word document, please.</div>

                            <button type="submit" class="submit_btn">
                                Submit Application <i class="fa fa-paper-plane"></i>
                            </button>

                            <div class="form-trust">
                                <i class="fa fa-shield"></i> Your details are kept private and used only for this recruitment.
                            </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>
</section>

<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SWEET ALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    // digits only in mobile
    $('#ajMobile').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $('#jobForm').on('submit', function (e) {

        e.preventDefault();

        var mobile = $('#ajMobile').val().trim();
        if (mobile.length !== 10) {
            Swal.fire({
                toast: true, position: 'bottom', icon: 'warning',
                title: 'Mobile number must be 10 digits',
                showConfirmButton: false, timer: 2200, timerProgressBar: true
            });
            return false;
        }

        let formData = new FormData(this);

        $.ajax({

            url: "<?php echo base_url('submit_job_application'); ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",

            beforeSend: function () {
                $('.submit_btn').html('<i class="fa fa-circle-o-notch fa-spin"></i> Submitting...');
                $('.submit_btn').prop('disabled', true);
            },

            success: function (response) {

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });

                if (response.status == 'success') {
                    Toast.fire({ icon: 'success', title: response.message });
                    $('#jobForm')[0].reset();
                } else {
                    Toast.fire({ icon: 'error', title: response.message });
                }

                $('.submit_btn').html('Submit Application <i class="fa fa-paper-plane"></i>');
                $('.submit_btn').prop('disabled', false);
            },

            error: function (xhr) {

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });

                Toast.fire({ icon: 'error', title: 'Something went wrong' });

                $('.submit_btn').html('Submit Application <i class="fa fa-paper-plane"></i>');
                $('.submit_btn').prop('disabled', false);
            }
        });

    });

});
</script>

<script src="<?php echo base_url("assets/js/wow.min.js") ?>"></script>
<script>if (typeof WOW === "function") { new WOW({ offset: 60, mobile: true, live: true }).init(); }</script>
</body>
</html>

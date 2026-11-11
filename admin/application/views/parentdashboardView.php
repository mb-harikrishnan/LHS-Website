<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Data can be passed from the controller. Defaults below let the view
 * render on its own while you wire up the database.
 */
$school_name   = isset($school_name)   ? $school_name   : 'Little Hearts School';
$tagline       = isset($tagline)       ? $tagline       : 'Together for a brighter future...';
$avg_attendance= isset($avg_attendance)? $avg_attendance: 95;
$performance   = isset($performance)   ? $performance   : 'Good';
$fee_due       = isset($fee_due)       ? $fee_due       : 12500;

$children = isset($children) ? $children : array(
    array('id' => 1, 'name' => 'Aarya Ramesh',  'class' => '5 - A', 'photo' => 'assets/images/girl.png'),
    array('id' => 2, 'name' => 'Adithya Ramesh', 'class' => '2 - B', 'photo' => 'assets/images/boy.png'),
);

$notices = isset($notices) ? $notices : array(
    array('day' => '10', 'month' => 'Nov', 'title' => 'Annual Day Celebration – Save the Date!', 'text' => 'Our Annual Day will be held on 20th December 2025...'),
    array('day' => '05', 'month' => 'Nov', 'title' => 'Fee Payment Reminder',                      'text' => 'Kindly clear the pending fee before 15th November 2025.'),
    array('day' => '01', 'month' => 'Nov', 'title' => 'Winter Holidays',                          'text' => 'School will remain closed from 24th December 2025...'),
);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    .pd {
        --pd-ink: #1e1b4b;
        --pd-primary: #4338ca;
        --pd-muted: #64748b;
        --pd-border: #dcdcf5;
        --pd-surface: #ffffff;
        font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: var(--pd-ink);
        max-width: 1200px;
        margin: 0 auto;
        padding: 16px;
        box-sizing: border-box;
    }
    .pd *, .pd *::before, .pd *::after { box-sizing: inherit; }
    .pd a { text-decoration: none; color: inherit; }

    /* Welcome banner */
    .pd-hero {
        position: relative;
        display: flex;
        align-items: center;
        min-height: 150px;
        padding: 20px 32px;
        border-radius: 12px;
        overflow: hidden;
        background: linear-gradient(90deg, #eef0ff 0%, #f3f0ff 45%, #dfe8f5 100%);
    }
    .pd-hero__text { position: relative; z-index: 2; display: flex; align-items: center; gap: 18px; }
    .pd-hero__heart { font-size: 44px; color: #7c5cf0; }
    .pd-hero h1 { margin: 0; font-size: 20px; font-weight: 500; line-height: 1.3; }
    .pd-hero h1 strong { display: block; font-size: 30px; font-weight: 700; }
    .pd-hero p { margin: 6px 0 0; font-style: italic; font-size: 15px; }
    .pd-hero__img {
        position: absolute; top: 0; right: 0; bottom: 0; width: 55%;
        background: url('<?= base_url("assets/images/schoolimage.jpg"); ?>') center/cover no-repeat;
        -webkit-mask-image: linear-gradient(to right, transparent, #000 40%);
                mask-image: linear-gradient(to right, transparent, #000 40%);
    }

    /* Stat tiles */
    .pd-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin: 16px 0; }
    .pd-stat {
        display: flex; align-items: center; gap: 16px;
        padding: 18px 20px; border-radius: 10px; border: 1px solid transparent;
    }
    .pd-stat__icon {
        flex: none; width: 56px; height: 56px; border-radius: 50%;
        display: grid; place-items: center; font-size: 24px;
    }
    .pd-stat__value { font-size: 20px; font-weight: 700; line-height: 1.2; }
    .pd-stat__label { font-size: 14px; color: var(--pd-muted); }
    .pd-stat--children   { background: #f1f0ff; } .pd-stat--children   .pd-stat__icon { color: #4f46e5; }
    .pd-stat--attendance { background: #e9f9f0; } .pd-stat--attendance .pd-stat__icon { background: #c8f0d9; color: #16a34a; }
    .pd-stat--performance{ background: #fff0f0; } .pd-stat--performance .pd-stat__icon { background: #fecdd3; color: #e11d48; }
    .pd-stat--fee        { background: #eef4ff; } .pd-stat--fee        .pd-stat__icon { background: #bfd6ff; color: #1d4ed8; }

    /* Main row */
    .pd-main { display: grid; grid-template-columns: 1.25fr 1.15fr 0.7fr; gap: 16px; align-items: stretch; }
    .pd-card {
        background: var(--pd-surface); border: 1px solid var(--pd-border);
        border-radius: 12px; padding: 18px 20px;
    }
    .pd-card__head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid var(--pd-border); }
    .pd-card__head h2 { margin: 0; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .pd-card__head h2 i { color: var(--pd-primary); }
    .pd-viewall { font-size: 14px; font-weight: 600; color: var(--pd-primary); white-space: nowrap; }
    .pd-viewall i { margin-left: 4px; font-size: 12px; }

    /* Notices */
    .pd-notice { display: flex; gap: 16px; padding: 10px 0; border-bottom: 1px solid #eceefb; }
    .pd-notice:last-child { border-bottom: 0; }
    .pd-date {
        flex: none; width: 54px; padding: 6px 0; text-align: center;
        border: 1px solid #b9bdf0; border-radius: 8px; line-height: 1.2;
        font-size: 13px; color: var(--pd-muted);
    }
    .pd-date b { display: block; font-size: 18px; color: var(--pd-primary); }
    .pd-notice h3 { margin: 2px 0 4px; font-size: 15px; font-weight: 700; }
    .pd-notice p  { margin: 0; font-size: 13px; color: var(--pd-muted); }

    /* Children */
    .pd-kids { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .pd-kid {
        text-align: center; padding: 16px 12px; border: 1px solid var(--pd-border); border-radius: 12px;
    }
    .pd-kid img {
        width: 58px; height: 58px; border-radius: 50%; object-fit: cover;
        border: 2px solid #e4e2fb; background: #e9e7fb;
    }
    .pd-kid h3 { margin: 8px 0 2px; font-size: 16px; font-weight: 700; }
    .pd-kid p  { margin: 0 0 12px; font-size: 13px; color: var(--pd-muted); }
    .pd-btn {
        display: block; padding: 9px 12px; border-radius: 8px;
        background: #ddd8fb; color: #2e2a7a; font-size: 14px; font-weight: 600;
        transition: background .15s;
    }
    .pd-btn:hover, .pd-btn:focus-visible { background: #cbc4f7; }
    .pd-btn i { margin-left: 6px; font-size: 12px; }

    /* Quote panel */
    .pd-quote {
        display: flex; flex-direction: column; justify-content: space-between;
        padding: 24px 18px 0; border-radius: 12px; overflow: hidden;
        background: linear-gradient(180deg, #fff1c9, #fde7a6);
    }
    .pd-quote p {
        margin: 0; font-family: "Brush Script MT", "Segoe Script", "Comic Sans MS", cursive;
        font-size: 30px; line-height: 1.2; font-weight: 700; text-align: center; color: #26307a;
    }
    .pd-quote img { width: 100%; display: block; margin-top: 12px; }

    /* Responsive */
    @media (max-width: 1024px) {
        .pd-stats { grid-template-columns: repeat(2, 1fr); }
        .pd-main  { grid-template-columns: 1fr 1fr; }
        .pd-quote { grid-column: 1 / -1; flex-direction: row; align-items: center; padding: 20px; }
        .pd-quote img { width: 160px; margin: 0; }
    }
    @media (max-width: 640px) {
        .pd-hero { padding: 20px; }
        .pd-hero__img { opacity: .35; width: 100%; }
        .pd-stats, .pd-main, .pd-kids { grid-template-columns: 1fr; }
    }
</style>

<div class="pd">

    <!-- Welcome banner -->
    <section class="pd-hero">
        <div class="pd-hero__text">
            <i class="fa-regular fa-heart pd-hero__heart" aria-hidden="true"></i>
            <div>
                <h1>Welcome to <strong><?= html_escape($school_name); ?></strong></h1>
                <p><?= html_escape($tagline); ?></p>
            </div>
        </div>
        <div class="pd-hero__img" role="img" aria-label="School building"></div>
    </section>

    <!-- Summary tiles -->
    <section class="pd-stats">
        <div class="pd-stat pd-stat--children">
            <div class="pd-stat__icon"><i class="fa-solid fa-user-group"></i></div>
            <div>
                <div class="pd-stat__value"><?= count($children); ?></div>
                <div class="pd-stat__label">Children</div>
            </div>
        </div>
        <div class="pd-stat pd-stat--attendance">
            <div class="pd-stat__icon"><i class="fa-regular fa-calendar-check"></i></div>
            <div>
                <div class="pd-stat__value"><?= (int) $avg_attendance; ?>%</div>
                <div class="pd-stat__label">Avg Attendance</div>
            </div>
        </div>
        <div class="pd-stat pd-stat--performance">
            <div class="pd-stat__icon"><i class="fa-solid fa-star"></i></div>
            <div>
                <div class="pd-stat__value"><?= html_escape($performance); ?></div>
                <div class="pd-stat__label">Overall Performance</div>
            </div>
        </div>
        <div class="pd-stat pd-stat--fee">
            <div class="pd-stat__icon"><i class="fa-solid fa-indian-rupee-sign"></i></div>
            <div>
                <div class="pd-stat__value">&#8377; <?= number_format($fee_due); ?></div>
                <div class="pd-stat__label">Fee Due</div>
            </div>
        </div>
    </section>

    <!-- Notices / Children / Quote -->
    <section class="pd-main">

        <div class="pd-card">
            <div class="pd-card__head">
                <h2><i class="fa-solid fa-bullhorn"></i> Latest Notices</h2>
                <a class="pd-viewall" href="<?= site_url('parent/notices'); ?>">View All <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <?php foreach ($notices as $n): ?>
                <div class="pd-notice">
                    <div class="pd-date"><b><?= html_escape($n['day']); ?></b><?= html_escape($n['month']); ?></div>
                    <div>
                        <h3><?= html_escape($n['title']); ?></h3>
                        <p><?= html_escape($n['text']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="pd-card">
            <div class="pd-card__head">
                <h2>My Children</h2>
                <a class="pd-viewall" href="<?= site_url('parent/children'); ?>">View All <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="pd-kids">
                <?php foreach ($children as $c): ?>
                    <div class="pd-kid">
                        <img src="<?= base_url($c['photo']); ?>" alt="<?= html_escape($c['name']); ?>">
                        <h3><?= html_escape($c['name']); ?></h3>
                        <p>Class : <?= html_escape($c['class']); ?></p>
                        <a class="pd-btn" href="<?= site_url('parent/child/' . $c['id']); ?>">View Details <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <aside class="pd-quote">
            <p>Education builds a better tomorrow</p>
            <img src="<?= base_url('assets/images/books-illustration.png'); ?>" alt="">
        </aside>

    </section>
</div>
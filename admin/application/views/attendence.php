<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Variables the controller can pass (all optional, sample data is used otherwise):
 *   $child        array  id, name, class, division, roll_no, gender, photo
 *   $month, $year int    month being shown
 *   $attendance   array  'Y-m-d' => 'P' | 'A' | 'L' | 'H'   (Present, Absent, Late, Half day)
 *   $holidays     array  'Y-m-d' => 'Holiday name'
 *   $saturday_off bool   true = Saturdays are a weekly off, false = Saturdays are working days
 *   $form_action  string URL the month picker submits to (GET)
 */
$child = isset($child) ? $child : array(
    'id' => 1, 'name' => 'Aarya Ramesh', 'class' => '5', 'division' => 'A', 'roll_no' => 12, 'gender' => 'F', 'photo' => '',
);
$month = isset($month) ? max(1, min(12, (int) $month)) : (int) date('n');
$year  = isset($year)  ? (int) $year : (int) date('Y');
$saturday_off = isset($saturday_off) ? (bool) $saturday_off : true;
$form_action  = isset($form_action) ? $form_action : site_url('parent/attendance/' . $child['id']);

$holidays = isset($holidays) ? $holidays : array(
    '2026-01-26' => 'Republic Day',
    '2026-08-15' => 'Independence Day',
    '2026-10-02' => 'Gandhi Jayanti',
    '2026-11-01' => 'Kerala Piravi',
    '2026-11-14' => "Children's Day",
    '2026-12-25' => 'Christmas',
);

$first_ts   = mktime(0, 0, 0, $month, 1, $year);
$days_in    = (int) date('t', $first_ts);
$start_dow  = (int) date('w', $first_ts);          // 0 = Sunday
$today      = date('Y-m-d');

/* Demo attendance so the page renders before the database is connected. */
if (!isset($attendance)) {
    $attendance = array();
    for ($d = 1; $d <= $days_in; $d++) {
        $ds  = sprintf('%04d-%02d-%02d', $year, $month, $d);
        $dow = (int) date('w', mktime(0, 0, 0, $month, $d, $year));
        if ($ds > $today || $dow === 0 || isset($holidays[$ds]) || ($saturday_off && $dow === 6)) continue;
        $attendance[$ds] = ($d % 11 === 0) ? 'A' : (($d % 7 === 0) ? 'L' : (($d % 13 === 0) ? 'H' : 'P'));
    }
}

/* Work out the type of every day, and the month totals. */
$cells = array();
$count = array('P' => 0, 'A' => 0, 'L' => 0, 'H' => 0, 'working' => 0, 'holidays' => 0, 'sundays' => 0, 'saturdays' => 0);
$month_holidays = array();

for ($d = 1; $d <= $days_in; $d++) {
    $ds  = sprintf('%04d-%02d-%02d', $year, $month, $d);
    $dow = (int) date('w', mktime(0, 0, 0, $month, $d, $year));

    if (isset($holidays[$ds]))                { $type = 'holiday';  $count['holidays']++; $month_holidays[$d] = $holidays[$ds]; }
    elseif ($dow === 0)                       { $type = 'sunday';   $count['sundays']++; }
    elseif ($dow === 6 && $saturday_off)      { $type = 'saturday'; $count['saturdays']++; }
    elseif ($ds > $today)                     { $type = 'upcoming'; $count['working']++; }
    elseif (isset($attendance[$ds]))          { $type = $attendance[$ds]; $count['working']++; $count[$attendance[$ds]]++; }
    else                                      { $type = 'none';     $count['working']++; }

    $cells[$d] = $type;
}

$attended   = $count['P'] + $count['L'] + (0.5 * $count['H']);
$marked     = $count['P'] + $count['A'] + $count['L'] + $count['H'];
$percentage = $marked > 0 ? round(($attended / $marked) * 100) : 0;

$month_names = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$prev_m = $month === 1  ? 12 : $month - 1;  $prev_y = $month === 1  ? $year - 1 : $year;
$next_m = $month === 12 ? 1  : $month + 1;  $next_y = $month === 12 ? $year + 1 : $year;

$labels = array(
    'P' => 'Present', 'A' => 'Absent', 'L' => 'Late', 'H' => 'Half day',
    'holiday' => 'Holiday', 'sunday' => 'Sunday', 'saturday' => 'Saturday',
);
$short = array('P' => 'P', 'A' => 'A', 'L' => 'L', 'H' => 'H');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    .pa {
        --pa-ink: #1e1b4b; --pa-primary: #4338ca; --pa-muted: #64748b; --pa-border: #dcdcf5;
        --c-present: #16a34a; --c-present-bg: #e3f7ec;
        --c-absent:  #e11d48; --c-absent-bg:  #ffe4e8;
        --c-late:    #d97706; --c-late-bg:    #fff1d6;
        --c-half:    #0891b2; --c-half-bg:    #d9f3f9;
        --c-holiday: #7c3aed; --c-holiday-bg: #ede4ff;
        --c-sunday:  #dc2626; --c-sunday-bg:  #fdeeee;
        --c-sat:     #ea580c; --c-sat-bg:     #fff0e4;
        font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: var(--pa-ink); max-width: 1200px; margin: 0 auto; padding: 16px; box-sizing: border-box;
    }
    .pa *, .pa *::before, .pa *::after { box-sizing: inherit; }
    .pa a { text-decoration: none; color: inherit; }

    /* Header */
    .pa-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
    .pa-top h1 { margin: 0; font-size: 26px; font-weight: 700; }
    .pa-top p  { margin: 4px 0 0; font-size: 14px; color: var(--pa-muted); }
    .pa-back { display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; border: 1px solid var(--pa-border);
               border-radius: 8px; font-weight: 600; font-size: 14px; color: var(--pa-primary); background: #fff; }
    .pa-back:hover, .pa-back:focus-visible { background: #f1f0ff; }

    /* Summary tiles */
    .pa-stats { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; margin-bottom: 16px; }
    .pa-stat { padding: 16px 18px; border-radius: 12px; }
    .pa-stat b { display: block; font-size: 26px; line-height: 1.1; }
    .pa-stat span { font-size: 13px; color: var(--pa-muted); }
    .pa-stat--pct     { background: linear-gradient(135deg, #4f46e5, #7c5cf0); color: #fff; }
    .pa-stat--pct span { color: #e0e0ff; }
    .pa-stat--present { background: var(--c-present-bg); } .pa-stat--present b { color: var(--c-present); }
    .pa-stat--absent  { background: var(--c-absent-bg); }  .pa-stat--absent b  { color: var(--c-absent); }
    .pa-stat--late    { background: var(--c-late-bg); }    .pa-stat--late b    { color: var(--c-late); }
    .pa-stat--work    { background: #eef0ff; }             .pa-stat--work b    { color: var(--pa-primary); }

    /* Layout */
    .pa-main { display: grid; grid-template-columns: 1fr 300px; gap: 16px; align-items: start; }
    .pa-card { background: #fff; border: 1px solid var(--pa-border); border-radius: 14px; padding: 18px 20px; }

    /* Month switcher */
    .pa-switch { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; }
    .pa-nav { display: flex; align-items: center; gap: 8px; }
    .pa-arrow { width: 38px; height: 38px; display: grid; place-items: center; border: 1px solid var(--pa-border); border-radius: 50%; color: var(--pa-primary); background: #fff; }
    .pa-arrow:hover, .pa-arrow:focus-visible { background: #f1f0ff; }
    .pa-title { font-size: 20px; font-weight: 700; min-width: 170px; text-align: center; }
    .pa-pick { display: flex; gap: 8px; }
    .pa-pick select, .pa-pick button {
        height: 38px; padding: 0 12px; border: 1px solid var(--pa-border); border-radius: 8px;
        font: inherit; font-size: 14px; background: #fff; color: var(--pa-ink);
    }
    .pa-pick button { background: var(--pa-primary); border-color: var(--pa-primary); color: #fff; font-weight: 600; cursor: pointer; }
    .pa-pick button:hover { background: #3730a3; }

    /* Calendar */
    .pa-week, .pa-days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; }
    .pa-week div { text-align: center; font-size: 13px; font-weight: 700; padding: 8px 0; border-radius: 8px; background: #f4f4fb; color: var(--pa-muted); }
    .pa-week div:first-child { background: var(--c-sunday-bg); color: var(--c-sunday); }
    .pa-week div:last-child  { background: var(--c-sat-bg);    color: var(--c-sat); }
    .pa-days { margin-top: 8px; }

    .pa-day {
        position: relative; min-height: 86px; padding: 8px 10px; border-radius: 10px;
        border: 1px solid #ececf8; background: #fff; display: flex; flex-direction: column; justify-content: space-between;
    }
    .pa-day__num { font-size: 15px; font-weight: 700; }
    .pa-day__tag { font-size: 12px; font-weight: 600; line-height: 1.25; }
    .pa-day--blank { border: 0; background: transparent; }
    .pa-day--today { outline: 2px solid var(--pa-primary); outline-offset: 1px; }

    .pa-day--P { background: var(--c-present-bg); border-color: #bfe9d0; } .pa-day--P .pa-day__tag { color: var(--c-present); }
    .pa-day--A { background: var(--c-absent-bg);  border-color: #fbc2cb; } .pa-day--A .pa-day__tag { color: var(--c-absent); }
    .pa-day--L { background: var(--c-late-bg);    border-color: #fadca6; } .pa-day--L .pa-day__tag { color: var(--c-late); }
    .pa-day--H { background: var(--c-half-bg);    border-color: #b3e4ee; } .pa-day--H .pa-day__tag { color: var(--c-half); }
    .pa-day--holiday  { background: var(--c-holiday-bg); border-color: #d6c6fb; } .pa-day--holiday .pa-day__tag { color: var(--c-holiday); }
    .pa-day--sunday   { background: var(--c-sunday-bg);  border-color: #f8cccc; } .pa-day--sunday  .pa-day__tag { color: var(--c-sunday); }
    .pa-day--saturday { background: var(--c-sat-bg);     border-color: #fbd5b8; } .pa-day--saturday .pa-day__tag { color: var(--c-sat); }
    .pa-day--upcoming, .pa-day--none { background: #fafaff; }
    .pa-day--upcoming .pa-day__num, .pa-day--none .pa-day__num { color: #9aa0b8; }
    .pa-day--none .pa-day__tag { color: #9aa0b8; font-weight: 500; }

    /* Side panel */
    .pa-side h2 { margin: 0 0 12px; font-size: 16px; font-weight: 700; }
    .pa-side section + section { margin-top: 22px; padding-top: 18px; border-top: 1px solid #eceefb; }
    .pa-legend { list-style: none; margin: 0; padding: 0; display: grid; gap: 9px; font-size: 14px; }
    .pa-legend li { display: flex; align-items: center; gap: 10px; }
    .pa-dot { width: 16px; height: 16px; border-radius: 5px; flex: none; }
    .pa-hol { list-style: none; margin: 0; padding: 0; display: grid; gap: 10px; }
    .pa-hol li { display: flex; gap: 12px; align-items: center; font-size: 14px; }
    .pa-hol time { flex: none; width: 44px; padding: 5px 0; text-align: center; border-radius: 8px; background: var(--c-holiday-bg); color: var(--c-holiday); font-weight: 700; line-height: 1.1; }
    .pa-hol time small { display: block; font-size: 11px; font-weight: 600; }
    .pa-note { margin: 0; font-size: 14px; color: var(--pa-muted); }
    .pa-off { display: flex; justify-content: space-between; font-size: 14px; padding: 6px 0; }

    /* Responsive */
    @media (max-width: 960px) {
        .pa-stats { grid-template-columns: repeat(3, 1fr); }
        .pa-main  { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
        .pa-stats { grid-template-columns: repeat(2, 1fr); }
        .pa-card  { padding: 14px 10px; }
        .pa-week, .pa-days { gap: 4px; }
        .pa-day { min-height: 56px; padding: 5px 6px; border-radius: 8px; }
        .pa-day__tag { font-size: 10px; }
        .pa-day--holiday .pa-day__tag { display: none; }
        .pa-title { min-width: 0; font-size: 17px; }
    }
</style>

<div class="pa">

    <!-- Header -->
    <div class="pa-top">
        <div>
            <h1>Attendance</h1>
            <p><?= html_escape($child['name']); ?> &nbsp;|&nbsp; Class <?= html_escape($child['class']); ?> - <?= html_escape($child['division']); ?> &nbsp;|&nbsp; Roll No. <?= html_escape($child['roll_no']); ?></p>
        </div>
        <a class="pa-back" href="<?= site_url('parent/children'); ?>"><i class="fa-solid fa-arrow-left"></i> Back to My Children</a>
    </div>

    <!-- Summary for the month shown -->
    <section class="pa-stats">
        <div class="pa-stat pa-stat--pct"><b><?= $percentage; ?>%</b><span>Attendance this month</span></div>
        <div class="pa-stat pa-stat--present"><b><?= $count['P']; ?></b><span>Days present</span></div>
        <div class="pa-stat pa-stat--absent"><b><?= $count['A']; ?></b><span>Days absent</span></div>
        <div class="pa-stat pa-stat--late"><b><?= $count['L'] + $count['H']; ?></b><span>Late / half days</span></div>
        <div class="pa-stat pa-stat--work"><b><?= $count['working']; ?></b><span>Working days</span></div>
    </section>

    <div class="pa-main">

        <!-- Calendar -->
        <div class="pa-card">
            <div class="pa-switch">
                <div class="pa-nav">
                    <a class="pa-arrow" href="<?= $form_action . '?month=' . $prev_m . '&year=' . $prev_y; ?>" aria-label="Previous month"><i class="fa-solid fa-chevron-left"></i></a>
                    <div class="pa-title"><?= $month_names[$month] . ' ' . $year; ?></div>
                    <a class="pa-arrow" href="<?= $form_action . '?month=' . $next_m . '&year=' . $next_y; ?>" aria-label="Next month"><i class="fa-solid fa-chevron-right"></i></a>
                </div>

                <form class="pa-pick" method="get" action="<?= $form_action; ?>">
                    <select name="month" aria-label="Month">
                        <?php foreach ($month_names as $num => $name): ?>
                            <option value="<?= $num; ?>" <?= $num === $month ? 'selected' : ''; ?>><?= $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="year" aria-label="Year">
                        <?php for ($y = (int) date('Y') - 2; $y <= (int) date('Y') + 1; $y++): ?>
                            <option value="<?= $y; ?>" <?= $y === $year ? 'selected' : ''; ?>><?= $y; ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit">Show</button>
                </form>
            </div>

            <div class="pa-week">
                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
            </div>

            <div class="pa-days">
                <?php for ($b = 0; $b < $start_dow; $b++): ?>
                    <div class="pa-day pa-day--blank"></div>
                <?php endfor; ?>

                <?php for ($d = 1; $d <= $days_in; $d++):
                    $type = $cells[$d];
                    $ds   = sprintf('%04d-%02d-%02d', $year, $month, $d);
                    if ($type === 'holiday')                       $tag = $month_holidays[$d];
                    elseif (isset($labels[$type]))                 $tag = $labels[$type];
                    else                                           $tag = '';
                ?>
                    <div class="pa-day pa-day--<?= $type; ?><?= $ds === $today ? ' pa-day--today' : ''; ?>" title="<?= html_escape($tag); ?>">
                        <span class="pa-day__num"><?= $d; ?></span>
                        <span class="pa-day__tag"><?= html_escape($tag); ?></span>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Legend and holidays -->
        <aside class="pa-card pa-side">
            <section>
                <h2>Legend</h2>
                <ul class="pa-legend">
                    <li><span class="pa-dot" style="background:var(--c-present)"></span> Present</li>
                    <li><span class="pa-dot" style="background:var(--c-absent)"></span> Absent</li>
                    <li><span class="pa-dot" style="background:var(--c-late)"></span> Late</li>
                    <li><span class="pa-dot" style="background:var(--c-half)"></span> Half day</li>
                    <li><span class="pa-dot" style="background:var(--c-holiday)"></span> Holiday</li>
                    <li><span class="pa-dot" style="background:var(--c-sunday)"></span> Sunday (weekly off)</li>
                    <li><span class="pa-dot" style="background:var(--c-sat)"></span> Saturday<?= $saturday_off ? ' (weekly off)' : ' (working day)'; ?></li>
                </ul>
            </section>

            <section>
                <h2>Holidays in <?= $month_names[$month]; ?></h2>
                <?php if (empty($month_holidays)): ?>
                    <p class="pa-note">No holidays this month.</p>
                <?php else: ?>
                    <ul class="pa-hol">
                        <?php foreach ($month_holidays as $d => $name): ?>
                            <li>
                                <time><?= $d; ?><small><?= substr($month_names[$month], 0, 3); ?></small></time>
                                <span><?= html_escape($name); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>

            <section>
                <h2>Days off this month</h2>
                <div class="pa-off"><span>Sundays</span><b><?= $count['sundays']; ?></b></div>
                <?php if ($saturday_off): ?>
                    <div class="pa-off"><span>Saturdays</span><b><?= $count['saturdays']; ?></b></div>
                <?php endif; ?>
                <div class="pa-off"><span>Holidays</span><b><?= $count['holidays']; ?></b></div>
            </section>
        </aside>

    </div>
</div>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$academic_year = isset($academic_year) ? $academic_year : '2026 - 27';

$children = isset($children) ? $children : array(
    array('id'=>1, 'name'=>'Aarya Ramesh', 'class'=>'5', 'division'=>'A'),
    array('id'=>2, 'name'=>'Adithya Ramesh', 'class'=>'2', 'division'=>'B')
);

$child = isset($child) ? $child : array(
    'id'=>1,
    'name'=>'Aarya Ramesh',
    'class'=>'5',
    'division'=>'A',
    'roll_no'=>'12',
    'admission_no'=>'ADM-1005'
);

$terms = isset($terms) ? $terms : array(
    1 => 'First Term',
    2 => 'Second Term',
    3 => 'Third Term',
    4 => 'Annual Examination'
);

$selected_term = isset($selected_term) ? $selected_term : 3;

$results = isset($results) ? $results : array(
    array('subject'=>'English','max_marks'=>100,'obtained'=>85,'pass_marks'=>35),
    array('subject'=>'Malayalam','max_marks'=>100,'obtained'=>92,'pass_marks'=>35),
    array('subject'=>'Mathematics','max_marks'=>100,'obtained'=>78,'pass_marks'=>35),
    array('subject'=>'Science','max_marks'=>100,'obtained'=>88,'pass_marks'=>35),
    array('subject'=>'Social Science','max_marks'=>100,'obtained'=>75,'pass_marks'=>35),
    array('subject'=>'Hindi','max_marks'=>100,'obtained'=>82,'pass_marks'=>35)
);

$remarks = isset($remarks) ? $remarks :
    'Very good performance. Keep up the excellent work!';

$total_max = 0;
$total_obtained = 0;
$failed = 0;

foreach ($results as $k => $r) {

    $max = (float)$r['max_marks'];
    $obtained = (float)$r['obtained'];

    $percentage_subject = $max > 0 ? ($obtained/$max)*100 : 0;

    if ($percentage_subject >= 90) $grade = 'A+';
    elseif ($percentage_subject >= 80) $grade = 'A';
    elseif ($percentage_subject >= 70) $grade = 'B+';
    elseif ($percentage_subject >= 60) $grade = 'B';
    elseif ($percentage_subject >= 50) $grade = 'C';
    elseif ($percentage_subject >= 35) $grade = 'D';
    else $grade = 'F';

    $status = $obtained >= (float)$r['pass_marks'] ? 'Pass' : 'Fail';

    if ($status == 'Fail') $failed++;

    $results[$k]['grade'] = $grade;
    $results[$k]['status'] = $status;

    $total_max += $max;
    $total_obtained += $obtained;
}

$percentage = $total_max > 0
    ? round(($total_obtained/$total_max)*100, 2) : 0;

$overall_status = $failed == 0 ? 'PASS' : 'FAIL';

if ($percentage >= 90) $overall_grade = 'A+';
elseif ($percentage >= 80) $overall_grade = 'A';
elseif ($percentage >= 70) $overall_grade = 'B+';
elseif ($percentage >= 60) $overall_grade = 'B';
elseif ($percentage >= 50) $overall_grade = 'C';
elseif ($percentage >= 35) $overall_grade = 'D';
else $overall_grade = 'F';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
.er-page {
    --primary: #4338ca;
    --dark: #29216e;
    --light: #eeedff;
    --border: #e3e1f4;
    --muted: #718096;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #f4f5fb;
    color: #242052;
    padding: 22px 15px;
    min-height: 100vh;
}

.er-page * {
    box-sizing: border-box;
}

.er-container {
    max-width: 1150px;
    margin: auto;
}

/* Header */
.er-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.er-header h2 {
    font-size: 25px;
    font-weight: 750;
    margin: 0;
}

.er-header p {
    color: var(--muted);
    font-size: 13px;
    margin: 5px 0 0;
}

.er-back {
    padding: 10px 17px;
    background: white;
    color: var(--primary);
    border: 1px solid var(--border);
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
}

/* Student tabs */
.er-children {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 18px;
}

.er-child {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 18px;
    background: white;
    border: 1px solid var(--border);
    border-radius: 10px;
    color: #555b78;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
}

.er-child i {
    font-size: 22px;
}

.er-child.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Term selection */
.er-term-box {
    background: white;
    padding: 18px;
    border-radius: 12px;
    border: 1px solid var(--border);
    margin-bottom: 18px;
}

.er-term-title {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 15px;
}

.er-term-title h3 {
    font-size: 17px;
    margin: 0;
}

.er-terms {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.er-term {
    text-align: center;
    padding: 14px 8px;
    background: #f5f4ff;
    color: var(--primary);
    border: 1px solid #e1defc;
    border-radius: 9px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 650;
}

.er-term i {
    display: block;
    font-size: 20px;
    margin-bottom: 7px;
}

.er-term.active {
    background: var(--primary);
    color: white;
}

/* Student details */
.er-details {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    padding: 20px;
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    margin-bottom: 18px;
}

.er-detail span {
    display: block;
    color: var(--muted);
    font-size: 12px;
    margin-bottom: 7px;
}

.er-detail strong {
    font-size: 14px;
}

/* Summary cards */
.er-summary {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 13px;
    margin-bottom: 18px;
}

.er-stat {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 17px;
    border-radius: 11px;
    background: white;
    border: 1px solid var(--border);
}

.er-stat-icon {
    width: 44px;
    height: 44px;
    display: grid;
    place-items: center;
    border-radius: 10px;
    font-size: 19px;
    flex-shrink: 0;
}

.er-stat b {
    display: block;
    font-size: 21px;
}

.er-stat span {
    color: var(--muted);
    font-size: 12px;
}

.purple .er-stat-icon {background:#e9e7ff;color:#4338ca;}
.green .er-stat-icon {background:#dcfce7;color:#15803d;}
.blue .er-stat-icon {background:#e0f2fe;color:#0284c7;}
.orange .er-stat-icon {background:#fff0d9;color:#d97706;}

/* Result card */
.er-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 18px;
}

.er-card-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 17px;
}

.er-card-head h3 {
    margin: 0;
    font-size: 18px;
}

.er-card-head p {
    color: var(--muted);
    font-size: 13px;
    margin: 5px 0 0;
}

.er-badge {
    padding: 7px 15px;
    border-radius: 25px;
    background: #dcfce7;
    color: #15803d;
    font-size: 12px;
    font-weight: 800;
}

.er-badge.fail {
    background: #ffe4e8;
    color: #e11d48;
}

/* Table */
.er-scroll {
    overflow-x: auto;
}

.er-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 650px;
    font-size: 14px;
}

.er-table th {
    background: #34277f;
    color: white;
    padding: 14px 12px;
    text-align: left;
    font-size: 13px;
}

.er-table td {
    padding: 14px 12px;
    border-bottom: 1px solid #eeeef5;
}

.er-table tbody tr:hover {
    background: #faf9ff;
}

.er-table tfoot td {
    background: #eeedff;
    color: #31216f;
    font-weight: 800;
    border-top: 2px solid #d7d2f5;
}

.er-center {
    text-align: center !important;
}

.er-grade {
    background: #e9e7ff;
    color: #4338ca;
    border-radius: 6px;
    padding: 5px 10px;
    font-weight: 800;
}

.er-pass {
    color: #15803d;
    font-weight: 700;
}

.er-fail {
    color: #e11d48;
    font-weight: 700;
}

/* Overall result */
.er-overall {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    padding: 23px;
    color: white;
    background: linear-gradient(110deg,#34277f,#6956d1);
    border-radius: 12px;
    margin-bottom: 18px;
}

.er-overall h3 {
    margin: 0 0 7px;
    font-size: 19px;
}

.er-overall p {
    margin: 0;
    color: #e6e2ff;
    font-size: 13px;
}

.er-percent {
    text-align: center;
}

.er-percent strong {
    display: block;
    font-size: 34px;
}

.er-percent span {
    font-size: 12px;
}

/* Remarks */
.er-remarks {
    padding: 17px;
    background: #f0efff;
    border-left: 4px solid var(--primary);
    border-radius: 8px;
    margin-bottom: 20px;
}

.er-remarks h4 {
    margin: 0 0 8px;
}

.er-remarks p {
    margin: 0;
    font-size: 14px;
    color: #616782;
    line-height: 1.6;
}

/* Signatures */
.er-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    margin-top: 40px;
    padding: 15px 5px;
    color: var(--muted);
    font-size: 12px;
}

.er-sign {
    width: 160px;
    text-align: center;
    border-top: 1px solid var(--primary);
    padding-top: 9px;
    color: var(--dark);
    font-weight: 700;
    margin-top: 35px;
}

/* Buttons */
.er-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.er-btn {
    padding: 11px 19px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 700;
}

.er-print {
    background: var(--primary);
    color: white;
}

.er-download {
    background: #15803d;
    color: white;
}

.er-empty {
    text-align: center;
    padding: 35px;
    color: var(--muted);
}

/* Responsive */
@media(max-width:850px) {
    .er-summary {
        grid-template-columns: repeat(2,1fr);
    }

    .er-details {
        grid-template-columns: repeat(2,1fr);
    }
}

@media(max-width:550px) {
    .er-terms {
        grid-template-columns: repeat(2,1fr);
    }

    .er-summary {
        gap: 8px;
    }

    .er-stat {
        padding: 11px 8px;
        gap: 8px;
    }

    .er-stat b {
        font-size: 17px;
    }

    .er-stat-icon {
        width: 34px;
        height: 34px;
        font-size: 15px;
    }

    .er-header h2 {
        font-size: 21px;
    }

    .er-footer {
        flex-wrap: wrap;
    }
}

/* PDF / PRINT */
@media print {
    @page {
        size: A4 portrait;
        margin: 10mm;
    }

    body, .er-page {
        background: white !important;
        padding: 0 !important;
    }

    .er-actions,
    .er-children,
    .er-term-box,
    .er-back {
        display: none !important;
    }

    .er-card,
    .er-details,
    .er-remarks {
        break-inside: avoid;
    }

    .er-table {
        min-width: 0;
        font-size: 11px;
    }

    .er-table th,
    .er-table td {
        padding: 9px 6px;
    }

    .er-overall,
    .er-table th,
    .er-table tfoot td,
    .er-grade {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

<div class="er-page">
<div class="er-container">

    <!-- PAGE HEADER -->
    <div class="er-header">
        <div>
            <h2><i class="fa fa-graduation-cap"></i> Exam Results</h2>
            <p>Academic Year: <?= html_escape($academic_year); ?></p>
        </div>

        <a href="<?= site_url('parent/dashboard'); ?>" class="er-back">
            <i class="fa fa-arrow-left"></i> Dashboard
        </a>
    </div>

    <!-- CHILD SWITCHER -->
    <?php if (count($children) > 1): ?>
    <div class="er-children">

        <?php foreach ($children as $c): ?>

        <a href="<?= site_url('parent/results/'.$c['id'].'?term='.$selected_term); ?>"
           class="er-child <?= $c['id'] == $child['id'] ? 'active' : ''; ?>">

            <i class="fa fa-user-circle"></i>

            <span>
                <?= html_escape($c['name']); ?>
                <br>
                <small><?= html_escape($c['class'].' - '.$c['division']); ?></small>
            </span>

        </a>

        <?php endforeach; ?>

    </div>
    <?php endif; ?>

    <!-- TERM SELECTION -->
    <div class="er-term-box">

        <div class="er-term-title">
            <h3><i class="fa fa-calendar"></i> Select Term</h3>
            <span>Choose examination</span>
        </div>

        <div class="er-terms">

            <?php foreach ($terms as $key => $term): ?>

            <a href="<?= site_url('parent/results/'.$child['id'].'?term='.$key); ?>"
               class="er-term <?= (string)$selected_term === (string)$key ? 'active' : ''; ?>">

                <i class="fa fa-book-open"></i>
                <?= html_escape($term); ?>

            </a>

            <?php endforeach; ?>

        </div>

    </div>

    <!-- STUDENT DETAILS -->
    <div class="er-details">

        <div class="er-detail">
            <span>Student Name</span>
            <strong><?= html_escape($child['name']); ?></strong>
        </div>

        <div class="er-detail">
            <span>Admission Number</span>
            <strong><?= html_escape($child['admission_no']); ?></strong>
        </div>

        <div class="er-detail">
            <span>Class / Division</span>
            <strong><?= html_escape($child['class'].' / '.$child['division']); ?></strong>
        </div>

        <div class="er-detail">
            <span>Roll Number</span>
            <strong><?= html_escape($child['roll_no']); ?></strong>
        </div>

    </div>

    <?php if (!empty($results)): ?>

    <!-- SUMMARY -->
    <div class="er-summary">

        <div class="er-stat purple">
            <div class="er-stat-icon"><i class="fa fa-book"></i></div>
            <div>
                <b><?= count($results); ?></b>
                <span>Total Subjects</span>
            </div>
        </div>

        <div class="er-stat green">
            <div class="er-stat-icon"><i class="fa fa-check-circle"></i></div>
            <div>
                <b><?= count($results)-$failed; ?></b>
                <span>Subjects Passed</span>
            </div>
        </div>

        <div class="er-stat blue">
            <div class="er-stat-icon"><i class="fa fa-award"></i></div>
            <div>
                <b><?= $overall_grade; ?></b>
                <span>Overall Grade</span>
            </div>
        </div>

        <div class="er-stat orange">
            <div class="er-stat-icon"><i class="fa fa-chart-line"></i></div>
            <div>
                <b><?= $percentage; ?>%</b>
                <span>Percentage</span>
            </div>
        </div>

    </div>

    <!-- SUBJECT RESULT TABLE -->
    <div class="er-card">

        <div class="er-card-head">

            <div>
                <h3>Subject-wise Result</h3>
                <p><?= html_escape($terms[$selected_term] ?? 'Examination'); ?></p>
            </div>

            <span class="er-badge <?= $overall_status == 'FAIL' ? 'fail' : ''; ?>">
                <?= $overall_status; ?>
            </span>

        </div>

        <div class="er-scroll">

            <table class="er-table">

                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Subject</th>
                        <th class="er-center">Maximum Marks</th>
                        <th class="er-center">Obtained Marks</th>
                        <th class="er-center">Grade</th>
                        <th class="er-center">Status</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($results as $i => $r): ?>

                    <tr>
                        <td><?= $i+1; ?></td>

                        <td><strong><?= html_escape($r['subject']); ?></strong></td>

                        <td class="er-center"><?= $r['max_marks']; ?></td>

                        <td class="er-center">
                            <strong><?= $r['obtained']; ?></strong>
                        </td>

                        <td class="er-center">
                            <span class="er-grade"><?= $r['grade']; ?></span>
                        </td>

                        <td class="er-center">
                            <span class="<?= $r['status'] == 'Pass' ? 'er-pass' : 'er-fail'; ?>">
                                <i class="fa <?= $r['status'] == 'Pass' ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                                <?= $r['status']; ?>
                            </span>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="2">Grand Total</td>
                        <td class="er-center"><?= $total_max; ?></td>
                        <td class="er-center"><?= $total_obtained; ?></td>
                        <td class="er-center"><?= $overall_grade; ?></td>
                        <td class="er-center"><?= $overall_status; ?></td>
                    </tr>
                </tfoot>

            </table>

        </div>

    </div>

    <!-- OVERALL RESULT -->
    <div class="er-overall">

        <div>
            <h3><i class="fa fa-trophy"></i> Overall Examination Result</h3>

            <p>
                <?= $overall_status == 'PASS'
                    ? 'Congratulations! Successfully passed the examination.'
                    : 'Please improve your academic performance.'; ?>
            </p>
        </div>

        <div class="er-percent">
            <strong><?= $percentage; ?>%</strong>
            <span>Overall Percentage</span>
        </div>

    </div>

    <!-- TEACHER REMARKS -->
    <div class="er-remarks">

        <h4><i class="fa fa-comment-dots"></i> Class Teacher Remarks</h4>

        <p><?= html_escape($remarks); ?></p>

    </div>

    <!-- SIGNATURE -->
    <div class="er-footer">

        <div>
            <i class="fa fa-graduation-cap"></i>
            Student Academic Report
            <br>
            Academic Year: <?= html_escape($academic_year); ?>
        </div>

        <div class="er-sign">Class Teacher</div>

        <div class="er-sign">Principal</div>

    </div>

    <?php else: ?>

        <div class="er-card er-empty">
            <i class="fa fa-folder-open fa-3x"></i>
            <h3>Results Not Published</h3>
            <p>Examination results are not available for this term yet.</p>
        </div>

    <?php endif; ?>

    <!-- ACTION BUTTONS -->
    <div class="er-actions">

        <button type="button" class="er-btn er-print"
                onclick="window.print()">
            <i class="fa fa-print"></i> Print Result
        </button>

        <button type="button" class="er-btn er-download"
                onclick="window.print()">
            <i class="fa fa-download"></i> Download PDF
        </button>

    </div>

</div>
</div>

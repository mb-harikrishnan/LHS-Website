<?php
/*
=====================================================================
 ASSUMPTIONS ABOUT DATA PASSED FROM THE CONTROLLER
=====================================================================
 Route:  edit_marks/{studentId}/{examId}

 Controller should load something like:

    $student  = $this->Student_model->getById($studentId);
    // expects: $student->id, $student->name, $student->className, $student->divName

    $exam     = $this->Exam_model->getById($examId);
    // expects: $exam->emId, $exam->emName

    $subjects = $this->Subject_model->getByExam($examId);
    // array of rows, each expects: ->subId, ->subName, ->maxMarks

    $marks    = $this->Marks_model->getStudentExamMarks($studentId, $examId);
    // associative array: [ subId => obtainedMarks ]  (empty array if none entered yet)

 Rename the variables below if your models return different property names.
=====================================================================
*/

$pageTitle    = 'Edit Marks';
$breadcrumb   = 'Reports / Edit Marks';
$activePage   = 'reports';
$showGlobalSearch = false;
?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/report.css'); ?>">

<style>
.info-strip {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
    padding: 16px 20px;
    background: #f5f6fb;
    border-bottom: 1px solid #e5e7eb;
}
.info-strip .info-item label {
    display: block;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #6b7280;
    font-weight: 600;
    margin-bottom: 2px;
}
.info-strip .info-item span {
    font-size: 15px;
    font-weight: 600;
    color: #1a1a4d;
}
.marks-table th, .marks-table td {
    vertical-align: middle;
}
.marks-table td.marks-input-cell {
    width: 160px;
}
.marks-input {
    width: 100%;
    padding: 6px 10px;
    border: 1px solid #999;
    border-radius: 4px;
    font-size: 14px;
    text-align: center;
}
.marks-input:focus {
    outline: none;
    border-color: #2b2fa0;
    box-shadow: 0 0 0 2px rgba(43,47,160,0.15);
}
.marks-input.is-invalid {
    border-color: #dc2626;
}
.max-marks-cell {
    color: #6b7280;
    font-weight: 500;
}
.form-actions {
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}
.btn-save {
    background-color: #2b2fa0;
    color: #fff;
    border: none;
    padding: 10px 28px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
}
.btn-save:hover {
    background-color: #22267f;
}
.btn-cancel {
    color: #6c2bd9;
    text-decoration: underline;
    font-weight: 600;
    font-size: 15px;
}
.btn-cancel:hover {
    color: #4b1c99;
}
.no-data-msg {
    text-align: center;
    padding: 30px 20px;
    color: #6b7280;
    font-size: 15px;
    font-weight: 500;
}
</style>

<!-- ============================================================
     Student / Exam Summary
============================================================= -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-head">
        <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Exam Summary
        </div>
    </div>

 <div class="info-strip">
    <div class="info-item">
        <label>Student Name</label>
        <span><?php echo htmlspecialchars($student->smName); ?></span>
    </div>
    <div class="info-item">
        <label>Class</label>
        <span><?php echo htmlspecialchars($student->className); ?></span>
    </div>
    <div class="info-item">
        <label>Division</label>
        <span><?php echo htmlspecialchars($student->divName); ?></span>
    </div>
    <div class="info-item">
        <label>Exam</label>
        <span><?php echo htmlspecialchars($exam->emName); ?></span>
    </div>
</div>
</div>

<!-- ============================================================
     Marks Edit Form
============================================================= -->
<div class="card">
    <div class="card-head">
        <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                <path d="M12 20h9"/>
                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
            </svg>
            Edit Marks
        </div>
        <button class="card-action" onclick="window.location.href='<?php echo base_url('Marksentry_list'); ?>'">
            <i class="fa fa-arrow-left"></i> Back to List
        </button>
    </div>

    <?php if (!empty($subjects)): ?>

   <form method="POST" action="<?php echo base_url('edit_marks/'.$studentId.'/'.$examId); ?>">
    <table class="table table-bordered marks-table">
        <thead>
            <tr><th>Subject</th><th>Mark</th></tr>
        </thead>
        <tbody>
        <?php foreach ($subjects as $sub): ?>
            <tr>
                <td><?php echo htmlspecialchars($sub->smName); ?></td>
                <td class="marks-input-cell">
                    <input type="number" 
                           name="marks[<?php echo $sub->esId; ?>]"
                           value="<?php echo htmlspecialchars($sub->edMark ?? ''); ?>"
                           class="marks-input">
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="form-actions">
        <button type="submit" class="btn-save">Save Marks</button>
        <a href="<?php echo base_url('Marksentry_list'); ?>" class="btn-cancel">Back to list</a>
    </div>
</form>

    <?php else: ?>

        <div class="no-data-msg">
            No subjects found for this exam.
        </div>

    <?php endif; ?>
</div>

<!-- ============================================================
     Vendor Scripts
============================================================= -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


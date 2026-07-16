<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/exam.css'); ?>">

<!-- Marks Entry Card -->
<div class="card">
    <div class="card-head">
        <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Enter Marks
        </div>
    </div>

    <!-- ================= FILTERS: Class / Division / Exam =================
         Class list is already restricted server-side (controller index())
         to the class(es) assigned to the logged-in employee. -->
    <div class="form-row">

        <div class="news-form-group">
            <label>Class</label>
            <select id="class" class="news-select select2">
                <option value="">Select Class</option>
                <?php foreach ($class as $classvalue) { ?>
                    <option value="<?php echo $classvalue->cmId; ?>">
                        <?php echo $classvalue->cmName; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="news-form-group">
            <label>Division</label>
            <select id="division" class="news-select select2">
                <option value="">Select Division</option>
                <?php foreach ($divition as $divitionvalue) { ?>
                    <option value="<?php echo $divitionvalue->dmId; ?>">
                        <?php echo $divitionvalue->dmName; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="news-form-group">
            <label>Exam</label>
            <select id="exam" class="news-select select2">
                <option value="">Select Exam</option>
                <?php foreach ($exam as $examvalue) { ?>
                    <option value="<?php echo $examvalue->emId; ?>">
                        <?php echo $examvalue->emName; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

    </div>

    <!-- ================= MARKS TABLE ================= -->
    <div id="marksTableWrapper" style="margin-top:20px; overflow-x:auto; display:none;">
        <table class="table" id="marksTable" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr id="marksTableHead"></tr>
            </thead>
            <tbody id="marksTableBody">
                <!-- rows injected by JS -->
            </tbody>
        </table>

        <div class="news-btn-group" style="margin-top:16px;">
            <button type="button" id="saveMarksBtn" class="submit-btn">
                <i class="fa fa-save"></i> Save Marks
            </button>
        </div>
    </div>

    <div id="noStudentsMsg" style="margin-top:20px; display:none; color:#888;">
        No students found for the selected Class / Division.
    </div>

    <div id="noSubjectsMsg" style="margin-top:20px; display:none; color:#888;">
        No subjects configured for the selected Class / Exam.
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    #marksTable th, #marksTable td {
        border: 1px solid #ddd;
        padding: 6px 8px;
        text-align: center;
        font-size: 13px;
    }
    #marksTable thead th {
        background: #f4f4f4;
        font-weight: 600;
    }
    #marksTable td.student-name-cell {
        text-align: left;
        white-space: nowrap;
    }
    #marksTable input.mark-input {
        width: 55px;
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 4px;
    }
    #marksTable input.mark-input:focus {
        outline: none;
        border-color: var(--green, #16a34a);
        box-shadow: 0 0 0 2px rgba(22,163,74,0.15);
    }
</style>





<script>



    $(document).ready(function () {

    $('.select2').select2();

    $("#class,#division,#exam").change(function () {

        let class_id = $("#class").val();
        let division_id = $("#division").val();
        let exam_id = $("#exam").val();

      

        loadMarksTable(class_id, division_id, exam_id);

    });

});





function loadMarksTable(class_id, division_id, exam_id)
{

    $.ajax({

        url: "<?php echo site_url('getMarksEntry');?>",
        type: "POST",
        dataType: "json",

        data:{
            class_id:class_id,
            division_id:division_id,
            exam_id:exam_id
        },

        success:function(res){

            if(res.status=="success")
            {
                buildTable(res.students,res.subjects);
            }
            else
            {
                Swal.fire("Info",res.message,"info");
            }

        }

    });

}


function buildTable(students, subjects)
{

    let head='';

    head += "<th>SL</th>";
    head += "<th>Admission No</th>";
    head += "<th>Student Name</th>";

    $.each(subjects,function(i,s){

        head += "<th>"+s.smName+"</th>";

    });

    $("#marksTableHead").html(head);

    let body='';

    $.each(students,function(i,st){

        body+="<tr>";

        body+="<td>"+(i+1)+"</td>";
        body+="<td>"+st.smAdmissionNo+"</td>";
        body+="<td class='student-name-cell'>"+st.smName+"</td>";
$.each(subjects, function(j, sub){

    body += "<td>";
    body += "<input type='text' class='mark-input' " +
            "data-row='"+i+"' " +
            "data-col='"+j+"' " +
            "name='marks["+st.smId+"]["+sub.smId+"]'>";
    body += "</td>";

});

        body+="</tr>";

    });

    $("#marksTableBody").html(body);

    $("#marksTableWrapper").show();

}
</script>


<script>
    $(document).on("keydown", ".mark-input", function(e){

    let row = parseInt($(this).data("row"));
    let col = parseInt($(this).data("col"));

    let next;

    switch(e.which){

        // Enter
        case 13:
            e.preventDefault();
            next = $(".mark-input[data-row='"+(row+1)+"'][data-col='"+col+"']");
            if(next.length) next.focus();
            break;

        // Right Arrow
        case 39:
            e.preventDefault();
            next = $(".mark-input[data-row='"+row+"'][data-col='"+(col+1)+"']");
            if(next.length) next.focus();
            break;

        // Left Arrow
        case 37:
            e.preventDefault();
            next = $(".mark-input[data-row='"+row+"'][data-col='"+(col-1)+"']");
            if(next.length) next.focus();
            break;

        // Down Arrow
        case 40:
            e.preventDefault();
            next = $(".mark-input[data-row='"+(row+1)+"'][data-col='"+col+"']");
            if(next.length) next.focus();
            break;

        // Up Arrow
        case 38:
            e.preventDefault();
            next = $(".mark-input[data-row='"+(row-1)+"'][data-col='"+col+"']");
            if(next.length) next.focus();
            break;
    }

});
</script>

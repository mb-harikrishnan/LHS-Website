<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/exam.css'); ?>">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

      <!-- Reports Table Card -->
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
            </svg>
            Edit Mark Allocation
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('allocation_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>
<form id="editForm">

<?php
$className = $allocation[0]->cmName;
$examName  = $allocation[0]->emDisplayName;
?>

<input type="hidden" name="emId" value="<?php echo $allocation[0]->emdEmId;?>">
<input type="hidden" name="cmId" value="<?php echo $allocation[0]->emdCmId;?>">

<div class="news-form-group">
    <label>Class</label>
    <input type="text"
           class="news-select"
           value="<?php echo $className;?>"
           readonly>
</div>

<div class="news-form-group">
    <label>Exam</label>
    <input type="text"
           class="news-select"
           value="<?php echo $examName;?>"
           readonly>
</div>
<?php foreach($allocation as $row){ ?>

<div class="allocation-card">

    <div class="subject-info">
        <label class="subject-label">
            <?php echo $row->smName; ?>
        </label>

        <input type="hidden"
               name="emdId[]"
               value="<?php echo $row->emdId; ?>">

        <input
            type="number"
            class="marks-input"
            name="marks[]"
            value="<?php echo $row->emdMaxMark; ?>"
            placeholder="Maximum Mark">
    </div>

    <button
        type="button"
        class="delete-row-btn"
        data-emdid="<?php echo $row->emdId; ?>">
<i class="fa-solid fa-trash"></i>    </button>

</div>

<?php } ?>
<div class="news-btn-group">
    <button type="submit" class="submit-btn">
        Update
    </button>
</div>

</form>
      </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<?php if($this->session->flashdata('success')){ ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '<?php echo $this->session->flashdata("success"); ?>',
    confirmButtonColor: '#16a34a',
    timer: 2500,
    showConfirmButton: false
});
</script>
<?php } ?>

<?php if($this->session->flashdata('error')){ ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '<?php echo $this->session->flashdata("error"); ?>',
    confirmButtonColor: '#dc2626'
});
</script>
<?php } ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<style>

 .allocation-card{
    display:flex;
    align-items:flex-end;
    gap:10px;

    padding:10px;
    margin-bottom:11px;

    border:1px solid #e5e7eb;
    border-radius:12px;
    background:#fff;
    box-shadow:0 2px 8px rgba(0,0,0,.05);
}

.subject-info{
    flex:1;
}

.subject-label{
    display:block;
    font-size:10px;
    font-weight:600;
    color:#374151;
    margin-bottom:10px;
    text-transform:uppercase;
}

/* Full width input like Exam field */
.marks-input{
    width:100%;
    height:30px;

    border:1px solid #d1d5db;
    border-radius:10px;

    padding:0 15px;
    font-size:16px;
    background:#fff;

    transition:.3s;
    box-sizing:border-box;
}

.marks-input:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.12);
}

.delete-row-btn{
    width:50px;
    height:30px;

    border:none;
    border-radius:10px;

    background:#ef4444;
    color:#fff;

    display:flex;
    align-items:center;
    justify-content:center;

    cursor:pointer;
    transition:.3s;
}

.delete-row-btn i{
    font-size:18px;
}

.delete-row-btn:hover{
    background:#dc2626;
}
    /* Multiple Select2 */
.select2-container--default .select2-selection--multiple{
    min-height:40px !important;
    border:1px solid #d1d5db !important;
    border-radius:8px !important;
    background:#f9fafb !important;
    padding:4px 10px !important;
    display:flex !important;
    align-items:center;
}

.select2-container--default.select2-container--focus .select2-selection--multiple{
    border-color:#2563eb !important;
    box-shadow:0 0 0 3px rgba(37,99,235,.12);
}

/* Selected items */
.select2-container--default .select2-selection--multiple .select2-selection__choice{
    background:#2563eb !important;
    border:none !important;
    color:#fff !important;
    border-radius:5px !important;
    padding:2px 8px !important;
    margin-top:3px !important;
}

.select2-container--default .select2-selection__choice__remove{
    color:#fff !important;
    margin-right:5px;
}

/* Search input */
.select2-container--default .select2-search--inline .select2-search__field{
    margin-top:5px !important;
    font-size:14px;
}

/* Dropdown */
.select2-dropdown{
    border-radius:8px;
    border:1px solid #d1d5db;
}
</style>
<script>
    $("#editForm").submit(function(e){

    e.preventDefault();

    $.ajax({

        url:"<?php echo base_url('update_allocation');?>",

        type:"POST",

        data:$(this).serialize(),

        dataType:"json",

        success:function(res){

            if(res.status=="success")
            {
                Swal.fire({
                    icon:'success',
                    title:'Updated',
                    text:'Allocation Updated Successfully'
                }).then(function(){

                    window.location.href="<?php echo base_url('allocation_list');?>";

                });
            }
            else
            {
                Swal.fire('Error',res.message,'error');
            }

        }

    });

});
</script>


<script>
    $(document).on('click', '.delete-row-btn', function(){

    var btn = $(this);
    var emdId = btn.data('emdid');
var row = btn.closest('.allocation-card');
    Swal.fire({
        icon: 'warning',
        title: 'Delete this allocation?',
        text: 'This cannot be undone.',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete',
        confirmButtonColor: '#dc2626'
    }).then(function(result){

        if(!result.isConfirmed) return;

        $.ajax({
            url: "<?php echo base_url('delete_allocation'); ?>",
            type: "POST",
            data: { emdId: emdId },
            dataType: "json",
            success: function(res){
                if(res.status == "success"){
                    row.remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function(){
                Swal.fire('Error', 'Something went wrong', 'error');
            }
        });
    });
});
</script>
<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/exam.css'); ?>">

      <!-- Reports Table Card -->
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
            </svg>
            Add Term
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('term_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>


<form id="newsform" method="post">

<div class="news-form-group">
    <label>Term <span style="color:red">*</span></label>
    <input type="text"
           id="term"
           name="term"
           class="news-select"
           placeholder="Enter Term">
    <small id="term_error" style="color:red;display:none;"></small>
</div>

<div class="news-form-group">
    <label>Code <span style="color:red">*</span></label>
    <input type="text"
           id="code"
           name="code"
           class="news-select"
           placeholder="Enter Code">
    <small id="code_error" style="color:red;display:none;"></small>
</div>

    <div class="news-btn-group">
        <button type="submit" class="submit-btn">
            <i class="fa fa-save"></i> Submit
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

<script>
$(function(){

    function checkTerm(){

        $.ajax({
            url:"<?php echo base_url('check_term');?>",
            type:"POST",
            data:{
                term:$("#term").val().trim(),
                code:$("#code").val().trim()
            },
            dataType:"json",
            success:function(res){

                $("#term_error").hide();
                $("#code_error").hide();

                if(res.term=="exists"){
                    $("#term_error").html("Term already exists.").show();
                }

                if(res.code=="exists"){
                    $("#code_error").html("Code already exists.").show();
                }

            }
        });

    }

    $("#term,#code").on("keyup blur",checkTerm);

    $("#newsform").submit(function(e){

        e.preventDefault();

        if($("#term").val().trim()==""){
            $("#term_error").html("Enter Term").show();
            return;
        }

        if($("#code").val().trim()==""){
            $("#code_error").html("Enter Code").show();
            return;
        }

        if($("#term_error").is(":visible") || $("#code_error").is(":visible")){
            return;
        }

        $.ajax({

            url:"<?php echo base_url('insert_term');?>",
            type:"POST",
            data:$(this).serialize(),
            dataType:"json",

            success:function(res){

                if(res.status=="success"){
                    Swal.fire("Success",res.message,"success");
                    $("#newsform")[0].reset();
                }else{
                    Swal.fire("Error",res.message,"error");
                }

            }

        });

    });

});

</script>
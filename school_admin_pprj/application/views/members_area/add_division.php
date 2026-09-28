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
            Add Division
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('divition_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>

<form id="divisionform" method="post">

    <div class="news-form-group">
        <label for="division_name">
            Division Name <span style="color:red">*</span>
        </label>

        <input type="text"
               id="division_name"
               name="division_name"
               class="news-select"
               placeholder="Enter Division Name">

        <small id="division_error" style="color:red;display:none;"></small>
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
    $(document).ready(function(){

    $("#division_name").on("keyup blur", function(){

        var division_name = $.trim($(this).val());

        if(division_name=="")
        {
            $("#division_error").html("Division Name is required").show();
            return;
        }

        $("#division_error").hide();

        $.ajax({

            url:"<?php echo base_url('check_divition'); ?>",
            type:"POST",
            data:{division_name:division_name},
            dataType:"json",

            success:function(response){

                if(response.status=="exists")
                {
                    $("#division_error").html("Division already exists.").show();
                }
                else
                {
                    $("#division_error").hide();
                }

            }

        });

    });


    $("#divisionform").submit(function(e){

        e.preventDefault();

        if($("#division_name").val().trim()=="")
        {
            $("#division_error").html("Division Name is required").show();
            return false;
        }

        if($("#division_error").is(":visible"))
            return false;

        $.ajax({

            url:"<?php echo base_url('insert_divition'); ?>",
            type:"POST",
            data:$(this).serialize(),
            dataType:"json",

            success:function(response){

                if(response.status=="success")
                {
                    Swal.fire({
                        icon:"success",
                        title:"Success",
                        text:response.message
                    });

                    $("#divisionform")[0].reset();
                }
                else
                {
                    Swal.fire({
                        icon:"error",
                        title:"Error",
                        text:response.message
                    });
                }

            }

        });

    });

});
</script>
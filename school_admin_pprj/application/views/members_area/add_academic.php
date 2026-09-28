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
            Add Academics
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('accademic_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>


<form id="newsform" method="post">

 <div class="news-form-group">
    <label for="academic_year">
        Academic Year <span style="color:red">*</span>
    </label>

    <input type="text"
           id="academic_year"
           name="academic_year"
           class="news-select"
           placeholder="Example: 2026-27"
           maxlength="7">

    <small id="year_error" style="color:red;display:none;"></small>
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
$(function () {

    // Allow only digits and one hyphen
    $("#academic_year").on("input", function () {

        let value = $(this).val();

        value = value.replace(/[^0-9-]/g, "");

        if (value.length > 7)
            value = value.substring(0,7);

        $(this).val(value);

    });

    // Check format + duplicate
    $("#academic_year").on("keyup blur", function(){

        var year = $.trim($(this).val());

        var pattern = /^\d{4}-\d{2}$/;

        if(year=="")
        {
            $("#year_error").html("Academic Year is required").show();
            return;
        }

        if(!pattern.test(year))
        {
            $("#year_error").html("Format should be YYYY-YY (Example: 2026-27)").show();
            return;
        }

        $("#year_error").hide();

        $.ajax({

            url:"<?php echo base_url('check_academic_year');?>",
            type:"POST",
            data:{academic_year:year},
            dataType:"json",

            success:function(res){

                if(res.status=="exists")
                {
                    $("#year_error").html("Academic Year already exists.").show();
                }
                else
                {
                    $("#year_error").hide();
                }

            }

        });

    });


    $("#newsform").submit(function(e){

        e.preventDefault();

        if($("#year_error").is(":visible"))
            return;

        $.ajax({

            url:"<?php echo base_url('insert_academic');?>",
            type:"POST",
            data:$(this).serialize(),
            dataType:"json",

            success:function(res){

                if(res.status=="success")
                {
                    Swal.fire({
                        icon:"success",
                        title:"Success",
                        text:res.message
                    });

                    $("#newsform")[0].reset();
                }
                else
                {
                    Swal.fire({
                        icon:"error",
                        title:"Error",
                        text:res.message
                    });
                }

            }

        });

    });

});

</script>
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
            Add Class
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('class_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>


<form id="newsform" method="post">

    <div class="news-form-group">
        <label for="class_name">Class Name <span style="color:red">*</span></label>

        <input type="text"
               id="class_name"
               name="class_name"
               class="news-select"
               placeholder="Enter Class Name">

        <small id="class_error" style="color:red;display:none;"></small>
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

    // Check class already exists
  $("#class_name").on("keyup blur", function () {

    var class_name = $.trim($(this).val());

    // Required validation
    if (class_name == "") {
        $("#class_error")
            .html("Class Name is required")
            .show();
        return;
    }

    // Hide required error immediately
    $("#class_error").hide();

    $.ajax({
        url: "<?php echo base_url('check_class_name'); ?>",
        type: "POST",
        data: { class_name: class_name },
        dataType: "json",
        success:function(response){

                if(response.status=="invalid"){
                    $("#class_error").html(response.message).show();
                }
                else if(response.status=="exists"){
                    $("#class_error").html("Class already exists.").show();
                }
                else{
                    $("#class_error").hide();
                }

            }
    });

});



    // Submit Form
    $("#newsform").submit(function(e){

        e.preventDefault();

        var class_name=$("#class_name").val().trim();

        if(class_name=="")
        {
            $("#class_error")
                .html("Class Name is required")
                .show();

            return false;
        }

        if($("#class_error").is(":visible"))
        {
            return false;
        }

        $.ajax({

            url:"<?php echo base_url('insert_class'); ?>",
            type:"POST",
            data:$(this).serialize(),
            dataType:"json",

            success:function(response){

                if(response.status=="success")
                {

                    Swal.fire({
                        icon:'success',
                        title:'Success',
                        text:response.message
                    });

                    $("#newsform")[0].reset();

                }
                else
                {

                    Swal.fire({
                        icon:'error',
                        title:'Error',
                        text:response.message
                    });

                }

            }

        });

    });

});

</script>
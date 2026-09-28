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
            Add Role
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('user_role_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>


<form id="newsform" method="post">

<div class="news-form-group">
    <label>Role Name <span style="color:red">*</span></label>

    <input type="text"
           id="role_name"
           name="role_name"
           class="news-select"
           placeholder="Enter Role Name">

    <small id="role_error" style="color:red;display:none;"></small>
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

    $("#role_name").on("keyup blur", function(){

        var role = $.trim($(this).val());

        if(role=="")
        {
            $("#role_error").html("Role Name is required").show();
            return;
        }

        $.ajax({

            url:"<?php echo base_url('check_role');?>",
            type:"POST",
            data:{role_name:role},
            dataType:"json",

            success:function(res){

                if(res.status=="exists")
                {
                    $("#role_error").html("Role already exists.").show();
                }
                else
                {
                    $("#role_error").hide();
                }

            }

        });

    });


    $("#newsform").submit(function(e){

        e.preventDefault();

        var role = $("#role_name").val().trim();

        if(role=="")
        {
            $("#role_error").html("Role Name is required").show();
            return false;
        }

        if($("#role_error").is(":visible"))
        {
            return false;
        }

        $.ajax({

            url:"<?php echo base_url('insert_role');?>",
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
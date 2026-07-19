<?php
$pageTitle = 'Edit Class';
$breadcrumb = 'Edit Class';
$activePage = 'reports';
?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/exam.css'); ?>">

<div class="card">

    <div class="card-head">
        <div class="card-title">
            <i class="fa fa-edit"></i> Edit Class
        </div>

        <button class="card-action"
            onclick="window.location.href='<?php echo base_url('class_list');?>'">
            <i class="fa fa-list"></i> List
        </button>
    </div>

    <form id="editClassForm">

        <input type="hidden"
               name="cmId"
               value="<?php echo $class->cmId; ?>">

        <div class="news-form-group">

            <label>
                Class Name <span style="color:red">*</span>
            </label>

            <input
                type="text"
                id="class_name"
                name="class_name"
                class="news-select"
                value="<?php echo $class->cmName; ?>"
                placeholder="Enter Class Name">

            <small
                id="class_error"
                style="color:red;display:none;">
            </small>

        </div>

        <div class="news-btn-group">

            <button type="submit" class="submit-btn">
                <i class="fa fa-save"></i> Update
            </button>

        </div>

    </form>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$("#class_name").on("keyup blur", function(){

    var class_name = $.trim($(this).val());
    var id = $("input[name='cmId']").val();

    if(class_name=="")
    {
        $("#class_error")
        .html("Class Name is required")
        .show();
        return;
    }

    $.ajax({

        url:"<?php echo base_url('check_class_name');?>",
        type:"POST",
        data:{
            class_name:class_name,
            id:id
        },
        dataType:"json",

        success:function(res){

            if(res.status=="exists")
            {
                $("#class_error")
                .html("Class already exists.")
                .show();
            }
            else
            {
                $("#class_error").hide();
            }

        }

    });

});

$("#editClassForm").submit(function(e){

    e.preventDefault();

    if($("#class_error").is(":visible"))
    {
        return false;
    }

    $.ajax({

        url:"<?php echo base_url('update_class');?>",
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
                }).then(function(){

                    window.location="<?php echo base_url('class_list');?>";

                });

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

</script>
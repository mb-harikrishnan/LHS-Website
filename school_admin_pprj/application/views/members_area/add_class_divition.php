<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/exam.css'); ?>">

<div class="card cd-form-card">
  <div class="card-head">
    <div class="card-title">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2" stroke-linecap="round">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
      </svg>
      Add Class Divition
    </div>
    <button class="card-action" onclick="window.location.href='<?php echo base_url('class_divition_list'); ?>'">
      <i class="fa fa-list"></i> List
    </button>
  </div>

  <form id="classDivForm" method="post" action="<?php echo base_url('insert_class_division'); ?>">

   <div class="cd-group">
  <label class="cd-label" for="cmId">Select class</label>
  <select name="cmId" id="cmId" class="cd-select" >
    <option value="">-- Select class --</option>
    <?php if(!empty($classes)){ foreach($classes as $class){ ?>
      <option value="<?php echo $class->cmId; ?>"><?php echo $class->cmName; ?></option>
    <?php } } ?>
  </select>
</div>

<div class="cd-group">
  <label class="cd-label">Select divitions</label>

  <?php if(!empty($divisions)){ ?>
    <div class="cd-division-grid">
      <?php foreach($divisions as $division){ ?>
        <label class="cd-chip">
          <input type="checkbox" name="dmId[]" value="<?php echo $division->dmId; ?>">
          <span><?php echo $division->dmName; ?></span>
        </label>
      <?php } ?>
    </div>
  <?php } else { ?>
    <div class="cd-empty">No divitions found. Add a divition first.</div>
  <?php } ?>
</div>
    <div class="cd-btn-row">
      <button type="submit" class="cd-submit"><i class="fa fa-save"></i> Submit</button>
    </div>

  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
$(document).ready(function(){

    $.validator.addMethod('atLeastOneChecked', function(value, element){
        return $('input[name="dmId[]"]:checked').length > 0;
    }, 'Please select at least one divition.');

    $('#classDivForm').validate({
        rules: {
            cmId: {
                required: true
            },
            'dmId[]': {
                required: true,
                atLeastOneChecked: true
            }
        },
        messages: {
            cmId: {
                required: 'Please select a class.'
            },
            'dmId[]': {
                required: 'Please select at least one divition.'
            }
        },
        errorPlacement: function(error, element){
            if(element.attr('name') === 'dmId[]'){
                error.insertAfter('.cd-division-grid');
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element){
            if($(element).attr('name') === 'dmId[]'){
                $('.cd-division-grid').addClass('cd-input-error');
            } else {
                $(element).addClass('cd-input-error');
            }
        },
        unhighlight: function(element){
            if($(element).attr('name') === 'dmId[]'){
                $('.cd-division-grid').removeClass('cd-input-error');
            } else {
                $(element).removeClass('cd-input-error');
            }
        },
        submitHandler: function(form){
            form.submit();
        }
    });

});
</script>

<?php if($this->session->flashdata('success')){ ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '<?php echo $this->session->flashdata("success"); ?>',
    confirmButtonColor: '#1e3a8a',
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
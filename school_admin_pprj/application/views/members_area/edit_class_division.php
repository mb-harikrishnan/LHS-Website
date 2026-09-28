<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>

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

  <form id="classDivForm" method="post" action="<?php echo base_url('update_class_division'); ?>">
   <div class="cd-group">
  <label class="cd-label" for="cmId">Select class</label>
    <input type="hidden" name="cmId" value="<?php echo $edit->cmId; ?>">

<select id="cmId" class="cd-select" disabled>
        <option value="">-- Select Class --</option>

        <?php foreach($classes as $class){ ?>

            <option
                value="<?php echo $class->cmId;?>"
                <?php if($edit->cmId==$class->cmId){ echo "selected"; } ?>>
                <?php echo $class->cmName;?>
            </option>

        <?php } ?>

    </select>
</div>

<div class="cd-group">
  <label class="cd-label">Select divitions</label>

  <?php if(!empty($divisions)){ ?>
    <div class="cd-division-grid">

<?php foreach($divisions as $division){ ?>

<label class="cd-chip">

<input
type="checkbox"
name="dmId[]"
value="<?php echo $division->dmId;?>"

<?php
if(in_array($division->dmId,$selected_divisions))
{
    echo "checked";
}
?>

>

<span><?php echo $division->dmName;?></span>

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

<style>
.cd-form-card .cd-group{
    margin-bottom:24px;
}
.cd-form-card .cd-label{
    display:block;
    margin-bottom:10px;
    font-size:14px;
    font-weight:700;
    color:#374151 !important;
    letter-spacing:.3px;
}
.cd-form-card .cd-select{
    width:100%;
    height:52px;
    border:1px solid #dbe4ee;
    background:#f8fafc;
    border-radius:14px;
    padding:0 16px;
    font-size:15px;
    color:#111827;
    outline:none;
    transition:.3s ease;
}
.cd-form-card .cd-select:focus{
    background:#fff;
    border-color:#1e3a8a;
    box-shadow:0 0 0 4px rgba(30,58,138,.12);
}
.cd-form-card .cd-division-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
    gap:10px;
    border:1px solid #dbe4ee;
    background:#f8fafc;
    border-radius:14px;
    padding:16px;
}
.cd-form-card .cd-chip{
    display:flex;
    align-items:center;
    gap:8px;
    background:#fff;
    border:1px solid #e5e9f0;
    border-radius:10px;
    padding:10px 12px;
    font-size:14px;
    font-weight:500;
    color:#374151 !important;
    cursor:pointer;
    transition:.2s ease;
}
.cd-form-card .cd-chip:hover{
    border-color:#1e3a8a;
}
.cd-form-card .cd-chip input[type="checkbox"]{
    width:16px;
    height:16px;
    accent-color:#1e3a8a;
    cursor:pointer;
    flex-shrink:0;
}
.cd-form-card .cd-chip:has(input:checked){
    background:#eef2ff;
    border-color:#1e3a8a;
}
.cd-form-card .cd-empty{
    border:1px dashed #dbe4ee;
    border-radius:14px;
    padding:20px;
    text-align:center;
    font-size:14px;
    color:#9ca3af;
    background:#f8fafc;
}
.cd-form-card .cd-btn-row{
    margin-top:10px;
}
.cd-form-card .cd-submit{
    border:none;
    outline:none;
    background:linear-gradient(135deg,#1e3a8a,#2f4fb0);
    color:#fff;
    padding:15px 34px;
    border-radius:14px;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    transition:.3s ease;
    box-shadow:0 12px 25px rgba(30,58,138,.25);
}
.cd-form-card .cd-submit:hover{
    transform:translateY(-3px);
    box-shadow:0 18px 35px rgba(30,58,138,.35);
}
.cd-form-card .cd-submit:active{
    transform:scale(.98);
}
.card{
    background:#fff;
    border-radius:24px;
    padding:35px;
    box-shadow:0 10px 40px rgba(0,0,0,0.06);
    border:1px solid #eef2f7;
}
.card-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:35px;
    flex-wrap:wrap;
    gap:15px;
}
.card-title{
    display:flex;
    align-items:center;
    gap:12px;
    font-size:20px;
    font-weight:700;
    color:#111827;
}
.card-action{
    border:none;
    outline:none;
    background:linear-gradient(135deg,#1e3a8a,#2f4fb0);
    color:#fff;
    padding:12px 22px;
    border-radius:12px;
    font-weight:600;
    cursor:pointer;
    transition:.3s ease;
    box-shadow:0 8px 20px rgba(30,58,138,.25);
}
.card-action:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 25px rgba(30,58,138,.35);
}
@media(max-width:768px){
    .card{ padding:22px; }
    .card-head{ flex-direction:column; align-items:flex-start; }
    .card-action{ width:100%; }
}
.cd-form-card label.error{
    display:block;
    margin-top:8px;
    font-size:13px;
    font-weight:600;
    color:#dc2626;
}
.cd-form-card .cd-input-error{
    border-color:#dc2626 !important;
}
.cd-form-card .cd-division-grid.cd-input-error{
    border-color:#dc2626 !important;
    box-shadow:0 0 0 4px rgba(220,38,38,.10);
}
</style>

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

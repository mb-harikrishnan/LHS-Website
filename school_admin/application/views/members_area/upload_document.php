    <?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;




?>


     
      <!-- Reports Table Card -->
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
            </svg>
            Upload Documents
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('general_information'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>






                    <form method="post" enctype="multipart/form-data"  action="<?php echo base_url('add_document'); ?>">

                        <div class="upload-form-grid">

                            <!-- Document Type -->
                            <div class="form-group-modern">
                                <label>Select Document Type</label>

                                <select name="document_type" class="modern-select" required>
                                    <option value="">Choose Document Type</option>
                                     <option value="general_information">General Information</option>
                                    <option value="copy_of_affiliation">Copies of Affiliation</option>
                                    <option value="copy_of_societies">Copies of Societies</option>
                                    <option value="NOC">NOC</option>
                                    <option value="copy_of_recognition">Copies of Recognition</option>
                                    <option value="copy_of_safty">Building Safety Certificate</option>
                                    <option value="copy_of_fire_and_safety">Fire Safety Certificate</option>
                                    <option value="DEO">DEO Certificate</option>
                                    <option value="sanitation">Water, Health and Sanitation Certificates</option>
                                    <option value="land">Certificate of Land</option>
                                </select>
                            </div>

                        

                        </div>

                        <!-- Upload Area -->
                        <div class="form-group-modern" style="margin-top:25px;">

                            <label>Upload PDF Document</label>

                            <div class="upload-box">

                                <input type="file" 
                                    name="document_file" 
                                    id="documentFile"
                                    accept=".pdf"
                                    required>

                                <div class="upload-icon">
                                    <i class="fa fa-cloud-upload"></i>
                                </div>

                                <div class="upload-title">
                                    Drag & Drop PDF Here
                                </div>

                                <div class="upload-sub">
                                    or click to browse file
                                </div>

                                <!-- Preview INSIDE Upload Box -->
                                <div class="file-preview" id="filePreview">

                                    <div class="preview-icon">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </div>

                                    <div class="preview-details">
                                        <div class="preview-name" id="fileName"></div>
                                        <div class="preview-size" id="fileSize"></div>
                                    </div>

                                </div>

                            </div>

                           
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="submit-btn-modern">
                            <i class="fa fa-save"></i> Upload Document
                        </button>

                    </form>



            
               
            

       


       
       
      </div>



<style>
    /* =========================
   CARD
========================= */
.card{
    background:#fff;
    border-radius:20px;
    padding:24px;
    box-shadow:0 6px 24px rgba(0,0,0,0.05);
    border:1px solid #edf2f7;
    overflow:hidden;
}

/* =========================
   CARD HEADER
========================= */
.card-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    margin-bottom:24px;
    padding-bottom:18px;
    border-bottom:1px solid #f1f5f9;
}

.card-title{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:18px;
    font-weight:700;
    color:#1f2937;
}

/* =========================
   ACTION BUTTON
========================= */
.card-action{
    border:none;
    background:linear-gradient(135deg,#2f855a,#38a169);
    color:#fff;
    padding:10px 18px;
    border-radius:10px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
    display:flex;
    align-items:center;
    gap:8px;
}

.card-action:hover{
    transform:translateY(-1px);
    box-shadow:0 8px 18px rgba(56,161,105,.25);
}

/* =========================
   FORM GRID
========================= */
.upload-form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:20px;
    margin-bottom:20px;
}

/* =========================
   FORM GROUP
========================= */
.form-group-modern{
    width:100%;
}

.form-group-modern label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
    font-weight:600;
    color:#374151;
}

/* =========================
   SELECT BOX
========================= */
.modern-select{
    width:100%;
    height:48px;
    padding:0 14px;
    border-radius:12px;
    border:1px solid #d1d5db;
    background:#fff;
    font-size:14px;
    color:#111827;
    outline:none;
    transition:.3s;
}

.modern-select:focus{
    border-color:#38a169;
    box-shadow:0 0 0 3px rgba(56,161,105,.12);
}

/* =========================
   UPLOAD BOX
========================= */
.upload-box{
    position:relative;
    border:2px dashed #38a169;
    border-radius:18px;
    padding:35px 20px;
    text-align:center;
    background:#f8fffb;
    transition:.3s;
}

.upload-box:hover{
    background:#f0fff4;
    border-color:#2f855a;
}

.upload-box input[type="file"]{
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    opacity:0;
    cursor:pointer;
}

.upload-icon{
    width:70px;
    height:70px;
    border-radius:50%;
    background:#dcfce7;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 14px;
    font-size:28px;
    color:#15803d;
}

.upload-title{
    font-size:18px;
    font-weight:700;
    color:#111827;
    margin-bottom:6px;
}

.upload-sub{
    font-size:13px;
    color:#6b7280;
}

/* =========================
   FILE PREVIEW
========================= */
.file-preview{
    margin-top:20px;
    display:none;
    align-items:center;
    gap:14px;
    background:#fff;
    border:1px solid #dcfce7;
    padding:14px;
    border-radius:14px;
}

.preview-icon{
    width:48px;
    height:48px;
    border-radius:12px;
    background:#fee2e2;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#dc2626;
    font-size:22px;
}

.preview-details{
    flex:1;
    overflow:hidden;
}

.preview-name{
    font-size:14px;
    font-weight:700;
    color:#111827;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.preview-size{
    font-size:12px;
    color:#6b7280;
    margin-top:3px;
}

/* =========================
   SUBMIT BUTTON
========================= */
.submit-btn-modern{
    width:100%;
    border:none;
    background:linear-gradient(135deg,#2f855a,#38a169);
    color:#fff;
    padding:14px;
    border-radius:14px;
    font-size:15px;
    font-weight:700;
    margin-top:24px;
    cursor:pointer;
    transition:.3s;
}

.submit-btn-modern:hover{
    transform:translateY(-1px);
    box-shadow:0 10px 24px rgba(56,161,105,.25);
}

/* =========================
   RESPONSIVE
========================= */
@media(max-width:768px){

    .card{
        padding:18px;
        border-radius:16px;
    }

    .card-head{
        flex-direction:column;
        align-items:flex-start;
    }

    .card-action{
        width:100%;
        justify-content:center;
    }

    .upload-box{
        padding:28px 15px;
    }

    .upload-title{
        font-size:16px;
    }
}
</style>

<script>
document.getElementById('documentFile').addEventListener('change', function(e){

    const file = e.target.files[0];

    if(file){

        document.getElementById('filePreview').style.display = 'flex';

        document.getElementById('fileName').innerText = file.name;

        document.getElementById('fileSize').innerText =
            (file.size / 1024 / 1024).toFixed(2) + ' MB';
    }
});
</script>

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

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function () {

    $('form').on('submit', function (e) {

        e.preventDefault();

        let form = this;

        let document_type = $('select[name="document_type"]').val();

        $.ajax({
            url: "<?php echo base_url('check_document_exist'); ?>",
            type: "POST",
            data: {
                document_type: document_type
            },
            dataType: "json",

            success: function (response) {

                // DOCUMENT EXISTS
                if (response.status == 'exists') {

                    Swal.fire({
                        icon: 'warning',
                        title: 'PDF Already Exists',
                        text: 'Delete existing PDF first.',
                        confirmButtonColor: '#22c55e',
                        width: '350px'
                    });

                     form.reset();

            // HIDE FILE PREVIEW
            $('#filePreview').hide();

                } else {

                    // SUBMIT FORM
                    form.submit();
                }
            }
        });

    });

});
</script>

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
              onclick="window.location.href='<?php echo base_url('Result_and_Staff'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>






                    <form method="post" enctype="multipart/form-data"  action="<?php echo base_url('add_document_details'); ?>">

                        <div class="upload-form-grid">

                            <!-- Document Type -->
                            <div class="form-group-modern">
                                <label>Select Document Type</label>

                                <select name="document_type" class="modern-select" required>
                                    <option value="">Choose Document Type</option>
                                      <option value="fee_structure">Fee Structure</option>
                                    <option value="anual_academic_calendar">Annual Academic Calendar</option>
                                    <option value="school_managment_comitte">School Management Committee</option>
                                    <option value="pta_members">PTA Members</option>
                                    <option value="3_yers_board_exam">3 Years Board Exam</option>
                                    <option value="staff_details">Staff Details</option>
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
   PAGE HEADER
========================= */
.page-header{
    margin-bottom: 28px;
}

.page-eyebrow{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:13px;
    font-weight:600;
    color:#16a34a;
    text-transform:uppercase;
    letter-spacing:.08em;
    margin-bottom:10px;
}

.eyebrow-pulse{
    width:10px;
    height:10px;
    border-radius:50%;
    background:#22c55e;
    animation:pulse 1.8s infinite;
}

@keyframes pulse{
    0%{transform:scale(.9);opacity:.7;}
    70%{transform:scale(1.4);opacity:0;}
    100%{transform:scale(.9);opacity:0;}
}

.page-title{
    font-size:34px;
    font-weight:800;
    color:#111827;
    margin:0;
}

.page-sub{
    color:#6b7280;
    margin-top:8px;
    font-size:15px;
}

/* =========================
   CARD
========================= */
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

/* =========================
   ACTION BUTTON
========================= */
.card-action{
    border:none;
    outline:none;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:#fff;
    padding:12px 22px;
    border-radius:12px;
    font-weight:600;
    cursor:pointer;
    transition:.3s ease;
    box-shadow:0 8px 20px rgba(34,197,94,.25);
}

.card-action:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 25px rgba(34,197,94,.35);
}

/* =========================
   FORM GRID
========================= */
.upload-form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:22px;
}

/* =========================
   FORM GROUP
========================= */
.form-group-modern label{
    display:block;
    margin-bottom:10px;
    font-size:14px;
    font-weight:700;
    color:#374151;
}

/* =========================
   SELECT
========================= */
.modern-select{
    width:100%;
    padding:14px 16px;
    border-radius:14px;
    border:1px solid #d1d5db;
    background:#f9fafb;
    font-size:15px;
    transition:.3s ease;
    outline:none;
}

.modern-select:focus{
    border-color:#22c55e;
    background:#fff;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

/* =========================
   UPLOAD BOX
========================= */
.upload-box{
    position:relative;
    border:2px dashed #22c55e;
    border-radius:24px;
    padding:45px 25px;
    text-align:center;
    background:linear-gradient(to bottom,#f0fdf4,#ffffff);
    transition:.3s ease;
    overflow:hidden;
}

.upload-box:hover{
    transform:translateY(-2px);
    box-shadow:0 15px 35px rgba(34,197,94,.12);
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
    width:85px;
    height:85px;
    border-radius:50%;
    background:#dcfce7;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 18px;
    font-size:34px;
    color:#16a34a;
}

.upload-title{
    font-size:20px;
    font-weight:700;
    color:#111827;
    margin-bottom:8px;
}

.upload-sub{
    color:#6b7280;
    font-size:14px;
}

/* =========================
   FILE PREVIEW
========================= */
.file-preview{
    margin-top:28px;
    display:none;
    align-items:center;
    gap:16px;
    background:#fff;
    border:1px solid #dcfce7;
    padding:16px;
    border-radius:16px;
    text-align:left;
    box-shadow:0 6px 18px rgba(0,0,0,0.04);
}

.preview-icon{
    width:52px;
    height:52px;
    border-radius:12px;
    background:#fee2e2;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#dc2626;
    font-size:24px;
    flex-shrink:0;
}

.preview-details{
    overflow:hidden;
}

.preview-name{
    font-weight:700;
    color:#111827;
    font-size:15px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.preview-size{
    color:#6b7280;
    font-size:13px;
    margin-top:4px;
}

/* =========================
   SUBMIT BUTTON
========================= */
.submit-btn-modern{
    margin-top:32px;
    width:100%;
    border:none;
    outline:none;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:#fff;
    padding:16px;
    border-radius:16px;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
    transition:.3s ease;
    box-shadow:0 12px 28px rgba(34,197,94,.25);
}

.submit-btn-modern:hover{
    transform:translateY(-2px);
    box-shadow:0 16px 34px rgba(34,197,94,.35);
}

/* =========================
   RESPONSIVE
========================= */
@media(max-width:768px){

    .card{
        padding:22px;
    }

    .page-title{
        font-size:26px;
    }

    .card-head{
        flex-direction:column;
        align-items:flex-start;
    }

    .card-action{
        width:100%;
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
            url: "<?php echo base_url('check_result_exist'); ?>",
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

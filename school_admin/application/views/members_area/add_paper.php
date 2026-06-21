    <?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;




?>


    
    <!-- PAGE CONTENT -->
      <div class="page-header">
        <div class="page-eyebrow">
          <div class="eyebrow-pulse"></div>
          Uploads
        </div>
        <h1 class="page-title">Upload Questionpaper
          <!-- <em>Reports</em> -->
        </h1>
        <p class="page-sub"></p>
      </div>

     
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
              onclick="window.location.href='<?php echo base_url('questionpaper_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>





<form id="newsform"
                        method="post"
                        enctype="multipart/form-data"
                        action="<?php echo base_url('insert_paper'); ?>">

                        <!-- News Type Selector -->
                        <div class="news-form-group">

                            <label>
                                Class
                            </label>

                            <select name="class_pdf"
                                id="class_pdf"
                                class="news-select">

                                <option value="">Select Class</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>


                            </select>

                        </div>

                    

                   

                     <!-- Image Upload -->
<!-- PDF Upload -->
<div class="news-form-group">

    <label>
        Upload PDF
    </label>

    <div class="upload-area" id="uploadArea">

        <input type="file"
            name="pdf"
            id="pdf"
            accept="application/pdf"
            hidden>

        <div class="upload-content" id="uploadContent">

            <div class="upload-icon">
                <i class="fa fa-file-pdf-o"></i>
            </div>

            <h4>Drag & Drop PDF Here</h4>

            <p>or click to browse files</p>

            <button type="button" class="browse-btn">
                Choose PDF
            </button>

        </div>

        <!-- PDF Preview -->
        <div class="image-preview" id="imagePreview" style="display:none;">

            <div style="padding:20px; text-align:center;">

                <i class="fa fa-file-pdf-o"
                   style="font-size:70px;color:#dc2626;"></i>

                <p id="pdfName"
                   style="margin-top:15px;font-weight:600;"></p>

            </div>

            <button type="button"
                    class="remove-image"
                    id="removeImage">

                <i class="fa fa-times"></i>

            </button>

        </div>

    </div>

</div>

                        <!-- Buttons -->
                        <div class="news-btn-group">

                            <button type="submit" class="submit-btn">
                                <i class="fa fa-save"></i> Submit
                            </button>

                        </div>

                    </form>



            
               
            

       


       
       
      </div>

     
<style>
/* =========================
   PAGE HEADER
========================= */
.page-header{
    margin-bottom:30px;
}

.page-eyebrow{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:13px;
    font-weight:700;
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
    70%{transform:scale(1.5);opacity:0;}
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
    margin-top:6px;
    font-size:15px;
}

/* =========================
   CARD
========================= */
.card{
    background:#fff;
    border-radius:28px;
    padding:35px;
    box-shadow:0 12px 40px rgba(15,23,42,.08);
    border:1px solid #eef2f7;
}

/* =========================
   CARD HEAD
========================= */
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
    font-size:22px;
    font-weight:700;
    color:#111827;
}

.card-action{
    border:none;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:#fff;
    padding:12px 22px;
    border-radius:14px;
    font-weight:600;
    cursor:pointer;
    transition:.3s ease;
    box-shadow:0 10px 24px rgba(34,197,94,.25);
}

.card-action:hover{
    transform:translateY(-2px);
}

/* =========================
   FORM
========================= */
#newsform{
    width:100%;
}

.news-form-group{
    margin-bottom:28px;
}

.news-form-group label{
    display:block;
    margin-bottom:12px;
    font-size:15px;
    font-weight:700;
    color:#374151;
}

/* =========================
   SELECT DESIGN
========================= */
.news-select{
    width:100%;
    height:56px;
    border-radius:16px;
    border:1px solid #d1d5db;
    padding:0 18px;
    font-size:15px;
    background:#f9fafb;
    color:#111827;
    outline:none;
    transition:.3s ease;
}

.news-select:focus{
    border-color:#22c55e;
    background:#fff;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

/* =========================
   UPLOAD AREA
========================= */
.upload-area{
    position:relative;
    border:2px dashed #22c55e;
    border-radius:24px;
    background:linear-gradient(to bottom,#f0fdf4,#ffffff);
    padding:45px 20px;
    text-align:center;
    transition:.3s ease;
    overflow:hidden;
    cursor:pointer;
}

.upload-area:hover{
    transform:translateY(-2px);
    box-shadow:0 15px 35px rgba(34,197,94,.15);
}

.upload-content h4{
    margin-top:18px;
    margin-bottom:10px;
    font-size:22px;
    font-weight:700;
    color:#111827;
}

.upload-content p{
    color:#6b7280;
    margin-bottom:20px;
    font-size:14px;
}

.upload-icon{
    width:90px;
    height:90px;
    margin:auto;
    border-radius:50%;
    background:#dcfce7;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:38px;
    color:#16a34a;
}

.browse-btn{
    border:none;
    background:#16a34a;
    color:#fff;
    padding:12px 24px;
    border-radius:14px;
    font-weight:600;
    cursor:pointer;
    transition:.3s ease;
}

.browse-btn:hover{
    background:#15803d;
}

/* =========================
   PDF PREVIEW
========================= */
.image-preview{
    position:relative;
    background:#fff;
    border-radius:20px;
    border:1px solid #dcfce7;
    box-shadow:0 8px 25px rgba(0,0,0,.05);
    overflow:hidden;
}

#pdfName{
    color:#111827;
    font-size:16px;
    word-break:break-word;
}

.remove-image{
    position:absolute;
    top:12px;
    right:12px;
    width:38px;
    height:38px;
    border:none;
    border-radius:50%;
    background:#ef4444;
    color:#fff;
    cursor:pointer;
    transition:.3s ease;
}

.remove-image:hover{
    transform:scale(1.08);
}

/* =========================
   BUTTONS
========================= */
.news-btn-group{
    margin-top:35px;
}

.submit-btn{
    width:100%;
    height:58px;
    border:none;
    border-radius:18px;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:#fff;
    font-size:17px;
    font-weight:700;
    cursor:pointer;
    transition:.3s ease;
    box-shadow:0 14px 30px rgba(34,197,94,.25);
}

.submit-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 18px 34px rgba(34,197,94,.35);
}

/* =========================
   RESPONSIVE
========================= */
@media(max-width:768px){

    .card{
        padding:22px;
        border-radius:22px;
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

    .upload-content h4{
        font-size:18px;
    }
}
</style>

<script>
const uploadArea = document.getElementById("uploadArea");
const fileInput = document.getElementById("pdf");
const uploadContent = document.getElementById("uploadContent");
const imagePreview = document.getElementById("imagePreview");
const pdfName = document.getElementById("pdfName");
const removeBtn = document.getElementById("removeImage");
const browseBtn = document.querySelector(".browse-btn");

// Open file chooser
uploadArea.addEventListener("click", () => {
    fileInput.click();
});

browseBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    fileInput.click();
});

// File selected
fileInput.addEventListener("change", function(){

    const file = this.files[0];

    if(file){

        pdfName.innerText = file.name;

        uploadContent.style.display = "none";
        imagePreview.style.display = "block";
    }
});

// Remove selected file
removeBtn.addEventListener("click", function(e){

    e.stopPropagation();

    fileInput.value = "";

    imagePreview.style.display = "none";
    uploadContent.style.display = "block";
});

/* =========================
   DRAG & DROP
========================= */
uploadArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    uploadArea.style.borderColor = "#15803d";
});

uploadArea.addEventListener("dragleave", () => {
    uploadArea.style.borderColor = "#22c55e";
});

uploadArea.addEventListener("drop", (e) => {

    e.preventDefault();

    uploadArea.style.borderColor = "#22c55e";

    const files = e.dataTransfer.files;

    if(files.length > 0){

        fileInput.files = files;

        pdfName.innerText = files[0].name;

        uploadContent.style.display = "none";
        imagePreview.style.display = "block";
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
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
        <h1 class="page-title">Upload Images
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
            Upload Images
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('gallery'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>






                    <form id="newsform"
                        method="post"
                        enctype="multipart/form-data"
                        action="<?php echo base_url('insert_school_image'); ?>">

                        <!-- News Type Selector -->
                        <div class="news-form-group">

                            <label>
                                Image Category
                            </label>

                            <select name="news_type"
                                id="news_type"
                                class="news-select">

                                <option value="">Select Category</option>
                                <option value="annual_day">Annual Day</option>
                                <option value="blue_day">Blue Day</option>


                            </select>

                        </div>

                    

                   

                     <!-- Image Upload -->
<div class="news-form-group">

    <label>
        Upload  Image
    </label>

    <div class="upload-area" id="uploadArea">

        <input type="file"
            name="news_image"
            id="news_image"
            accept="image/*"
            hidden>

        <div class="upload-content" id="uploadContent">

            <div class="upload-icon">
                <i class="fa fa-cloud-upload"></i>
            </div>

            <h4>Drag & Drop Image Here</h4>

            <p>or click to browse files</p>

            <button type="button" class="browse-btn">
                Choose Image
            </button>

        </div>

        <!-- Preview -->
        <div class="image-preview" id="imagePreview" style="display:none;">

            <img id="previewImg" src="" alt="Preview">

            <button type="button" class="remove-image" id="removeImage">
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
/* =========================================
   PAGE HEADER
========================================= */
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
    0%{
        transform:scale(.9);
        opacity:.7;
    }
    70%{
        transform:scale(1.4);
        opacity:0;
    }
    100%{
        transform:scale(.9);
        opacity:0;
    }
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

/* =========================================
   CARD
========================================= */
.card{
    background:#ffffff;
    border-radius:26px;
    padding:35px;
    box-shadow:0 10px 35px rgba(0,0,0,0.06);
    border:1px solid #eef2f7;
}

.card-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:35px;
}

.card-title{
    display:flex;
    align-items:center;
    gap:12px;
    font-size:21px;
    font-weight:700;
    color:#111827;
}

/* =========================================
   ACTION BUTTON
========================================= */
.card-action{
    border:none;
    outline:none;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:#fff;
    padding:12px 22px;
    border-radius:14px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:.3s ease;
    box-shadow:0 10px 22px rgba(34,197,94,.22);
}

.card-action:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 28px rgba(34,197,94,.32);
}

/* =========================================
   FORM
========================================= */
#newsform{
    width:100%;
}

.news-form-group{
    margin-bottom:28px;
}

.news-form-group label{
    display:block;
    margin-bottom:12px;
    font-size:14px;
    font-weight:700;
    color:#374151;
}

/* =========================================
   SELECT BOX
========================================= */
.news-select{
    width:100%;
    padding:15px 18px;
    border-radius:16px;
    border:1px solid #d1d5db;
    background:#f9fafb;
    font-size:15px;
    color:#111827;
    transition:.3s ease;
    outline:none;
}

.news-select:focus{
    border-color:#22c55e;
    background:#fff;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

/* =========================================
   UPLOAD AREA
========================================= */
.upload-area{
    position:relative;
    border:2px dashed #22c55e;
    border-radius:24px;
    background:linear-gradient(to bottom,#f0fdf4,#ffffff);
    padding:45px 25px;
    text-align:center;
    cursor:pointer;
    transition:.35s ease;
    overflow:hidden;
}

.upload-area:hover{
    transform:translateY(-2px);
    box-shadow:0 18px 35px rgba(34,197,94,.12);
}

.upload-area.dragover{
    background:#dcfce7;
    border-color:#16a34a;
}

.upload-content{
    pointer-events:none;
}

.upload-icon{
    width:85px;
    height:85px;
    border-radius:50%;
    background:#dcfce7;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 20px;
    font-size:34px;
    color:#16a34a;
}

.upload-content h4{
    margin:0 0 8px;
    font-size:22px;
    font-weight:700;
    color:#111827;
}

.upload-content p{
    margin:0 0 20px;
    color:#6b7280;
    font-size:14px;
}

.browse-btn{
    border:none;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:#fff;
    padding:12px 22px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    box-shadow:0 8px 18px rgba(34,197,94,.2);
}

/* =========================================
   IMAGE PREVIEW
========================================= */
.image-preview{
    position:relative;
    margin-top:20px;
    display:flex;
    justify-content:center;
    align-items:center;
}

.image-preview img{
    width:100%;
    max-width:420px;
    max-height:320px;
    object-fit:cover;
    border-radius:18px;
    border:1px solid #d1fae5;
    box-shadow:0 12px 25px rgba(0,0,0,0.08);
}

.remove-image{
    position:absolute;
    top:-12px;
    right:-12px;
    width:36px;
    height:36px;
    border:none;
    border-radius:50%;
    background:#ef4444;
    color:#fff;
    cursor:pointer;
    font-size:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 8px 20px rgba(239,68,68,.3);
}

/* =========================================
   BUTTON GROUP
========================================= */
.news-btn-group{
    margin-top:35px;
}

.submit-btn{
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
    box-shadow:0 14px 28px rgba(34,197,94,.25);
}

.submit-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 18px 34px rgba(34,197,94,.35);
}

/* =========================================
   RESPONSIVE
========================================= */
@media(max-width:768px){

    .card{
        padding:22px;
        border-radius:20px;
    }

    .page-title{
        font-size:28px;
    }

    .card-head{
        flex-direction:column;
        align-items:flex-start;
    }

    .card-action{
        width:100%;
    }

    .upload-area{
        padding:35px 18px;
    }

    .upload-content h4{
        font-size:18px;
    }

    .submit-btn{
        font-size:15px;
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



<script>
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('news_image');
const preview = document.getElementById('imagePreview');
const previewImg = document.getElementById('previewImg');
const removeBtn = document.getElementById('removeImage');

/* Open file chooser */
uploadArea.addEventListener('click', () => {
    fileInput.click();
});

/* File change */
fileInput.addEventListener('change', function () {

    const file = this.files[0];

    if(file){
        showPreview(file);
    }
});

/* Drag events */
uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {

    e.preventDefault();

    uploadArea.classList.remove('dragover');

    const file = e.dataTransfer.files[0];

    if(file){

        fileInput.files = e.dataTransfer.files;

        showPreview(file);
    }
});

/* Preview Function */
function showPreview(file){

    const reader = new FileReader();

    reader.onload = function(e){

        preview.style.display = 'flex';

        previewImg.src = e.target.result;

        document.getElementById('uploadContent').style.display = 'none';
    }

    reader.readAsDataURL(file);
}

/* Remove image */
removeBtn.addEventListener('click', function(e){

    e.stopPropagation();

    fileInput.value = '';

    preview.style.display = 'none';

    previewImg.src = '';

    document.getElementById('uploadContent').style.display = 'block';
});
</script>
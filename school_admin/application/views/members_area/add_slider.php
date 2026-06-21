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
        <h1 class="page-title">Upload Slider
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
            Upload Slider
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('slider_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>

 <form id="newsform"
                        method="post"
                        enctype="multipart/form-data"
                        action="<?php echo base_url('insert_slider'); ?>">

                       

                        <!-- Title -->
                        <div class="news-form-group">

                            <label>Title</label>

                            <input type="text"
                                name="title"
                                id="title"
                                class="news-input"
                                placeholder="Enter title">

                        </div>

                        <!-- Description -->
                        <div class="news-form-group">

                            <label>Description</label>

                            <textarea name="description"
                                    id="description"
                                    class="news-textarea"
                                    placeholder="Enter description"></textarea>

                        </div>

                        <!-- Upload Type -->
                        <div class="news-form-group">

                            <label>Upload Type</label>

                            <select name="upload_type"
                                    id="upload_type"
                                    class="news-select">

                                <option value="">Select Type</option>
                                <option value="image">Image</option>
                                <option value="video">Video</option>
                                <option value="link">External Link</option>

                            </select>

                        </div>

                        <!-- Image Upload -->
                      <!-- Image Upload -->
<div class="news-form-group upload-section" id="imageSection" style="display:none;">

    <label>Upload Image</label>

    <div class="custom-upload-box">
        <input type="file"
            name="news_image"
            id="news_image"
            class="news-file"
            accept="image/*">

        <div class="upload-content">
            <i class="fa fa-image upload-icon"></i>
            <p>Click to upload image</p>
            <span>PNG, JPG, JPEG</span>
        </div>
    </div>

    <!-- IMAGE PREVIEW -->
    <div class="image-preview-box" id="imagePreviewBox" style="display:none;">
        <img id="imagePreview" src="">
    </div>

</div>


<!-- Video Upload -->
<div class="news-form-group upload-section" id="videoSection" style="display:none;">

    <label>Upload Video</label>

    <div class="custom-upload-box">
        <input type="file"
            name="news_video"
            id="news_video"
            class="news-file"
            accept="video/mp4,video/webm">

        <div class="upload-content">
            <i class="fa fa-video-camera upload-icon"></i>
            <p>Click to upload video</p>
            <span>MP4, WEBM</span>
        </div>
    </div>

    <!-- VIDEO NAME -->
    <div class="video-file-name" id="videoFileName" style="display:none;"></div>

</div>


<!-- External Link -->
<div class="news-form-group upload-section" id="linkSection" style="display:none;">

    <label>Enter Link</label>

    <input type="url"
        name="external_link"
        id="external_link"
        class="news-input"
        placeholder="https://example.com">

</div>

                        <!-- Submit -->
                        <div class="news-btn-group">

                            <button type="submit" class="submit-btn">
                                <i class="fa fa-save"></i> Submit
                            </button>

                        </div>

                    </form>

       
       
      </div>

     <style>


/* =========================
   CUSTOM UPLOAD BOX
========================= */
.custom-upload-box{
    position:relative;
    border:2px dashed #86efac;
    border-radius:18px;
    background:#f0fdf4;
    padding:35px 20px;
    text-align:center;
    overflow:hidden;
    transition:.3s ease;
}

.custom-upload-box:hover{
    background:#ecfdf5;
    border-color:#22c55e;
}

.custom-upload-box input[type="file"]{
    position:absolute;
    width:100%;
    height:100%;
    left:0;
    top:0;
    opacity:0;
    cursor:pointer;
}

.upload-content{
    pointer-events:none;
}

.upload-icon{
    font-size:42px;
    color:#16a34a;
    margin-bottom:12px;
}

.upload-content p{
    margin:0;
    font-size:16px;
    font-weight:700;
    color:#111827;
}

.upload-content span{
    color:#6b7280;
    font-size:13px;
}

/* =========================
   IMAGE PREVIEW
========================= */
.image-preview-box{
    margin-top:18px;
    text-align:center;
}

.image-preview-box img{
    max-width:100%;
    max-height:300px;
    border-radius:16px;
    border:1px solid #e5e7eb;
    padding:6px;
    background:#fff;
    box-shadow:0 5px 18px rgba(0,0,0,0.06);
}

/* =========================
   VIDEO FILE NAME
========================= */
.video-file-name{
    margin-top:15px;
    background:#f9fafb;
    border:1px solid #d1d5db;
    padding:12px 15px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    color:#374151;
}
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
    border:1px solid #edf2f7;
    box-shadow:0 10px 35px rgba(0,0,0,0.05);
}

.card-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
    margin-bottom:35px;
}

.card-title{
    display:flex;
    align-items:center;
    gap:12px;
    font-size:22px;
    font-weight:700;
    color:#111827;
}

/* =========================
   ACTION BUTTON
========================= */
.card-action{
    border:none;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:#fff;
    padding:12px 22px;
    border-radius:14px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:.3s ease;
    box-shadow:0 10px 25px rgba(34,197,94,.25);
}

.card-action:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 28px rgba(34,197,94,.35);
}

/* =========================
   FORM
========================= */
#newsform{
    display:flex;
    flex-direction:column;
    gap:24px;
}

/* =========================
   FORM GROUP
========================= */
.news-form-group{
    display:flex;
    flex-direction:column;
}

.news-form-group label{
    font-size:14px;
    font-weight:700;
    color:#374151;
    margin-bottom:10px;
}

/* =========================
   INPUTS
========================= */
.news-input,
.news-select,
.news-textarea,
.news-file{
    width:100%;
    padding:15px 16px;
    border-radius:16px;
    border:1px solid #d1d5db;
    background:#f9fafb;
    font-size:15px;
    transition:.3s ease;
    outline:none;
    color:#111827;
}

.news-input:focus,
.news-select:focus,
.news-textarea:focus,
.news-file:focus{
    border-color:#22c55e;
    background:#fff;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

/* =========================
   TEXTAREA
========================= */
.news-textarea{
    min-height:130px;
    resize:vertical;
}

/* =========================
   FILE INPUT
========================= */
.news-file{
    padding:13px;
    cursor:pointer;
    background:#f0fdf4;
    border:2px dashed #86efac;
}

.news-file:hover{
    background:#ecfdf5;
}

/* =========================
   UPLOAD SECTION
========================= */
.upload-section{
    background:#fafafa;
    border:1px solid #e5e7eb;
    padding:22px;
    border-radius:18px;
    animation:fadeIn .3s ease;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(8px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* =========================
   SUBMIT BUTTON
========================= */
.news-btn-group{
    margin-top:10px;
}

.submit-btn{
    width:100%;
    border:none;
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

.submit-btn:hover{
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function(){

    // UPLOAD TYPE CHANGE
    $('#upload_type').on('change', function(){

        let type = $(this).val();

        $('.upload-section').hide();

        if(type === 'image'){
            $('#imageSection').fadeIn();
        }
        else if(type === 'video'){
            $('#videoSection').fadeIn();
        }
        else if(type === 'link'){
            $('#linkSection').fadeIn();
        }

    });

    // IMAGE PREVIEW
    $('#news_image').on('change', function(e){

        const file = e.target.files[0];

        if(file){

            let reader = new FileReader();

            reader.onload = function(event){

                $('#imagePreview').attr('src', event.target.result);

                $('#imagePreviewBox').fadeIn();

            }

            reader.readAsDataURL(file);

        }

    });

    // VIDEO FILE NAME
    $('#news_video').on('change', function(e){

        const file = e.target.files[0];

        if(file){

            $('#videoFileName')
                .html('<i class="fa fa-video-camera"></i> ' + file.name)
                .fadeIn();

        }

    });

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
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
        <h1 class="page-title">Upload Video
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
            Upload Video
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

    <div class="form-grid">

        <!-- TITLE -->
        <div class="news-form-group full-width">
            <label>Title</label>

            <input type="text"
                   name="title"
                   id="title"
                   class="news-input"
                   placeholder="Enter title">
        </div>

        <!-- DESCRIPTION -->
        <div class="news-form-group full-width">
            <label>Description</label>

            <textarea name="description"
                      id="description"
                      class="news-textarea"
                      placeholder="Enter description"></textarea>
        </div>

        <!-- UPLOAD TYPE -->
        <div class="news-form-group">
            <label>Upload Type</label>

            <select name="upload_type"
                    id="upload_type"
                    class="news-select">

                <option value="">Select Upload Type</option>
                <option value="image">Image</option>
                <option value="video">Video</option>
                <option value="link">External Link</option>

            </select>
        </div>

        <!-- IMAGE SECTION -->
        <div class="news-form-group upload-section full-width"
             id="imageSection">

            <label>Upload Image</label>

            <div class="upload-box">

                <input type="file"
                       name="news_image"
                       id="news_image"
                       accept="image/*">

                <div class="upload-icon">
                    <i class="fa fa-image"></i>
                </div>

                <div class="upload-title">
                    Choose Image
                </div>

                <div class="upload-sub">
                    JPG, PNG, JPEG files allowed
                </div>

            </div>

            <div class="file-preview" id="imagePreview">
                <div class="preview-icon">
                    <i class="fa fa-file-image"></i>
                </div>

                <div class="preview-details">
                    <div class="preview-name" id="imageName"></div>
                    <div class="preview-size" id="imageSize"></div>
                </div>
            </div>

        </div>

        <!-- VIDEO SECTION -->
        <div class="news-form-group upload-section full-width"
             id="videoSection">

            <label>Upload Video</label>

            <div class="upload-box">

                <input type="file"
                       name="news_video"
                       id="news_video"
                       accept="video/webm">

                <div class="upload-icon">
                    <i class="fa fa-video-camera"></i>
                </div>

                <div class="upload-title">
                    Choose WEBM Video
                </div>

                <div class="upload-sub">
                    Only WEBM video allowed
                </div>

            </div>

            <div class="file-preview" id="videoPreview">
                <div class="preview-icon">
                    <i class="fa fa-file-video-o"></i>
                </div>

                <div class="preview-details">
                    <div class="preview-name" id="videoName"></div>
                    <div class="preview-size" id="videoSize"></div>
                </div>
            </div>

        </div>

        <!-- LINK SECTION -->
        <div class="news-form-group upload-section full-width"
             id="linkSection">

            <label>External Link</label>

            <input type="url"
                   name="external_link"
                   id="external_link"
                   class="news-input"
                   placeholder="https://example.com">

        </div>

    </div>

    <!-- SUBMIT -->
    <div class="news-btn-group">

        <button type="submit" class="submit-btn">
            <i class="fa fa-save"></i> Submit
        </button>

    </div>

</form>


            
               
            

       


       
       
      </div>

     

<style>
/* =========================
   FORM DESIGN
========================= */

.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:24px;
}

.full-width{
    grid-column:1/-1;
}

.news-form-group{
    display:flex;
    flex-direction:column;
}

.news-form-group label{
    margin-bottom:10px;
    font-size:14px;
    font-weight:700;
    color:#374151;
}

/* =========================
   INPUT / SELECT / TEXTAREA
========================= */

.news-input,
.news-select,
.news-textarea{
    width:100%;
    border:1px solid #d1d5db;
    background:#f9fafb;
    border-radius:16px;
    padding:14px 16px;
    font-size:15px;
    color:#111827;
    transition:.3s ease;
    outline:none;
}

.news-input:focus,
.news-select:focus,
.news-textarea:focus{
    border-color:#22c55e;
    background:#fff;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

.news-textarea{
    min-height:130px;
    resize:vertical;
}

/* =========================
   UPLOAD BOX
========================= */

.upload-box{
    position:relative;
    border:2px dashed #22c55e;
    border-radius:22px;
    padding:40px 20px;
    text-align:center;
    background:linear-gradient(to bottom,#f0fdf4,#ffffff);
    transition:.3s ease;
    overflow:hidden;
}

.upload-box:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(34,197,94,.12);
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
    width:80px;
    height:80px;
    border-radius:50%;
    background:#dcfce7;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 18px;
    font-size:32px;
    color:#16a34a;
}

.upload-title{
    font-size:20px;
    font-weight:700;
    color:#111827;
    margin-bottom:8px;
}

.upload-sub{
    font-size:14px;
    color:#6b7280;
}

/* =========================
   FILE PREVIEW
========================= */

.file-preview{
    margin-top:20px;
    display:none;
    align-items:center;
    gap:15px;
    border:1px solid #dcfce7;
    background:#fff;
    padding:16px;
    border-radius:16px;
}

.preview-icon{
    width:50px;
    height:50px;
    border-radius:12px;
    background:#dcfce7;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#16a34a;
    font-size:22px;
}

.preview-name{
    font-weight:700;
    color:#111827;
}

.preview-size{
    font-size:13px;
    color:#6b7280;
    margin-top:3px;
}

/* =========================
   BUTTON
========================= */

.news-btn-group{
    margin-top:30px;
}

.submit-btn{
    border:none;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:#fff;
    padding:15px 28px;
    border-radius:16px;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    transition:.3s ease;
    box-shadow:0 10px 25px rgba(34,197,94,.25);
}

.submit-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 30px rgba(34,197,94,.35);
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:768px){

    .form-grid{
        grid-template-columns:1fr;
    }

}
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>

$(document).ready(function () {

    // Hide all sections initially
    $('#imageSection').hide();
    $('#videoSection').hide();
    $('#linkSection').hide();

    // Upload type change
    $('#upload_type').on('change', function () {

        var type = $(this).val();

        // Hide all first
        $('#imageSection').hide();
        $('#videoSection').hide();
        $('#linkSection').hide();

        // Show selected section
        if (type == 'image') {

            $('#imageSection').slideDown();

        } else if (type == 'video') {

            $('#videoSection').slideDown();

        } else if (type == 'link') {

            $('#linkSection').slideDown();
        }

    });

    // IMAGE PREVIEW
    $('#news_image').on('change', function () {

        let file = this.files[0];

        if (file) {

            $('#imagePreview').css('display', 'flex');

            $('#imageName').text(file.name);

            $('#imageSize').text(
                (file.size / 1024 / 1024).toFixed(2) + ' MB'
            );
        }

    });

    // VIDEO PREVIEW
    $('#news_video').on('change', function () {

        let file = this.files[0];

        if (file) {

            let extension = file.name.split('.').pop().toLowerCase();

            // Allow only WEBM
            if (extension != 'webm') {

                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File',
                    text: 'Only WEBM video allowed'
                });

                $('#news_video').val('');
                $('#videoPreview').hide();

                return false;
            }

            $('#videoPreview').css('display', 'flex');

            $('#videoName').text(file.name);

            $('#videoSize').text(
                (file.size / 1024 / 1024).toFixed(2) + ' MB'
            );
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
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
      action="<?php echo base_url('update_slider'); ?>">

    <div class="form-grid">

        <!-- TITLE -->
        <div class="news-form-group full-width">
            <label>Title</label>

            <input type="text"
                   name="title"
                   id="title"
                   class="news-input"
                   placeholder="Enter title"
                   value="<?php echo $slider->c_title; ?>"
                   >
        </div>

        <input type="hidden"
       name="id"
       value="<?php echo $slider->n_slno; ?>">

        <!-- DESCRIPTION -->
        <div class="news-form-group full-width">
            <label>Description</label>

            <textarea name="description"
                      id="description"
                      class="news-textarea"
                      placeholder="Enter description"><?php echo $slider->c_description; ?></textarea>
        </div>

        <!-- UPLOAD TYPE -->
        <div class="news-form-group">
            <label>Upload Type</label>

            <select name="upload_type"
                    id="upload_type"
                    class="news-select">

                <option value="">Select Upload Type</option>
                <option value="image">Image</option>
                <option value="video">Video (WEBM)</option>
                <option value="link">External Link (YOUTUBE)</option>

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
   BLUE THEME FORM DESIGN
========================= */
.form-grid{
    width:100%;
    padding:24px;
    display:grid;
    grid-template-columns:1fr;
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
    color:#1e3a8a;
    letter-spacing:.3px;
}

/* =========================
   INPUT / SELECT / TEXTAREA
========================= */

.news-input,
.news-select,
.news-textarea{
    width:100%;
    border:1px solid #cbd5e1;
    background:#f8fbff;
    border-radius:16px;
    padding:15px 18px;
    font-size:15px;
    color:#0f172a;
    transition:all .3s ease;
    outline:none;
}

.news-input:focus,
.news-select:focus,
.news-textarea:focus{
    border-color:#1e3a8a;
    background:#ffffff;
    box-shadow:0 0 0 4px rgba(30,58,138,.12);
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
    border:2px dashed #1e3a8a;
    border-radius:24px;
    padding:42px 20px;
    text-align:center;
    background:linear-gradient(180deg,#eff6ff 0%, #ffffff 100%);
    transition:all .3s ease;
    overflow:hidden;
}

.upload-box:hover{
    transform:translateY(-3px);
    box-shadow:0 12px 28px rgba(30,58,138,.15);
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
    width:82px;
    height:82px;
    border-radius:50%;
    background:#dbeafe;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 18px;
    font-size:34px;
    color:#1e3a8a;
}

.upload-title{
    font-size:20px;
    font-weight:700;
    color:#0f172a;
    margin-bottom:8px;
}

.upload-sub{
    font-size:14px;
    color:#64748b;
}

/* =========================
   FILE PREVIEW
========================= */

.file-preview{
    margin-top:20px;
    display:none;
    align-items:center;
    gap:15px;
    border:1px solid #bfdbfe;
    background:#ffffff;
    padding:16px;
    border-radius:16px;
    box-shadow:0 4px 12px rgba(0,0,0,.04);
}

.preview-icon{
    width:52px;
    height:52px;
    border-radius:14px;
    background:#dbeafe;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#1e3a8a;
    font-size:22px;
}

.preview-name{
    font-weight:700;
    color:#0f172a;
}

.preview-size{
    font-size:13px;
    color:#64748b;
    margin-top:4px;
}

/* =========================
   BUTTON DESIGN
========================= */

.news-btn-group{
    margin-top:34px;
    display:flex;
    justify-content:flex-end;
}

.submit-btn{
    border:none;
background:linear-gradient(135deg,#1e3a8a,#2563eb);    color:#fff;
    padding:15px 34px;
    border-radius:16px;
    font-size:15px;
    font-weight:700;
    letter-spacing:.4px;
    cursor:pointer;
    transition:all .3s ease;
    box-shadow:0 10px 25px rgba(37,99,235,.25);
}

.submit-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 30px rgba(37,99,235,.35);
}

.submit-btn i{
    margin-right:8px;
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:768px){

    .form-grid{
        grid-template-columns:1fr;
    }

    .submit-btn{
        width:100%;
        justify-content:center;
    }

}

.card{
    width:100%;
    background:#fff;
    border-radius:24px;
    border:1px solid #dbe4f0;
    overflow:hidden;
    padding:0;
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
    confirmButtonColor: '#2563eb',
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
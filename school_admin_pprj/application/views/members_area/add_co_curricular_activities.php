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
            Upload Images
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('co_curricular_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>






                    <form id="newsform"
                        method="post"
                        enctype="multipart/form-data"
                        action="<?php echo base_url('insert_activities'); ?>">

                        <!-- News Type Selector -->
                        <div class="news-form-group">

                            <label>
                                Image Category
                            </label>

                            <select name="news_type"
                                id="news_type"
                                class="news-select">

                                <option value="">Select Category</option>
                          <option value="">All</option>
                            <option value="library">Library</option>
                            <option value="extra_curricular_activities">Extra Curricular Activities</option>
                            <option value="sports">Sports</option>
                            <option value="volley_ball">Volley Ball</option>
                            <option value="basket_ball">Basket Ball</option>
                            <option value="foot_ball">Foot Ball</option>
                            <option value="cricket">Cricket</option>
                            <option value="kho_kho">Kho-Kho</option>
                            <option value="badminton">Badminton</option>
                            <option value="roll_ball">Roll Ball</option>
                            <option value="dance">Dance</option>
                            <option value="music">Music</option>
                            <option value="yoga">Yoga</option>
                            <option value="karate">Karate</option>
                            <option value="drawing">Drawing</option>
                            <option value="painting">Painting</option>
                            <option value="roller_skating">Roller Skating</option>
                            <option value="transportation_facility">Transportation Facility</option>
                            <option value="educational_tours_excursions">Educational Tours / Excursions</option>
                            <option value="computer_labs">Computer Labs</option>
                            <option value="science_labs">Science Labs</option>
                            <option value="smart_class_facilities">Smart Class Facilities</option>
                            <option value="stationary_to_students">Stationary to Students</option>
                            <option value="low_achievers">Low Achievers</option>


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
    color:#1e3a8a;
    text-transform:uppercase;
    letter-spacing:.08em;
    margin-bottom:10px;
}

.eyebrow-pulse{
    width:10px;
    height:10px;
    border-radius:50%;
    background:#2563eb;
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
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
    color:#fff;
    padding:12px 22px;
    border-radius:14px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:.3s ease;
    box-shadow:0 10px 22px rgba(37,99,235,.22);
}

.card-action:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 28px rgba(37,99,235,.32);
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
    border-color:#2563eb;
    background:#fff;
    box-shadow:0 0 0 4px rgba(37,99,235,.12);
}

/* =========================================
   UPLOAD AREA
========================================= */
.upload-area{
    position:relative;
    border:2px dashed #2563eb;
    border-radius:24px;
    background:linear-gradient(to bottom,#eff6ff,#ffffff);
    padding:45px 25px;
    text-align:center;
    cursor:pointer;
    transition:.35s ease;
    overflow:hidden;
}

.upload-area:hover{
    transform:translateY(-2px);
    box-shadow:0 18px 35px rgba(37,99,235,.12);
}

.upload-area.dragover{
    background:#dbeafe;
    border-color:#1e40af;
}

.upload-content{
    pointer-events:none;
}

.upload-icon{
    width:85px;
    height:85px;
    border-radius:50%;
    background:#dbeafe;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 20px;
    font-size:34px;
    color:#1e3a8a;
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
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
    color:#fff;
    padding:12px 22px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    box-shadow:0 8px 18px rgba(37,99,235,.2);
}

.submit-btn{
    border:none;
    outline:none;
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
    color:#fff;
    padding:14px 26px;
    border-radius:14px;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:10px;
    transition:.3s ease;
    box-shadow:0 10px 22px rgba(37,99,235,.22);
}


.submit-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 15px 30px rgba(37,99,235,.35);
    background:linear-gradient(135deg,#1e40af,#3b82f6);
}

.submit-btn:active{
    transform:scale(.98);
}

.submit-btn i{
    font-size:14px;
}
</style>

<script>

$(document).ready(function () {

    $('#news_type').select2({
        placeholder: "Select Category",
        allowClear: true
    });

});

</script>

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




<script>

$("#newsform").on("submit", function(e){

    e.preventDefault();

    var news_type = $("#news_type").val();

    if(news_type == "")
    {
        Swal.fire({
            icon:'warning',
            title:'Warning',
            text:'Please select category'
        });

        return false;
    }

    $.ajax({
        url: "<?php echo base_url('check_news_type'); ?>",
        type: "POST",
        data: {
            news_type: news_type
        },
        dataType: "json",
        success: function(response)
        {

            if(response.status == 'exists')
            {

                Swal.fire({
                    title: 'Already Exists',
                    html: `
                        <p style="margin-bottom:10px;">
                            Image already exists.<br>
                            Delete first and upload new image.
                        </p>

                        <img src="${response.image}"
                             style="width:250px;height:auto;border-radius:10px;border:1px solid #ddd;">
                    `,
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {

                    // clear select2
                    $('#news_type').val('').trigger('change');

                    // clear file input
                    $('#news_image').val('');

                    // hide preview
                    $('#imagePreview').hide();

                    // remove image src
                    $('#previewImg').attr('src', '');

                    // show upload content again
                    $('#uploadContent').show();

    });

            }
            else
            {
                // submit form
                $("#newsform")[0].submit();
            }
        }
    });

});

</script>
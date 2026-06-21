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
          Add News
        </div>
        <h1 class="page-title">Add News
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
            Upload News
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('school_news'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>






                    <form id="newsform"
                        method="post"
                        action="<?php echo base_url('insert_school_news'); ?>">

                        <!-- Title -->
                        <div class="news-form-group">

                            <label>
                                News Title
                            </label>

                            <input type="text"
                                name="title"
                                id="title"
                                class="news-input"
                                placeholder="Enter News Title">

                        </div>

                        <!-- Description -->
                        <div class="news-form-group">

                            <label>
                                Description
                            </label>

                            <textarea name="description"
                                id="description"
                                class="news-textarea"
                                placeholder="Enter News Description"></textarea>

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
   INPUT FIELD DESIGN
========================= */
.news-form-group{
    margin-bottom:24px;
}

.news-form-group label{
    display:block;
    margin-bottom:10px;
    font-size:14px;
    font-weight:700;
    color:#374151;
    letter-spacing:.3px;
}

.news-input,
.news-textarea{
    width:100%;
    border:1px solid #dbe4ee;
    background:#f8fafc;
    border-radius:18px;
    padding:16px 18px;
    font-size:15px;
    color:#111827;
    transition:all .3s ease;
    outline:none;
    box-shadow:0 2px 6px rgba(0,0,0,0.03);
}

.news-input{
    height:58px;
}

.news-textarea{
    min-height:180px;
    resize:none;
    line-height:1.7;
}

/* Focus Effect */
.news-input:focus,
.news-textarea:focus{
    background:#ffffff;
    border-color:#22c55e;
    box-shadow:
        0 0 0 4px rgba(34,197,94,.12),
        0 10px 25px rgba(34,197,94,.08);
    transform:translateY(-1px);
}

/* Placeholder */
.news-input::placeholder,
.news-textarea::placeholder{
    color:#9ca3af;
    font-weight:400;
}

/* =========================
   BUTTON DESIGN
========================= */
.news-btn-group{
    margin-top:10px;
}

.submit-btn{
    border:none;
    outline:none;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:#fff;
    padding:15px 34px;
    border-radius:18px;
    font-size:15px;
    font-weight:700;
    letter-spacing:.3px;
    cursor:pointer;
    transition:all .3s ease;
    box-shadow:0 12px 25px rgba(34,197,94,.25);
}

.submit-btn i{
    margin-right:8px;
}

/* Hover */
.submit-btn:hover{
    transform:translateY(-3px);
    box-shadow:0 18px 35px rgba(34,197,94,.35);
}

/* Click Effect */
.submit-btn:active{
    transform:scale(.98);
}
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
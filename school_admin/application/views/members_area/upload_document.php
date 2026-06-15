
<!-- DataTables CSS -->
<link rel="stylesheet" href="<?php echo CSS_PATH ?>jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo CSS_PATH ?>responsive.dataTables.min.css">
<style>
    :root {
        --primary: var(--g600);
        --primary-light: var(--g100);
        --accent: #fbbf24;
        --bg: var(--g50);
        --card-bg: #ffffff;
        --text-main: var(--gray800);
        --text-muted: var(--gray500);
        --border-color: rgba(22, 163, 74, 0.15);
    }

    .report-container {
        padding: 24px;
        color: var(--text-main);
        font-family: 'DM Sans', sans-serif;
    }

    /* Premium Card Design */
    .premium-card {
        background: var(--card-bg);
        border-radius: 20px;
        border: 1px solid var(--border-color);
        box-shadow: 0 10px 30px rgba(22, 163, 74, 0.05);
        margin-bottom: 24px;
        overflow: hidden;
        animation: fadeIn 0.8s cubic-bezier(0.22, 1, 0.36, 1);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-header-creative {
        padding: 24px;
        background: linear-gradient(to right, #ffffff, var(--g50));
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 15px;
        position: relative;
    }

    .card-header-creative::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
        background: var(--primary);
        box-shadow: 0 0 10px rgba(22, 163, 74, 0.3);
    }

    .card-icon-box {
        width: 44px;
        height: 44px;
        background: var(--primary-light);
        color: var(--primary);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.1);
    }

    .card-title-creative {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--gray900);
        letter-spacing: -0.02em;
    }

    /* Summary Section */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-box {
        background: white;
        padding: 20px;
        border-radius: 18px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.3s;
        background: linear-gradient(135deg, var(--g800) 0%, var(--g600) 100%) !important;
    }

    .stat-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(22, 163, 74, 0.1);
        border-color: var(--g400);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .stat-details h4 {
        font-size: 0.75rem;
        color: #27ff87;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 4px;
    }

    .stat-details p {
        font-size: 1.5rem;
        font-weight: 800;
        color: #fff;
    }

    /* Custom DataTables Styling */
    .dataTables_wrapper {
        padding: 24px;
    }

    .dataTables_length select, 
    .dataTables_filter input {
        border: 1px solid var(--g200);
        border-radius: 8px;
        padding: 8px 12px;
        outline: none;
        background: var(--g50);
        transition: all 0.2s;
        font-family: 'DM Sans', sans-serif;
    }

    .dataTables_length select:focus, 
    .dataTables_filter input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
    }

    table.dataTable {
        border-collapse: separate !important;
        border-spacing: 0 10px !important;
        border: none !important;
        width: 100% !important;
    }

    table.dataTable thead th {
        background: var(--g50) !important;
        color: var(--primary) !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        font-size: 0.75rem !important;
        letter-spacing: 0.05em !important;
        padding: 15px !important;
        border: none !important;
        border-radius: 0 !important;
    }

    table.dataTable thead th:first-child { border-top-left-radius: 10px !important; border-bottom-left-radius: 10px !important; }
    table.dataTable thead th:last-child { border-top-right-radius: 10px !important; border-bottom-right-radius: 10px !important; }

    table.dataTable tbody tr {
        background-color: #ffffff !important;
        transition: all 0.2s;
        border-radius: 10px !important;
    }

    table.dataTable tbody tr:hover {
        background-color: var(--g50) !important;
        transform: scale(1.005);
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    table.dataTable tbody td {
        padding: 15px !important;
        border: none !important;
        font-size: 0.9rem;
        color: var(--gray700);
        vertical-align: middle;
    }

    table.dataTable tbody tr td:first-child { border-top-left-radius: 10px !important; border-bottom-left-radius: 10px !important; border-left: 1px solid var(--border-color) !important; }
    table.dataTable tbody tr td:last-child { border-top-right-radius: 10px !important; border-bottom-right-radius: 10px !important; border-right: 1px solid var(--border-color) !important; }
    table.dataTable tbody tr td { border-top: 1px solid var(--border-color) !important; border-bottom: 1px solid var(--border-color) !important; }

    /* Custom Badge Styles */
    .badge-soft {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .badge-roi { background: rgba(22, 163, 74, 0.1); color: #16a34a; }
    .badge-ref { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-level { background: rgba(168, 85, 247, 0.1); color: #a855f7; }
    .badge-withdraw { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .amount-in { color: #059669; font-weight: 700; }
    .amount-out { color: #dc2626; font-weight: 700; }

    /* Pagination Styling */
    .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        border: 1px solid var(--g200) !important;
        background: white !important;
        color: var(--gray600) !important;
        margin: 0 3px !important;
        padding: 6px 12px !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: var(--primary) !important;
        color: white !important;
        border-color: var(--primary) !important;
    }

    .dataTables_paginate .paginate_button:hover {
        background: var(--g100) !important;
        color: var(--primary) !important;
    }

    @media (max-width: 768px) {
        .report-container { padding: 15px; }
        .dataTables_wrapper { padding: 15px; }
    }

    .date-filter-box {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: end;
    padding: 20px;
    background: #ffffff;
    border: 1px solid var(--border-color);
    border-radius: 14px;
    margin: 15px 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.filter-group label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray600);
    letter-spacing: 0.05em;
}

.filter-input {
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid var(--g200);
    background: var(--g50);
    font-family: 'DM Sans', sans-serif;
    transition: 0.2s;
}

.filter-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
    outline: none;
}

/* Buttons */
.filter-actions {
    display: flex;
    gap: 10px;
}

.btn-filter {
    background: var(--primary);
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.btn-filter:hover {
    background: var(--g700);
}

.btn-reset {
    background: #f1f5f9;
    color: var(--gray700);
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.btn-reset:hover {
    background: #e2e8f0;
}
</style>



<div class="main">

    <div class="content">
        <div class="report-container">
            
        

            <div class="premium-card">
                <div class="card-header-creative">
                    <div class="card-icon-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <div class="card-title-creative">Add New Document</div>

                   <a href="<?php echo base_url('general_information'); ?>" class="upload-btn">
                        <i class="fa fa-upload"></i> Document List
                    </a>
    
                </div>

                <div class="table-responsive-wrapper">

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
            </div>

        </div>
    </div>

<style>
    .card-header-creative{
    position:relative;
}

.upload-btn{
    position:absolute;
    right:20px;
    top:50%;
    transform:translateY(-50%);
    background:linear-gradient(135deg,var(--g600),var(--g800));
    color:#fff;
    padding:10px 18px;
    border-radius:10px;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    transition:0.3s;
}

.upload-btn:hover{
    color:#fff;
    text-decoration:none;
    background:linear-gradient(135deg,var(--g700),var(--g900));
}


/*  */


 .document-form-wrapper {
        padding: 30px;
        background: linear-gradient(135deg, #f8fff8, #eefbf2);
        border-radius: 24px;
    }

    .upload-form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-top: 25px;
    }

    .form-group-modern {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .form-group-modern label {
        font-size: 15px;
        font-weight: 700;
        color: #1d3b2f;
    }

    .modern-select,
    .modern-input {
        height: 52px;
        border: 1px solid #d8e7dd;
        border-radius: 14px;
        padding: 0 16px;
        font-size: 15px;
        background: #fff;
        transition: 0.3s ease;
        outline: none;
    }

    .modern-select:focus,
    .modern-input:focus {
        border-color: #38b000;
        box-shadow: 0 0 0 4px rgba(56, 176, 0, 0.12);
    }

    /* Upload Box */
    .upload-box {
        position: relative;
        border: 2px dashed #9ad7a4;
        border-radius: 22px;
        background: #ffffff;
        padding: 25px 20px;
        text-align: center;
        transition: 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }

    .upload-box:hover {
        border-color: #38b000;
        background: #f6fff6;
        transform: translateY(-2px);
    }

    .upload-box input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .upload-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #38b000, #70e000);
        margin: auto;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        margin-bottom: 18px;
        box-shadow: 0 12px 25px rgba(56, 176, 0, 0.2);
    }

    .upload-title {
        font-size: 17px;
        font-weight: 700;
        color: #173524;
    }

    .upload-sub {
        margin-top: 6px;
        color: #6b7280;
        font-size: 14px;
    }

    /* Preview */
    .file-preview {
        margin-top: 20px;
        display: none;
        align-items: center;
        gap: 14px;
        background: #fff;
        border: 1px solid #e3efe7;
        padding: 14px;
        border-radius: 16px;
    }

    .preview-icon {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        background: #ff4d4f;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .preview-details {
        flex: 1;
    }

    .preview-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 15px;
    }

    .preview-size {
        font-size: 13px;
        color: #64748b;
        margin-top: 3px;
    }

    /* Button */
    .submit-btn-modern {
        margin-top: 30px;
        border: none;
        background: linear-gradient(135deg, #38b000, #008000);
        color: white;
        padding: 14px 34px;
        border-radius: 14px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s ease;
        box-shadow: 0 10px 20px rgba(56, 176, 0, 0.2);
    }

    .submit-btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 24px rgba(56, 176, 0, 0.28);
    }

    @media(max-width:768px){
        .document-form-wrapper{
            padding:20px;
        }
    }

</style>



    <!-- Scripts -->
    <script src="<?php echo JS_PATH ?>jquery-3.6.0.min.js"></script>
    <script src="<?php echo JS_PATH ?>jquery.dataTables.min.js"></script>
    <script src="<?php echo JS_PATH ?>dataTables.responsive.min.js"></script>
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
    document.getElementById('documentFile').addEventListener('change', function () {

        const file = this.files[0];

        if (file) {

            document.getElementById('filePreview').style.display = 'flex';

            document.getElementById('fileName').innerText = file.name;

            let size = (file.size / 1024 / 1024).toFixed(2);

            document.getElementById('fileSize').innerText = size + ' MB';

        }
    });
</script>

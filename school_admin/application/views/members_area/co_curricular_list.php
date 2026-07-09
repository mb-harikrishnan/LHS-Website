<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>



<!-- CARD -->
<div class="card">

    <!-- CARD HEADER -->
    <div class="card-head">
        <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>

            Co Curricular List
        </div>

        <button class="card-action"
                onclick="window.location.href='<?php echo base_url('add_co_curricular_activities'); ?>'">
            <i class="fa fa-upload"></i> Add Image
        </button>
    </div>

    <!-- FILTER -->
    <form method="post" action="<?php echo base_url('co_curricular_list'); ?>">

        <div class="date-filter-box">
                        
                         
                <div class="filter-group">
                <label>Status</label>
                <select id="statusFilter" class="filter-input" name="type">
                    <option value="">All</option>
                    <option value="Library"  <?php if($this->input->post('type') == 'Library'){ echo 'selected'; } ?>>Library</option>
                    <option value="Extra_Curricular_Activities" <?php if($this->input->post('type') == 'Extra_Curricular_Activities'){ echo 'selected'; } ?>>Extra Curricular Activities</option>
                    <option value="Sports" <?php if($this->input->post('type') == 'Sports'){ echo 'selected'; } ?>>Sports</option>
                    <option value="Volley_Ball" <?php if($this->input->post('type') == 'Volley_Ball'){ echo 'selected'; } ?>>Volley Ball</option>
                    <option value="Basket_Ball" <?php if($this->input->post('type') == 'Basket_Ball'){ echo 'selected'; } ?>>Basket Ball</option>
                    <option value="Foot_Ball" <?php if($this->input->post('type') == 'Foot_Ball'){ echo 'selected'; } ?>>Foot Ball</option>
                    <option value="Cricket" <?php if($this->input->post('type') == 'Cricket'){ echo 'selected'; } ?>>Cricket</option>
                    <option value="Kho-Kho" <?php if($this->input->post('type') == 'Kho-Kho'){ echo 'selected'; } ?>>Kho-Kho</option>
                    <option value="Badminton" <?php if($this->input->post('type') == 'Badminton'){ echo 'selected'; } ?>>Badminton</option>
                    <option value="Roll_Ball" <?php if($this->input->post('type') == 'Roll_Ball'){ echo 'selected'; } ?>>Roll Ball</option>
                    <option value="Dance" <?php if($this->input->post('type') == 'Dance'){ echo 'selected'; } ?>>Dance</option>
                    <option value="Music" <?php if($this->input->post('type') == 'Music'){ echo 'selected'; } ?>>Music</option>
                    <option value="Yoga" <?php if($this->input->post('type') == 'Yoga'){ echo 'selected'; } ?>>Yoga</option>
                    <option value="Karate" <?php if($this->input->post('type') == 'Karate'){ echo 'selected'; } ?>>Karate</option>
                    <option value="Drawing" <?php if($this->input->post('type') == 'Drawing'){ echo 'selected'; } ?>>Drawing</option>
                    <option value="Painting" <?php if($this->input->post('type') == 'Painting'){ echo 'selected'; } ?>>Painting</option>
                    <option value="Roller_Skating" <?php if($this->input->post('type') == 'Roller_Skating'){ echo 'selected'; } ?>>Roller Skating</option>
                    <option value="Transportation_Facility" <?php if($this->input->post('type') == 'Transportation_Facility'){ echo 'selected'; } ?>>Transportation Facility</option>
                    <option value="Educational_Tours_Excursions" <?php if($this->input->post('type') == 'Educational_Tours_Excursions'){ echo 'selected'; } ?>>Educational Tours / Excursions</option>
                    <option value="Computer_Labs" <?php if($this->input->post('type') == 'Computer_Labs'){ echo 'selected'; } ?>>Computer Labs</option>
                    <option value="Science_Labs" <?php if($this->input->post('type') == 'Science_Labs'){ echo 'selected'; } ?>>Science Labs</option>
                    <option value="Smart_Class_Facilities" <?php if($this->input->post('type') == 'Smart_Class_Facilities'){ echo 'selected'; } ?>>Smart Class Facilities</option>
                    <option value="Stationary_to_Students" <?php if($this->input->post('type') == 'Stationary_to_Students'){ echo 'selected'; } ?>>Stationary to Students</option>
                    <option value="Low_Achievers" <?php if($this->input->post('type') == 'Low_Achievers'){ echo 'selected'; } ?>> Low Achievers</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">Filter</button>
            </div>


                        

                        
     </div>
    </form>

    <p class="report-meta">
        Showing all reports
    </p>

    <!-- TABLE -->
    <div class="report-table-wrap">

        <table id="reportsDataTable"
               class="report-table display nowrap"
               style="width:100%">

            <thead>
            <tr>
               <th>#SL</th>
                <th>Date</th>
                <th>Title</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>

            <?php if(!empty($image)) { ?>

                <?php $i = 1; foreach($image as $row) { ?>

                    <tr>

                        <!-- SL NO -->
                        <td>
                            <?php echo $i++; ?>
                        </td>

                        <!-- DATE -->
                        <td>
                            <?php echo date('d-m-Y', strtotime($row->d_date)); ?>
                        </td>

                        <!-- TYPE -->
                        <td>
                            <span class="report-type-badge">
                                <?php echo ucfirst(str_replace('_', ' ', $row->c_type)); ?>
                            </span>
                        </td>

                         <td>
                                    
                            <img src="<?php echo base_url() . '../assets/images/gallery/' . $row->c_images; ?>" 
                                alt="Image"
                                class="gallery-img"
                                style="width:100px; height:auto; cursor:pointer; border-radius:10px;">

                        </td>    
                        <!-- ACTION -->
                        <td>

                            <button type="button"
                                    class="table-btn delete-btn deleteBtn"
                                    data-id="<?php echo $row->n_slno; ?>">

                                <i class="fa fa-trash"></i> Delete

                            </button>

                        </td>

                    </tr>

                <?php } ?>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>




    <!-- Image Popup Modal -->
<div id="imageModal" class="image-modal">

    <span class="close-modal">&times;</span>

    <img class="modal-content-img" id="popupImage">

</div>

<!-- CSS -->



<style>

.card{
    background:#ffffff;
    border-radius:18px;
    padding:24px;
    box-shadow:0 4px 20px rgba(0,0,0,0.06);
    border:1px solid #e8ecef;
    overflow:hidden;
}

/* =========================
   FILTER AREA
========================= */
/* CARD HEADER SMALL */

.card-head{
    padding:10px 0 !important;
    margin-bottom:10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
}
/* FILTER BAR SMALL */

.filter-bar{
    display:grid;
    grid-template-columns: 1fr auto;
    gap:20px;
    align-items:end;
     background:#f8fbff;
    padding:20px;
    border-radius:14px;
border:1px solid #dbeafe;    margin-top:18px;
    margin-bottom:18px;
}

.filter-group{
    width:100%;
}

.filter-actions{
    display:flex;
    align-items:end;
}
.report-meta{
    font-size:14px;
    color:#6b7280;
    margin-bottom:10px;
    padding-left:4px !important;
}

@media(max-width:768px){

    .filter-bar{
        grid-template-columns:1fr;
        padding:16px;
        gap:14px;
    }

    .filter-actions{
        width:100%;
    }

    .btn-primary{
        width:100%;
    }
}


.filter-group label{
    display:block;
    font-size:13px;
    font-weight:700;
    color:#1e3a8a;
    margin-bottom:8px;
    letter-spacing:0.3px;
}

.form-select{
    width:100%;
    height:46px;
    border:1px solid #d7e0db;
    border-radius:12px;
    padding:0 14px;
    font-size:14px;
    background:#ffffff;
    transition:0.3s;
    color:#374151;
}

.form-select:focus{
    border-color:#1e3a8a ;
    outline:none;
    box-shadow:0 0 0 3px rgba(37,99,235,0.12);
}

/* =========================
   BUTTONS
========================= */

.btn-primary{
    background:#1e3a8a ;
    border:none;
    color:#fff;
    padding:12px 22px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    min-width:160px;
    height:46px;
    transition:0.3s;
}


.btn-primary:hover{
    background:#1e40af;
    transform:translateY(-1px);
}

/* SMALL TITLE */

.card-title{
    font-size:16px;
    font-weight:700;
}


.card-action{
    background:#1E3A8A;
    color:#fff;
    border:none;
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

/* ADD BUTTON FULL GREEN HOVER */
.card-action:hover{
    background:#2563eb;   /* blue */
    color:#fff;
    box-shadow:0 0 0 4px rgba(37, 99, 235, 0.18);
    transform:translateY(-1px);
}

/* =========================
   TABLE DESIGN
========================= */
/* TABLE HEADER GREEN */

.report-table thead tr,
.report-table thead th{
    background:#1e3a8a !important;
    color:#ffffff !important;
}

/* HEADER TEXT STYLE */

.report-table thead th{
    padding:18px 16px;
    font-size:15px;
    font-weight:700;
    text-transform:uppercase;
    border:none !important;
    letter-spacing:0.5px;
}

/* ROUNDED CORNERS */

.report-table thead th:first-child{
    border-top-left-radius:14px;
}

.report-table thead th:last-child{
    border-top-right-radius:14px;
}

/* DATATABLE SORT ICON COLOR */

table.dataTable thead .sorting:before,
table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:before,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:before,
table.dataTable thead .sorting_desc:after{
    color:#ffffff !important;
    opacity:1 !important;
}

/* BODY */

.report-table tbody td{
    padding:18px 16px;
    font-size:14px;
    color:#374151;
    border-bottom:1px solid #edf2ef;
    vertical-align:middle;
    background:#fff;
}

.report-table tbody tr{
    transition:0.25s;
}

.report-table tbody tr:hover td{
    background:#eff6ff;
}

/* =========================
   BADGES
========================= */

.report-type-badge{
    background:#dbeafe;
    color:#1d4ed8;
    padding:8px 14px;
    border-radius:30px;
    font-size:12px;
    font-weight:700;
    display:inline-block;
    text-transform:capitalize;
}

/* =========================
   PDF BUTTON
========================= */

.pdf-btn{
    background:#fff1f2;
    color:#dc2626;
    padding:10px 14px;
    border-radius:10px;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
    display:inline-flex;
    align-items:center;
    gap:6px;
    transition:0.3s;
}

.pdf-btn:hover{
    background:#dc2626;
    color:#fff;
}

/* =========================
   ACTION BUTTONS
========================= */

.action-btn-group{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.table-btn{
    border:none;
    padding:10px 14px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:6px;
    transition:0.3s;
    cursor:pointer;
}

.download-btn{
    background:#1e3a8a ;
    color:#fff;
}


.download-btn:hover{
    background:#2563eb;
}

.deleteBtn{
    background:#dc2626 !important;
    color:#fff !important;
}

.deleteBtn:hover{
    background:#b91c1c !important;
}

/* =========================
   DATATABLE DESIGN
========================= */

/* PAGINATION DESIGN */

.dataTables_wrapper .dataTables_paginate{
    margin-top:15px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button{
    background:#dbeafe !important;
    color:#1e3a8a !important;
    border:none !important;
    border-radius:10px !important;
    padding:7px 14px !important;
    margin:0 4px !important;
    font-weight:600;
    transition:0.3s;
}

/* ACTIVE PAGE */

.dataTables_wrapper .dataTables_paginate .paginate_button.current{
    background:#1e3a8a !important;
    color:#fff !important;
    border:none !important;
}

/* HOVER */

.dataTables_wrapper .dataTables_paginate .paginate_button:hover{
    background:#1e3a8a  !important;
    color:#fff !important;
}

/* REMOVE BLACK BORDER */

.dataTables_wrapper .dataTables_paginate .paginate_button:focus{
    outline:none !important;
    box-shadow:none !important;
}


/* TOP DATATABLE AREA */

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter{
    margin-bottom:18px;
}

/* SHOW ENTRIES TEXT */

.dataTables_wrapper .dataTables_length label{
    font-size:15px;
    font-weight:600;
    color:#1e3a8a;
}

/* DROPDOWN */

.dataTables_wrapper .dataTables_length select{
    border:1px solid #bfdbfe !important;
    border-radius:10px !important;
    padding:8px 35px 8px 12px !important;
    height:42px;
    background:#eff6ff !important;
    color:#1e3a8a !important;
    font-weight:600;
    outline:none !important;
    box-shadow:none !important;
}

/* SEARCH LABEL */

.dataTables_wrapper .dataTables_filter label{
    font-size:15px;
    font-weight:600;
    color:#416e54;
}

/* SEARCH INPUT */

.dataTables_wrapper .dataTables_filter input{
    width:260px !important;
    height:44px !important;
    border:1px solid #bfdbfe !important;
    border-radius:12px !important;
    padding:0 15px !important;
    background:#eff6ff !important;
    color:#333 !important;
    font-size:14px !important;
    outline:none !important;
    box-shadow:none !important;
    transition:0.3s;
}

/* SEARCH FOCUS */

.dataTables_wrapper .dataTables_filter input:focus{
    border-color:#1e3a8a  !important;
    background:#fff !important;
    box-shadow:0 0 0 3px rgba(37,99,235,0.10) !important;
}

/* MOBILE */

@media(max-width:768px){

    .dataTables_wrapper .dataTables_filter{
        margin-top:15px;
        text-align:left !important;
    }

    .dataTables_wrapper .dataTables_filter input{
        width:100% !important;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter{
        width:100%;
    }
}

/* =========================
   EMPTY TEXT
========================= */

.no-file{
    color:#9ca3af;
    font-size:13px;
    font-weight:500;
}

/* =========================
   MOBILE
========================= */

@media(max-width:768px){

    .card{
        padding:16px;
    }

    .filter-bar{
        flex-direction:column;
        align-items:stretch;
    }

    .form-select{
        width:100%;
        min-width:100%;
    }

    .btn-primary,
    .card-action{
        width:100%;
        justify-content:center;
    }

    .report-table thead th,
    .report-table tbody td{
        padding:14px 12px;
        font-size:13px;
    }

    .table-btn{
        width:100%;
        justify-content:center;
    }

    .action-btn-group{
        flex-direction:column;
    }
}

</style>

<style>
      /* popup modal styles */


    .image-modal {
    display: none;
    position: fixed;
    z-index: 99999;
    padding-top: 60px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background: rgba(0,0,0,0.85);
    backdrop-filter: blur(4px);
}

.modal-content-img {
    display: block;
    margin: auto;
    max-width: 85%;
    max-height: 85vh;
    border-radius: 15px;
    animation: zoomIn 0.3s ease;
}

@keyframes zoomIn {
    from {
        transform: scale(0.7);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.close-modal {
    position: absolute;
    top: 20px;
    right: 35px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}

.close-modal:hover {
    color: #ddd;
}

.gallery-img {
    transition: 0.3s;
}

.gallery-img:hover {
    transform:scale(1.05);
}
        .report-table-wrap{
    width:100%;
    overflow-x:auto;
    margin-top:20px;
}

.report-table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
}

.report-table thead tr{
    background:#1f4e3d;
}

.report-table thead th{
    color:#fff;
    padding:15px;
    font-size:14px;
    font-weight:600;
    text-align:left;
    border:none;
}

.report-table tbody td{
    padding:14px;
    border-bottom:1px solid #e5e5e5;
    font-size:14px;
    color:#444;
    vertical-align:middle;
}

.report-table tbody tr:hover{
    background:#f8fbf9;
    transition:0.3s;
}

.report-type-badge{
    background:#e7f7ef;
    color:#1E3A8A;
    padding:6px 12px;
    border-radius:30px;
    font-size:12px;
    font-weight:600;
    text-transform:capitalize;
}

.pdf-btn{
    background:#ffe9e9;
    color:#dc3545;
    padding:8px 14px;
    border-radius:6px;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
    display:inline-block;
}

.pdf-btn:hover{
    background:#dc3545;
    color:#fff;
}

.action-btn-group{
    display:flex;
    gap:10px;
}

.table-btn{
    padding:8px 14px;
    border-radius:6px;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
    transition:0.3s;
}

.view-btn{
    background:#0d6efd;
    color:#fff;
}

.view-btn:hover{
    background:#0b5ed7;
}

.download-btn{
    background:#1E3A8A;
    color:#fff;
}

.download-btn:hover{
    background:#157347;
}

.no-file{
    color:#999;
    font-size:13px;
}

.no-data-box{
    padding:30px;
    text-align:center;
    color:#999;
    font-size:15px;
}

.filter-form{
    margin-top:20px;
}

.date-filter-box{
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:16px;
    padding:20px;
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    align-items:end;
    box-shadow:0 4px 15px rgba(0,0,0,0.04);
}

.filter-group{
    flex:1;
    min-width:220px;
}

.filter-group label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
    font-weight:600;
    color:#1f2937;
}

.input-wrapper{
    position:relative;
}

.input-wrapper i{
    position:absolute;
    left:14px;
    top:50%;
    transform:translateY(-50%);
    color:#6b7280;
    font-size:14px;
}

.filter-input{
    width:100%;
    height:48px;
    border:1px solid #d1d5db;
    border-radius:12px;
    padding:0 14px 0 42px;
    font-size:14px;
    background:#f9fafb;
    transition:all 0.3s ease;
    outline:none;
}

.filter-input:focus{
    border-color:#1E3A8A;
    background:#fff;
    box-shadow:0 0 0 4px rgba(30, 58, 138, 0.15);
}

.filter-actions{
    display:flex;
    gap:12px;
    align-items:center;
}

/* FILTER BUTTON HOVER GREEN */

.btn-filter{
    height:48px;
    padding:0 22px;
    border:none;
    border-radius:12px;
    background:#1E3A8A;
    color:#fff;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
    display:flex;
    align-items:center;
    gap:8px;
}

/* FILTER BUTTON HOVER */
.btn-filter:hover{
    background:#2563eb;   /* blue */
    color:#fff;
    transform:translateY(-1px);
    box-shadow:0 8px 18px rgba(37, 99, 235, 0.25);
}


.btn-reset{
    height:48px;
    padding:0 20px;
    border-radius:12px;
    background:#f3f4f6;
    color:#374151;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    display:flex;
    align-items:center;
    gap:8px;
    transition:0.3s;
}

.btn-reset:hover{
    background:#374151;
}

@media(max-width:768px){

    .date-filter-box{
        flex-direction:column;
        align-items:stretch;
    }

    .filter-group{
        width:100%;
    }

    .filter-actions{
        width:100%;
    }

    .btn-filter,
    .btn-reset{
        width:100%;
        justify-content:center;
    }
}
      </style>


<style>

/* SELECT2 FIX */

.select2-container{
    width:100% !important;
}

.select2-container .select2-selection--single{
    height:48px !important;
    border:1px solid #d1d5db !important;
    border-radius:12px !important;
    background:#f9fafb !important;
    display:flex !important;
    align-items:center !important;
    padding:0 14px !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered{
    color:#111827 !important;
    line-height:48px !important;
    font-size:14px;
    padding-left:0 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow{
    height:48px !important;
    right:10px !important;
}

.select2-dropdown{
    border-radius:12px !important;
    border:1px solid #d1d5db !important;
    overflow:hidden;
}

.select2-search--dropdown .select2-search__field{
    border:1px solid #d1d5db !important;
    border-radius:8px !important;
    padding:8px !important;
}

.select2-results__options{
    max-height:200px !important;
    overflow-y:auto !important;
}

</style>


      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- DATATABLE CSS -->
<link rel="stylesheet"
      href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

<link rel="stylesheet"
      href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<!-- DATATABLE JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- SWEET ALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DATATABLE -->
<script>

$(document).ready(function () {

    $('#reportsDataTable').DataTable({
        responsive: true,
        pageLength: 10,
        autoWidth: false,

        columnDefs: [
            {
                orderable: false,
                targets: [3, 4]
            }
        ],

        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search reports...",
            lengthMenu: "Show _MENU_ entries"
        }
    });

});

</script>

<!-- DELETE -->
<script>

$(document).on('click', '.deleteBtn', function (e) {

    e.preventDefault();

    let id  = $(this).data('id');
    let row = $(this).closest('tr');

    Swal.fire({
        title: 'Are you sure?',
        text: 'You want to delete this record!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    })

    .then((result) => {

        if (result.isConfirmed) {

            $.ajax({

                url: "<?php echo base_url('delete_activities'); ?>",
                type: "POST",
                data: {id:id},

                success: function (response) {

                    if ($.trim(response) == '1') {

                        row.fadeOut(500, function () {
                            $(this).remove();
                        });

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Deleted Successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });

                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Delete Failed'
                        });

                    }

                },

                error: function (xhr) {

                    console.log(xhr.responseText);

                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error'
                    });

                }

            });

        }

    });

});

</script>


<script>
$(document).ready(function () {
    $('#statusFilter').select2({
        placeholder: "Select Type",
        allowClear: true
    });
});
</script>




<!-- modal script -->


<script>

$(document).ready(function () {

    $('.gallery-img').click(function () {

        let imgSrc = $(this).attr('src');

        $('#popupImage').attr('src', imgSrc);

        $('#imageModal').fadeIn();

    });

    $('.close-modal').click(function () {

        $('#imageModal').fadeOut();

    });

    $('#imageModal').click(function (e) {

        if (e.target.id === 'imageModal') {

            $('#imageModal').fadeOut();

        }

    });

});

</script>
<!-- modal script -->

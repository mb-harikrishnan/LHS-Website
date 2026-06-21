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
        Reports
    </div>

    <h1 class="page-title">
        Galery Image List
    </h1>
</div>

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

            Report Records
        </div>

        <button class="card-action"
                onclick="window.location.href='<?php echo base_url('add_gallery_image'); ?>'">
            <i class="fa fa-upload"></i> Add Image
        </button>
    </div>

    <!-- FILTER -->
    <form method="post" action="<?php echo base_url('gallery'); ?>">

        <div class="date-filter-box">
                        
                            <div class="filter-group">
                                <label>From Date</label>
                                <input type="date" id="fromDate" name="fromDate"  value="<?php echo !empty(set_value('fromDate')) ? set_value('fromDate') : date('Y-m-d'); ?>" class="filter-input">
                            </div>

                            <div class="filter-group">
                                <label>To Date</label>
                                <input type="date" id="toDate" name="toDate" value="<?php echo !empty(set_value('toDate')) ? set_value('toDate') : date('Y-m-d'); ?>" class="filter-input">
                            </div>



                             <div class="filter-group">
                                <label>Status</label>
                                <select id="statusFilter" class="filter-input" name="type">
                                    <option value="">All</option>
                                    <option value="annual_day"  <?php if($this->input->post('type') == 'annual_day'){ echo 'selected'; } ?>>Annual Day</option>
                                    <option value="blue_day" <?php if($this->input->post('type') == 'blue_day'){ echo 'selected'; } ?>>Blue Day</option>
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
                                    
                            <img src="<?php echo base_url() . '../assets/images/gallery/' . $row->c_image; ?>" 
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
    transform: scale(1.05);
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
    color:#198754;
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
    background:#198754;
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
    border-color:#198754;
    background:#fff;
    box-shadow:0 0 0 4px rgba(25,135,84,0.12);
}

.filter-actions{
    display:flex;
    gap:12px;
    align-items:center;
}

.btn-filter{
    height:48px;
    padding:0 22px;
    border:none;
    border-radius:12px;
    background:#198754;
    color:#fff;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
    display:flex;
    align-items:center;
    gap:8px;
}

.btn-filter:hover{
    background:#157347;
    transform:translateY(-1px);
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
    background:#e5e7eb;
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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

                url: "<?php echo base_url('delete_image'); ?>",
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

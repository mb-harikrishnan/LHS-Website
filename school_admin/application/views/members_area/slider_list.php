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
        Slider List
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
                onclick="window.location.href='<?php echo base_url('add_slider'); ?>'">
            <i class="fa fa-upload"></i> Add Video
        </button>
    </div>

    <!-- FILTER -->
    

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
                <th>#</th>
                <th>Date</th>
                <th>Type</th>
                <th>Video</th>
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
                                <?php echo ucfirst(str_replace('_', ' ', $row->c_title)); ?>
                            </span>
                        </td>

                        <td><?php if($row->c_upload_type == 'image') { ?>

                    <img src="<?php echo base_url('../assets/images/gallery/'.$row->c_file); ?>" 
                         alt="Image"
                         style="width:120px; height:auto; border-radius:8px;">

                <?php } elseif($row->c_upload_type == 'video') { ?>

                    <video width="220" height="120" controls>
                        <source src="<?php echo base_url('../assets/images/gallery/'.$row->c_file); ?>" type="video/webm">
                        Your browser does not support the video tag.
                    </video>

                <?php } elseif($row->c_upload_type == 'link') { ?>

                    <iframe 
                        width="220" 
                        height="120"
                        src="<?php echo $row->c_file; ?>" 
                        frameborder="0"
                        allowfullscreen>
                    </iframe>

                <?php } ?>

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

<!-- CSS -->
<style>
.table-video{
    width:250px;
    height:140px;
    border-radius:10px;
    border:none;
     pointer-events:none;

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
    padding:14px;
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

.table-video{
    width:180px;
    height:100px;
    border-radius:8px;
    object-fit:cover;
    border:1px solid #ddd;
}

.table-btn{
    border:none;
    padding:8px 14px;
    border-radius:6px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.delete-btn{
    background:#dc3545;
    color:#fff;
}

.delete-btn:hover{
    background:#bb2d3b;
}

.no-file{
    color:#999;
    font-size:13px;
}

.report-meta{
    padding:12px 24px 0;
    color:#666;
}

.filter-bar{
    display:flex;
    align-items:end;
    justify-content:space-between;
    gap:15px;
    padding:20px 24px 0;
    flex-wrap:wrap;
}

.filter-group{
    display:flex;
    flex-direction:column;
    gap:8px;
}

.form-select{
    padding:10px 12px;
    min-width:220px;
    border:1px solid #ddd;
    border-radius:8px;
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

                url: "<?php echo base_url('delete_video'); ?>",
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





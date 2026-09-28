<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/report.css'); ?>">


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

             Galery Image List
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

                              <?php 
                                    $sql = "SELECT cid,c_category FROM gallery_category WHERE c_status='Y'";
                                    $query = $this->db->query($sql);
                                    $res = $query->result();
                                    ?>

                                    <select id="statusFilter" class="filter-input" name="type">

                                    <option value="">All</option>

                                    <?php 
                                    if($res)
                                    {
                                        foreach($res as $row)
                                        {
                                    ?>
                                            <option value="<?php echo $row->cid; ?>"
                                                <?php 
                                                if($this->input->post('type') == $row->cid)
                                                { 
                                                    echo 'selected'; 
                                                } 
                                                ?>>
                                                
                                                <?php echo $row->c_category; ?>

                                            </option>
                                    <?php 
                                        }
                                    } 
                                    ?>

                                    </select>
                            </div>

                            <div class="filter-actions">
                                <button type="submit" class="btn-filter">Filter</button>
                            </div>


                        

                        
                        </div>
    </form>



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

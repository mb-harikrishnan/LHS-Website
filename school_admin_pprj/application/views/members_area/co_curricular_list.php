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

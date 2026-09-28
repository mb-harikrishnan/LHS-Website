    <?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;




?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/report.css'); ?>">


     
      <!-- Reports Table Card -->
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
            </svg>
            Report Records
          </div>
        
        </div>

     

        <div class="report-table-wrap" id="reportTableWrap">
          <table class="report-table display" id="reportsDataTable" style="width:100%">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Date</th>
                      <th>Job</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Mobile No</th>
                      <th>View</th>
                      <th>Delete</th>
                      <th>Action</th>
                  </tr>
              </thead>

    <tbody>

<?php if(!empty($applications)) { ?>

    <?php $i=1; foreach($applications as $row){ ?>

        <tr>

            <td><?php echo $i++; ?></td>

            <td>
                <?php echo date('d-m-Y', strtotime($row->d_date)); ?>
            </td>

            <td>
                 <?php $sql="SELECT c_title  FROM  school_vacancy WHERE n_slno ='$row->n_job_id'  ";
                      $query = $this->db->query($sql);
                      $res=$query->row();
                 ?>
                <span class="report-type-badge">
                    <?php echo $res->c_title; ?>
                </span>
            </td>

            <td><?php echo $row->c_name  ;?></td>
            <td><?php echo $row->c_email  ;?></td>
            <td><?php echo $row->n_mobile  ;?></td>

            <td>

                <?php if(!empty($row->c_document)){ ?>

                    <a href="<?php echo base_url('../assets/images/resumes/'.$row->c_resume); ?>"
                       target="_blank"
                       class="pdf-btn">

                        <i class="fa fa-file-pdf"></i> View PDF

                    </a>

                <?php } else { ?>

                    <span class="no-file">No File</span>

                <?php } ?>

            </td>

            <td>

                <div class="action-btn-group">

                    <a href="<?php echo base_url('../assets/images/resumes/'.$row->c_resume); ?>"
                       download
                       class="table-btn download-btn">

                        <i class="fa fa-download"></i> Download

                    </a>

                </div>

            </td>

            <td>

                <button type="button"
                        class="table-btn btn btn-danger deleteBtn"
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

     


  
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

<!-- Responsive CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<!-- Responsive JS -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
$(document).ready(function () {

    $('#reportsDataTable').DataTable({
        responsive: true,
        pageLength: 10,
        autoWidth: false,

        columnDefs: [
            { orderable: false, targets: [3,4,5] }
        ],

        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search reports...",
            lengthMenu: "Show _MENU_ entries",
        }
    });

});

</script>




<script>

$(document).on('click', '.deleteBtn', function (e) {

    e.preventDefault();

    var id  = $(this).data('id');
    var row = $(this).closest('tr');

    Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete this record!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({

                url: "<?php echo base_url('delete_application'); ?>",
                type: "POST",
                data: {id:id},

                success: function (response) {

                    console.log(response);

                    if($.trim(response) == '1')
                    {
                        row.fadeOut(500, function(){
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
                    }
                    else
                    {
                        Swal.fire({
                            icon: 'error',
                            title: 'Delete Failed'
                        });
                    }

                },

                error:function(xhr)
                {
                    console.log(xhr.responseText);

                    Swal.fire({
                        icon:'error',
                        title:'Server Error'
                    });
                }

            });

        }

    });

});

</script>

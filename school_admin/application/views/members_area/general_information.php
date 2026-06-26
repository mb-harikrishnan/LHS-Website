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
           DOCUMENTS AND INFORMATION
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('upload_document'); ?>'">
          <i class="fa fa-upload"></i> Add Document
          </button>
        </div>

        <!-- Date Filter -->
        <form method="post" action="<?php echo base_url('general_information'); ?>">
        <div class="filter-bar">
       
          <div class="filter-group">
            <label for="reportType">Report Type</label>
            <select class="form-select" id="type" name="type" style="padding:9px 12px">
             <option value="">All</option>
              <option value="general_information"  <?php if($this->input->post('type') == 'general_information'){ echo 'selected'; } ?>>General Information</option>
              <option value="copy_of_affiliation" <?php if($this->input->post('type') == 'copy_of_affiliation'){ echo 'selected'; } ?>>Copies of Affiliation</option>
              <option value="copy_of_societies" <?php if($this->input->post('type') == 'copy_of_societies'){ echo 'selected'; } ?>>Copies of Societies</option>
              <option value="NOC" <?php if($this->input->post('type') == 'NOC'){ echo 'selected'; } ?>>NOC</option>
              <option value="copy_of_recognition" <?php if($this->input->post('type') == 'copy_of_recognition'){ echo 'selected'; } ?>>Copies of Recognition</option>
              <option value="copy_of_safty" <?php if($this->input->post('type') == 'copy_of_safty'){ echo 'selected'; } ?>>Building Safety Certificate</option>
              <option value="copy_of_fire_and_safety <?php if($this->input->post('type') == 'copy_of_fire_and_safety'){ echo 'selected'; } ?>">Fire Safety Certificate</option>
              <option value="DEO" <?php if($this->input->post('type') == 'DEO'){ echo 'selected'; } ?>>DEO Certificate</option>
              <option value="sanitation" <?php if($this->input->post('type') == 'sanitation'){ echo 'selected'; } ?>>Water, Health and Sanitation Certificates</option>
              <option value="land" <?php if($this->input->post('type') == 'land'){ echo 'selected'; } ?>>Certificate of Land</option>
            </select>
          </div>
          <div class="filter-actions">
            <button  type="submit" class="btn btn-primary">Apply Filter</button>
          </div>
        </div>
        </form>

        <p class="report-meta" style="padding:12px 24px 0" id="filterMeta">Showing all reports</p>

        <div class="report-table-wrap" id="reportTableWrap">
          <table class="report-table display" id="reportsDataTable" style="width:100%">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Date</th>
                      <th>Type</th>
                      <th>PDF</th>
                      <th>View</th>
                      <th>Action</th>
                  </tr>
              </thead>

    <tbody>

<?php if(!empty($information)) { ?>

    <?php $i=1; foreach($information as $row){ ?>

        <tr>

            <td><?php echo $i++; ?></td>

            <td>
                <?php echo date('d-m-Y', strtotime($row->d_date)); ?>
            </td>

            <td>
                <span class="report-type-badge">
                    <?php echo ucfirst(str_replace('_',' ', $row->c_type)); ?>
                </span>
            </td>

            <td>

                <?php if(!empty($row->c_document)){ ?>

                    <a href="<?php echo base_url('../assets/uploads/documents/'.$row->c_document); ?>"
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

                    <a href="<?php echo base_url('../assets/uploads/documents/'.$row->c_document); ?>"
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
        <div class="empty-reports" id="emptyReports" style="display:none">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
          </svg>
          <p>No reports found for the selected date range.</p>
        </div>
      </div>

     





      <style>
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
      </style>


  
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

                url: "<?php echo base_url('delete_general_information'); ?>",
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

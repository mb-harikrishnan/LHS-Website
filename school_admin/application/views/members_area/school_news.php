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
            News List
            <!-- <span class="card-badge" id="tableBadge">0 records</span> -->
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('add_news'); ?>'">
          <i class="fa fa-upload"></i> Add News
          </button>
        </div>

        <!-- Date Filter -->
       <!-- <form method="post" action="<?php echo base_url('school_news'); ?>" class="filter-form">

    <div class="date-filter-box">

        <div class="filter-group">
            <label for="fromDate">From Date</label>

            <div class="input-wrapper">
                <i class="fa fa-calendar"></i>

                <input type="date"
                       id="fromDate"
                       name="fromDate"
                       value="<?php echo !empty(set_value('fromDate')) ? set_value('fromDate') : date('Y-m-d'); ?>"
                       class="filter-input">
            </div>
        </div>

        <div class="filter-group">
            <label for="toDate">To Date</label>

            <div class="input-wrapper">
                <i class="fa fa-calendar"></i>

                <input type="date"
                       id="toDate"
                       name="toDate"
                       value="<?php echo !empty(set_value('toDate')) ? set_value('toDate') : date('Y-m-d'); ?>"
                       class="filter-input">
            </div>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn-filter">
                <i class="fa fa-filter"></i> Filter
            </button>

         
        </div>

    </div>

</form> -->


        <div class="report-table-wrap" id="reportTableWrap">
          <table class="report-table display" id="reportsDataTable" style="width:100%">
              <thead>
                  <tr>
                       <th>#SL</th>
                        <th>Date</th>
                        <th>Title</th>
                        <th>News</th>
                        <th>Delete</th>
                        <th>Edit</th>
                        <th>Action</th>
                  </tr>
              </thead>

   <tbody>

<?php if(!empty($news)) { ?>

    <?php $i=1; foreach($news as $row){ ?>

        <tr>

            <td><?php echo $i++; ?></td>

            <td data-order="<?php echo date('Y-m-d', strtotime($row->d_date)); ?>">
                <?php echo date('d-m-Y', strtotime($row->d_date)); ?>
            </td>

            <td>
                <span class="report-type-badge">
                    <?php echo ucfirst(str_replace('_',' ', $row->c_title)); ?>
                </span>
            </td>

            <td><?php echo $row->c_news; ?></td>

            <td>
                <button type="button"
                        class="table-btn btn btn-danger deleteBtn"
                        data-id="<?php echo $row->n_slno; ?>">

                    <i class="fa fa-trash"></i> Delete
                </button>
            </td>


        <td>

            <button type="button"
                    class="table-btn edit-btn"
                    onclick="window.location.href='<?php echo base_url('edit_news/'.$row->n_slno); ?>'">

                <i class="fa fa-edit"></i> Edit

            </button>

        </td>



<td>

    <?php if($row->c_status == 'Y') { ?>

        <button type="button"
                class="table-btn status-btn status-active"
                style="background:#16a34a;color:#fff;"
                onclick="window.location.href='<?php echo base_url('change_news_status/'.$row->n_slno.'/N'); ?>'">

            Active

        </button>

    <?php } else { ?>

        <button type="button"
                class="table-btn status-btn status-inactive"
                style="background:#dc2626;color:#fff;"
                onclick="window.location.href='<?php echo base_url('change_news_status/'.$row->n_slno.'/Y'); ?>'">

            Inactive

        </button>

    <?php } ?>

</td>

        </tr>

    <?php } ?>

<?php } else { ?>


        <tr>
    <td></td>
    <td></td>
    <td class="text-center">No News Found</td>
    <td></td>
    <td></td>
</tr>
    

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
            { orderable: false, targets: [3,4] }
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

                url: "<?php echo base_url('delete_news'); ?>",
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



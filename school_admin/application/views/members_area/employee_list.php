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
            Employee List
            <!-- <span class="card-badge" id="tableBadge">0 records</span> -->
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('add_employee'); ?>'">
          <i class="fa fa-upload"></i> Add Employee
          </button>
        </div>

        <!-- Date Filter -->
        
        <p class="report-meta" style="padding:12px 24px 0" id="filterMeta">Showing all reports</p>

       <div class="report-table-wrap" id="reportTableWrap">

    <table class="report-table display nowrap" id="reportsDataTable" style="width:100%">

        <thead>
            <tr>
                <th>#SL</th>
                <th>Date</th>
                <th>Name</th>
                <th>Password</th>
                <th>Mobile</th>
                <th>Designation</th>
                <th>Class</th>
                <th>Division</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

            <?php 
            $count = 1;

            foreach ($details as $row){ 

                $emClass = $row->emClass;
                $select = "SELECT cmName FROM class_master WHERE cmId = '$emClass'";
                $classResult = $this->db->query($select);
                $className = $classResult->row()->cmName ?? '';

                $emDiv = $row->emDiv;
                $selectDiv = "SELECT dmName FROM division_master WHERE dmId = '$emDiv'";
                $divResult = $this->db->query($selectDiv);
                $divName = $divResult->row()->dmName ?? '';
            ?>


              

            <tr>

                <td><?php echo $count; ?></td>

                <td>
                    <?php echo date('d-m-Y', strtotime($row->emTS)); ?>
                </td>

                <td>
                    <?php echo $row->emName;  ?>
                </td>
                <td>
                    <?php echo $row->emPassword;  ?>
                </td>
                <td>
                    <?php echo $row->emPhoneNo;  ?>
                </td>

                <td>
                    <?php echo $row->emDesigId;  ?>
                </td>
                <td>
                    <?php echo $className;  ?>
                </td>
                <td>
                    <?php echo $divName;  ?>
                </td>

                <td>
                    <button class="deleteBtn"
                            data-id="<?php echo $row->emId; ?>">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </td>

            </tr>

            <?php 
            $count++; 
            } 
            ?>

        </tbody>

    </table>


         


</div>   

      </div>

     

<style>
/* =========================================
   CARD
========================================= */

.card{
    background:#fff;
    border-radius:18px;
    padding:24px;
    border:1px solid #e5e7eb;
    box-shadow:0 4px 20px rgba(0,0,0,0.05);
    overflow:hidden;
}

.card-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    margin-bottom:18px;
    flex-wrap:wrap;
}

.card-title{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:18px;
    font-weight:700;
    color:#1e293b;
}

.card-action{
    background:#1E3A8A;
    color:#fff;
    border:none;
    padding:11px 18px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.card-action:hover{
    background:#172554;
    transform:translateY(-1px);
}

/* =========================================
   FILTER
========================================= */

.date-filter-box{
    background:#f8fbff;
    border:1px solid #dbeafe;
    border-radius:16px;
    padding:20px;
    display:flex;
    gap:18px;
    align-items:end;
    flex-wrap:wrap;
    margin-bottom:15px;
}

.filter-item{
    flex:1;
    min-width:220px;
}

.filter-item label{
    display:block;
    margin-bottom:8px;
    font-size:13px;
    font-weight:700;
    color:#1E3A8A;
}

.date-input-box{
    position:relative;
}

.date-input-box i{
    position:absolute;
    left:14px;
    top:50%;
    transform:translateY(-50%);
    color:#64748b;
}

.filter-input{
    width:100%;
    height:48px;
    border:1px solid #cbd5e1;
    border-radius:12px;
    padding:0 14px 0 42px;
    background:#fff;
    font-size:14px;
    transition:0.3s;
    outline:none;
}

.filter-input:focus{
    border-color:#1E3A8A;
    box-shadow:0 0 0 4px rgba(30,58,138,0.12);
}

.filter-btn-box{
    display:flex;
    align-items:end;
}

.btn-filter{
    height:48px;
    padding:0 24px;
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

.btn-filter:hover{
    background:#172554;
}

/* =========================================
   TABLE
========================================= */

.report-table-wrap{
    width:100%;
    overflow-x:auto;
    margin-top:20px;
}

.report-table{
    width:100%;
    border-collapse:collapse !important;
}

.report-table thead th{
    background:#1E3A8A !important;
    color:#fff !important;
    padding:16px;
    font-size:14px;
    font-weight:700;
    border:none !important;
    text-transform:uppercase;
}

.report-table thead th:first-child{
    border-top-left-radius:12px;
}

.report-table thead th:last-child{
    border-top-right-radius:12px;
}

.report-table tbody td{
    padding:16px;
    border-bottom:1px solid #e5e7eb;
    font-size:14px;
    color:#374151;
    vertical-align:middle;
    background:#fff;
}

.report-table tbody tr{
    transition:0.3s;
}

.report-table tbody tr:hover td{
    background:#eff6ff;
}

/* =========================================
   BUTTONS
========================================= */

.view-btn{
    background:#2563eb;
    color:#fff;
    border:none;
    padding:9px 16px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.view-btn:hover{
    background:#1d4ed8;
}

.deleteBtn{
    background:#dc2626;
    color:#fff;
    border:none;
    padding:9px 16px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.deleteBtn:hover{
    background:#b91c1c;
}

/* =========================================
   DATATABLE
========================================= */

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter{
    margin-bottom:18px;
}

.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label{
    font-size:14px;
    font-weight:600;
    color:#1E3A8A;
}

.dataTables_wrapper .dataTables_filter input{
    width:260px !important;
    height:44px !important;
    border:1px solid #bfdbfe !important;
    border-radius:12px !important;
    padding:0 14px !important;
    background:#eff6ff !important;
    outline:none !important;
    transition:0.3s;
}

.dataTables_wrapper .dataTables_filter input:focus{
    border-color:#1E3A8A !important;
    background:#fff !important;
    box-shadow:0 0 0 4px rgba(30,58,138,0.12) !important;
}

.dataTables_wrapper .dataTables_paginate{
    margin-top:20px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button{
    background:#dbeafe !important;
    color:#1E3A8A !important;
    border:none !important;
    border-radius:10px !important;
    padding:7px 14px !important;
    margin:0 4px !important;
    font-weight:600;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current{
    background:#1E3A8A !important;
    color:#fff !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover{
    background:#1d4ed8 !important;
    color:#fff !important;
}


/* =========================================
   MOBILE
========================================= */

@media(max-width:768px){

    .card{
        padding:16px;
    }

    .date-filter-box{
        flex-direction:column;
        align-items:stretch;
    }

    .filter-item{
        width:100%;
    }

    .btn-filter,
    .card-action{
        width:100%;
        justify-content:center;
    }

    .report-table thead th,
    .report-table tbody td{
        padding:12px;
        font-size:13px;
    }

    .dataTables_wrapper .dataTables_filter{
        margin-top:15px;
        text-align:left !important;
    }

    .dataTables_wrapper .dataTables_filter input{
        width:100% !important;
    }
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
        autoWidth: false,
        pageLength: 10,

        order: [[0, 'desc']],

        columnDefs: [
            {
                orderable: false,
                targets: [3,4]
            }
        ],

        language: {
            search: "",
            searchPlaceholder: "Search reports...",
            lengthMenu: "Show _MENU_ entries",
            zeroRecords: "No reports found",
            info: "Showing _START_ to _END_ of _TOTAL_ reports",
            paginate: {
                previous: "Prev",
                next: "Next"
            }
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

                url: "<?php echo base_url('delete_employee'); ?>",
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



<script>

function openModal(id)
{
    document.getElementById('modal_' + id).classList.add('active');

    document.body.style.overflow = 'hidden';
}

function closeModal(id)
{
    document.getElementById('modal_' + id).classList.remove('active');

    document.body.style.overflow = 'auto';
}

/* CLOSE WHEN CLICK OUTSIDE */
window.onclick = function(event)
{
    let modals = document.getElementsByClassName('custom-modal');

    for(let i = 0; i < modals.length; i++)
    {
        if(event.target === modals[i])
        {
            modals[i].classList.remove('active');

            document.body.style.overflow = 'auto';
        }
    }
}

</script>

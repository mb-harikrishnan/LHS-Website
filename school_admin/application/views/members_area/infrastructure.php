
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
                    <div class="card-title-creative">Video List</div>

                   <a href="<?php echo base_url('upload_video'); ?>" class="upload-btn">
                        <i class="fa fa-upload"></i> Upload Video
                    </a>
    
                </div>

                <div class="table-responsive-wrapper">
                    <form method="post" action="<?php echo base_url('infrastructure'); ?>">


                        <div class="date-filter-box">
                        
                            <div class="filter-group">
                                <label>Status</label>
                                <select id="statusFilter" class="filter-input" name="type">
                                    <option value="">All</option>
                                    <option value="infrastructure"  <?php if($this->input->post('type') == 'infrastructure'){ echo 'selected'; } ?>>Infrastructure</option>
                                    <option value="slider_video"  <?php if($this->input->post('type') == 'slider_video'){ echo 'selected'; } ?>>Slider Video</option>
                                    
                                </select>
                            </div>

                            <div class="filter-actions">
                                <button type="submit" class="btn-filter">Filter</button>
                            </div>
                        

                        
                        </div>

                    </form>
                    <table id="incomeTable" class="display responsive nowrap creative-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>#SL</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>video</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 1;
                            foreach ($information as $row){   ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $row->d_date; ?></td>
                                <td><?php echo $row->c_type; ?></td>
<td>

<?php if(!empty($row->c_videos)) { ?>

    <video class="table-video" controls preload="auto">
        <source src="<?php echo base_url('../assets/uploads/videos/' . $row->c_videos); ?>" type="video/mp4">
    </video>

<?php } elseif(!empty($row->links)) { ?>

    <video class="table-video" controls preload="auto">
        <source src="<?php echo $row->links; ?>" type="video/mp4">
    </video>

<?php } ?>

</td>
                                <td>
                                   <button class="btn btn-danger deleteBtn" 
                                                data-id="<?php echo $row->n_slno; ?>">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </td>
                            </tr>
                            <?php $count++; } ?>
                        </tbody>
                    </table>
         
               
                </div>
            </div>

        </div>
    </div>

<style>
.table-video{
    width: 220px;
    height: 140px;
    border-radius: 10px;
    object-fit: cover;
}
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


.btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 6px 14px rgba(239, 68, 68, 0.25);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
    box-shadow: 0 10px 18px rgba(239, 68, 68, 0.35);
}

.btn-danger:active {
    transform: scale(0.97);
}

.btn-danger:focus {
    outline: none;
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2);
}
</style>



    <!-- Scripts -->
    <script src="<?php echo JS_PATH ?>jquery-3.6.0.min.js"></script>
    <script src="<?php echo JS_PATH ?>jquery.dataTables.min.js"></script>
    <script src="<?php echo JS_PATH ?>dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        $(document).ready(function() {
            var table = $('#incomeTable').DataTable({
                responsive: true,
                "pageLength": 10,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search records...",
                    "lengthMenu": "_MENU_ entries",
                    "paginate": {
                        "previous": '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
                        "next": '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>'
                    }
                },
                "dom": '<"top"lf>rt<"bottom"ip><"clear">',
                // "order": [[1, "desc"]]
            });

            // Adjust table on sidebar toggle if necessary
            $(window).on('resize', function() {
                table.columns.adjust();
            });
        });
    </script>


<script>

$(document).on('click', '.deleteBtn', function () {

    var id = $(this).data('id');
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
                url: "<?php echo base_url('delete_video'); ?>",
                type: "POST",
                data: {id:id},

                success: function (response) {

                    if(response == 1)
                    {
                        row.fadeOut(500);

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
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Delete Failed',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }

                }

            });

        }

    });

});

</script>





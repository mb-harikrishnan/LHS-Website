<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>

<!-- Report CSS -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/report.css'); ?>">



<!-- ==========================================================
    REPORT CARD
========================================================== -->
<div class="card">

    <div class="card-head">
        <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Exam List
        </div>

        <button class="card-action"
            onclick="window.location.href='<?php echo base_url('add_exam'); ?>'">
            <i class="fa fa-plus"></i> Add Exam
        </button>
    </div>

    <div class="report-table-wrap">
        <table id="reportsDataTable"
               class="report-table display nowrap"
               style="width:100%">

            <thead>
                <tr >
                    <th>#SL</th>
                    <th>Abbreviation</th>
                    <th>Name</th>
                    <th>Display Order</th>
                    <th>Edit</th>
                    <th width="120">Action</th>
                </tr>
            </thead>

            <tbody>

                <?php $count = 1; ?>

                <?php foreach ($details as $row) { ?>

                    <tr data-id="<?php echo $row->emId; ?>">

                        <td><?php echo $count++; ?></td>

                        <td><?php echo $row->emName; ?></td>

                        <td><?php echo $row->emDisplayName; ?></td>
                        <td><?php echo $row->emDisplayOrder; ?></td>

                        <td>
                              <a href="<?php echo base_url('edit_exam/'.$row->emId); ?>"
                                class="editBtn">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                        </td>

                        <td>
                            <button class="deleteBtn"
                                    data-id="<?php echo (int)$row->emId; ?>">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>
    </div>

</div>

      <style>
        .editBtn{
    display:inline-block;
    padding:7px 12px;
    background:#2563eb;
    color:#fff;
    border-radius:5px;
    text-decoration:none;
    margin-right:6px;
}

.editBtn:hover{
    background:#1d4ed8;
    color:#fff;
}
      </style>

<!-- ==========================================================
    SCRIPTS
========================================================== -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(function () {

    /*------------------------------
      DataTable
    ------------------------------*/
   

    /*------------------------------
      Delete Record
    ------------------------------*/
    $(document).on('click', '.deleteBtn', function (e) {

        e.preventDefault();

        let id  = $(this).data('id');
        let row = $(this).closest('tr');

        Swal.fire({

            title: 'Are you sure?',
            text: 'You want to delete this exam.',
            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',

            confirmButtonText: 'Yes, Delete'

        }).then(function(result){

            if (!result.isConfirmed) return;

            $.ajax({

                url: "<?php echo base_url('delete_exam'); ?>",
                type: "POST",
                data: {
                    id: id
                },

                success: function(response){

                    if($.trim(response) == "1"){

                        row.fadeOut(300,function(){
                            $(this).remove();
                        });

                        Swal.fire({
                            toast:true,
                            position:'top-end',
                            icon:'success',
                            title:'Deleted Successfully',
                            showConfirmButton:false,
                            timer:2000
                        });

                    }else{

                        Swal.fire(
                            'Failed',
                            'Unable to delete record.',
                            'error'
                        );

                    }

                },

                error:function(xhr){

                    console.log(xhr.responseText);

                    Swal.fire(
                        'Error',
                        'Server Error',
                        'error'
                    );

                }

            });

        });

    });

});

</script>


<script>
    var table = $('#reportsDataTable').DataTable({
    responsive: true,
    autoWidth: false,
    pageLength: 10,
    ordering: false   // Disable DataTable sorting
});

$("#reportsDataTable tbody").sortable({

    helper: function(e, tr) {
        var originals = tr.children();
        var helper = tr.clone();

        helper.children().each(function(index) {
            $(this).width(originals.eq(index).width());
        });

        return helper;
    },

    update: function(event, ui) {

        var order = [];

        $('#reportsDataTable tbody tr').each(function(index){

            order.push({
                id: $(this).data('id'),
                displayOrder: index + 1
            });

        });

        $.ajax({

            url: "<?php echo base_url('update_exam_order'); ?>",
            type: "POST",
            data: {
                order: order
            },

            success:function(){

                Swal.fire({
                    toast:true,
                    icon:'success',
                    title:'Order Updated',
                    position:'top-end',
                    timer:1500,
                    showConfirmButton:false
                });

            }

        });

    }

}).disableSelection();
</script>
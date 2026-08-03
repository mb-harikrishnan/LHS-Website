<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>

<!-- Report Page Styles -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/report.css'); ?>">

<!-- Reports Table Card -->
<div class="card">

    <!-- CARD HEADER -->
    <div class="card-head">
        <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Menu List
        </div>

        <button class="card-action"
                onclick="window.location.href='<?php echo base_url('add_menu'); ?>'">
            <i class="fa fa-upload"></i> Add Menu
        </button>
    </div>

    <!-- SEARCH BOX (replaces DataTables search, since parent/child grouping
         doesn't work reliably with DataTables' own pagination/search) -->
    <div class="report-search-wrap" style="padding:12px 16px;">
        <input type="text"
               id="menuSearchInput"
               class="form-control"
               placeholder="Search menu..."
               style="max-width:280px; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
    </div>

    <!-- TABLE -->
    <div class="report-table-wrap" id="reportTableWrap">

        <!-- NOTE: id changed from "reportsDataTable" to "menuTreeTable"
             so this table is NOT picked up by any global DataTables
             auto-init script that may run on tables with the old id/class -->
        <table id="menuTreeTable" class="report-table display nowrap" style="width:100%">

            <thead>
            <tr>
                <th>#</th>
                <th>Menu Name</th>
                <th>Display Name</th>
                <th>Link</th>
                <th>Order</th>
                <th>Status</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>

            <tbody>

            <?php if (!empty($menu)) { ?>

                <?php $i = 1; foreach ($menu as $parent) { ?>

                    <!-- PARENT ROW -->
                    <tr class="parent-row" data-group="group-<?php echo $parent->menu_id; ?>"
                        style="background:#f3f4f6; font-weight:600;">

                        <td><?php echo $i++; ?></td>

                        <td>
                            <?php if (!empty($parent->children)) { ?>
                                <i class="fa fa-plus-square toggle-icon"
                                   data-target="group-<?php echo $parent->menu_id; ?>"
                                   data-state="collapsed"
                                   style="cursor:pointer; color:var(--green); margin-right:6px;"></i>
                            <?php } else { ?>
                                <i class="fa fa-square" style="visibility:hidden; margin-right:6px;"></i>
                            <?php } ?>
                            <?php echo $parent->menu_name; ?>
                        </td>

                        <td><?php echo $parent->display_name; ?></td>
                        <td><?php echo $parent->menu_link ?? '-'; ?></td>
                        <td><?php echo $parent->display_order; ?></td>

                        <td>
                            <span class="badge <?php echo $parent->status ? 'badge-success' : 'badge-danger'; ?>">
                                <?php echo $parent->status ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>

                        <td>
                            <button type="button" class="table-btn edit-btn"
                                    onclick="window.location.href='<?php echo base_url('edit_menu/'.$parent->menu_id); ?>'">
                                <i class="fa fa-edit"></i>
                            </button>
                        </td>

                        <td>
                            <button type="button" class="table-btn delete-btn deleteBtn"
                                    data-id="<?php echo $parent->menu_id; ?>">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>

                    </tr>

                    <!-- CHILD ROWS (hidden by default) -->
                    <?php if (!empty($parent->children)) { ?>
                        <?php foreach ($parent->children as $child) { ?>
                            <tr class="child-row group-<?php echo $parent->menu_id; ?>" style="display:none;">

                                <td></td>

                                <td style="padding-left:30px;">
                                    &#8627; <?php echo $child->menu_name; ?>
                                </td>

                                <td><?php echo $child->display_name; ?></td>
                                <td><?php echo $child->menu_link ?? '-'; ?></td>
                                <td><?php echo $child->display_order; ?></td>

                                <td>
                                    <span class="badge <?php echo $child->status ? 'badge-success' : 'badge-danger'; ?>">
                                        <?php echo $child->status ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>

                                <td>
                                    <button type="button" class="table-btn edit-btn"
                                            onclick="window.location.href='<?php echo base_url('edit_menu/'.$child->menu_id); ?>'">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>

                                <td>
                                    <button type="button" class="table-btn delete-btn deleteBtn"
                                            data-id="<?php echo $child->menu_id; ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>

                            </tr>
                        <?php } ?>
                    <?php } ?>

                <?php } ?>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Font Awesome (required for fa-plus-square / fa-minus-square / fa-edit / fa-trash icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- EXPAND / COLLAPSE TOGGLE -->
<script>
$(document).on('click', '.toggle-icon', function () {

    let icon   = $(this);
    let target = icon.data('target');
    let rows   = $('.' + target);

    rows.toggle();

    let expanded = rows.first().is(':visible');

    icon.data('state', expanded ? 'expanded' : 'collapsed');

    icon
        .toggleClass('fa-plus-square', !expanded)
        .toggleClass('fa-minus-square', expanded);
});
</script>

<!-- SEARCH FILTER (plain JS filter, no DataTables — keeps parent/child grouping intact) -->
<script>
$('#menuSearchInput').on('keyup', function () {

    let value = $(this).val().toLowerCase();

    $('#menuTreeTable tbody tr.parent-row').each(function () {

        let parentRow   = $(this);
        let groupClass  = parentRow.data('group');
        let childRows   = $('.' + groupClass);

        let parentText  = parentRow.text().toLowerCase();
        let childText   = childRows.text().toLowerCase();

        let matches = parentText.indexOf(value) > -1 || childText.indexOf(value) > -1;

        parentRow.toggle(matches);

        // if searching and a match is found in children, auto-expand them
        if (value.length > 0 && matches && childText.indexOf(value) > -1) {
            childRows.show();
            $('.toggle-icon[data-target="' + groupClass + '"]')
                .removeClass('fa-plus-square')
                .addClass('fa-minus-square')
                .data('state', 'expanded');
        } else if (value.length === 0) {
            childRows.hide();
            $('.toggle-icon[data-target="' + groupClass + '"]')
                .removeClass('fa-minus-square')
                .addClass('fa-plus-square')
                .data('state', 'collapsed');
        } else {
            childRows.hide();
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

                url: "<?php echo base_url('delete_menu'); ?>",
                type: "POST",
                data: { id: id },

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
<?php
$pageTitle    = 'Reports';
$breadcrumb   = 'Reports';
$activePage   = 'reports';
$showGlobalSearch = false;

/**
 * Helper: safely look up a single column from a table by id.
 * Uses query bindings (prevents SQL injection) and never calls
 * ->row() on a possibly-empty result set (prevents fatal errors
 * that used to blank out the whole page, including the modal).
 */
function lookupValue($db, $table, $idColumn, $id, $column, $fallback = '-')
{
    if ($id === null || $id === '') {
        return $fallback;
    }

    $qry = $db->query("SELECT {$column} FROM {$table} WHERE {$idColumn} = ?", array($id));

    if ($qry && $qry->num_rows() > 0) {
        return $qry->row()->$column;
    }

    return $fallback;
}
?>

<!-- Report Page Styles -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/report.css'); ?>">

<!-- Bootstrap (needed for the modal below) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- ============================================================
     Reports Table Card
============================================================= -->
<div class="card">
    <div class="card-head">
        <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Student List
        </div>
        <button class="card-action"
                onclick="window.location.href='<?php echo base_url('add_student'); ?>'">
            <i class="fa fa-upload"></i> Add Students
        </button>
    </div>

    <div class="report-table-wrap" id="reportTableWrap">
        <table class="report-table display nowrap" id="reportsDataTable" style="width:100%">

            <thead>
                <tr>
                    <th>#SL</th>
                    <th>Admission Number</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Divition</th>
                    <th>Details</th>
                    <th>Edit</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $count = 1;

                foreach ($details as $row) {

                    $res_class = lookupValue($this->db, 'class_master', 'cmId', $row->smClass, 'cmName');
                    $res_div   = lookupValue($this->db, 'division_master', 'dmId', $row->smDiv, 'dmName');
                    $res_country = lookupValue($this->db, 'country', 'country_id', $row->smCountry ?? null, 'name');
                    $res_state   = lookupValue($this->db, 'country_states', 'code', $row->smState ?? null, 'name');

                    $genderMap = array(
                        '0' => 'Male',
                        '1' => 'Female',
                        '2' => 'Other'
                    );
                    $genderLabel = isset($genderMap[$row->smGender]) ? $genderMap[$row->smGender] : '';
                    
                    ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo htmlspecialchars($row->smAdmissionNo ?? '', ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($row->smName ?? '', ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($res_class, ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($res_div, ENT_QUOTES); ?></td>
                        <td>
                            <button type="button" class="viewDetailsBtn btn btn-sm btn-primary"
                                data-aadhar="<?php echo htmlspecialchars($row->smAadharNo ?? '', ENT_QUOTES); ?>"
                           data-gender="<?php echo htmlspecialchars($genderLabel, ENT_QUOTES); ?>"
                                data-dob="<?php echo htmlspecialchars($row->smDOB ?? '', ENT_QUOTES); ?>"
                                data-mobile="<?php echo htmlspecialchars($row->smMobile ?? '', ENT_QUOTES); ?>"
                                data-religion="<?php echo htmlspecialchars($row->smReligion ?? '', ENT_QUOTES); ?>"
                                data-caste="<?php echo htmlspecialchars($row->smCaste ?? '', ENT_QUOTES); ?>"
                                data-language="<?php echo htmlspecialchars($row->smMotherTongue ?? '', ENT_QUOTES); ?>"
                                data-address="<?php echo htmlspecialchars($row->smAddress ?? '', ENT_QUOTES); ?>"
                                data-country="<?php echo htmlspecialchars($res_country, ENT_QUOTES); ?>"
                                data-state="<?php echo htmlspecialchars($res_state, ENT_QUOTES); ?>">
                                View Details
                            </button>
                        </td>

                        <td>    
                            <button type="button"
                                    class="table-btn edit-btn"
                                    onclick="window.location.href='<?php echo base_url('edit_students/'.$row->smId); ?>'">

                                <i class="fa fa-edit"></i> Edit

                            </button>
                        <td>
                            <button class="deleteBtn" data-id="<?php echo (int) $row->smId; ?>">
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

<!-- ============================================================
     Student Details Modal (Bootstrap 5) — restyled
============================================================= -->
<style>
#studentDetailsModal .modal-content {
    border: none;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0,0,0,.25);
}
#studentDetailsModal .modal-header {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    padding: 18px 24px;
    border: none;
}
#studentDetailsModal .modal-title {
    font-weight: 600;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}
#studentDetailsModal .modal-title i {
    font-size: 16px;
}
#studentDetailsModal .btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
    opacity: .9;
}
#studentDetailsModal .modal-body {
    padding: 0;
    background: #f9fafb;
    max-height: 60vh;
    overflow-y: auto;
}
#studentDetailsModal .detail-row {
    display: flex;
    align-items: center;
    padding: 12px 24px;
    border-bottom: 1px solid #eef0f3;
    background: #fff;
}
#studentDetailsModal .detail-row:nth-child(even) {
    background: #fafbfc;
}
#studentDetailsModal .detail-row:last-child {
    border-bottom: none;
}
#studentDetailsModal .detail-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #eff4ff;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    margin-right: 14px;
    flex-shrink: 0;
}
#studentDetailsModal .detail-label {
    width: 150px;
    font-weight: 600;
    color: #374151;
    font-size: 13px;
    flex-shrink: 0;
}
#studentDetailsModal .detail-value {
    color: #111827;
    font-size: 14px;
    word-break: break-word;
}
</style>

<div class="modal fade" id="studentDetailsModal" tabindex="-1" aria-labelledby="studentDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="studentDetailsModalLabel">
            <i class="fa fa-id-card"></i> Student Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="detail-row">
            <div class="detail-icon"><i class="fa fa-address-card"></i></div>
            <div class="detail-label">Aadhar Number</div>
            <div class="detail-value" id="modalAadhar"></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="fa fa-venus-mars"></i></div>
            <div class="detail-label">Gender</div>
            <div class="detail-value" id="modalGender"></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="fa fa-calendar"></i></div>
            <div class="detail-label">DOB</div>
            <div class="detail-value" id="modalDOB"></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="fa fa-mobile"></i></div>
            <div class="detail-label">Mobile</div>
            <div class="detail-value" id="modalMobile"></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="fa fa-pray"></i></div>
            <div class="detail-label">Religion</div>
            <div class="detail-value" id="modalReligion"></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="fa fa-users"></i></div>
            <div class="detail-label">Caste</div>
            <div class="detail-value" id="modalCaste"></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="fa fa-language"></i></div>
            <div class="detail-label">Mother Tongue</div>
            <div class="detail-value" id="modalLanguage"></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="fa fa-map-marker-alt"></i></div>
            <div class="detail-label">Address</div>
            <div class="detail-value" id="modalAddress"></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="fa fa-flag"></i></div>
            <div class="detail-label">Country</div>
            <div class="detail-value" id="modalCountry"></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="fa fa-map"></i></div>
            <div class="detail-label">State</div>
            <div class="detail-value" id="modalState"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ============================================================
     Vendor Scripts
============================================================= -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Bootstrap JS bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- ============================================================
     Page Script
============================================================= -->
<script>
$(document).ready(function () {

    // ---- DataTable init ----
    $('#reportsDataTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [5, 6] } // Details / Action columns shouldn't be sortable
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

    // ---- Delete handler ----
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
            if (!result.isConfirmed) return;

            $.ajax({
                url: "<?php echo base_url('delete_students'); ?>",
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
                        Swal.fire({ icon: 'error', title: 'Delete Failed' });
                    }
                },

                error: function (xhr) {
                    console.log(xhr.responseText);
                    Swal.fire({ icon: 'error', title: 'Server Error' });
                }
            });
        });
    });

    // ---- View details / modal handler ----
    $(document).on('click', '.viewDetailsBtn', function (e) {
        e.preventDefault();

        $('#modalAadhar').text($(this).data('aadhar'));
        $('#modalGender').text($(this).data('gender'));
        $('#modalDOB').text($(this).data('dob'));
        $('#modalMobile').text($(this).data('mobile'));
        $('#modalReligion').text($(this).data('religion'));
        $('#modalCaste').text($(this).data('caste'));
        $('#modalLanguage').text($(this).data('language'));
        $('#modalAddress').text($(this).data('address'));
        $('#modalCountry').text($(this).data('country'));
        $('#modalState').text($(this).data('state'));

        var modalEl = document.getElementById('studentDetailsModal');
        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    });

});
</script>
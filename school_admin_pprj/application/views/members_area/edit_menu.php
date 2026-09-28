<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
<?php endif; ?>

<div class="card shadow-sm">

    <!-- Card Header -->
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary">
            <i class="fa fa-file-alt me-2"></i> Edit Menu
        </h5>
        <a href="<?= base_url('menu_list') ?>" class="text-primary">List</a>
    </div>

    <!-- Card Body -->
    <div class="card-body">
        <?= form_open(current_url()) ?>

            <!-- Hidden field to carry the menu_id on submit -->
            <input type="hidden" name="menu_id" value="<?= $menu->menu_id ?>">

            <div class="row mb-3 align-items-center">
                <label class="col-sm-2 col-form-label text-primary">Menu Name</label>
                <div class="col-sm-6">
                    <input type="text" name="menu_name" class="form-control"
                           value="<?= set_value('menu_name', $menu->menu_name) ?>"
                           placeholder="e.g. EXAM_MASTER" required>
                    <?= form_error('menu_name', '<small class="text-danger">', '</small>') ?>
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-sm-2 col-form-label text-primary">Display Name</label>
                <div class="col-sm-6">
                    <input type="text" name="display_name" class="form-control"
                           value="<?= set_value('display_name', $menu->display_name) ?>"
                           placeholder="e.g. Exam Master" required>
                    <?= form_error('display_name', '<small class="text-danger">', '</small>') ?>
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-sm-2 col-form-label text-primary">Menu Link</label>
                <div class="col-sm-6">
                    <input type="text" name="menu_link" class="form-control"
                           value="<?= set_value('menu_link', $menu->menu_link) ?>"
                           placeholder="/exams/master">
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-sm-2 col-form-label text-primary">Parent Menu</label>
                <div class="col-sm-6">
                    <select name="parent_menu_id" class="form-select">
                        <option value="">-- None (Top Level) --</option>
                        <?php foreach ($menus as $m): ?>
                            <?php if ($m->menu_id == $menu->menu_id) continue; // don't allow self as parent ?>
                            <option value="<?= $m->menu_id ?>"
                                <?= set_select('parent_menu_id', $m->menu_id, $menu->parent_menu_id == $m->menu_id) ?>>
                                <?= $m->menu_id ?> - <?= $m->display_name ?> (<?= $m->menu_name ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-sm-2 col-form-label text-primary">Display Order</label>
                <div class="col-sm-6">
                    <input type="number" name="display_order" class="form-control"
                           value="<?= set_value('display_order', $menu->display_order) ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-6">
                    <button type="submit" id="updateMenuBtn" class="btn btn-primary px-4">Update Menu</button>
                    <a href="<?= base_url('menu_list') ?>" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </div>

        <?= form_close() ?>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- CONFIRM BEFORE UPDATE -->
<script>
$(document).on('submit', 'form', function (e) {

    // Only intercept if this form contains the Update Menu button
    if ($('#updateMenuBtn').length === 0) return;

    e.preventDefault();
    let form = this;

    Swal.fire({
        title: 'Update this menu?',
        text: 'Your changes will be saved.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2c3e91',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, update it'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>

<style>
.card { background:#fff; border:none; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,.06); overflow:hidden; }
.card-header { display:flex; justify-content:space-between; align-items:center; padding:18px 24px; background:#fff; border-bottom:1px solid #eef0f4; }
.card-header h5 { margin:0; font-size:18px; font-weight:600; color:#2c3e91; display:flex; align-items:center; gap:8px; }
.card-header h5 i { color:#2c3e91; font-size:16px; }
.card-header a { color:#2c3e91; font-size:14px; font-weight:500; text-decoration:none; }
.card-header a:hover { text-decoration:underline; }
.card-body { padding:28px 24px; }
.row { display:flex; flex-wrap:wrap; margin-bottom:20px; }
.row:last-child { margin-bottom:0; }
.col-sm-2 { flex:0 0 160px; max-width:160px; display:flex; align-items:center; }
.col-form-label { font-size:14px; font-weight:500; color:#2c3e91; margin:0; }
.col-sm-6 { flex:1 1 380px; max-width:420px; }
.form-control, .form-select { width:100%; padding:8px 12px; font-size:14px; color:#333; background:#fff; border:1px solid #d5d9e2; border-radius:6px; transition:border-color .15s ease, box-shadow .15s ease; }
.form-control::placeholder { color:#a3a9b7; }
.form-control:focus, .form-select:focus { outline:none; border-color:#2c3e91; box-shadow:0 0 0 3px rgba(44,62,145,.12); }
.form-select { appearance:none; background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232c3e91' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat:no-repeat; background-position:right 10px center; background-size:16px; padding-right:32px; cursor:pointer; }
.btn-primary { background:#2c3e91; border:none; color:#fff; font-size:14px; font-weight:600; padding:10px 28px; border-radius:6px; cursor:pointer; transition:background .15s ease; }
.btn-primary:hover { background:#24337a; }
.btn-secondary { background:#e5e7eb; border:none; color:#374151; font-size:14px; font-weight:600; padding:10px 28px; border-radius:6px; cursor:pointer; text-decoration:none; margin-left:8px; display:inline-block; }
.btn-secondary:hover { background:#d1d5db; }
.alert { padding:12px 16px; border-radius:8px; font-size:14px; margin-bottom:16px; }
.alert-success { background:#e6f7ee; color:#1a7f4b; border:1px solid #b7ecd0; }
.alert-danger { background:#fdeceb; color:#c0392b; border:1px solid #f5c6c2; }
@media (max-width: 576px) {
    .col-sm-2 { flex:0 0 100%; max-width:100%; margin-bottom:6px; }
    .col-sm-6 { flex:0 0 100%; max-width:100%; }
}
</style>
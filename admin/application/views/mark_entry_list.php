<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= html_escape($title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f6fa; }
        .box { background: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 1px 4px rgba(0,0,0,.1); }
        h2 { margin: 0 0 15px; }
        .filters { margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap; }
        select, input, button { padding: 7px 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #2d6cdf; color: #fff; border-color: #2d6cdf; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 9px 10px; border-bottom: 1px solid #e3e3e3; text-align: left; }
        th { background: #f0f2f7; }
        .badge { background: #1e9e55; color: #fff; padding: 2px 8px; border-radius: 10px; font-size: 12px; }
        .empty { text-align: center; color: #888; padding: 25px; }
    </style>
</head>
<body>
<div class="box">
    <h2><?= html_escape($title) ?></h2>

    <form method="get" action="<?= site_url('mark_entry_list') ?>" class="filters">
        <select name="exam">
            <option value="">All Exams</option>
            <?php foreach ($exams as $e): ?>
                <option value="<?= (int)$e->emId ?>" <?= ($exam == $e->emId) ? 'selected' : '' ?>>
                    <?= html_escape($e->emName) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filter</button>
        <input type="text" id="search" placeholder="Search class / division...">
    </form>

    <table id="list">
        <thead>
            <tr>
                <th>#</th>
                <th>Exam</th>
                <th>Class</th>
                <th>Division</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($rows)): $i = 1; foreach ($rows as $r): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= html_escape($r->emName) ?></td>
                <td><?= html_escape($r->cmName) ?></td>
                <td><?= html_escape($r->dmName) ?></td>
                <td><span class="badge">Final Submitted</span></td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="5" class="empty">No final submitted mark entries found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('search').addEventListener('keyup', function () {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#list tbody tr').forEach(function (tr) {
        tr.style.display = tr.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
    });
});
</script>
</body>
</html>
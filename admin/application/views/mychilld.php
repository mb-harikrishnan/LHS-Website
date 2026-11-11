<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * $children can be passed from the controller. Each child:
 *  id, name, class, division, roll_no, admission_no, gender (F/M), photo, attendance
 * Sample data is used when nothing is passed, so the page renders on its own.
 */
$children = isset($children) ? $children : array(
    array('id' => 1, 'name' => 'Aarya Ramesh',   'class' => '5', 'division' => 'A', 'roll_no' => 12, 'admission_no' => 'LHS-2021-045', 'gender' => 'F', 'photo' => '', 'attendance' => 96),
    array('id' => 2, 'name' => 'Adithya Ramesh', 'class' => '2', 'division' => 'B', 'roll_no' => 7,  'admission_no' => 'LHS-2023-118', 'gender' => 'M', 'photo' => '', 'attendance' => 94),
);

/* Use the uploaded photo if it exists, otherwise a default avatar. */
function pc_photo($c)
{
    if (!empty($c['photo']) && file_exists(FCPATH . $c['photo'])) {
        return base_url($c['photo']);
    }
    $gender = isset($c['gender']) ? strtoupper($c['gender']) : 'F';
    return base_url('assets/images/' . ($gender === 'M' ? 'boy.png' : 'girl.png'));
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    .pc {
        --pc-ink: #1e1b4b; --pc-primary: #4338ca; --pc-muted: #64748b; --pc-border: #dcdcf5;
        font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: var(--pc-ink); max-width: 1200px; margin: 0 auto; padding: 16px; box-sizing: border-box;
    }
    .pc *, .pc *::before, .pc *::after { box-sizing: inherit; }
    .pc a { text-decoration: none; color: inherit; }

    .pc-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
    .pc-top h1 { margin: 0; font-size: 26px; font-weight: 700; }
    .pc-top p  { margin: 4px 0 0; color: var(--pc-muted); font-size: 14px; }
    .pc-back { display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; border: 1px solid var(--pc-border);
               border-radius: 8px; font-weight: 600; font-size: 14px; color: var(--pc-primary); background: #fff; }
    .pc-back:hover, .pc-back:focus-visible { background: #f1f0ff; }

    .pc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 18px; }

    .pc-card { background: #fff; border: 1px solid var(--pc-border); border-radius: 14px; overflow: hidden; }
    .pc-card__head {
        display: flex; align-items: center; gap: 16px; padding: 18px 20px;
        background: linear-gradient(90deg, #eef0ff, #f6f3ff);
    }
    .pc-card__head img {
        flex: none; width: 76px; height: 76px; border-radius: 50%; object-fit: cover;
        border: 3px solid #fff; background: #e9e7fb;
    }
    .pc-card__head h2 { margin: 0; font-size: 19px; font-weight: 700; }
    .pc-card__head span { font-size: 13px; color: var(--pc-muted); }

    .pc-details { margin: 0; padding: 8px 20px 4px; }
    .pc-row { display: flex; justify-content: space-between; gap: 12px; padding: 11px 0; border-bottom: 1px solid #eceefb; font-size: 14px; }
    .pc-row:last-child { border-bottom: 0; }
    .pc-row dt { color: var(--pc-muted); margin: 0; }
    .pc-row dd { margin: 0; font-weight: 600; text-align: right; }

    .pc-bar { height: 6px; border-radius: 3px; background: #e6f4ec; margin-top: 6px; overflow: hidden; }
    .pc-bar i { display: block; height: 100%; background: #16a34a; }

    .pc-actions { display: flex; gap: 10px; padding: 14px 20px 20px; }
    .pc-btn { flex: 1; text-align: center; padding: 10px 12px; border-radius: 8px; background: #ddd8fb; color: #2e2a7a; font-size: 14px; font-weight: 600; }
    .pc-btn:hover, .pc-btn:focus-visible { background: #cbc4f7; }
    .pc-btn--alt { background: #fff; border: 1px solid var(--pc-border); color: var(--pc-primary); }
    .pc-btn i { margin-right: 6px; font-size: 12px; }

    .pc-empty { padding: 40px 20px; text-align: center; border: 1px dashed var(--pc-border); border-radius: 12px; color: var(--pc-muted); }
</style>

<div class="pc">

    <div class="pc-top">
        <div>
            <h1>My Children</h1>
            <p><?= count($children); ?> student<?= count($children) === 1 ? '' : 's'; ?> linked to your account</p>
        </div>
        <a class="pc-back" href="<?= site_url('parent/dashboard'); ?>"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <?php if (empty($children)): ?>
        <div class="pc-empty">No students are linked to your account yet. Please contact the school office.</div>
    <?php else: ?>
    <div class="pc-grid">
        <?php foreach ($children as $c): ?>
            <article class="pc-card">
                <div class="pc-card__head">
                    <img src="<?= pc_photo($c); ?>" alt="<?= html_escape($c['name']); ?>">
                    <div>
                        <h2><?= html_escape($c['name']); ?></h2>
                        <span>Class <?= html_escape($c['class']); ?> - <?= html_escape($c['division']); ?></span>
                    </div>
                </div>

                <dl class="pc-details">
                    <div class="pc-row"><dt>Class</dt><dd><?= html_escape($c['class']); ?></dd></div>
                    <div class="pc-row"><dt>Division</dt><dd><?= html_escape($c['division']); ?></dd></div>
                    <div class="pc-row"><dt>Roll Number</dt><dd><?= html_escape($c['roll_no']); ?></dd></div>
                    <div class="pc-row"><dt>Admission No.</dt><dd><?= html_escape($c['admission_no']); ?></dd></div>
                    <?php if (isset($c['attendance'])): ?>
                    <div class="pc-row" style="display:block">
                        <div style="display:flex;justify-content:space-between">
                            <dt>Attendance</dt><dd><?= (int) $c['attendance']; ?>%</dd>
                        </div>
                        <div class="pc-bar"><i style="width:<?= (int) $c['attendance']; ?>%"></i></div>
                    </div>
                    <?php endif; ?>
                </dl>

                <div class="pc-actions">
                    <a class="pc-btn" href="<?= site_url('parent/child/' . $c['id']); ?>"><i class="fa-solid fa-id-card"></i>View Details</a>
                    <a class="pc-btn pc-btn--alt" href="<?= site_url('parent/fees/' . $c['id']); ?>"><i class="fa-solid fa-indian-rupee-sign"></i>Fees</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>
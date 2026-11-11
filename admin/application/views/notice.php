
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Sample data - replace with controller data */

$academic_year = isset($academic_year) ? $academic_year : '2026 - 27';

$circulars = isset($circulars) ? $circulars : array(

    array(
        'id' => 1,
        'title' => 'Annual Examination Time Table 2026',
        'description' => 'The annual examination will commence from March 10, 2027. Parents are requested to ensure that students prepare according to the examination schedule.',
        'category' => 'Exam',
        'date' => '2026-11-10',
        'expiry_date' => '2027-03-20',
        'attachment' => 'uploads/circulars/exam_timetable.pdf',
        'attachment_name' => 'Annual_Exam_Timetable.pdf',
        'important' => 1
    ),

    array(
        'id' => 2,
        'title' => 'Children Day Celebration',
        'description' => 'Our school will celebrate Children Day on November 14. Students are requested to attend the celebration in colourful dress.',
        'category' => 'Events',
        'date' => '2026-11-08',
        'expiry_date' => '2026-11-14',
        'attachment' => '',
        'attachment_name' => '',
        'important' => 0
    ),

    array(
        'id' => 3,
        'title' => 'School Holiday Announcement',
        'description' => 'The school will remain closed on November 20 due to a public holiday. Regular classes will resume on the next working day.',
        'category' => 'Holiday',
        'date' => '2026-11-07',
        'expiry_date' => '2026-11-21',
        'attachment' => '',
        'attachment_name' => '',
        'important' => 1
    ),

    array(
        'id' => 4,
        'title' => 'Parent Teacher Meeting',
        'description' => 'A parent teacher meeting will be conducted on November 25 from 10 AM to 1 PM. All parents are requested to attend.',
        'category' => 'Meeting',
        'date' => '2026-11-05',
        'expiry_date' => '2026-11-25',
        'attachment' => 'uploads/circulars/ptm_notice.pdf',
        'attachment_name' => 'PTM_Notice.pdf',
        'important' => 0
    ),

    array(
        'id' => 5,
        'title' => 'School Uniform Instructions',
        'description' => 'All students must follow the prescribed school uniform and maintain proper discipline during school hours.',
        'category' => 'General',
        'date' => '2026-11-01',
        'expiry_date' => '2026-12-31',
        'attachment' => '',
        'attachment_name' => '',
        'important' => 0
    ),

    array(
        'id' => 6,
        'title' => 'Science Exhibition Registration',
        'description' => 'Students interested in participating in the upcoming science exhibition should submit their names to their respective class teachers.',
        'category' => 'Events',
        'date' => '2026-10-28',
        'expiry_date' => '2026-11-18',
        'attachment' => 'uploads/circulars/science_exhibition.pdf',
        'attachment_name' => 'Science_Exhibition.pdf',
        'important' => 0
    )
);

$total_notices = count($circulars);
$important_count = 0;
$categories = array();

foreach ($circulars as $c) {
    if (!empty($c['important'])) {
        $important_count++;
    }
    $categories[$c['category']] = $c['category'];
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
.cn-page {
    --primary: #4338ca;
    --dark: #27215f;
    --muted: #718096;
    --border: #e5e7f2;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #f5f6fc;
    color: #25234c;
    min-height: 100vh;
    padding: 22px 16px;
}

.cn-page * {
    box-sizing: border-box;
}

.cn-container {
    max-width: 1200px;
    margin: auto;
}

/* Header */

.cn-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.cn-header h2 {
    font-size: 25px;
    font-weight: 750;
    margin: 0;
}

.cn-header p {
    color: var(--muted);
    margin: 6px 0 0;
    font-size: 13px;
}

.cn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    color: var(--primary);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 10px 16px;
    text-decoration: none;
    font-weight: 650;
    font-size: 13px;
}

/* Welcome banner */

.cn-banner {
    position: relative;
    overflow: hidden;
    background: linear-gradient(115deg, #37308f, #6655d8);
    border-radius: 15px;
    padding: 25px 28px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.cn-banner h3 {
    margin: 0 0 8px;
    font-size: 21px;
}

.cn-banner p {
    color: #e1dfff;
    font-size: 13px;
    margin: 0;
    line-height: 1.6;
}

.cn-banner-icon {
    width: 75px;
    height: 75px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
    background: rgba(255,255,255,.15);
    border-radius: 50%;
    font-size: 34px;
}

/* Statistics */

.cn-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 22px;
}

.cn-stat {
    display: flex;
    align-items: center;
    gap: 14px;
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 17px;
}

.cn-stat-icon {
    width: 46px;
    height: 46px;
    display: grid;
    place-items: center;
    border-radius: 11px;
    font-size: 19px;
}

.cn-stat strong {
    display: block;
    font-size: 22px;
}

.cn-stat span {
    font-size: 12px;
    color: var(--muted);
}

.cn-purple { background: #eceaff; color: #5144cb; }
.cn-red { background: #ffe9ed; color: #e11d48; }
.cn-green { background: #e2f8ed; color: #159451; }

/* Search */

.cn-toolbar {
    background: white;
    padding: 17px;
    border: 1px solid var(--border);
    border-radius: 12px;
    margin-bottom: 20px;
}

.cn-search-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.cn-search {
    flex: 1;
    min-width: 220px;
    position: relative;
}

.cn-search i {
    position: absolute;
    left: 14px;
    top: 14px;
    color: #9295ac;
}

.cn-search input {
    width: 100%;
    height: 44px;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 10px 12px 10px 40px;
    outline: none;
    font-size: 13px;
}

.cn-search input:focus {
    border-color: var(--primary);
}

.cn-select {
    min-width: 170px;
    height: 44px;
    padding: 8px 12px;
    border: 1px solid var(--border);
    border-radius: 8px;
    color: #444;
    outline: none;
}

/* Section title */

.cn-section-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 25px 0 15px;
    gap: 10px;
}

.cn-section-head h3 {
    font-size: 18px;
    margin: 0;
}

.cn-section-head span {
    font-size: 12px;
    color: var(--muted);
}

/* Notice grid */

.cn-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

/* Notice card */

.cn-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 13px;
    padding: 19px;
    transition: .2s ease;
    position: relative;
}

.cn-card:hover {
    border-color: #b9b3f1;
    box-shadow: 0 7px 22px rgba(47,39,120,.07);
    transform: translateY(-2px);
}

.cn-card.important {
    border-left: 4px solid #e49b26;
}

.cn-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}

.cn-category {
    display: inline-block;
    padding: 5px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 750;
    background: #eceaff;
    color: #4939c3;
}

.cn-category.exam { background:#e9e8ff; color:#4939c3; }
.cn-category.holiday { background:#fff1db; color:#c47b10; }
.cn-category.events { background:#e0f6ed; color:#128451; }
.cn-category.meeting { background:#e0f2fe; color:#0876b6; }
.cn-category.general { background:#f1e8ff; color:#8a40b6; }

.cn-important {
    color: #d97706;
    font-size: 11px;
    font-weight: 750;
}

.cn-card h4 {
    font-size: 16px;
    line-height: 1.5;
    margin: 0 0 9px;
    color: #29245e;
}

.cn-description {
    font-size: 13px;
    line-height: 1.7;
    color: #73788f;
    margin: 0 0 16px;
}

.cn-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    padding-top: 13px;
    border-top: 1px solid #f0f0f5;
    font-size: 11px;
    color: #81869b;
}

.cn-meta i {
    color: #6255cf;
    margin-right: 5px;
}

/* Attachment */

.cn-attachment {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f7f7fc;
    border: 1px solid #eeeeF6;
    border-radius: 8px;
    padding: 10px;
    margin-top: 14px;
}

.cn-pdf-icon {
    width: 35px;
    height: 35px;
    display: grid;
    place-items: center;
    color: #e34a4a;
    background: #ffe7e7;
    border-radius: 7px;
    font-size: 17px;
}

.cn-pdf-info {
    flex: 1;
    min-width: 0;
}

.cn-pdf-info strong {
    display: block;
    font-size: 11px;
    overflow-wrap: anywhere;
}

.cn-pdf-info span {
    font-size: 10px;
    color: var(--muted);
}

.cn-download {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 11px;
    color: white;
    background: var(--primary);
    border-radius: 7px;
    text-decoration: none;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.cn-download:hover {
    background: #30269d;
    color: white;
}

/* Read more */

.cn-read {
    display: inline-block;
    margin-top: 13px;
    font-size: 12px;
    color: var(--primary);
    font-weight: 700;
    cursor: pointer;
    border: 0;
    background: none;
    padding: 0;
}

/* Empty */

.cn-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 45px 20px;
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    color: var(--muted);
}

.cn-empty i {
    font-size: 35px;
    margin-bottom: 12px;
    color: #a5a1d7;
}

.cn-empty h3 {
    color: #383568;
    font-size: 17px;
}

/* Modal */

.cn-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(20,18,55,.6);
    padding: 20px;
    align-items: center;
    justify-content: center;
}

.cn-modal.show {
    display: flex;
}

.cn-modal-box {
    width: 100%;
    max-width: 540px;
    background: white;
    border-radius: 14px;
    padding: 25px;
    max-height: 85vh;
    overflow-y: auto;
}

.cn-modal-head {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 15px;
}

.cn-modal-head h3 {
    margin: 0;
    font-size: 18px;
}

.cn-close {
    border: 0;
    background: #f1f1f7;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
}

.cn-modal-description {
    font-size: 14px;
    line-height: 1.8;
    color: #666c80;
    white-space: pre-line;
}

/* Responsive */

@media(max-width: 750px) {
    .cn-grid {
        grid-template-columns: 1fr;
    }

    .cn-stats {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media(max-width: 520px) {
    .cn-page {
        padding: 15px 10px;
    }

    .cn-stats {
        grid-template-columns: 1fr;
    }

    .cn-stat {
        padding: 12px;
    }

    .cn-banner {
        padding: 20px;
    }

    .cn-banner h3 {
        font-size: 18px;
    }

    .cn-banner-icon {
        width: 55px;
        height: 55px;
        font-size: 24px;
    }

    .cn-select {
        width: 100%;
    }

    .cn-search {
        width: 100%;
    }

    .cn-card {
        padding: 15px;
    }
}
</style>

<div class="cn-page">
<div class="cn-container">

    <!-- HEADER -->
    <div class="cn-header">
        <div>
            <h2><i class="fa-solid fa-bullhorn"></i> Circulars & Notices</h2>
            <p>Academic Year: <?= html_escape($academic_year); ?></p>
        </div>

        <a href="<?= site_url('parent/dashboard'); ?>" class="cn-back">
            <i class="fa fa-arrow-left"></i> Dashboard
        </a>
    </div>

    <!-- WELCOME BANNER -->
    <div class="cn-banner">
        <div>
            <h3>Stay Updated with School Announcements!</h3>
            <p>All important school circulars, announcements, examination updates and upcoming events in one place.</p>
        </div>

        <div class="cn-banner-icon">
            <i class="fa-solid fa-bell"></i>
        </div>
    </div>

    <!-- STATISTICS -->
    <div class="cn-stats">

        <div class="cn-stat">
            <div class="cn-stat-icon cn-purple">
                <i class="fa-solid fa-file-lines"></i>
            </div>
            <div>
                <strong><?= $total_notices; ?></strong>
                <span>Total Notices</span>
            </div>
        </div>

        <div class="cn-stat">
            <div class="cn-stat-icon cn-red">
                <i class="fa-solid fa-thumbtack"></i>
            </div>
            <div>
                <strong><?= $important_count; ?></strong>
                <span>Important Notices</span>
            </div>
        </div>

        <div class="cn-stat">
            <div class="cn-stat-icon cn-green">
                <i class="fa-solid fa-file-pdf"></i>
            </div>
            <div>
                <strong>
                    <?php
                    $attachment_count = 0;
                    foreach ($circulars as $c) {
                        if (!empty($c['attachment'])) $attachment_count++;
                    }
                    echo $attachment_count;
                    ?>
                </strong>
                <span>PDF Attachments</span>
            </div>
        </div>

    </div>

    <!-- SEARCH AND FILTER -->
    <div class="cn-toolbar">

        <div class="cn-search-row">

            <div class="cn-search">
                <i class="fa fa-search"></i>
                <input type="text" id="noticeSearch"
                       placeholder="Search notices by title or description...">
            </div>

            <select id="noticeCategory" class="cn-select">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= html_escape(strtolower($category)); ?>">
                        <?= html_escape($category); ?>
                    </option>
                <?php endforeach; ?>
            </select>

        </div>

    </div>

    <!-- IMPORTANT NOTICES -->
    <div class="cn-section-head">
        <h3><i class="fa-solid fa-star" style="color:#e5a52c;"></i> School Notices</h3>
        <span><?= $total_notices; ?> announcements</span>
    </div>

    <!-- NOTICE CARDS -->
    <div class="cn-grid" id="noticeGrid">

        <?php foreach ($circulars as $c): ?>

        <?php
            $category_class = strtolower($c['category']);
            $search_text = strtolower($c['title'].' '.$c['description'].' '.$c['category']);
        ?>

        <div class="cn-card <?= !empty($c['important']) ? 'important' : ''; ?>"
             data-category="<?= html_escape($category_class); ?>"
             data-search="<?= html_escape($search_text); ?>">

            <div class="cn-card-top">

                <span class="cn-category <?= html_escape($category_class); ?>">
                    <?= html_escape($c['category']); ?>
                </span>

                <?php if (!empty($c['important'])): ?>
                    <span class="cn-important">
                        <i class="fa-solid fa-thumbtack"></i> IMPORTANT
                    </span>
                <?php endif; ?>

            </div>

            <h4><?= html_escape($c['title']); ?></h4>

            <p class="cn-description">
                <?= html_escape(mb_strimwidth($c['description'], 0, 150, '...')); ?>
            </p>

            <div class="cn-meta">

                <span>
                    <i class="fa-regular fa-calendar"></i>
                    <?= date('d M Y', strtotime($c['date'])); ?>
                </span>

                <span>
                    <i class="fa-regular fa-clock"></i>
                    Valid till:
                    <?= date('d M Y', strtotime($c['expiry_date'])); ?>
                </span>

            </div>

            <?php if (!empty($c['attachment'])): ?>

            <div class="cn-attachment">

                <div class="cn-pdf-icon">
                    <i class="fa-solid fa-file-pdf"></i>
                </div>

                <div class="cn-pdf-info">
                    <strong><?= html_escape($c['attachment_name']); ?></strong>
                    <span>PDF Document</span>
                </div>

                <a href="<?= base_url($c['attachment']); ?>"
                   class="cn-download" download>
                    <i class="fa fa-download"></i> Download
                </a>

            </div>

            <?php endif; ?>

            <button type="button"
                    class="cn-read"
                    data-title="<?= html_escape($c['title']); ?>"
                    data-description="<?= html_escape($c['description']); ?>"
                    data-category="<?= html_escape($c['category']); ?>"
                    data-date="<?= date('d M Y', strtotime($c['date'])); ?>"
                    onclick="showNotice(this)">

                Read Full Notice <i class="fa fa-arrow-right"></i>

            </button>

        </div>

        <?php endforeach; ?>

        <div class="cn-empty" id="noNotices" style="display:none;">
            <i class="fa-solid fa-folder-open"></i>
            <h3>No Notices Found</h3>
            <p>Try searching with a different keyword or category.</p>
        </div>

    </div>

</div>
</div>

<!-- FULL NOTICE MODAL -->

<div class="cn-modal" id="noticeModal">

    <div class="cn-modal-box">

        <div class="cn-modal-head">
            <h3 id="modalTitle">Notice Details</h3>

            <button class="cn-close" onclick="closeNotice()">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <span class="cn-category" id="modalCategory"></span>

        <p style="font-size:12px;color:#888;margin-top:12px;">
            <i class="fa-regular fa-calendar"></i>
            Published: <span id="modalDate"></span>
        </p>

        <div class="cn-modal-description" id="modalDescription"></div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const search = document.getElementById('noticeSearch');
    const category = document.getElementById('noticeCategory');
    const cards = document.querySelectorAll('.cn-card');
    const empty = document.getElementById('noNotices');

    function filterNotices() {

        let keyword = search.value.toLowerCase().trim();
        let selectedCategory = category.value.toLowerCase();
        let visible = 0;

        cards.forEach(function(card) {

            let text = card.getAttribute('data-search');
            let cardCategory = card.getAttribute('data-category');

            let matchesSearch = text.includes(keyword);

            let matchesCategory = !selectedCategory ||
                                  cardCategory === selectedCategory;

            if (matchesSearch && matchesCategory) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }

        });

        empty.style.display = visible === 0 ? 'block' : 'none';
    }

    search.addEventListener('input', filterNotices);
    category.addEventListener('change', filterNotices);

});

function showNotice(button) {

    document.getElementById('modalTitle').textContent =
        button.dataset.title;

    document.getElementById('modalDescription').textContent =
        button.dataset.description;

    document.getElementById('modalCategory').textContent =
        button.dataset.category;

    document.getElementById('modalDate').textContent =
        button.dataset.date;

    document.getElementById('noticeModal').classList.add('show');
}

function closeNotice() {
    document.getElementById('noticeModal').classList.remove('show');
}

document.getElementById('noticeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeNotice();
    }
});
</script>


<?php
$school_name = isset($school_name) ? $school_name : 'Little Hearts Public School';
$school_address = isset($school_address) ? $school_address : 'North Paravur, Ernakulam, Kerala';
$school_phone = isset($school_phone) ? $school_phone : '+91 98765 43210';
$school_logo = isset($school_logo) ? $school_logo : base_url('assets/images/school-logo.png');

$receipt = isset($receipt) ? $receipt : array(
    'receipt_no' => 'RCP-10452',
    'bill_date' => '2026-11-02',
    'student_name' => 'Aarya Ramesh',
    'admission_no' => 'ADM-1005',
    'class' => '5',
    'division' => 'A',
    'term' => 'Term 3',
    'fee_title' => 'Tuition Fee - Term 3',
    'amount' => 6000,
    'mode' => 'UPI',
    'transaction_id' => 'TXN123456789',
    'payment_date' => '2026-11-02',
    'status' => 'Success',
    'received_by' => 'School Administration'
);

function receipt_money($amount) {
    return '₹ ' . number_format((float)$amount, 2, '.', ',');
}

function receipt_date($date) {
    return !empty($date) ? date('d M Y', strtotime($date)) : '-';
}
?>

<link rel="stylesheet"
 href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
.receipt-page {
    background: #f3f4fb;
    padding: 25px 15px;
    min-height: 100vh;
    font-family: 'Segoe UI', Arial, sans-serif;
    color: #1e1b4b;
}

.receipt-actions {
    max-width: 850px;
    margin: 0 auto 18px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.receipt-actions button {
    border: none;
    padding: 11px 20px;
    border-radius: 7px;
    font-weight: 600;
    cursor: pointer;
}

.btn-print {
    background: #4338ca;
    color: white;
}

.btn-download {
    background: #15803d;
    color: white;
}

.receipt-paper {
    max-width: 850px;
    margin: auto;
    background: #fff;
    padding: 35px 40px;
    border-radius: 12px;
    box-shadow: 0 5px 25px rgba(0,0,0,.08);
    border-top: 7px solid #4338ca;
    position: relative;
    overflow: hidden;
}

.receipt-header {
    display: flex;
    align-items: center;
    gap: 18px;
    padding-bottom: 22px;
    border-bottom: 2px solid #e0ddfa;
}

.receipt-logo {
    width: 95px;
    height: 95px;
    object-fit: contain;
}

.school-info h1 {
    font-size: 25px;
    color: #312e81;
    font-weight: 800;
    margin: 0 0 7px;
}

.school-info p {
    margin: 4px 0;
    font-size: 13px;
    color: #64748b;
}

.receipt-heading {
    text-align: center;
    margin: 25px 0;
}

.receipt-heading h2 {
    background: #4338ca;
    color: #fff;
    display: inline-block;
    padding: 12px 35px;
    border-radius: 7px;
    font-size: 22px;
    letter-spacing: 1px;
    margin: 0;
}

.receipt-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    background: #f0efff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 22px;
}

.info-item {
    font-size: 14px;
    line-height: 1.7;
}

.info-item strong {
    display: inline-block;
    min-width: 115px;
    color: #312e81;
}

.receipt-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    font-size: 14px;
}

.receipt-table th {
    background: #312e81;
    color: white;
    padding: 13px;
    text-align: left;
}

.receipt-table td {
    padding: 13px;
    border: 1px solid #e5e7eb;
}

.receipt-table .amount {
    text-align: right;
    white-space: nowrap;
}

.receipt-table .total-row td {
    background: #eeedff;
    color: #312e81;
    font-weight: 800;
    font-size: 16px;
}

.payment-status {
    display: inline-block;
    background: #dcfce7;
    color: #15803d;
    padding: 5px 13px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 12px;
}

.amount-words {
    margin-top: 20px;
    background: #f0efff;
    padding: 15px;
    border-radius: 7px;
    font-size: 14px;
}

.receipt-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 55px;
    gap: 20px;
}

.receipt-thanks {
    text-align: center;
    color: #4338ca;
    font-weight: 700;
    font-size: 17px;
}

.signature {
    text-align: center;
    min-width: 180px;
}

.signature-line {
    border-top: 1px solid #312e81;
    padding-top: 8px;
    margin-top: 45px;
    font-weight: 700;
}

.receipt-bottom {
    text-align: center;
    color: #64748b;
    font-size: 12px;
    border-top: 1px solid #e5e7eb;
    margin-top: 30px;
    padding-top: 15px;
}

@media(max-width:600px) {
    .receipt-paper {
        padding: 20px 15px;
    }

    .receipt-header {
        align-items: flex-start;
    }

    .receipt-logo {
        width: 65px;
        height: 65px;
    }

    .school-info h1 {
        font-size: 18px;
    }

    .receipt-info {
        grid-template-columns: 1fr;
    }

    .receipt-footer {
        flex-direction: column;
        align-items: center;
    }

    .receipt-heading h2 {
        font-size: 17px;
        padding: 12px 18px;
    }

    .receipt-table {
        font-size: 12px;
    }
}

@media print {
    @page {
        size: A4;
        margin: 12mm;
    }

    body {
        background: white !important;
    }

    .receipt-page {
        padding: 0;
        background: white !important;
        min-height: auto;
    }

    .receipt-actions {
        display: none !important;
    }

    .receipt-paper {
        box-shadow: none;
        border-radius: 0;
        max-width: 100%;
        padding: 20px;
        border-top: 5px solid #4338ca;
    }

    .receipt-table th,
    .receipt-table .total-row td,
    .receipt-heading h2,
    .receipt-info,
    .amount-words {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>

<div class="receipt-page">

    <!-- Download and Print Buttons -->
    <div class="receipt-actions">
        <button class="btn-print" onclick="window.print()">
            <i class="fa fa-print"></i> Print Receipt
        </button>

        <button class="btn-download" onclick="downloadReceipt()">
            <i class="fa fa-download"></i> Download PDF
        </button>
    </div>

    <!-- Receipt Document -->
    <div class="receipt-paper" id="feeReceipt">

        <!-- School Header -->
        <div class="receipt-header">

            <img src="<?= html_escape($school_logo); ?>"
                 class="receipt-logo"
                 alt="School Logo">

            <div class="school-info">
                <h1><?= html_escape($school_name); ?></h1>

                <p>
                    <i class="fa fa-location-dot"></i>
                    <?= html_escape($school_address); ?>
                </p>

                <p>
                    <i class="fa fa-phone"></i>
                    <?= html_escape($school_phone); ?>
                </p>
            </div>

        </div>

        <!-- Title -->
        <div class="receipt-heading">
            <h2>FEE PAYMENT RECEIPT</h2>
        </div>

        <!-- Student Details -->
        <div class="receipt-info">

            <div class="info-item">
                <strong>Receipt No.</strong>:
                <?= html_escape($receipt['receipt_no']); ?>
            </div>

            <div class="info-item">
                <strong>Bill Date</strong>:
                <?= receipt_date($receipt['bill_date']); ?>
            </div>

            <div class="info-item">
                <strong>Student Name</strong>:
                <?= html_escape($receipt['student_name']); ?>
            </div>

            <div class="info-item">
                <strong>Admission No.</strong>:
                <?= html_escape($receipt['admission_no']); ?>
            </div>

            <div class="info-item">
                <strong>Class / Division</strong>:
                <?= html_escape($receipt['class']); ?> -
                <?= html_escape($receipt['division']); ?>
            </div>

            <div class="info-item">
                <strong>Term</strong>:
                <?= html_escape($receipt['term']); ?>
            </div>

        </div>

        <!-- Payment Details -->
        <table class="receipt-table">

            <thead>
                <tr>
                    <th>Description</th>
                    <th class="amount">Amount</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td><?= html_escape($receipt['fee_title']); ?></td>
                    <td class="amount">
                        <?= receipt_money($receipt['amount']); ?>
                    </td>
                </tr>

                <tr>
                    <td>Payment Mode</td>
                    <td class="amount">
                        <?= html_escape($receipt['mode']); ?>
                    </td>
                </tr>

                <tr>
                    <td>Transaction ID</td>
                    <td class="amount">
                        <?= html_escape($receipt['transaction_id']); ?>
                    </td>
                </tr>

                <tr>
                    <td>Payment Date</td>
                    <td class="amount">
                        <?= receipt_date($receipt['payment_date']); ?>
                    </td>
                </tr>

                <tr>
                    <td>Payment Status</td>
                    <td class="amount">
                        <?php if ($receipt['status'] == 'Success'): ?>
                            <span class="payment-status">
                                <i class="fa fa-check-circle"></i> Success
                            </span>
                        <?php else: ?>
                            <?= html_escape($receipt['status']); ?>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr class="total-row">
                    <td>Total Paid</td>
                    <td class="amount">
                        <?= receipt_money($receipt['amount']); ?>
                    </td>
                </tr>

            </tbody>
        </table>

        <!-- Amount in Words -->
        <div class="amount-words">
            <strong>Amount in Words:</strong>
            <?= html_escape(isset($amount_words) ? $amount_words : 'Rupees Six Thousand Only'); ?>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">

            <div class="receipt-thanks">
                <i class="fa fa-graduation-cap fa-2x"></i>
                <p>Thank you for your payment!</p>
                <small>Together for a brighter future.</small>
            </div>

            <div class="signature">
                <div class="signature-line">
                    Principal
                    <br>
                    <?= html_escape($school_name); ?>
                </div>
            </div>

        </div>

        <div class="receipt-bottom">
            This is a computer-generated receipt.
            Thank you for choosing our school.
        </div>

    </div>
</div>

<script>
function downloadReceipt() {
    window.print();
}
</script>

<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<body style="margin:0;padding:0;background:#f6f9ff;font-family:Arial,Helvetica,sans-serif;color:#22314a;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f9ff;padding:24px 12px;">
<tr><td align="center">
  <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e3ecf7;">

    <tr><td style="background:#0b2447;padding:26px 30px;">
      <div style="font-size:22px;font-weight:bold;color:#ffffff;">Little Hearts School</div>
      <div style="font-size:13px;color:#f5a623;margin-top:4px;">Admission 2027-28</div>
    </td></tr>

    <tr><td style="padding:30px;">
      <h2 style="margin:0 0 12px;font-size:22px;color:#0b2447;">Thank you, <?php echo html_escape($parent_name); ?>!</h2>
      <p style="margin:0 0 14px;font-size:15px;line-height:1.7;">
        We have received the admission enquiry for <b><?php echo html_escape($student_name); ?></b>.
        <b style="color:#e0475f;">We will contact you soon</b> to confirm the details and the school visit.
      </p>
      <p style="margin:0 0 20px;font-size:14px;color:#5b6b84;">Your reference number: <b style="color:#0b2447;"><?php echo html_escape($ref); ?></b></p>

      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e3ecf7;border-radius:12px;font-size:14px;">
        <tr><td colspan="2" style="background:#fff7f8;padding:10px 14px;font-weight:bold;color:#e0475f;">Application details</td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;width:40%;border-top:1px solid #e3ecf7;">Student name</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo html_escape($student_name); ?></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Date of birth</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo html_escape(date('d M Y', strtotime($dob))); ?></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Gender</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo html_escape($gender); ?></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Class applying for</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo html_escape($class_applied); ?></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Previous school</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo $prev_school !== '' ? html_escape($prev_school) : '-'; ?></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Parent name</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo html_escape($parent_name); ?></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Phone</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo html_escape($phone); ?></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Email</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo html_escape($email); ?></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Address</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo $address !== '' ? nl2br(html_escape($address)) : '-'; ?></td></tr>
      </table>

      <p style="margin:22px 0 6px;font-size:14px;font-weight:bold;color:#0b2447;">Please bring these documents when you visit:</p>
      <p style="margin:0;font-size:14px;line-height:1.7;color:#5b6b84;">Birth certificate, previous report card, passport-size photos and parent ID proof.</p>
    </td></tr>

    <tr><td style="background:#0b2447;padding:18px 30px;font-size:12px;color:#b8c6e6;text-align:center;">
      Questions? Reply to this email or write to info.littleheartsschool@gmail.com<br>
      &copy; <?php echo date('Y'); ?> Little Hearts School
    </td></tr>

  </table>
</td></tr>
</table>
</body>
</html>
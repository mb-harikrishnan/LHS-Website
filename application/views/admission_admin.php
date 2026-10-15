<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<body style="margin:0;padding:0;background:#f6f9ff;font-family:Arial,Helvetica,sans-serif;color:#22314a;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f9ff;padding:24px 12px;">
<tr><td align="center">
  <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e3ecf7;">

    <tr><td style="background:#e0475f;padding:22px 30px;">
      <div style="font-size:20px;font-weight:bold;color:#ffffff;">New admission enquiry</div>
      <div style="font-size:13px;color:#ffe9ed;margin-top:4px;">Reference <?php echo html_escape($ref); ?> &middot; <?php echo date('d M Y, h:i A'); ?></div>
    </td></tr>

    <tr><td style="padding:26px 30px;">
      <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">
        A new enquiry for <b><?php echo html_escape($student_name); ?></b> (<?php echo html_escape($class_applied); ?>) was submitted on the website.
        Replying to this email goes straight to the parent.
      </p>

      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e3ecf7;font-size:14px;">
        <tr><td colspan="2" style="background:#f6f9ff;padding:10px 14px;font-weight:bold;color:#0b2447;">Student details</td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;width:38%;border-top:1px solid #e3ecf7;">Student name</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo html_escape($student_name); ?></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Date of birth</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo html_escape(date('d M Y', strtotime($dob))); ?></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Gender</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo html_escape($gender); ?></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Class</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo html_escape($class_applied); ?></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Previous school</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo $prev_school !== '' ? html_escape($prev_school) : '-'; ?></td></tr>
        <tr><td colspan="2" style="background:#f6f9ff;padding:10px 14px;font-weight:bold;color:#0b2447;border-top:1px solid #e3ecf7;">Parent details</td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Parent name</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo html_escape($parent_name); ?></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Phone</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><a href="tel:<?php echo html_escape($phone); ?>" style="color:#2f6fed;"><?php echo html_escape($phone); ?></a></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Email</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><a href="mailto:<?php echo html_escape($email); ?>" style="color:#2f6fed;"><?php echo html_escape($email); ?></a></td></tr>
        <tr><td style="padding:9px 14px;color:#5b6b84;border-top:1px solid #e3ecf7;">Address</td><td style="padding:9px 14px;border-top:1px solid #e3ecf7;"><?php echo $address !== '' ? nl2br(html_escape($address)) : '-'; ?></td></tr>
      </table>

      <p style="margin:18px 0 0;font-size:13px;color:#5b6b84;">The parent has been sent a "we will contact you soon" confirmation email.</p>
    </td></tr>

  </table>
</td></tr>
</table>
</body>
</html>
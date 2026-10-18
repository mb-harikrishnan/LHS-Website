<!-- Admission Enquiry (view: application/views/admissions_enqry.php) -->
<?php
$classes = ['PRE KG', 'LKG', 'UKG'];
for ($i = 1; $i <= 10; $i++) { $classes[] = 'Class ' . $i; }

$ok  = $this->session->flashdata('ae_success');   // success text from controller
$err = $this->session->flashdata('ae_error');     // save-failed text from controller
?>
<section id="admission_enquiry" class="padding bg_light">
  <div class="container">

    <div class="ae_head">
      <h2>Admission 2027-28</h2>
      <p>Give your child a brighter future. Fill in the form and our admission team will contact you.</p>
    </div>

    <div class="ae_grid">

      <!-- Left: how to apply -->
      <aside class="ae_card">
        <h3 class="ae_h3">How to Apply</h3>
        <p class="ae_text">Admission takes three simple steps.</p>
        <ol class="ae_steps">
          <li><div><b>Submit the form</b><span>Fill in your child's details.</span></div></li>
          <li><div><b>We call you</b><span>Our team confirms details and the visit date.</span></div></li>
          <li><div><b>Visit and enrol</b><span>Meet us with the documents and complete admission.</span></div></li>
        </ol>
        <div class="ae_docs">
          <b>Documents to bring</b>
          <ul>
            <li>Birth certificate</li>
            <li>Previous report card</li>
            <li>Passport-size photos</li>
            <li>Parent ID proof</li>
          </ul>
        </div>
      </aside>

      <!-- Right: success panel OR form -->
      <div class="ae_card">
        <h3 class="ae_h3">Admission Application Form</h3>

        <?php if ($ok) { ?>

        <!-- GREEN SUCCESS PANEL (replaces the form after a successful submit) -->
        <div class="ae_success" id="ae_success" role="status">
          <div class="ae_tick">&#10003;</div>
          <h4>Application submitted!</h4>
          <p><?php echo html_escape($ok); ?></p>
        </div>

        <?php } else { ?>

        <?php if ($err) { ?><div class="ae_alert ae_alert_err" role="alert"><?php echo html_escape($err); ?></div><?php } ?>
        <?php if (validation_errors()) { ?><div class="ae_alert ae_alert_err" role="alert">Please correct the highlighted fields and submit again.</div><?php } ?>

        <form id="ae_form" method="post" action="<?php echo current_url(); ?>" novalidate>
          <?php if ($this->config->item('csrf_protection') === TRUE) { ?>
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <?php } ?>

          <div class="ae_sub">Student details</div>
          <div class="ae_fields">
            <div class="ae_f">
              <label class="ae_lbl" for="ae_student">Student name*</label>
              <input id="ae_student" name="student_name" maxlength="100" placeholder="Full name" autocomplete="off"
                     value="<?php echo set_value('student_name'); ?>" class="<?php echo form_error('student_name') ? 'ae_bad' : ''; ?>">
              <span class="ae_ferr"><?php echo form_error('student_name'); ?></span>
            </div>
            <div class="ae_f">
              <label class="ae_lbl" for="ae_dob">Date of birth*</label>
              <input id="ae_dob" name="dob" type="date" max="<?php echo date('Y-m-d'); ?>"
                     value="<?php echo set_value('dob'); ?>" class="<?php echo form_error('dob') ? 'ae_bad' : ''; ?>">
              <span class="ae_ferr"><?php echo form_error('dob'); ?></span>
            </div>
            <div class="ae_f ae_full">
              <span class="ae_lbl">Gender*</span>
              <div class="ae_pills">
                <?php foreach (['Girl', 'Boy', 'Other'] as $g) { ?>
                <label><input type="radio" name="gender" value="<?php echo $g; ?>" <?php echo set_radio('gender', $g); ?>><span><?php echo $g; ?></span></label>
                <?php } ?>
              </div>
              <span class="ae_ferr"><?php echo form_error('gender'); ?></span>
            </div>
            <div class="ae_f">
              <label class="ae_lbl" for="ae_cls">Class applying for*</label>
              <select id="ae_cls" name="class" class="<?php echo form_error('class') ? 'ae_bad' : ''; ?>">
                <option value="">Select class</option>
                <?php foreach ($classes as $c) { ?>
                <option value="<?php echo $c; ?>" <?php echo set_select('class', $c); ?>><?php echo $c; ?></option>
                <?php } ?>
              </select>
              <span class="ae_ferr"><?php echo form_error('class'); ?></span>
            </div>
            <div class="ae_f">
              <label class="ae_lbl" for="ae_prev">Previous school (if any)</label>
              <input id="ae_prev" name="prev_school" maxlength="150" placeholder="School name" value="<?php echo set_value('prev_school'); ?>">
              <span class="ae_ferr"><?php echo form_error('prev_school'); ?></span>
            </div>
          </div>

          <div class="ae_sub">Parent details</div>
          <div class="ae_fields">
            <div class="ae_f">
              <label class="ae_lbl" for="ae_parent">Parent name*</label>
              <input id="ae_parent" name="parent_name" maxlength="100" placeholder="Full name" autocomplete="name"
                     value="<?php echo set_value('parent_name'); ?>" class="<?php echo form_error('parent_name') ? 'ae_bad' : ''; ?>">
              <span class="ae_ferr"><?php echo form_error('parent_name'); ?></span>
            </div>
            <div class="ae_f">
              <label class="ae_lbl" for="ae_phone">Phone number*</label>
              <input id="ae_phone" name="phone" type="tel" inputmode="numeric" maxlength="10" placeholder="10-digit mobile number" autocomplete="tel"
                     value="<?php echo set_value('phone'); ?>" class="<?php echo form_error('phone') ? 'ae_bad' : ''; ?>">
              <span class="ae_ferr"><?php echo form_error('phone'); ?></span>
            </div>
            <div class="ae_f ae_full">
              <label class="ae_lbl" for="ae_email">Email*</label>
              <input id="ae_email" name="email" type="email" maxlength="120" placeholder="Your email" autocomplete="email"
                     value="<?php echo set_value('email'); ?>" class="<?php echo form_error('email') ? 'ae_bad' : ''; ?>">
              <span class="ae_ferr"><?php echo form_error('email'); ?></span>
            </div>
            <div class="ae_f ae_full">
              <label class="ae_lbl" for="ae_addr">Address</label>
              <textarea id="ae_addr" name="address" maxlength="300" placeholder="House, street, city, PIN"><?php echo set_value('address'); ?></textarea>
              <span class="ae_ferr"><?php echo form_error('address'); ?></span>
            </div>
          </div>

          <button type="submit" id="ae_btn" class="ae_submit">Submit application</button>
          <div id="ae_msg" role="status"></div>
        </form>

        <?php } ?>
      </div>

    </div>
  </div>
</section>

<style>
/* Everything is scoped to #admission_enquiry so it cannot clash with the site CSS */
#admission_enquiry{
  --acc:#e0475f; --navy:#0b2447; --gold:#f5a623; --ink:#22314a; --muted:#5b6b84;
  --line:#e3ecf7; --field:#f6f9ff; --blue:#2f6fed;
  position:relative; overflow-x:hidden;
  background-color:#f6f9ff;
  background-image:
    radial-gradient(circle at 8% 6%, rgba(224,71,95,.09) 0, transparent 24%),
    radial-gradient(circle at 96% 90%, rgba(245,166,35,.10) 0, transparent 28%),
    radial-gradient(rgba(11,36,71,.055) 1.3px, transparent 1.3px);
  background-size:auto,auto,26px 26px;
}
#admission_enquiry *,#admission_enquiry *::before,#admission_enquiry *::after{box-sizing:border-box}

#admission_enquiry .ae_head{text-align:center;margin-bottom:34px}
#admission_enquiry .ae_head h2{
  font-family:'Fredoka',cursive !important; color:var(--navy); font-size:38px; line-height:1.15;
  margin:0 0 6px; display:inline-block; padding:0 6px;
  background:linear-gradient(transparent 68%, rgba(245,166,35,.40) 68%);
}
#admission_enquiry .ae_head p{color:var(--muted); font-size:15.5px; line-height:1.8; max-width:640px; margin:12px auto 0}

#admission_enquiry .ae_grid{display:grid; grid-template-columns:minmax(0,4fr) minmax(0,8fr); gap:26px; align-items:start}

#admission_enquiry .ae_card{
  background:#fff; border:2px solid var(--line); border-top:4px solid #f1b3bd;
  border-radius:22px; box-shadow:0 16px 36px rgba(11,36,71,.10); padding:32px;
}
#admission_enquiry .ae_h3{font-family:'Fredoka',cursive !important; color:var(--navy); font-size:26px; margin:0 0 12px}
#admission_enquiry .ae_text{color:var(--muted); margin:0 0 18px; font-size:15px}

#admission_enquiry .ae_steps{list-style:none; margin:0 0 22px; padding:0; display:grid; gap:14px; counter-reset:ae}
#admission_enquiry .ae_steps li{display:flex; gap:14px; align-items:flex-start; counter-increment:ae; padding:0}
#admission_enquiry .ae_steps li::before{
  content:counter(ae); flex:none; width:34px; height:34px; border-radius:12px;
  background:linear-gradient(135deg,var(--acc),#a82e43); color:#fff; font-weight:800; font-size:14px;
  display:flex; align-items:center; justify-content:center; box-shadow:0 8px 16px rgba(224,71,95,.3);
}
#admission_enquiry .ae_steps b{display:block; color:var(--navy); font-weight:800; font-size:15px}
#admission_enquiry .ae_steps span{color:var(--muted); font-size:14px}

#admission_enquiry .ae_docs{border:2px dashed #f1b3bd; background:#fff7f8; border-radius:18px; padding:16px 18px; font-size:14px; color:var(--muted)}
#admission_enquiry .ae_docs b{display:block; color:var(--navy); margin-bottom:6px; font-weight:800}
#admission_enquiry .ae_docs ul{margin:0; padding-left:18px}

#admission_enquiry .ae_sub{font-family:'Fredoka',cursive !important; font-size:19px; color:var(--acc); margin:0 0 14px}
#admission_enquiry .ae_fields{display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; margin-bottom:22px}
#admission_enquiry .ae_f{min-width:0}
#admission_enquiry .ae_full{grid-column:1/-1}
#admission_enquiry .ae_lbl{display:block; font-size:13px; font-weight:700; color:var(--muted); margin:0 0 6px 6px}

#admission_enquiry input[type=text],#admission_enquiry input:not([type]),#admission_enquiry input[type=tel],
#admission_enquiry input[type=email],#admission_enquiry input[type=date],
#admission_enquiry select,#admission_enquiry textarea{
  display:block; width:100%; height:50px; margin:0; padding:0 20px; font-size:15px; color:var(--ink);
  background:var(--field); border:1.5px solid var(--line); border-radius:16px; box-shadow:none; outline:none;
}
#admission_enquiry textarea{height:auto; min-height:110px; padding:14px 20px; resize:vertical}
#admission_enquiry select{
  -webkit-appearance:none; appearance:none; padding-right:40px;
  background-image:linear-gradient(45deg,transparent 50%,var(--muted) 50%),linear-gradient(135deg,var(--muted) 50%,transparent 50%);
  background-position:calc(100% - 22px) 52%, calc(100% - 16px) 52%; background-size:6px 6px; background-repeat:no-repeat;
}
#admission_enquiry input:focus,#admission_enquiry select:focus,#admission_enquiry textarea:focus{
  border-color:var(--acc); box-shadow:0 0 0 3px rgba(224,71,95,.18); background-color:#fff;
}
#admission_enquiry ::placeholder{color:#8a97ad}
#admission_enquiry .ae_bad{border-color:#d93a3a !important; background-color:#fff5f5 !important}
#admission_enquiry .ae_ferr{display:block; min-height:0; margin:5px 0 0 6px; color:#d93a3a; font-size:13px; font-weight:700}
#admission_enquiry .ae_ferr:empty{display:none}

#admission_enquiry .ae_alert{border-radius:14px; padding:13px 16px; margin:0 0 20px; font-size:14.5px; font-weight:700; line-height:1.6}
#admission_enquiry .ae_alert_err{background:#fff1f2; color:#c42b3f; border:2px dashed #f1b3bd}

/* Green success panel */
#admission_enquiry .ae_success{
  text-align:center; background:#e9f9f0; border:2px dashed #3cb878;
  border-radius:20px; padding:40px 24px; color:#157a45;
  animation:ae_pop .5s cubic-bezier(.34,1.56,.64,1) both;
}
#admission_enquiry .ae_tick{
  width:72px; height:72px; margin:0 auto 14px; border-radius:50%;
  background:linear-gradient(135deg,#2fb380,#1a8a4a); color:#fff;
  font-size:38px; line-height:72px; box-shadow:0 10px 20px rgba(26,138,74,.35);
}
#admission_enquiry .ae_success h4{font-family:'Fredoka',cursive !important; color:#157a45; font-size:26px; margin:0 0 8px}
#admission_enquiry .ae_success p{color:#1f6b45; font-size:16px; line-height:1.7; margin:0}
@keyframes ae_pop{from{opacity:0; transform:scale(.92)}}

#admission_enquiry .ae_pills{display:flex; gap:10px; flex-wrap:wrap}
#admission_enquiry .ae_pills label{margin:0; cursor:pointer; font-weight:400}
#admission_enquiry .ae_pills input{position:absolute; opacity:0; width:1px; height:1px}
#admission_enquiry .ae_pills span{display:inline-block; padding:11px 24px; border:1.5px solid var(--line); border-radius:50px; background:var(--field); font-size:15px; color:var(--ink)}
#admission_enquiry .ae_pills input:checked + span{background:var(--acc); border-color:var(--acc); color:#fff}
#admission_enquiry .ae_pills input:focus-visible + span{box-shadow:0 0 0 3px rgba(224,71,95,.25)}

#admission_enquiry .ae_submit{
  border:0; cursor:pointer; color:#fff; font-weight:800; font-size:16px; letter-spacing:.3px;
  padding:14px 36px; border-radius:50px;
  background:linear-gradient(135deg,var(--acc),#b83249);
  box-shadow:0 6px 0 #8f2336;
  transition:transform .2s cubic-bezier(.34,1.56,.64,1), box-shadow .2s ease;
}
#admission_enquiry .ae_submit:hover{transform:translateY(-3px); box-shadow:0 9px 0 #8f2336}
#admission_enquiry .ae_submit:active{transform:translateY(2px); box-shadow:0 3px 0 #8f2336}
#admission_enquiry .ae_submit:focus-visible{outline:3px solid var(--gold); outline-offset:3px}
#admission_enquiry .ae_submit[disabled]{opacity:.7; cursor:wait; transform:none}
#admission_enquiry #ae_msg{margin-top:16px; min-height:22px; font-size:14.5px; font-weight:700}
#admission_enquiry .ae_err{color:#d93a3a}

@media (max-width:991px){
  #admission_enquiry .ae_grid{grid-template-columns:1fr}
}
@media (max-width:600px){
  #admission_enquiry .ae_head h2{font-size:28px}
  #admission_enquiry .ae_fields{grid-template-columns:1fr}
  #admission_enquiry .ae_card{padding:24px 18px}
}
</style>

<script>
(function(){
  /* After a successful submit, scroll the green panel into view */
  var s=document.getElementById('ae_success');
  if(s){ s.scrollIntoView({behavior:'smooth', block:'center'}); }

  /* Quick browser check. The server (CodeIgniter form_validation) is the real validation. */
  var form=document.getElementById('ae_form'), msg=document.getElementById('ae_msg'), btn=document.getElementById('ae_btn');
  if(!form) return;
  var phone=document.getElementById('ae_phone');
  phone.addEventListener('input',function(){ this.value=this.value.replace(/\D/g,'').slice(0,10); });

  form.addEventListener('submit',function(e){
    var list=[['ae_student','the student name'],['ae_dob','the date of birth'],['ae_cls','the class'],['ae_parent','the parent name'],['ae_phone','a valid 10-digit mobile number'],['ae_email','a valid email address']];
    var bad=form.querySelectorAll('.ae_bad'); for(var i=0;i<bad.length;i++) bad[i].classList.remove('ae_bad');
    msg.className='';
    for(var j=0;j<list.length;j++){
      var el=document.getElementById(list[j][0]), v=el.value.trim(), invalid=!v;
      if(list[j][0]==='ae_email' && !/^\S+@\S+\.\S+$/.test(v)) invalid=true;
      if(list[j][0]==='ae_phone' && !/^[6-9][0-9]{9}$/.test(v)) invalid=true;
      if(invalid){ e.preventDefault(); el.classList.add('ae_bad'); el.focus(); msg.className='ae_err'; msg.textContent='Please enter '+list[j][1]+'.'; return; }
    }
    if(!form.querySelector('input[name=gender]:checked')){ e.preventDefault(); msg.className='ae_err'; msg.textContent='Please choose a gender.'; return; }
    btn.disabled=true; btn.textContent='Submitting...';
  });
})();
</script>
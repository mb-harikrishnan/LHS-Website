const fs = require('fs');
const files = ['about.php','admissions.php','anual_day.php','band_page.php','clubs.php','co_curricular_activities.php','contact.php','curriculum.php','directors_message.php','discipline.php','downloads.php','fee_regulations.php','fun_n_learn.php','house_system.php','mandatory_disclosure.php','mission_vision.php','news.php','parental_support.php','principals_message.php','rules_and_regulations.php','scheme_of_studies.php','school_uniform.php','sports_and_games.php','study_tour.php','teachers.php','testimonials.php','transfer_certificates.php'];
const selector = '.admission_card::before,.curriculum_card::before,.fee_card::before,.rules_card::before,\r\n.study_card::before,.support_card::before,.club_box::before,.download_box::before,\r\n.fun_content_box::before,.news_box::before,.teacher::before,.note_card::before';
const selLF = selector.replace(/\r\n/g, '\n');
let removed = 0;
for (const f of files) {
  let s = fs.readFileSync(f, 'utf8');
  const before = s.length;
  // v5 reposition block (both EOLs)
  s = s.replace(new RegExp('\/\* washi tape stays INSIDE its card[^*]*\*\/\r?\n[\s\S]*?\r?\n\}\r?\n'), '');
  // v2 tape rule: selector followed by the striped-gradient declaration block
  for (const sel of [selector, selLF]) {
    const i = s.indexOf(sel);
    if (i > -1) {
      // find the closing brace of this rule
      const open = s.indexOf('{', i);
      const close = s.indexOf('}', open);
      if (open > -1 && close > -1 && s.slice(i, open).length < 400) {
        s = s.slice(0, i) + s.slice(close + 1).replace(/^(\r?\n)/, '');
        removed++;
        break;
      }
    }
  }
  // neutralize leftover comment text
  s = s.replace(/cards: white paper with washi-tape top/g, 'cards: clean white paper');
  if (s.length !== before) fs.writeFileSync(f, s);
}
console.log('tape rules removed in', removed, 'files');

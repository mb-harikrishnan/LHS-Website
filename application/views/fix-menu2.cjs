const fs = require('fs');
let css = fs.readFileSync('assets/css/school-theme.css', 'utf8');
const oldRule = `    .navbar .container { display: block; }\n\n    #navbar-menu { width: 100%; }\n`;
const newRule = `    .navbar .container { display: block; padding-left: 15px; padding-right: 15px; }\n\n    #navbar-menu {\n        width: 100% !important;\n        margin-left: 0 !important;\n        margin-right: 0 !important;\n    }\n`;
if (!css.includes(oldRule)) { console.log('STILL not found'); process.exit(1); }
css = css.replace(oldRule, newRule);
fs.writeFileSync('assets/css/school-theme.css', css);
console.log('applied (LF variant)');

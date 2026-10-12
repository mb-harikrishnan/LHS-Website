const fs = require('fs');
let css = fs.readFileSync('assets/css/school-theme.css', 'utf8');
const oldRule = `    .navbar .container { display: block; }

    #navbar-menu { width: 100%; }`;
const newRule = `    .navbar .container { display: block; padding-left: 15px; padding-right: 15px; }

    #navbar-menu {
        width: 100% !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }`;
if (!css.includes(oldRule)) { console.log('not found'); process.exit(1); }
css = css.replace(oldRule, newRule);
fs.writeFileSync('assets/css/school-theme.css', css);
console.log('menu full-width fix applied');

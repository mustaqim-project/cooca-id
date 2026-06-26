import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const jsDir = path.join(__dirname, 'resources', 'js');
const uiJsDir = path.join(jsDir, 'ui');

// Create forms.js
const formsJs = `
import Swal from 'sweetalert2';

export function initFormConfirmations() {
    // Submit / Update Confirmations
    const submitForms = document.querySelectorAll('.form-confirm-submit');
    submitForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to save these changes?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb', // primary-600
                cancelButtonColor: '#64748b', // surface-500
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Delete Confirmations
    const deleteForms = document.querySelectorAll('.form-confirm-delete');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626', // red-600
                cancelButtonColor: '#64748b', // surface-500
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
}
`;

fs.writeFileSync(path.join(uiJsDir, 'forms.js'), formsJs);

// Update app.js
const appJsPath = path.join(jsDir, 'app.js');
let appJsContent = fs.readFileSync(appJsPath, 'utf-8');

const appJsAdditions = `
import { initFormConfirmations } from './ui/forms.js';
`;

if (!appJsContent.includes('./ui/forms.js')) {
    appJsContent = appJsAdditions + appJsContent;
    appJsContent = appJsContent.replace('initAlerts();', 'initAlerts();\\n    initFormConfirmations();');
    fs.writeFileSync(appJsPath, appJsContent);
}

// Update the regenerate script
const regenScriptPath = path.join(__dirname, 'regenerate_views_structurally.mjs');
let regenScriptContent = fs.readFileSync(regenScriptPath, 'utf-8');

// Inject .form-confirm-submit into create/edit forms
regenScriptContent = regenScriptContent.replace(
    /<form action="\\$\\{action\\}" method="\\$\\{method\\}" \\$\\{enctype\\}>/g,
    '<form action="${action}" method="${method}" ${enctype} class="form-confirm-submit">'
);

// Inject .form-confirm-delete into delete forms and remove onsubmit in regen script template
regenScriptContent = regenScriptContent.replace(
    /<form action="([^"]+)" method="POST" class="inline(?:-block)?" onsubmit="return confirm\\([^)]+\\);?">/g,
    '<form action="$1" method="POST" class="inline-block form-confirm-delete">'
);

// Specifically handle the tbody part where onsubmit is present
let lines = regenScriptContent.split('\\n');
let newLines = [];
for (let i = 0; i < lines.length; i++) {
    let line = lines[i];
    if (line.includes('let tbody = tbodyMatch[1];')) {
        newLines.push(line);
        newLines.push("            // Clean up delete forms");
        newLines.push("            tbody = tbody.replace(/onsubmit=\\"return confirm\\\\([^)]+\\\\);?\\"/g, '');");
        newLines.push("            tbody = tbody.replace(/<form action=\\"([^\"]+)\\" method=\\"POST\\" class=\\"([^\"]*)\\"/g, function(match, p1, p2) {");
        newLines.push("                if(p2.includes('form-confirm-delete')) return match;");
        newLines.push("                return '<form action=\"' + p1 + '\" method=\"POST\" class=\"' + p2 + ' form-confirm-delete\"';");
        newLines.push("            });");
    } else {
        newLines.push(line);
    }
}
regenScriptContent = newLines.join('\\n');

fs.writeFileSync(regenScriptPath, regenScriptContent);

console.log("Successfully created SweetAlert forms handler, updated app.js, and updated regenerate script.");

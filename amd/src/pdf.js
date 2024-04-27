/**
 * @module     local_attendance
 * @copyright  Pragathiswaran Ramyasri Rukkesh
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

const button = document.getElementById('id_submitbutton');
        button.addEventListener('click', function() {
            generatePDF();
        });
        function generatePDF() {
            const doc = new jsPDF();

            // Convert HTML table to PDF
            doc.autoTable({ html: '#example' });

            // Save the PDF to a data URL
            const pdfData = doc.output('datauristring');

            // Send the PDF data to the server using AJAX
            savePDFOnServer(pdfData);
        }

        function savePDFOnServer(pdfData) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'manage.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log('PDF saved on server successfully!');
                    } else {
                        console.error('Failed to save PDF on server.');
                    }
                }
            };
            xhr.send('pdfData=' + encodeURIComponent(pdfData));
        }
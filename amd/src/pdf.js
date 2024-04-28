/**
 * @module     local_attendance
 * @copyright  Pragathiswaran Ramyasri Rukkesh
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

const button = document.getElementById('btn-Email');
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
            $.ajax({
                type: 'POST',
                url: 'http://localhost/moodle/local/attendance/manage.php',
                data: { pdfDataUri: pdfData },
                success: function(response) {
                    alert('pdf sent successfully!');
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    alert('Error sending pdf.');
                    console.error(error);
                }
            });
        }
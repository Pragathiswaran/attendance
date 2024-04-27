/**
 * @module     local_attendance
 * @copyright  Pragathiswaran Ramyasri Rukkesh
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function printTable() {
    var printWindow = window.open('', '', 'height=400,width=800');
   
    printWindow.document.write(document.getElementById('example').outerHTML);
   
    printWindow.document.close();
    printWindow.print();
}
/**
 * @module     local_attendance
 * @copyright  Pragathiswaran Ramyasri Rukkesh
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
jQuery(document).ready(function() {
    function handleButtonClick() {
        var coursename = $(this).text().trim(); 
        $.ajax({
            url: 'http://localhost/moodle/local/attendance/ajax.php',
            data: { coursename: coursename },
            type: 'POST',
            success: function(response) {
                $('#example').html(response);
                console.log(response);
            },
            error: function(response) {
                console.log('Error: ' + response);
            }
        });
    }
    $('.btn-value').click(handleButtonClick);

});


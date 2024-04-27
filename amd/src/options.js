/**
 * @module     local_attendance
 * @copyright  Pragathiswaran Ramyasri Rukkesh
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

jQuery(document).ready(function() {
    function handleButtonClick() {
        var options = $(this).text().trim(); 
        $.ajax({
            url: 'http://localhost/moodle/local/attendance/option.php',
            data: { options: options },
            type: 'POST',
            success: function(response) {
                $('#example').html(response);
                $('#title').html(options+' '+'Report');
                //console.log('Option selected: '+' '+ response);
            }
        });
    }
    $('.btn-options').click(handleButtonClick);

    $('.btn-options:first').trigger('click');
});
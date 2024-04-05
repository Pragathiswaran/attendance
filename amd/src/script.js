jQuery(document).ready(function() {
    function handleButtonClick() {
        var coursename = $(this).text().trim(); 
        $.ajax({
            url: 'http://localhost/moodle/local/attendance/ajax.php',
            data: { coursename: coursename },
            type: 'POST',
            success: function(response) {
                $('.testrender').html(response);
            },
            error: function(response) {
                console.log('Error: ' + response);
            }
        });
    }
    $('.btn-value').click(handleButtonClick);

    $('.btn-value:first').trigger('click');
});


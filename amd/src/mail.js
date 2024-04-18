// Use jQuery document ready function to ensure the DOM is fully loaded
jQuery(document).ready(function($) {
    // Define a function to handle button click
    function handleButtonClick() {
        // Extract HTML content of the table with ID 'example'
        var table = $('#example').html(); 
        
        // Make AJAX request to mail.php script
        $.ajax({
            url: M.cfg.wwwroot + '/local/attendance/sendmail.php', // Use M.cfg.wwwroot to get Moodle root URL
            data: { table: table }, // Pass the table content as data
            type: 'POST',
            success: function(response) {
                // Handle successful response
                console.log(response);
                console.log('Mail sent successfully');
                
                // Example: Update a specific element with the response
                $('.example-table').html(response);
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.log('Error: ' + error);
            }
        });
    }
    
    // Attach click event handler to the button with ID 'examplebutton'
    $('#examplebutton').click(handleButtonClick);
});

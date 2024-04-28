/**
 * @module     local_attendance
 * @copyright  Pragathiswaran Ramyasri Rukkesh
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
    // Custom JavaScript for submenu hover effect
  $(document).ready(function() {
    // Show submenu on clicking "Option 2"
    $('.dropdown-submenu').on('click', function(e) {
        e.stopPropagation(); // Prevent default bootstrap behavior
        $(this).find('.dropdown-menu').toggleClass('show');
    });

    // Hide submenu when clicking outside the dropdown
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown-submenu').length) {
            $('.dropdown-menu').removeClass('show');
        }
    });

    // Show submenu on hover
    $('.dropdown-submenu').hover(function() {
        $(this).find('.dropdown-menu').addClass('show');
    }, function() {
        $(this).find('.dropdown-menu').removeClass('show');
    });
});
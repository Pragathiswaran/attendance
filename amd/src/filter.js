/**
 * @module     local_attendance
 * @copyright  Pragathiswaran Ramyasri Rukkesh
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$(document).ready(function(){
  $('.incompleted').each(function(){
      $(this).popover({
          content: 'Failed',
          placement: 'right',
          trigger: 'hover'
      });
  });

  var delay = 700;
  var timeoutId;

  $(".search-text").on("input", function() {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(performSearch, delay);
  });

  function performSearch() {
    var searchTerm = $(".search-text").val().trim().toLowerCase();
    
    if(searchTerm.length === 0) {
        $("table tbody tr").show();
        $(".search-text").removeClass("no-match").attr("placeholder", "Type to Filter . . .");
        return;
    }
    
    var found = false;

    $("table tbody tr").each(function() {
      var rowData = $(this).text().toLowerCase();
      if (rowData.includes(searchTerm)) {
        $(this).show();
        found = true;
      } else {
        $(this).hide();
      }
    });

    if (!found) {
      $(".search-text").addClass("no-match").attr("placeholder", "No records found").val("");
      
      setTimeout(function() {
        $("table tbody tr").show();
        $(".search-text").removeClass("no-match").attr("placeholder", "Type to Filter . . .").val("");
      }, 3000); // 1 seconds
    } else {
      $(".search-text").removeClass("no-match").attr("placeholder", "Type to Filter . . .");
    }
  }
});
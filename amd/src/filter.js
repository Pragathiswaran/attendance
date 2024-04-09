$(document).ready(function(){
    $('.incompleted').each(function(){
        $(this).popover({
            content: 'Failed',
            placement: 'right',
            trigger: 'hover'
        });
    });
});
  $(document).ready(function(){
    var delay = 500; 
    var timeoutId;

    $(".search-text").on("input", function() {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(performSearch, delay);
    });

    function performSearch() {
      var searchTerm = $(".search-text").val().toLowerCase();
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
        $(".search-text").addClass("no-match").attr("placeholder", "No records found.").val("");
        $("table").focus(); 
      } else {
        $(".search-text").removeClass("no-match").attr("placeholder", "Type to Filter . . .");
      }
    }
  });

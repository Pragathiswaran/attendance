$(document).ready(function() {
    $('.btn-filepdf').on('click', function() {
        // Initialize jsPDF
        var doc = new jsPDF();

        // Get table content dynamically
        var table = $('#example');
        var headers = [];

        // Get headers from the first visible row in thead
        table.find('thead tr:first th').each(function() {
            headers.push($(this).text().trim());
        });

        // Prepare data array for filtered rows
        var filteredRows = [];
        table.find('tbody tr:visible').each(function() {
            var rowData = [];
            $(this).find('td').each(function() {
                rowData.push($(this).text().trim());
            });
            filteredRows.push(rowData);
        });

        // Add table content to PDF using jsPDF AutoTable plugin
        doc.autoTable({
            head: [headers],
            body: filteredRows
        });

        // Save PDF
        doc.save('filtered_table_data.pdf');
    });
});

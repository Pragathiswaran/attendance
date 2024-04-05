function copyTable() {
    var table = document.getElementById("example");
    var tableContent = "";

    // Loop through rows and cells to construct table content
    for (var i = 0; i < table.rows.length; i++) {
        for (var j = 0; j < table.rows[i].cells.length; j++) {
            tableContent += table.rows[i].cells[j].innerText + "\t"; // Separate cells with tab
        }
        tableContent += "\n"; // Newline after each row
    }

    // Copy the table content to clipboard
    navigator.clipboard.writeText(tableContent)
        .then(() => alert("Table copied to clipboard!"))
        .catch(err => console.error('Failed to copy: ', err));
}
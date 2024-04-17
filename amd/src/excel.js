function exportToExcel() {
    // Get the table element by its ID
    const table = document.getElementById("#example");
    if (!table) {
        console.error('Table element not found.');
        return;
    }

    // Convert the HTML table to a worksheet
    const ws = XLSX.utils.table_to_sheet(table);
    // Create a new workbook
    const wb = XLSX.utils.book_new();
    // Append the worksheet to the workbook
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
    // Write the workbook to a binary array
    const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

    // Trigger the download
    const blob = new Blob([s2ab(wbout)], { type: "application/octet-stream" });
    saveAs(blob, 'table_data.xlsx');
}

// Helper function to convert string to ArrayBuffer
function s2ab(s) {
    const buf = new ArrayBuffer(s.length);
    const view = new Uint8Array(buf);
    for (let i = 0; i < s.length; i++) {
        view[i] = s.charCodeAt(i) & 0xFF;
    }
    return buf;
}

function exportToExcel() {
    const table = document.getElementById("example");
    const ws = XLSX.utils.table_to_sheet(table);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
    const wbout = XLSX.write(wb, {bookType:'xlsx', type:'array'});

    saveAs(new Blob([wbout],{type:"application/octet-stream"}), 'table_data.xlsx');
}
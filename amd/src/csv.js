/**
 * @module     local_attendance
 * @copyright  Pragathiswaran Ramyasri Rukkesh
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function exportTocsv() {
    const table = document.getElementById("example");
    const visibleRows = Array.from(table.querySelectorAll("tbody tr")).filter(row => {
        // Check if the row is visible (not hidden by filters or other means)
        return window.getComputedStyle(row).display !== 'none';
    });

    if (visibleRows.length === 0) {
        console.error("No visible rows found in the table.");
        return;
    }

    const headers = Array.from(table.querySelectorAll("thead th"))
                         .map(th => th.textContent.trim());

    const rowsData = visibleRows.map(row => {
        return Array.from(row.querySelectorAll("td"))
                    .map(cell => cell.textContent.trim());
    });

    const csvRows = [headers, ...rowsData];
    const csvContent = csvRows.map(row => row.join(",")).join("\n");
    
    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const blobUrl = URL.createObjectURL(blob);

    const link = document.createElement("a");
    link.setAttribute("href", blobUrl);
    link.setAttribute("download", "filtered_table_data.csv");

    document.body.appendChild(link);
    link.click();

    document.body.removeChild(link);
    URL.revokeObjectURL(blobUrl);
}

function printTable() {
    var printWindow = window.open('', '', 'height=400,width=800');
   
    printWindow.document.write(document.getElementById('example').outerHTML);
   
    printWindow.document.close();
    printWindow.print();
}
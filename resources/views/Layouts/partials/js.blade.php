<script src="{{asset('assets/js/jquery-3.5.1.min.js')}}"></script>
<!-- feather icon js-->
<script src="{{asset('assets/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{asset('assets/js/icons/feather-icon/feather-icon.js')}}"></script>
<!-- Sidebar jquery-->
<script src="{{asset('assets/js/sidebar-menu.js')}}"></script>
<script src="{{asset('assets/js/config.js')}}"></script>
<!-- Bootstrap js-->
<script src="{{asset('assets/js/bootstrap/popper.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap/bootstrap.min.js')}}"></script>
<!-- Plugins JS start-->
@stack('scripts')
<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{asset('assets/js/script.js')}}"></script>

<script>
    $(document).ready(function() {
        let colorTheme = false;
        let defaultTheme = localStorage.getItem('dark');
        var dirValue = $('html').attr('dir');

        // Function to get the value of 'dir' attribute
        function getDirAttributeValue() {
            dirValue = $('html').attr('dir');
            // Perform actions based on the 'dir' value here
        }
        getDirAttributeValue();

        $("body").attr("class", defaultTheme);
        $(".mode").on("click", function() {
            colorTheme = !colorTheme;
            console.log(dirValue);
            if (dirValue == 'rtl') {
                if (colorTheme) {
                    $("body").attr("class", "rtl dark-only");
                    localStorage.setItem("dark", "dark-only");
                } else {
                    $("body").attr("class", "rtl");
                    localStorage.setItem("dark", "light");
                }
            } else {
                if (colorTheme) {
                    $("body").attr("class", "dark-only");
                    localStorage.setItem("dark", "dark-only");
                } else {
                    $("body").attr("class", "");
                    localStorage.setItem("dark", "light");
                }
            }
        });
        setInterval(getDirAttributeValue, 5000);
    });
</script>

<script>

 $(document).ready(function () {
    console.log('Initializing DataTables...');

    // Fungsi toggle date range
    window.toggleDateRange = function () {
        const dateRangeElement = document.getElementById('date-range');
        if (dateRangeElement) {
            if (dateRangeElement.classList.contains('d-none')) {
                dateRangeElement.classList.remove('d-none');
            } else {
                dateRangeElement.classList.add('d-none');
            }
        } else {
            console.error('Element with ID "date-range" not found.');
        }
    };

    document.addEventListener('click', function (event) {
        const dateRangeElement = document.getElementById('date-range');
        const toggleButton = document.querySelector('.btn-secondary');

        if (
            dateRangeElement &&
            !dateRangeElement.classList.contains('d-none') &&
            !dateRangeElement.contains(event.target) &&
            !toggleButton.contains(event.target)
        ) {
            dateRangeElement.classList.add('d-none');
        }
    });


    function initializeDataTable(tableId) {
        if ($(tableId).length && !$.fn.DataTable.isDataTable(tableId)) {
            const columnsToExport = $(tableId).data('columns-export');
            console.log(`Initializing DataTable for ${tableId}`);
            return $(tableId).DataTable({
                info: true,
                ordering: true,
                paging: true,
                pageLength: 10,
                lengthMenu: [

                    [10, 25, 100, 250, 500, 1000, -1],
                    ['10', '25', '100', '250', '500', '1000', 'All']
                ],

                dom: '<"top"lBf>rt<"bottom"ip><"clear">',
                buttons: [
                    {
                        extend: 'csv',
                        text: 'Export CSV',
                        className: 'btn btn-success btn-sm active ms-4',
                        exportOptions: {
                            columns: columnsToExport,
                            modifier: {
                                search: 'applied', // Hanya data yang difilter
                                order: 'applied',  // Mempertahankan urutan yang diterapkan
                                page: 'current'        // Ekspor semua halaman
                            }
                        }
                    }
                ],
                columnDefs: [
                    { orderable: false, targets: 3 }
                ],
                initComplete: function () {
                    console.log(`Setting up search inputs for ${tableId}`);
                    $(`${tableId} tfoot th`).each(function () {
                        const title = $(this).text();
                        if ($(this).length) {
                            $(this).html(`<input type="text" placeholder="Search ${title}" />`);
                        }
                    });

                    this.api().columns().every(function () {
                        const that = this;
                        $('input', this.footer()).on('keyup change', function () {
                            if (that.search() !== this.value) {
                                that.search(this.value).draw();
                            }
                        });
                    });
                }
            });
        }
        return null;
    }

    if (document.getElementById('norm-1')) {
        const tableNorm1 = initializeDataTable('#norm-1');
    }

    if (document.getElementById('norm-2')) {
        const tableNorm2 = initializeDataTable('#norm-2');
        console.log("Data Row:", data);

        

        if (tableNorm2) {
            console.log('DataTable "example" initialized successfully.');

            // Filter tanggal custom menggunakan DataTables
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                const startDateInput = document.getElementById('start-date')?.value || null;
                const endDateInput = document.getElementById('end-date')?.value || null;
                console.log("Row Date:", rowStartDate, "Start Date Input:", startDateInput, "End Date Input:", endDateInput);

                if (!startDateInput && !endDateInput) {
                    return true;
                }

                const row = tableNorm2.row(dataIndex).node();
                const rowStartDate = row?.getAttribute('data-start-date')
                    ? new Date(row.getAttribute('data-start-date'))
                    : new Date(data[7]); 

                if (isNaN(rowStartDate.getTime())) {
                    return false; 
                }

                const startDate = startDateInput ? new Date(startDateInput) : null;
                const endDate = endDateInput ? new Date(endDateInput) : null;

                return (
                    (!startDate || rowStartDate >= startDate) &&
                    (!endDate || rowStartDate <= endDate)
                );
            });

            window.applyBetweenFilter = function () {
                console.log('Applying date filter...');
                tableNorm2.draw();
            };

            // Tambahkan event listener pada tombol eksternal untuk eksport
            $('#export-excel').on('click', function () {
                if (tableNorm2) {
                    tableNorm2.button('.buttons-excel').trigger();
                } else {
                    console.error('Table is not initialized for export.');
                }
            });
        }
    }
});

</script>
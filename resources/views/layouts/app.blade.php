<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="user-role" content="{{ Auth::user()->roleUser }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/Lambang_Kabupaten_Brebes.png') }}" type="image/x-icon" />
    <title>{{ $title ?? '' }} | Sistem Monitoring Kios Adminduk Desa</title>

    <!-- ========== All CSS files linkup ========= -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lineicons.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/materialdesignicons.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- ========== All CSS files linkup ========= -->
    <link rel="stylesheet" href="assets/css/quill/bubble.css" />
    <link rel="stylesheet" href="assets/css/quill/snow.css" />
    <link rel="stylesheet" href="assets/css/morris.css" />
    <link rel="stylesheet" href="assets/css/datatable.css" />
</head>

<body>

    <!-- ======== Preloader =========== -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <!-- ======== Preloader =========== -->

    <!-- ======== sidebar-nav start =========== -->
    @include('layouts.sidebar')
    <div class="overlay"></div>
    <!-- ======== sidebar-nav end =========== -->

    <!-- ======== main-wrapper start =========== -->
    <main class="main-wrapper">
        <!-- ========== header start ========== -->
        @include('layouts.header')
        <!-- ========== header end ========== -->

        <!-- ========== section start ========== -->
        @yield('content')
        <!-- ========== section end ========== -->

        <!-- ========== footer start =========== -->
        @include('layouts.footer')
        <!-- ========== footer end =========== -->
    </main>
    <!-- ======== main-wrapper end =========== -->

    <!-- ========= All Javascript files linkup ======== -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/dynamic-pie-chart.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/fullcalendar.js') }}"></script>
    <script src="{{ asset('assets/js/jvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/world-merc.js') }}"></script>
    <script src="{{ asset('assets/js/polyfill.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <!-- ========= All Javascript files linkup ======== -->
    <script src="{{ asset('assets/js/quill.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable.js') }}"></script>
    <script src="{{ asset('assets/js/Sortable.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script>
        const dataTable = new simpleDatatables.DataTable("#table", {
            searchable: true,
        });
    </script>
    <script>
        function exportToExcel(jenis) {
            const table = document.getElementById("table");

            // Ambil data filter dari form
            const form = document.getElementById("filterForm");
            const startDate = form.elements["startDate"].value || "Semua";
            const endDate = form.elements["endDate"].value || "Semua";

            const layananSelect = form.elements["layanan"];
            const statusSelect = form.elements["status"];

            let layananText = layananSelect.value;
            if (
                layananSelect.selectedIndex === 0 || // -- Pilih Layanan --
                layananText === undefined || layananText.trim() === ""
            ) {
                layananText = "Semua";
            } else {
                layananText = layananSelect.options[layananSelect.selectedIndex].text;
            }

            let statusText = statusSelect.value;
            if (
                statusSelect.selectedIndex === 0 || // -- Pilih Status --
                statusText === undefined || statusText.trim() === ""
            ) {
                statusText = "Semua";
            } else {
                statusText = statusSelect.options[statusSelect.selectedIndex].text;
            }

            // Ambil isi tabel dan buang 3 kolom terakhir
            const rows = Array.from(table.querySelectorAll("tr")).map(row => {
                const cells = Array.from(row.querySelectorAll("th, td"));
                return cells.slice(0, -3).map(cell => cell.innerText);
            });

            // Tentukan judul
            let judul = "DATA PENGAJUAN";
            if (jenis === "dafduk") {
                judul = "DATA PENGAJUAN DAFDUK";
            } else if (jenis === "capil") {
                judul = "DATA PENGAJUAN CAPIL";
            } else {
                judul = `DATA PENGAJUAN ${jenis.toUpperCase()}`;
            }

            // Buat baris awal dengan info filter di kolom A dan B
            const infoBaris1 = [judul]; // Baris 0
            const infoBaris2 = ["Tanggal Awal :", "", startDate, "", "Layanan :", layananText]; // Baris 1
            const infoBaris3 = ["Tanggal Akhir :", "", endDate, "", "Status :", statusText]; // Baris 2
            const infoBaris4 = []; // Baris kosong

            // Gabungkan semua baris
            const allRows = [infoBaris1, infoBaris2, infoBaris3, infoBaris4, ...rows];

            // Buat worksheet
            const ws = XLSX.utils.aoa_to_sheet(allRows);

            // Merge judul (baris 0 kolom A sampai kolom terakhir)
            const totalColumns = rows[0]?.length || 1;
            ws['!merges'] = [{
                    s: {
                        r: 0,
                        c: 0
                    },
                    e: {
                        r: 0,
                        c: Math.max(rows[0]?.length || 5, 5) - 1
                    }
                }, // Merge A1:F1 (judul)
                {
                    s: {
                        r: 1,
                        c: 0
                    },
                    e: {
                        r: 1,
                        c: 1
                    }
                }, // Merge A2:B2 (label Tanggal Awal)
                {
                    s: {
                        r: 2,
                        c: 0
                    },
                    e: {
                        r: 2,
                        c: 1
                    }
                } // Merge A3:B3 (label Tanggal Akhir)
            ];

            // Buat workbook dan sheet
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Data");

            // Nama file dinamis
            const now = new Date();
            const tanggal = now.toISOString().slice(0, 10);
            const jam = now.toTimeString().slice(0, 8).replace(/:/g, "-");
            const fileName = `data-export-${jenis}-${tanggal}_${jam}.xlsx`;

            XLSX.writeFile(wb, fileName);
        }
    </script>
    <script>
        async function exportToPDF(jenis) {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF({
                orientation: 'landscape',
                unit: 'pt',
                format: 'a4',
            });

            const table = document.getElementById("table");

            // Ambil data filter
            const form = document.getElementById("filterForm");
            const startDate = form.elements["startDate"].value || "Semua";
            const endDate = form.elements["endDate"].value || "Semua";

            const layananSelect = form.elements["layanan"];
            const statusSelect = form.elements["status"];

            let layananText = layananSelect.value;
            if (layananSelect.selectedIndex === 0 || layananText.trim() === "") {
                layananText = "Semua";
            } else {
                layananText = layananSelect.options[layananSelect.selectedIndex].text;
            }

            let statusText = statusSelect.value;
            if (statusSelect.selectedIndex === 0 || statusText.trim() === "") {
                statusText = "Semua";
            } else {
                statusText = statusSelect.options[statusSelect.selectedIndex].text;
            }

            // Tentukan judul berdasarkan jenis
            let judul = "DATA PENGAJUAN";
            if (jenis === "dafduk") {
                judul = "DATA PENGAJUAN DAFDUK";
            } else if (jenis === "capil") {
                judul = "DATA PENGAJUAN CAPIL";
            } else {
                judul = `DATA PENGAJUAN ${jenis.toUpperCase()}`;
            }

            // Tambahkan judul dan informasi filter ke PDF
            doc.setFontSize(14);
            doc.text(judul, 40, 40);

            doc.setFontSize(10);
            doc.text(`Tanggal Awal : ${startDate}`, 40, 60);
            doc.text(`Tanggal Akhir : ${endDate}`, 40, 75);
            doc.text(`Layanan : ${layananText}`, 300, 60);
            doc.text(`Status : ${statusText}`, 300, 75);

            // Ambil header dan isi tabel
            const headers = Array.from(table.querySelectorAll("thead th"))
                .slice(0, -3)
                .map(th => th.innerText);

            const body = Array.from(table.querySelectorAll("tbody tr")).map(row =>
                Array.from(row.querySelectorAll("td"))
                .slice(0, -3)
                .map(td => td.innerText)
            );

            // Tambahkan tabel
            doc.autoTable({
                head: [headers],
                body: body,
                startY: 95,
                theme: 'grid',
                styles: {
                    fontSize: 10
                },
                headStyles: {
                    fillColor: [22, 160, 133]
                }
            });

            // Nama file dinamis
            const now = new Date();
            const tanggal = now.toISOString().slice(0, 10);
            const jam = now.toTimeString().slice(0, 8).replace(/:/g, "-");
            const fileName = `data-export-${jenis}-${tanggal}_${jam}.pdf`;

            doc.save(fileName);
        }
    </script>

    <script>
        // ======== jvectormap activation
        var markers = [{
                name: "Egypt",
                coords: [26.8206, 30.8025]
            },
            {
                name: "Russia",
                coords: [61.524, 105.3188]
            },
            {
                name: "Canada",
                coords: [56.1304, -106.3468]
            },
            {
                name: "Greenland",
                coords: [71.7069, -42.6043]
            },
            {
                name: "Brazil",
                coords: [-14.235, -51.9253]
            },
        ];

        var jvm = new jsVectorMap({
            map: "world_merc",
            selector: "#map",
            zoomButtons: true,

            regionStyle: {
                initial: {
                    fill: "#d1d5db",
                },
            },

            labels: {
                markers: {
                    render: (marker) => marker.name,
                },
            },

            markersSelectable: true,
            selectedMarkers: markers.map((marker, index) => {
                var name = marker.name;

                if (name === "Russia" || name === "Brazil") {
                    return index;
                }
            }),
            markers: markers,
            markerStyle: {
                initial: {
                    fill: "#4A6CF7"
                },
                selected: {
                    fill: "#ff5050"
                },
            },
            markerLabelStyle: {
                initial: {
                    fontWeight: 400,
                    fontSize: 14,
                },
            },
        });
        // ====== calendar activation
        document.addEventListener("DOMContentLoaded", function() {
            var calendarMiniEl = document.getElementById("calendar-mini");
            var calendarMini = new FullCalendar.Calendar(calendarMiniEl, {
                initialView: "dayGridMonth",
                headerToolbar: {
                    end: "today prev,next",
                },
            });
            calendarMini.render();
        });

        // =========== chart one start
        const ctx1 = document.getElementById("Chart1").getContext("2d");
        const chart1 = new Chart(ctx1, {
            type: "line",
            data: {
                labels: [
                    "Jan",
                    "Fab",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                ],
                datasets: [{
                    label: "",
                    backgroundColor: "transparent",
                    borderColor: "#365CF5",
                    data: [
                        600, 800, 750, 880, 940, 880, 900, 770, 920, 890, 976, 1100,
                    ],
                    pointBackgroundColor: "transparent",
                    pointHoverBackgroundColor: "#365CF5",
                    pointBorderColor: "transparent",
                    pointHoverBorderColor: "#fff",
                    pointHoverBorderWidth: 5,
                    borderWidth: 5,
                    pointRadius: 8,
                    pointHoverRadius: 8,
                    cubicInterpolationMode: "monotone", // Add this line for curved line
                }, ],
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            labelColor: function(context) {
                                return {
                                    backgroundColor: "#ffffff",
                                    color: "#171717",
                                };
                            },
                        },
                        intersect: false,
                        backgroundColor: "#f9f9f9",
                        title: {
                            fontFamily: "Plus Jakarta Sans",
                            color: "#8F92A1",
                            fontSize: 12,
                        },
                        body: {
                            fontFamily: "Plus Jakarta Sans",
                            color: "#171717",
                            fontStyle: "bold",
                            fontSize: 16,
                        },
                        multiKeyBackground: "transparent",
                        displayColors: false,
                        padding: {
                            x: 30,
                            y: 10,
                        },
                        bodyAlign: "center",
                        titleAlign: "center",
                        titleColor: "#8F92A1",
                        bodyColor: "#171717",
                        bodyFont: {
                            family: "Plus Jakarta Sans",
                            size: "16",
                            weight: "bold",
                        },
                    },
                    legend: {
                        display: false,
                    },
                },
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: false,
                },
                scales: {
                    y: {
                        grid: {
                            display: false,
                            drawTicks: false,
                            drawBorder: false,
                        },
                        ticks: {
                            padding: 35,
                            max: 1200,
                            min: 500,
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            color: "rgba(143, 146, 161, .1)",
                            zeroLineColor: "rgba(143, 146, 161, .1)",
                        },
                        ticks: {
                            padding: 20,
                        },
                    },
                },
            },
        });
        // =========== chart one end

        // =========== chart two start
        const ctx2 = document.getElementById("Chart2").getContext("2d");
        const chart2 = new Chart(ctx2, {
            type: "bar",
            data: {
                labels: [
                    "Jan",
                    "Fab",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                ],
                datasets: [{
                    label: "",
                    backgroundColor: "#365CF5",
                    borderRadius: 30,
                    barThickness: 6,
                    maxBarThickness: 8,
                    data: [
                        600, 700, 1000, 700, 650, 800, 690, 740, 720, 1120, 876, 900,
                    ],
                }, ],
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            titleColor: function(context) {
                                return "#8F92A1";
                            },
                            label: function(context) {
                                let label = context.dataset.label || "";

                                if (label) {
                                    label += ": ";
                                }
                                label += context.parsed.y;
                                return label;
                            },
                        },
                        backgroundColor: "#F3F6F8",
                        titleAlign: "center",
                        bodyAlign: "center",
                        titleFont: {
                            size: 12,
                            weight: "bold",
                            color: "#8F92A1",
                        },
                        bodyFont: {
                            size: 16,
                            weight: "bold",
                            color: "#171717",
                        },
                        displayColors: false,
                        padding: {
                            x: 30,
                            y: 10,
                        },
                    },
                },
                legend: {
                    display: false,
                },
                legend: {
                    display: false,
                },
                layout: {
                    padding: {
                        top: 15,
                        right: 15,
                        bottom: 15,
                        left: 15,
                    },
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        grid: {
                            display: false,
                            drawTicks: false,
                            drawBorder: false,
                        },
                        ticks: {
                            padding: 35,
                            max: 1200,
                            min: 0,
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                            color: "rgba(143, 146, 161, .1)",
                            drawTicks: false,
                            zeroLineColor: "rgba(143, 146, 161, .1)",
                        },
                        ticks: {
                            padding: 20,
                        },
                    },
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    title: {
                        display: false,
                    },
                },
            },
        });
        // =========== chart two end

        // =========== chart three start
        const ctx3 = document.getElementById("Chart3").getContext("2d");
        const chart3 = new Chart(ctx3, {
            type: "line",
            data: {
                labels: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                ],
                datasets: [{
                        label: "Revenue",
                        backgroundColor: "transparent",
                        borderColor: "#365CF5",
                        data: [80, 120, 110, 100, 130, 150, 115, 145, 140, 130, 160, 210],
                        pointBackgroundColor: "transparent",
                        pointHoverBackgroundColor: "#365CF5",
                        pointBorderColor: "transparent",
                        pointHoverBorderColor: "#365CF5",
                        pointHoverBorderWidth: 3,
                        pointBorderWidth: 5,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        fill: false,
                        tension: 0.4,
                    },
                    {
                        label: "Profit",
                        backgroundColor: "transparent",
                        borderColor: "#9b51e0",
                        data: [
                            120, 160, 150, 140, 165, 210, 135, 155, 170, 140, 130, 200,
                        ],
                        pointBackgroundColor: "transparent",
                        pointHoverBackgroundColor: "#9b51e0",
                        pointBorderColor: "transparent",
                        pointHoverBorderColor: "#9b51e0",
                        pointHoverBorderWidth: 3,
                        pointBorderWidth: 5,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        fill: false,
                        tension: 0.4,
                    },
                    {
                        label: "Order",
                        backgroundColor: "transparent",
                        borderColor: "#f2994a",
                        data: [180, 110, 140, 135, 100, 90, 145, 115, 100, 110, 115, 150],
                        pointBackgroundColor: "transparent",
                        pointHoverBackgroundColor: "#f2994a",
                        pointBorderColor: "transparent",
                        pointHoverBorderColor: "#f2994a",
                        pointHoverBorderWidth: 3,
                        pointBorderWidth: 5,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        fill: false,
                        tension: 0.4,
                    },
                ],
            },
            options: {
                plugins: {
                    tooltip: {
                        intersect: false,
                        backgroundColor: "#fbfbfb",
                        titleColor: "#8F92A1",
                        bodyColor: "#272727",
                        titleFont: {
                            size: 16,
                            family: "Plus Jakarta Sans",
                            weight: "400",
                        },
                        bodyFont: {
                            family: "Plus Jakarta Sans",
                            size: 16,
                        },
                        multiKeyBackground: "transparent",
                        displayColors: false,
                        padding: {
                            x: 30,
                            y: 15,
                        },
                        borderColor: "rgba(143, 146, 161, .1)",
                        borderWidth: 1,
                        enabled: true,
                    },
                    title: {
                        display: false,
                    },
                    legend: {
                        display: false,
                    },
                },
                layout: {
                    padding: {
                        top: 0,
                    },
                },
                responsive: true,
                // maintainAspectRatio: false,
                legend: {
                    display: false,
                },
                scales: {
                    y: {
                        grid: {
                            display: false,
                            drawTicks: false,
                            drawBorder: false,
                        },
                        ticks: {
                            padding: 35,
                        },
                        max: 350,
                        min: 50,
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            color: "rgba(143, 146, 161, .1)",
                            drawTicks: false,
                            zeroLineColor: "rgba(143, 146, 161, .1)",
                        },
                        ticks: {
                            padding: 20,
                        },
                    },
                },
            },
        });
        // =========== chart three end

        // ================== chart four start
        const ctx4 = document.getElementById("Chart4").getContext("2d");
        const chart4 = new Chart(ctx4, {
            type: "bar",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                datasets: [{
                        label: "",
                        backgroundColor: "#365CF5",
                        borderColor: "transparent",
                        borderRadius: 20,
                        borderWidth: 5,
                        barThickness: 20,
                        maxBarThickness: 20,
                        data: [600, 700, 1000, 700, 650, 800],
                    },
                    {
                        label: "",
                        backgroundColor: "#d50100",
                        borderColor: "transparent",
                        borderRadius: 20,
                        borderWidth: 5,
                        barThickness: 20,
                        maxBarThickness: 20,
                        data: [690, 740, 720, 1120, 876, 900],
                    },
                ],
            },
            options: {
                plugins: {
                    tooltip: {
                        backgroundColor: "#F3F6F8",
                        titleColor: "#8F92A1",
                        titleFontSize: 12,
                        bodyColor: "#171717",
                        bodyFont: {
                            weight: "bold",
                            size: 16,
                        },
                        multiKeyBackground: "transparent",
                        displayColors: false,
                        padding: {
                            x: 30,
                            y: 10,
                        },
                        bodyAlign: "center",
                        titleAlign: "center",
                        enabled: true,
                    },
                    legend: {
                        display: false,
                    },
                },
                layout: {
                    padding: {
                        top: 0,
                    },
                },
                responsive: true,
                // maintainAspectRatio: false,
                title: {
                    display: false,
                },
                scales: {
                    y: {
                        grid: {
                            display: false,
                            drawTicks: false,
                            drawBorder: false,
                        },
                        ticks: {
                            padding: 35,
                            max: 1200,
                            min: 0,
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                            color: "rgba(143, 146, 161, .1)",
                            zeroLineColor: "rgba(143, 146, 161, .1)",
                        },
                        ticks: {
                            padding: 20,
                        },
                    },
                },
            },
        });
        // =========== chart four end
    </script>
    @yield(section: 'scripts')
</body>

</html>

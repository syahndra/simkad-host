@extends('layouts.app', ['title' => 'Dashboard', 'menu' => 'dashboard'])
@section('content')
    <section class="section">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>
                                Dashboard {{ $role }}
                            </h2>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="#0">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ $role }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- ========== title-wrapper end ========== -->
            <div class="row">
                <!-- dalam antrian -->
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="icon-card mb-30">
                        <div class="icon purple">
                            <i class="lni lni-spinner"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb-10">dalam antrian</h6>
                            <h3 class="text-bold mb-10">{{ $statusCounts['dalam antrian'] }}</h3>
                            <p class="text-sm text-info">
                                <i class="lni lni-hourglass"></i> Menunggu verifikasi
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sudah Diproses -->
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="icon-card mb-30">
                        <div class="icon primary">
                            <i class="lni lni-checkmark-circle"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb-10">Sudah Diproses</h6>
                            <h3 class="text-bold mb-10">{{ $statusCounts['sudah diproses'] }}</h3>
                            <p class="text-sm text-success">
                                <i class="lni lni-arrow-up"></i> Proses Dinas
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="icon-card mb-30">
                        <div class="icon danger">
                            <i class="lni lni-close"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb-10">Ditolak</h6>
                            <h3 class="text-bold mb-10">{{ $statusCounts['ditolak'] }}</h3>
                            <p class="text-sm text-danger">
                                <i class="lni lni-warning"></i> Data tidak valid
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Selesai -->
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="icon-card mb-30">
                        <div class="icon success">
                            <i class="lni lni-checkmark"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb-10">Selesai</h6>
                            <h3 class="text-bold mb-10">{{ $statusCounts['selesai'] }}</h3>
                            <p class="text-sm text-success">
                                <i class="lni lni-thumbs-up"></i> Dokumen Siap
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-style mb-30">
                        <div class="title d-flex flex-wrap justify-content-between">
                            <div class="left">
                                <h6 class="text-medium mb-30">Grafik Pengajuan Selesai</h6>
                            </div>
                            <div class="right">
                                <div class="select-style-1">
                                    <div class="select-position select-sm">
                                        <select class="light-bg" id="chartFilter">
                                            <option value="yearly" selected>Tahunan</option>
                                            <option value="monthly">Bulanan</option>
                                            <option value="weekly">Mingguan</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- end select -->
                            </div>
                        </div>
                        <!-- End Title -->
                        <div class="chart">
                            <canvas id="ChartAjuan" style="width: 100%; height: 400px; margin-left: -35px"></canvas>
                        </div>
                        <!-- End Chart -->
                    </div>
                </div>
                <!-- End Col -->
            </div>
            <!-- End Row -->
        </div>
        <!-- end container -->
    </section>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chartAjuan = null;

        async function loadChartAjuan(filter = 'yearly') {
            const res = await fetch(`/dashboard/chart-data?filter=${filter}`);
            const chartData = await res.json();

            const ctx1 = document.getElementById("ChartAjuan").getContext("2d");

            const data = {
                labels: chartData.labels,
                datasets: [{
                    label: "Ajuan Selesai",
                    backgroundColor: "transparent",
                    borderColor: "#365CF5",
                    data: chartData.data,
                    pointBackgroundColor: "transparent",
                    pointHoverBackgroundColor: "#365CF5",
                    pointBorderColor: "transparent",
                    pointHoverBorderColor: "#fff",
                    pointHoverBorderWidth: 5,
                    borderWidth: 5,
                    pointRadius: 8,
                    pointHoverRadius: 8,
                    cubicInterpolationMode: "monotone",
                }]
            };

            const options = {
                plugins: {
                    tooltip: {
                        callbacks: {
                            labelColor: () => ({
                                backgroundColor: "#ffffff",
                                color: "#171717",
                            }),
                        },
                        intersect: false,
                        backgroundColor: "#f9f9f9",
                        titleColor: "#8F92A1",
                        bodyColor: "#171717",
                        bodyFont: {
                            family: "Plus Jakarta Sans",
                            size: 16,
                            weight: "bold",
                        },
                        displayColors: false,
                        padding: {
                            x: 30,
                            y: 10
                        },
                        bodyAlign: "center",
                        titleAlign: "center",
                    },
                    legend: {
                        display: false,
                    },
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        grid: {
                            display: false,
                            drawTicks: false,
                            drawBorder: false
                        },
                        ticks: {
                            padding: 35,
                            beginAtZero: true
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            color: "rgba(143, 146, 161, .1)",
                            zeroLineColor: "rgba(143, 146, 161, .1)",
                        },
                        ticks: {
                            padding: 20
                        },
                    },
                },
            };

            if (chartAjuan) {
                chartAjuan.data = data;
                chartAjuan.update();
            } else {
                chartAjuan = new Chart(ctx1, {
                    type: "line",
                    data: data,
                    options: options
                });
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Load awal
            loadChartAjuan();

            // Event saat dropdown berubah
            const filterSelect = document.getElementById('chartFilter');
            if (filterSelect) {
                filterSelect.addEventListener('change', function() {
                    const selectedFilter = this.value || 'yearly';
                    loadChartAjuan(selectedFilter);
                });
            }
        });
    </script>
@endsection

@extends('layouts.management')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500&display=swap');

        .dash-root {
            font-family: 'DM Sans', sans-serif;
            padding: 2rem 1.5rem;
            max-width: 960px;
            margin: 0 auto;
        }

        .dash-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.4rem;
            letter-spacing: 0.08em;
            color: #fff;
            margin: 0 0 0.2rem;
        }

        .dash-sub {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.45);
            margin: 0 0 2rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: #111;
            border-radius: 12px;
            padding: 1rem 1.1rem;
            border: 0.5px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .stat-card:hover {
            border-color: rgba(255, 255, 255, 0.22);
        }

        .stat-card .accent-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 12px 12px 0 0;
        }

        .stat-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            font-size: 14px;
        }

        .stat-label {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.45);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 0.35rem;
            font-weight: 500;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 500;
            color: #fff;
            line-height: 1;
        }

        .stat-value.small {
            font-size: 15px;
            margin-top: 4px;
            line-height: 1.3;
        }

        .stat-unit {
            font-size: 11px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.4);
        }

        .chart-section {
            background: #111;
            border-radius: 12px;
            border: 0.5px solid rgba(255, 255, 255, 0.1);
            padding: 1.25rem 1.25rem 1rem;
        }

        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .chart-title {
            font-size: 12px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .chart-badge {
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            background: rgba(55, 138, 221, 0.18);
            color: #85B7EB;
            font-weight: 500;
        }



        .chart-legend {
            display: flex;
            gap: 16px;
            margin-bottom: 12px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.45);
        }

        .legend-dot {
            width: 9px;
            height: 9px;
            border-radius: 2px;
        }

        .summary-row {
            display: flex;
            gap: 24px;
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 0.5px solid rgba(255, 255, 255, 0.08);
            flex-wrap: wrap;
        }

        .summary-stat {
            text-align: center;
        }

        .summary-stat .lbl {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-stat .val {
            font-size: 17px;
            font-weight: 500;
            color: #fff;
        }

        .divider-v {
            width: 0.5px;
            background: rgba(255, 255, 255, 0.1);
            align-self: stretch;
        }
    </style>

    <div class="dash-root">

        {{-- Header --}}
        <p class="dash-title">Dashboard</p>
        <p class="dash-sub">Quản lý rạp chiếu phim &middot; {{ $month_year }}</p>

        {{-- Stat Cards --}}
        <div class="cards-grid">

            {{-- Tổng phim --}}
            <div class="stat-card">
                <div class="accent-bar" style="background:#E24B4A"></div>
                <div class="stat-icon" style="background:rgba(226,75,74,0.15)">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                        <rect x="1" y="3" width="14" height="10" rx="2" stroke="#E24B4A"
                            stroke-width="1.5" />
                        <path d="M1 6h14M5 3v3M11 3v3" stroke="#E24B4A" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="stat-label">Tổng phim</div>
                <div class="stat-value">{{ $total_movies }}</div>
            </div>

            {{-- Đang chiếu --}}
            <div class="stat-card">
                <div class="accent-bar" style="background:#1D9E75"></div>
                <div class="stat-icon" style="background:rgba(29,158,117,0.15)">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                        <circle cx="8" cy="8" r="6" stroke="#1D9E75" stroke-width="1.5" />
                        <path d="M6 8l1.5 1.5L11 6" stroke="#1D9E75" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="stat-label">Đang chiếu</div>
                <div class="stat-value">{{ $movie_showing }}</div>
            </div>

            {{-- Sắp chiếu --}}
            <div class="stat-card">
                <div class="accent-bar" style="background:#378ADD"></div>
                <div class="stat-icon" style="background:rgba(55,138,221,0.15)">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                        <path d="M8 2v4l3 1.5" stroke="#378ADD" stroke-width="1.5" stroke-linecap="round" />
                        <circle cx="8" cy="8" r="6" stroke="#378ADD" stroke-width="1.5" />
                    </svg>
                </div>
                <div class="stat-label">Sắp chiếu</div>
                <div class="stat-value">{{ $movie_upcomming }}</div>
            </div>

            {{-- Đã kết thúc --}}
            <div class="stat-card">
                <div class="accent-bar" style="background:#888780"></div>
                <div class="stat-icon" style="background:rgba(136,135,128,0.15)">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                        <path d="M2 13l4-4 3 3 5-6" stroke="#888780" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="stat-label">Đã kết thúc</div>
                <div class="stat-value">{{ $movie_ended }}</div>
            </div>

            {{-- Người dùng --}}
            <div class="stat-card">
                <div class="accent-bar" style="background:#D4537E"></div>
                <div class="stat-icon" style="background:rgba(212,83,126,0.15)">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                        <circle cx="8" cy="5" r="3" stroke="#D4537E" stroke-width="1.5" />
                        <path d="M3 14c0-3 2-4 5-4s5 1 5 4" stroke="#D4537E" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="stat-label">Người dùng</div>
                <div class="stat-value">{{ number_format($total_user) }}</div>
            </div>

            {{-- Vé đã bán --}}
            <div class="stat-card">
                <div class="accent-bar" style="background:#EF9F27"></div>
                <div class="stat-icon" style="background:rgba(239,159,39,0.15)">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                        <rect x="2" y="4" width="12" height="8" rx="2" stroke="#EF9F27"
                            stroke-width="1.5" />
                        <path d="M5 4V3m6 1V3M5 10h2m2 0h2" stroke="#EF9F27" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="stat-label">Vé đã bán</div>
                <div class="stat-value">{{ number_format($tickets_sold) }}</div>
            </div>

            {{-- Doanh thu tháng --}}
            <div class="stat-card">
                <div class="accent-bar" style="background:#639922"></div>
                <div class="stat-icon" style="background:rgba(99,153,34,0.15)">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                        <path d="M8 2c3.3 0 6 2.7 6 6s-2.7 6-6 6-6-2.7-6-6 2.7-6 6-6z" stroke="#639922"
                            stroke-width="1.5" />
                        <path d="M8 5v3l2 1" stroke="#639922" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="stat-label">Doanh thu tháng</div>
                <div class="stat-value small">
                    {{ number_format($month_income) }}
                    <span class="stat-unit">VND</span>
                </div>
            </div>

            {{-- Tổng doanh thu --}}
            <div class="stat-card">
                <div class="accent-bar" style="background:#7F77DD"></div>
                <div class="stat-icon" style="background:rgba(127,119,221,0.15)">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                        <path d="M2 12V4l6 4 6-4v8" stroke="#7F77DD" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="stat-label">Tổng doanh thu</div>
                <div class="stat-value small">
                    {{ number_format($total_income) }}
                    <span class="stat-unit">VND</span>
                </div>
            </div>

        </div>

        {{-- Chart --}}
        <div class="chart-section">
            <div class="chart-header">
                <span class="chart-title">Doanh thu theo tháng</span>
                <span class="chart-badge">{{ date('Y') }}</span>
            </div>
            <div class="chart-legend">
                <div class="legend-item">
                    <div style="display:flex;gap:3px;margin-right:2px">
                        <div style="width:9px;height:9px;border-radius:2px;background:#378ADD"></div>
                        <div style="width:9px;height:9px;border-radius:2px;background:#1D9E75"></div>
                        <div style="width:9px;height:9px;border-radius:2px;background:#EF9F27"></div>
                        <div style="width:9px;height:9px;border-radius:2px;background:#E24B4A"></div>
                    </div>
                    Doanh thu (VND)
                </div>
            </div>
            <div style="position:relative; width:100%; height:260px;">
                <canvas id="revenueChart" role="img" aria-label="Biểu đồ doanh thu theo tháng trong năm">
                    Biểu đồ doanh thu hàng tháng.
                </canvas>
            </div>

            @php
                // Chuẩn hoá thành mảng PHP
                $dataArray = is_array($data) ? $data : json_decode($data, true) ?? [];
                $labelsArray = is_array($labels) ? $labels : json_decode($labels, true) ?? [];

                // Lọc chỉ lấy các tháng có dữ liệu thực (loại null / '' / 0)
                $numericData = [];
                $numericIndexes = [];
                foreach ($dataArray as $idx => $val) {
                    if ($val !== null && $val !== '' && (float) $val > 0) {
                        $numericData[] = (float) $val;
                        $numericIndexes[] = $idx;
                    }
                }

                if (!empty($numericData)) {
                    $maxIncome = max($numericData);
                    $minIncome = min($numericData);
                    $avgIncome = array_sum($numericData) / count($numericData);
                    $totalYTD = array_sum($numericData);

                    // Tìm index trong mảng gốc
                    $maxIndex = array_search($maxIncome, array_map('floatval', $dataArray));
                    $minIndex = array_search($minIncome, array_map('floatval', $dataArray));

                    $maxLabel = $labelsArray[$maxIndex] ?? '—';
                    $minLabel = $labelsArray[$minIndex] ?? '—';
                } else {
                    $maxIncome = $minIncome = $avgIncome = $totalYTD = 0;
                    $maxLabel = $minLabel = '—';
                }

                // Hàm format: luôn hiển thị số nguyên đầy đủ, dấu chấm ngăn cách hàng nghìn
                $fmtVND = function (float $v): string {
                    return number_format($v, 0, ',', '.') . ' ₫';
                };
            @endphp

            <div class="summary-row">
                <div class="summary-stat">
                    <div class="lbl">Cao nhất</div>
                    <div class="val" id="summaryMax" style="color:#1D9E75">
                        {{ $maxLabel }} · {{ $fmtVND($maxIncome) }}
                    </div>
                </div>
                <div class="divider-v"></div>
                <div class="summary-stat">
                    <div class="lbl">Thấp nhất</div>
                    <div class="val" id="summaryMin" style="color:#E24B4A">
                        {{ $minLabel }} · {{ $fmtVND($minIncome) }}
                    </div>
                </div>
                <div class="divider-v"></div>
                <div class="summary-stat">
                    <div class="lbl">Trung bình / tháng</div>
                    <div class="val" id="summaryAvg">{{ $fmtVND($avgIncome) }}</div>
                </div>
                <div class="divider-v"></div>
                <div class="summary-stat">
                    <div class="lbl">Tổng YTD</div>
                    <div class="val" id="summaryTotal">{{ $fmtVND($totalYTD) }}</div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function() {
            const palette = [{
                    bg: '#378ADD',
                    border: '#185FA5'
                },
                {
                    bg: '#7F77DD',
                    border: '#534AB7'
                },
                {
                    bg: '#1D9E75',
                    border: '#0F6E56'
                },
                {
                    bg: '#EF9F27',
                    border: '#BA7517'
                },
                {
                    bg: '#D4537E',
                    border: '#993556'
                },
                {
                    bg: '#5DCAA5',
                    border: '#1D9E75'
                },
                {
                    bg: '#E24B4A',
                    border: '#A32D2D'
                },
                {
                    bg: '#85B7EB',
                    border: '#378ADD'
                },
                {
                    bg: '#639922',
                    border: '#3B6D11'
                },
                {
                    bg: '#F09595',
                    border: '#E24B4A'
                },
                {
                    bg: '#AFA9EC',
                    border: '#7F77DD'
                },
                {
                    bg: '#FAC775',
                    border: '#EF9F27'
                },
            ];

            function buildColors(rawData) {
                return {
                    bg: rawData.map((v, i) => v === null ? 'rgba(255,255,255,0.06)' : palette[i % palette.length].bg),
                    border: rawData.map((v, i) => v === null ? 'transparent' : palette[i % palette.length].border),
                };
            }


            // Khởi tạo chart
            const initialLabels = @json($labels);
            const initialData = @json($data);
            const colors = buildColors(initialData);

            new Chart(document.getElementById('revenueChart'), {
                type: 'bar',
                data: {
                    labels: initialLabels,
                    datasets: [{
                        label: 'Doanh thu (VND)',
                        data: initialData,
                        backgroundColor: colors.bg,
                        borderColor: colors.border,
                        borderWidth: 1.5,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 500,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1a1a1a',
                            titleColor: 'rgba(255,255,255,0.7)',
                            bodyColor: 'rgba(255,255,255,0.5)',
                            borderColor: 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            padding: 10,
                            callbacks: {
                                label: ctx => ctx.raw !== null ?
                                    new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' VND' :
                                    'Chưa có dữ liệu'
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: 'rgba(255,255,255,0.4)',
                                font: {
                                    size: 12
                                },
                                autoSkip: false
                            },
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'rgba(255,255,255,0.4)',
                                font: {
                                    size: 11
                                },
                                callback: v => v >= 1000000 ?
                                    (v / 1000000).toFixed(0) + 'M' :
                                    new Intl.NumberFormat('vi-VN').format(v)
                            },
                            grid: {
                                color: 'rgba(255,255,255,0.06)'
                            },
                            border: {
                                display: false
                            }
                        }
                    }
                }
            });

        })();
    </script>
@endsection

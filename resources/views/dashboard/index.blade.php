@extends('dashboard.layout')

@section('title', __('messages.dashboard.title'))
@section('page_title', __('messages.dashboard.page_title'))

@section('content')
@hasrole('admin')

<div class="h-screen overflow-hidden flex flex-col p-6" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- ===== Stats ===== --}}
    <div class="flex-none">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="bg-white rounded-2xl p-5 shadow-sm ring-1 ring-indigo-50">
                <p class="text-sm text-gray-500 {{ app()->getLocale() == 'ar' ? 'text-start' : 'text-end' }}">
                    {{ __('messages.dashboard.total_cars') }}
                </p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalCars ?? 0 }}</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm ring-1 ring-indigo-50">
                <p class="text-sm text-gray-500 {{ app()->getLocale() == 'ar' ? 'text-start' : 'text-end' }}">
                    {{ __('messages.dashboard.total_bookings') }}
                </p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalBookings ?? 0 }}</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm ring-1 ring-yellow-100">
                <p class="text-sm text-yellow-600 {{ app()->getLocale() == 'ar' ? 'text-start' : 'text-end' }}">
                    {{ __('messages.dashboard.pending_last_6m') }}
                </p>
                <p id="pendingCount" class="text-3xl font-bold text-yellow-700 mt-2">—</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm ring-1 ring-green-100">
                <p class="text-sm text-green-600 {{ app()->getLocale() == 'ar' ? 'text-start' : 'text-end' }}">
                    {{ __('messages.dashboard.new_lessors_last_6m') }}
                </p>
                <p id="lessorsCount" class="text-3xl font-bold text-green-700 mt-2">—</p>
            </div>

        </div>
    </div>

    {{-- ===== Charts area ===== --}}
    <div class="flex-1 mt-6 flex flex-col min-h-0">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 min-h-0">

            {{-- Booking Status --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm ring-1 ring-indigo-50 flex flex-col min-h-0">
                <div class="flex items-start {{ app()->getLocale() == 'ar' ? 'justify-start' : 'justify-end' }} mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-black">
                            {{ __('messages.dashboard.booking_status_over_time') }}
                        </h3>
                        <p class="text-sm text-gray-500 {{ app()->getLocale() == 'ar' ? 'text-start' : 'text-end' }}">
                            {{ __('messages.dashboard.last_6_months') }}
                        </p>
                    </div>
                </div>

                <div class="flex-1 min-h-0 h-72">
                    <canvas id="statusChart" class="w-full h-full"></canvas>
                </div>
            </div>

            {{-- Cars per Month --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm ring-1 ring-indigo-50 flex flex-col min-h-0">
                <div class="flex items-start {{ app()->getLocale() == 'ar' ? 'justify-start' : 'justify-end' }} mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-black">
                            {{ __('messages.dashboard.cars_per_month') }}
                        </h3>
                        <p class="text-sm text-gray-500 {{ app()->getLocale() == 'ar' ? 'text-start' : 'text-end' }}">
                            {{ __('messages.dashboard.last_6_months') }}
                        </p>
                    </div>
                </div>

                <div class="flex-1 min-h-0 h-72">
                    <canvas id="propertiesChart" class="w-full h-full"></canvas>
                </div>
            </div>

            {{-- Lessors Growth --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm ring-1 ring-indigo-50 flex flex-col min-h-0">
                <div class="flex items-start {{ app()->getLocale() == 'ar' ? 'justify-start' : 'justify-end' }} mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-black">
                            {{ __('messages.dashboard.lessors_growth') }}
                        </h3>
                        <p class="text-sm text-gray-500 {{ app()->getLocale() == 'ar' ? 'text-start' : 'text-end' }}">
                            {{ __('messages.dashboard.last_6_months') }}
                        </p>
                    </div>
                </div>

                <div class="flex-1 min-h-0 h-72">
                    <canvas id="lessorsChart" class="w-full h-full"></canvas>
                </div>
            </div>

        </div>

        {{-- Decorative strip --}}
        <div class="mt-4 flex-none">
            <div class="bg-gradient-to-r from-indigo-100 via-white to-indigo-50 rounded-2xl p-3 shadow-inner ring-1 ring-indigo-50">
                <div class="max-w-6xl p-4 mx-auto flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm min-w-[180px]">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-black font-semibold">
                            C
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('messages.dashboard.total_cars') }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $totalCars ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm min-w-[180px]">
                        <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 font-semibold">
                            B
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('messages.dashboard.total_bookings') }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $totalBookings ?? 0 }}</p>
                        </div>
                    </div>

                    <div id="miniPendingCard" class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm min-w-[180px]">
                        <div class="w-10 h-10 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600 font-semibold">
                            P
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('messages.dashboard.pending_6m') }}</p>
                            <p id="miniPending" class="text-sm font-semibold text-gray-800">—</p>
                        </div>
                    </div>

                    <div id="miniLessorsCard" class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm min-w-[180px]">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 font-semibold">
                            L
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('messages.dashboard.new_lessors_last_6m') }}</p>
                            <p id="miniLessors" class="text-sm font-semibold text-gray-800">—</p>
                        </div>
                    </div>

                    <div class="flex-1"></div>
                    <div class="text-xs text-gray-500">
                        {{ __('messages.dashboard.dashboard_preview') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    window.DASHBOARD = {
        status: @json($statusStats ?? []),
        propertiesPerMonth: @json($carsPerMonth ?? []),
        lessorsPerMonth: @json($lessorsPerMonth ?? []),
        translations: {
            pending: "{{ __('messages.reports.pending') }}",
            approved: "{{ __('messages.reports.approved') }}",
            rejected: "{{ __('messages.reports.rejected') }}",
            properties: "{{ __('messages.sidebar.cars') }}",
            lessors: "{{ __('messages.dashboard.new_lessors') }}"
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        const statusRows = (window.DASHBOARD.status || []).map(r => ({
            month: r.month ?? r.month_key ?? '',
            pending: Number(r.pending ?? 0),
            approved: Number(r.approved ?? 0),
            rejected: Number(r.rejected ?? 0)
        }));

        const propertyRows = (window.DASHBOARD.propertiesPerMonth || []).map(r => ({
            month: r.month ?? r.month_key ?? '',
            total: Number(r.total ?? 0)
        }));

        const lessorRows = (window.DASHBOARD.lessorsPerMonth || []).map(r => ({
            month: r.month ?? r.month_key ?? '',
            total: Number(r.total ?? 0)
        }));

        const pendingSum = statusRows.reduce((s, r) => s + r.pending, 0);
        const lessorSum = lessorRows.reduce((s, r) => s + r.total, 0);

        const pendingCountEl = document.getElementById('pendingCount');
        const approvedCountEl = document.getElementById('approvedCount');
        const miniPendingEl = document.getElementById('miniPending');
        const lessorsCountEl = document.getElementById('lessorsCount');
        const miniLessorsEl = document.getElementById('miniLessors');

        if (pendingCountEl) pendingCountEl.textContent = pendingSum;
        if (miniPendingEl) miniPendingEl.textContent = pendingSum;
        if (lessorsCountEl) lessorsCountEl.textContent = lessorSum;
        if (miniLessorsEl) miniLessorsEl.textContent = lessorSum;

        const labels = statusRows.map(r => r.month);

        // Booking Status Chart
        const statusCanvas = document.getElementById('statusChart');
        if (statusCanvas) {
            const statusCtx = statusCanvas.getContext('2d');
            new Chart(statusCtx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                            label: window.DASHBOARD.translations.pending,
                            data: statusRows.map(r => r.pending),
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245,158,11,0.10)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 3
                        },
                        {
                            label: window.DASHBOARD.translations.approved,
                            data: statusRows.map(r => r.approved),
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22,163,74,0.10)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 3
                        },
                        {
                            label: window.DASHBOARD.translations.rejected,
                            data: statusRows.map(r => r.rejected),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239,68,68,0.10)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.06)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0,0,0,0.04)'
                            }
                        }
                    }
                }
            });
        }

        // Cars per Month Chart
        const propCanvas = document.getElementById('propertiesChart');
        if (propCanvas) {
            const propCtx = propCanvas.getContext('2d');
            new Chart(propCtx, {
                type: 'line',
                data: {
                    labels: propertyRows.map(r => r.month),
                    datasets: [{
                        label: window.DASHBOARD.translations.properties,
                        data: propertyRows.map(r => r.total),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.10)',
                        tension: 0.36,
                        fill: true,
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.06)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0,0,0,0.04)'
                            }
                        }
                    }
                }
            });
        }

        // Lessors Growth Chart
        const lessorsCanvas = document.getElementById('lessorsChart');
        if (lessorsCanvas) {
            const lessorsCtx = lessorsCanvas.getContext('2d');
            new Chart(lessorsCtx, {
                type: 'line',
                data: {
                    labels: lessorRows.map(r => r.month),
                    datasets: [{
                        label: window.DASHBOARD.translations.lessors,
                        data: lessorRows.map(r => r.total),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.10)',
                        tension: 0.36,
                        fill: true,
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.06)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0,0,0,0.04)'
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endhasrole
@endsection

@extends('layouts.user')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <!-- Left Column - Greeting Card -->
        <div class="col-lg-8">
            <!-- Greeting Card -->
            <div class="card" style="background-color: #002B5B; border-radius: 15px; overflow: hidden;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div class="text-white">
                            <div class="mb-3">
                                <span class="bg-white bg-opacity-25 px-3 py-1 rounded-pill" style="font-size: 14px;">
                                    <i class="bi bi-calendar me-2"></i>
                                    <span id="currentDateTime"></span>
                                </span>
                            </div>
                            <h3 class="mb-2">Good Day, {{ Auth::user()->nama }} !</h3>
                            <p class="text-white-50">Have a nice <span id="currentDayName"></span></p>
                        </div>
                        <div>
                            <img src="{{ asset('img/orang.png') }}" alt="Workers" height="120">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Calendar -->
        <div class="col-lg-4">
            <div class="card" style="background-color: #002B5B; border-radius: 15px;">
                <div class="card-body p-3">
                    <div class="text-center text-white">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="#" class="text-white" id="prevMonth"><i class="bi bi-chevron-left"></i></a>
                            <h6 class="mb-0" id="currentMonthYear">Januari 2025</h6>
                            <a href="#" class="text-white" id="nextMonth"><i class="bi bi-chevron-right"></i></a>
                        </div>
                        <div class="calendar-wrapper">
                            <div class="row g-1 text-center small mb-2">
                                <div class="col px-0">S</div>
                                <div class="col px-0">M</div>
                                <div class="col px-0">T</div>
                                <div class="col px-0">W</div>
                                <div class="col px-0">T</div>
                                <div class="col px-0">F</div>
                                <div class="col px-0">S</div>
                            </div>
                            <div id="calendarGrid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #E8F1FF;">
                            <i class="bi bi-clock-history text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Dosis TLD</div>
                            <div class="fw-bold">8,25 mSv</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #E8F1FF;">
                            <i class="bi bi-radioactive text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Dosis Hari ini</div>
                            <div class="fw-bold">0,5 mSv</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #E8F1FF;">
                            <i class="bi bi-heart-pulse text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Status Kesehatan Terbaru</div>
                            <div class="fw-bold">Baik</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Card -->
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius: 15px; background-color: #002B5B;">
                <div class="card-body p-4">
                    <h5 class="text-white mb-4">Grafik Tren Dosis</h5>
                    <div style="height: 300px; position: relative;">
                        <canvas id="doseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.calendar-wrapper {
    font-size: 12px;
}

.calendar-day {
    width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.2s;
    color: rgba(255, 255, 255, 0.7);
    font-size: 12px;
    margin: 1px;
}

.calendar-day:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.calendar-day.current {
    background-color: #3498db;
    color: white;
}

.calendar-day.other-month {
    color: rgba(255, 255, 255, 0.3);
}
</style>

@push('scripts')
<!-- Add Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Current date tracking
    let currentDate = new Date();
    let displayedMonth = currentDate.getMonth();
    let displayedYear = currentDate.getFullYear();

    // Update date and time
    function updateDateTime() {
        const now = new Date();
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        const month = months[now.getMonth()];
        const date = now.getDate();
        const year = now.getFullYear();
        let hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;

        document.getElementById('currentDateTime').textContent = 
            `${month} ${date} ${year}, ${hours}:${minutes} ${ampm}`;
        document.getElementById('currentDayName').textContent = days[now.getDay()];
    }

    // Update calendar
    function updateCalendar() {
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        document.getElementById('currentMonthYear').textContent = `${months[displayedMonth]} ${displayedYear}`;

        const firstDay = new Date(displayedYear, displayedMonth, 1).getDay();
        const daysInMonth = new Date(displayedYear, displayedMonth + 1, 0).getDate();
        const lastDayPrevMonth = new Date(displayedYear, displayedMonth, 0).getDate();
        
        let html = '';
        let dayCounter = 1;
        let totalCells = 0;
        
        // Start first row
        html += '<div class="row g-1 mb-1">';
        
        // Previous month days
        for (let i = 0; i < firstDay; i++) {
            const prevMonthDay = lastDayPrevMonth - firstDay + i + 1;
            html += `<div class="col px-0"><div class="calendar-day other-month">${prevMonthDay}</div></div>`;
            totalCells++;
        }

        // Current month days
        while (dayCounter <= daysInMonth) {
            if (totalCells % 7 === 0) {
                html += '</div><div class="row g-1 mb-1">';
            }
            
            const isToday = dayCounter === currentDate.getDate() && 
                           displayedMonth === currentDate.getMonth() && 
                           displayedYear === currentDate.getFullYear();
            
            html += `
                <div class="col px-0">
                    <div class="calendar-day${isToday ? ' current' : ''}">${dayCounter}</div>
                </div>`;
            
            dayCounter++;
            totalCells++;
        }

        // Next month days
        let nextMonthDay = 1;
        while (totalCells % 7 !== 0) {
            html += `<div class="col px-0"><div class="calendar-day other-month">${nextMonthDay}</div></div>`;
            nextMonthDay++;
            totalCells++;
        }
        html += '</div>';

        document.getElementById('calendarGrid').innerHTML = html;
    }

    // Event listeners for calendar navigation
    document.getElementById('prevMonth').addEventListener('click', function(e) {
        e.preventDefault();
        displayedMonth--;
        if (displayedMonth < 0) {
            displayedMonth = 11;
            displayedYear--;
        }
        updateCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', function(e) {
        e.preventDefault();
        displayedMonth++;
        if (displayedMonth > 11) {
            displayedMonth = 0;
            displayedYear++;
        }
        updateCalendar();
    });

    // Initialize displays
    updateDateTime();
    updateCalendar();

    // Update time every second
    setInterval(updateDateTime, 1000);

    // Initialize Dose Trend Chart
    const ctx = document.getElementById('doseChart').getContext('2d');
    
    // Sample data - replace with actual data from your backend
    const doseData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Dosis (mSv)',
            data: [0.5, 1.2, 0.8, 1.5, 0.9, 1.1],
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#3498db',
            pointBorderColor: '#fff',
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    };

    const doseChart = new Chart(ctx, {
        type: 'line',
        data: doseData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)',
                        callback: function(value) {
                            return value + ' mSv';
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#002B5B',
                    bodyColor: '#002B5B',
                    callbacks: {
                        label: function(context) {
                            return `Dosis: ${context.parsed.y} mSv`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
@extends('layouts.super_admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <!-- Greeting Card -->
        <div class="col-lg-8 mb-4">
            <div class="card bg-dark-blue text-white" style="background-color: #0d2c54; border-radius: 10px; overflow: hidden;">
                <div class="row g-0">
                    <div class="col-md-8">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-secondary bg-opacity-25 rounded-pill py-2 px-3 d-flex align-items-center">
                                    <i class="bi bi-calendar-date me-2"></i>
                                    <span id="currentDateTime"></span>
                                </div>
                            </div>
                            <div>
                                <h2 class="fw-bold">Good Day, {{ Auth::user()->nama}} !</h2>
                                <p class="text-light opacity-75">Have nice <span id="currentDay"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 p-0" style="height: 100%;">
                        <div style="height: 100%; overflow: hidden;">
                            <img src="{{ asset('img/orang.png') }}" alt="Workers" class="img-fluid h-100 w-100" style="object-fit: cover; object-position: center;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light p-2 rounded-circle me-3" style="background-color: #e9f0ff;">
                                <i class="bi bi-people-fill text-primary"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Total Pekerja Radiasi</div>
                                <div class="fw-bold">1,260</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light p-2 rounded-circle me-3" style="background-color: #e9f0ff;">
                                <i class="bi bi-clipboard-data text-primary"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Data Dosis Bulan Ini</div>
                                <div class="fw-bold">10</div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="d-flex align-items-center">
                            <div class="bg-light p-2 rounded-circle me-3" style="background-color: #e9f0ff;">
                                <i class="bi bi-bell text-primary"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Data Kesehatan Terbaru</div>
                                <div class="fw-bold">54</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Chart Card -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Grafik Tren Dosis</h5>
                    <div style="height: 300px;">
                        <canvas id="doseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Card -->
        <div class="col-lg-4 mb-4">
            <div class="card text-white" style="background-color: #0d2c54; border-radius: 10px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <a href="#" class="text-white opacity-50" id="prevMonth"><i class="bi bi-chevron-left"></i></a>
                        <h6 class="fw-bold mb-0" id="currentMonthYear"></h6>
                        <a href="#" class="text-white opacity-50" id="nextMonth"><i class="bi bi-chevron-right"></i></a>
                    </div>
                    
                    <div class="row text-center mb-2 small">
                        <div class="col">SUN</div>
                        <div class="col">MON</div>
                        <div class="col">TUE</div>
                        <div class="col">WED</div>
                        <div class="col">THU</div>
                        <div class="col">FRI</div>
                        <div class="col">SAT</div>
                    </div>
                    
                    <div id="calendarGrid" class="calendar-grid">
                        <!-- Calendar grid will be generated dynamically by JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Notifications Card -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Notifikasi Terbaru</h5>
                    <div class="notification-item bg-light p-3 rounded d-flex align-items-center mb-2" style="background-color: #f6f9ff;">
                        <div class="bg-primary text-white p-2 rounded me-3">
                            <i class="bi bi-bell"></i>
                        </div>
                        <div class="small">Data pemeriksaan dosis TLD telah diinput</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Statistik Kunjungan</h5>
                    <div class="text-center position-relative" style="height: 150px;">
                        <div class="donut-chart">
                            <canvas id="visitChart" width="200" height="200"></canvas>
                            <div class="donut-label position-absolute top-50 start-50 translate-middle text-center">
                                <h3 class="mb-0 text-primary">100%</h3>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap CSS dan Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.bg-dark-blue {
    background-color: #0d2c54;
}

.calendar-grid .col {
    padding: 4px 2px;
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
}

.calendar-day:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.calendar-day.current {
    background-color: #3498db;
}

.calendar-day.other-month {
    opacity: 0.3;
}

.donut-chart {
    position: relative;
    width: 160px;
    height: 160px;
    margin: 0 auto;
}

/* Tambahan style untuk konsistensi dengan landing page */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.notification-item {
    transition: all 0.3s ease;
}

.notification-item:hover {
    transform: translateX(5px);
    background-color: #e9f0ff !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update clock and date
    function updateDateTime() {
        const now = new Date();
        
        // Format date: Month DD YYYY, HH:MM AM/PM
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const month = months[now.getMonth()];
        const date = now.getDate();
        const year = now.getFullYear();
        
        let hours = now.getHours();
        const ampm = hours >= 12 ? 'Pm' : 'Am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        const minutes = now.getMinutes().toString().padStart(2, '0');
        
        const dateTimeStr = `${month} ${date} ${year}, ${hours}:${minutes} ${ampm}`;
        document.getElementById('currentDateTime').textContent = dateTimeStr;
        
        // Update day
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const day = days[now.getDay()];
        document.getElementById('currentDay').textContent = day;
    }
    
    // Run immediately and then update every second
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    // Calendar functionality
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    
    function updateCalendar() {
        // Update month and year display
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        document.getElementById('currentMonthYear').textContent = `${months[currentMonth]} ${currentYear}`;
        
        // Get the first day of the month (0-6, where 0 is Sunday)
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        
        // Get the number of days in the month
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        
        // Get the number of days in the previous month
        const daysInPrevMonth = new Date(currentYear, currentMonth, 0).getDate();
        
        // Generate the calendar grid
        let calendarHtml = '';
        
        let dayCounter = 1;
        let nextMonthCounter = 1;
        
        // Calculate the number of rows needed
        const numRows = Math.ceil((firstDay + daysInMonth) / 7);
        
        // Create the calendar rows
        for (let row = 0; row < numRows; row++) {
            calendarHtml += '<div class="row text-center small mb-2">';
            
            // Create the cells for each day
            for (let col = 0; col < 7; col++) {
                // Determine if this cell is for the current month, previous month, or next month
                if (row === 0 && col < firstDay) {
                    // Previous month
                    const prevDay = daysInPrevMonth - (firstDay - col - 1);
                    calendarHtml += `<div class="col"><span class="calendar-day other-month">${prevDay}</span></div>`;
                } else if (dayCounter > daysInMonth) {
                    // Next month
                    calendarHtml += `<div class="col"><span class="calendar-day other-month">${nextMonthCounter}</span></div>`;
                    nextMonthCounter++;
                } else {
                    // Current month
                    const today = new Date();
                    const isToday = dayCounter === today.getDate() && 
                                    currentMonth === today.getMonth() && 
                                    currentYear === today.getFullYear();
                    
                    if (isToday) {
                        calendarHtml += `<div class="col"><span class="calendar-day current">${dayCounter}</span></div>`;
                    } else {
                        calendarHtml += `<div class="col"><span class="calendar-day">${dayCounter}</span></div>`;
                    }
                    dayCounter++;
                }
            }
            
            calendarHtml += '</div>';
        }
        
        document.getElementById('calendarGrid').innerHTML = calendarHtml;
    }
    
    // Event listeners for month navigation
    document.getElementById('prevMonth').addEventListener('click', function(e) {
        e.preventDefault();
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        updateCalendar();
    });
    
    document.getElementById('nextMonth').addEventListener('click', function(e) {
        e.preventDefault();
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        updateCalendar();
    });
    
    // Initialize calendar
    updateCalendar();
    
    // Chart untuk Grafik Tren Dosis
    const doseCtx = document.getElementById('doseChart').getContext('2d');
    const doseChart = new Chart(doseCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Dosis 1',
                data: [180, 240, 200, 280, 300, 270, 240, 230, 150, 190, 170, 240],
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true
            }, {
                label: 'Dosis 2',
                data: [400, 220, 180, 250, 320, 400, 380, 320, 300, 350, 220, 380],
                borderColor: '#b2bec3',
                backgroundColor: 'rgba(178, 190, 195, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Chart untuk Statistik Kunjungan
    const visitCtx = document.getElementById('visitChart').getContext('2d');
    const visitChart = new Chart(visitCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed'],
            datasets: [{
                data: [100],
                backgroundColor: ['#3498db'],
                borderWidth: 0,
                cutout: '80%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endsection
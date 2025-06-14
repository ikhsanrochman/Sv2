@extends('layouts.user')

@section('content')
<div class="container-fluid py-4" style="background:#f6f8fa; min-height:100vh;">
    <div class="row g-4 mb-4">
        <!-- Greeting Card -->
        <div class="col-lg-8">
            <div class="card shadow-sm" style="border-radius:18px;">
                <div class="card-body d-flex align-items-center" style="min-height:180px;">
                    <div class="me-4">
                        <img src="{{ asset('img/orang.png') }}" alt="Profile" style="width:110px; height:110px; object-fit:cover; border-radius:50%; border:4px solid #f6f8fa; background:#fff;">
                    </div>
                    <div class="flex-grow-1">
                        <div class="mb-2">
                            <span class="text-muted" style="font-size:1rem;"><i class="bi bi-calendar me-2"></i><span id="currentDateTime"></span></span>
                        </div>
                        <h2 class="fw-bold mb-1" style="font-size:2.3rem;">Good Afternoon,<br>{{ Auth::user()->nama }} !</h2>
                        <div class="text-muted" style="font-size:1.1rem;">Have nice <span id="currentDayName"></span></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dosis Saat Ini -->
        <div class="col-lg-4">
            <div class="card shadow-sm h-100" style="border-radius:18px;">
                <div class="card-body d-flex flex-column justify-content-between h-100">
                    <div class="mb-2">
                        <div class="fw-semibold text-muted mb-1">Dosis Saat Ini</div>
                        <div class="text-muted" style="font-size:0.95rem;">
                            Total dosis akumulasi:
                        </div>
                        <div class="bg-light rounded-3 text-center my-3 py-3" style="font-size:2.2rem; font-weight:600; color:#222;">
                            {{ number_format($totalDosis, 1) }} <span style="font-size:1.3rem;">μSv</span>
                        </div>
                    </div>
                    <button class="btn w-100 mt-auto" style="background:#ffb84d; color:#222; font-weight:600; border-radius:10px;">Masih dalam batas aman</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4">
        <!-- Dosis TLD -->
        <div class="col-lg-8">
            <div class="card shadow-sm" style="border-radius:18px;">
                <div class="card-body">
                    <div class="fw-bold mb-3" style="font-size:1.2rem;">Dosis TLD</div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center justify-content-between bg-light rounded-3 px-3 py-2">
                            <div>
                                <div class="fw-semibold text-muted">1</div>
                                <div class="small text-muted">Periode : 12/03/2021 - 11/06/2021</div>
                            </div>
                            <div class="bg-dark text-white px-4 py-2 rounded-3 fw-bold" style="font-size:1.5rem; letter-spacing:1px;">10.8 μSv</div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between bg-light rounded-3 px-3 py-2">
                            <div>
                                <div class="fw-semibold text-muted">2</div>
                                <div class="small text-muted">Periode : 12/06/2021 - 11/09/2025</div>
                            </div>
                            <div class="bg-dark text-white px-4 py-2 rounded-3 fw-bold" style="font-size:1.5rem; letter-spacing:1px;">8.7 μSv</div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between bg-light rounded-3 px-3 py-2">
                            <div>
                                <div class="fw-semibold text-muted">3</div>
                                <div class="small text-muted">Periode : 12/09/2021 - 11/12/2021</div>
                            </div>
                            <div class="bg-dark text-white px-4 py-2 rounded-3 fw-bold" style="font-size:1.5rem; letter-spacing:1px;">11.9 μSv</div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between bg-light rounded-3 px-3 py-2">
                            <div>
                                <div class="fw-semibold text-muted">4</div>
                                <div class="small text-muted">Periode : 12/12/2021 - 11/03/2022</div>
                            </div>
                            <div class="bg-dark text-white px-4 py-2 rounded-3 fw-bold" style="font-size:1.5rem; letter-spacing:1px;">9.4 μSv</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Kalender -->
        <div class="col-lg-4">
            <div class="card shadow-sm h-100" style="border-radius:18px; background:#0a2a47; color:#fff;">
                <div class="card-body">
                    <div class="fw-bold mb-3">Kalender</div>
                    <div class="text-center mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <a href="#" class="text-white" id="prevMonth"><i class="bi bi-chevron-left"></i></a>
                            <span class="fw-semibold" id="currentMonthYear">Januari 2025</span>
                            <a href="#" class="text-white" id="nextMonth"><i class="bi bi-chevron-right"></i></a>
                        </div>
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

<style>
body { background: #f6f8fa; }
.card { border: none; }
.bg-dark { background: #0a2a47 !important; }
.calendar-wrapper { font-size: 13px; }
.calendar-day {
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.2s;
    color: rgba(255,255,255,0.7);
    font-size: 13px;
    margin: 1px;
}
.calendar-day:hover { background: rgba(255,255,255,0.1); }
.calendar-day.current { background: #ffb84d; color: #222; }
.calendar-day.other-month { color: rgba(255,255,255,0.3); }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Date & Day
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
        document.getElementById('currentDateTime').textContent = `${month} ${date}, ${year}, ${hours}:${minutes} ${ampm}`;
        document.getElementById('currentDayName').textContent = days[now.getDay()];
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // Kalender
    let currentDate = new Date();
    let displayedMonth = currentDate.getMonth();
    let displayedYear = currentDate.getFullYear();
    function updateCalendar() {
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        document.getElementById('currentMonthYear').textContent = `${months[displayedMonth]} ${displayedYear}`;
        const firstDay = new Date(displayedYear, displayedMonth, 1).getDay();
        const daysInMonth = new Date(displayedYear, displayedMonth + 1, 0).getDate();
        const lastDayPrevMonth = new Date(displayedYear, displayedMonth, 0).getDate();
        let html = '';
        let dayCounter = 1;
        let totalCells = 0;
        html += '<div class="row g-1 mb-1">';
        for (let i = 0; i < firstDay; i++) {
            const prevMonthDay = lastDayPrevMonth - firstDay + i + 1;
            html += `<div class="col px-0"><div class="calendar-day other-month">${prevMonthDay}</div></div>`;
            totalCells++;
        }
        while (dayCounter <= daysInMonth) {
            if (totalCells % 7 === 0) {
                html += '</div><div class="row g-1 mb-1">';
            }
            const isToday = dayCounter === currentDate.getDate() && displayedMonth === currentDate.getMonth() && displayedYear === currentDate.getFullYear();
            html += `<div class="col px-0"><div class="calendar-day${isToday ? ' current' : ''}">${dayCounter}</div></div>`;
            dayCounter++;
            totalCells++;
        }
        let nextMonthDay = 1;
        while (totalCells % 7 !== 0) {
            html += `<div class="col px-0"><div class="calendar-day other-month">${nextMonthDay}</div></div>`;
            nextMonthDay++;
            totalCells++;
        }
        html += '</div>';
        document.getElementById('calendarGrid').innerHTML = html;
    }
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
    updateCalendar();
});
</script>
@endpush
@endsection
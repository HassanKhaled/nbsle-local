@extends('templ.head')

@section('tmplt-contnt')
 <main id="main">

    <section class="breadcrumbs bg-color shadow-lg">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Calendar</h2>
        </div>

      </div>
    </section><!-- End Top green Section -->

    <div class="calendar-container">
        <div class="calendar-header">
            <button class="btn btn-primary btn-nav" id="prevMonth">
                <i class="fas fa-chevron-left"></i> Previous
            </button>
            <div class="month-year" id="monthYear"></div>
            <button class="btn btn-primary btn-nav" id="nextMonth">
                Next <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div id="loadingIndicator" class="loading" style="display: none;">
            <div class="spinner-border text-muted" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div class="calendar-grid" id="calendar"></div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="eventModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</main>
    <script>
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();
        let eventsData = [];

        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Initialize calendar
        document.addEventListener('DOMContentLoaded', function() {
            loadCalendar();

            document.getElementById('prevMonth').addEventListener('click', () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                loadCalendar();
            });

            document.getElementById('nextMonth').addEventListener('click', () => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                loadCalendar();
            });
        });

        async function loadCalendar() {
            document.getElementById('monthYear').textContent = 
                `${monthNames[currentMonth]} ${currentYear}`;
            
            document.getElementById('loadingIndicator').style.display = 'block';
            
            try {
                const response = await fetch(
                    `/calendar/events?month=${currentMonth + 1}&year=${currentYear}`
                );
                eventsData = await response.json();
                renderCalendar();
            } catch (error) {
                console.error('Error loading events:', error);
                alert('An error occurred while loading events');
            } finally {
                document.getElementById('loadingIndicator').style.display = 'none';
            }
        }

        function renderCalendar() {
            const calendar = document.getElementById('calendar');
            calendar.innerHTML = '';

            // Add day headers
            dayNames.forEach(day => {
                const header = document.createElement('div');
                header.className = 'calendar-day-header';
                header.textContent = day;
                calendar.appendChild(header);
            });

            // Get first day of month and number of days
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const daysInPrevMonth = new Date(currentYear, currentMonth, 0).getDate();

            const today = new Date();
            const isCurrentMonth = today.getMonth() === currentMonth && 
                                   today.getFullYear() === currentYear;

            // Previous month days
            for (let i = firstDay - 1; i >= 0; i--) {
                const day = daysInPrevMonth - i;
                const dayEl = createDayElement(day, true);
                calendar.appendChild(dayEl);
            }

            // Current month days
            for (let day = 1; day <= daysInMonth; day++) {
                const isToday = isCurrentMonth && today.getDate() === day;
                const dayEl = createDayElement(day, false, isToday);
                
                // Add events for this day
                const dayEvents = getEventsForDay(day);
                dayEvents.forEach(event => {
                    const eventEl = document.createElement('div');
                    eventEl.className = 'event-item';
                    eventEl.style.backgroundColor = event.color;
                    eventEl.textContent = event.title;
                    eventEl.onclick = () => showEventDetails(event);
                    dayEl.appendChild(eventEl);
                });
                
                calendar.appendChild(dayEl);
            }

            // Next month days
            const totalCells = calendar.children.length - 7; // Subtract headers
            const remainingCells = (Math.ceil(totalCells / 7) * 7) - totalCells;
            for (let day = 1; day <= remainingCells; day++) {
                const dayEl = createDayElement(day, true);
                calendar.appendChild(dayEl);
            }
        }

        function createDayElement(day, isOtherMonth, isToday = false) {
            const dayEl = document.createElement('div');
            dayEl.className = 'calendar-day';
            if (isOtherMonth) dayEl.classList.add('other-month');
            if (isToday) dayEl.classList.add('today');
            
            const dayNumber = document.createElement('div');
            dayNumber.className = 'day-number';
            dayNumber.textContent = day;
            dayEl.appendChild(dayNumber);
            
            return dayEl;
        }

        function getEventsForDay(day) {
            const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            return eventsData.filter(event => {
                const eventStart = new Date(event.start).toISOString().split('T')[0];
                const eventEnd = new Date(event.end).toISOString().split('T')[0];
                return dateStr >= eventStart && dateStr <= eventEnd;
            });
        }

        function showEventDetails(event) {
            const modal = new bootstrap.Modal(document.getElementById('eventModal'));
            document.getElementById('eventModalTitle').textContent = event.title;
            
            const badgeClass = event.type === 'news' ? 'bg-success' : 'bg-primary';
            const typeText = event.type === 'news' ? 'News' : 'Workshop';
            
            let modalContent = `
                <span class="modal-event-badge ${badgeClass}">${typeText}</span>
                <p><strong>Date:</strong> ${event.start} ${event.end}</p>
            `;
            
            if (event.location) {
                modalContent += `<p><strong>Location:</strong> ${event.location}</p>`;
            }
            
            if (event.time) {
                modalContent += `<p><strong>Time:</strong> ${event.time}</p>`;
            }
            
            document.getElementById('eventModalBody').innerHTML = modalContent;
            modal.show();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <style>
        .calendar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #dee2e6;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }
        .calendar-day-header {
            background: #0d2735;
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: bold;
        }
        .calendar-day {
            background: white;
            min-height: 100px;
            padding: 8px;
            position: relative;
        }
        .calendar-day.other-month {
            background: #f8f9fa;
            color: #adb5bd;
        }
        .calendar-day.today {
            background: #fff3cd;
        }
        .day-number {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .event-item {
            font-size: 11px;
            padding: 3px 6px;
            margin-bottom: 3px;
            border-radius: 3px;
            color: white;
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: opacity 0.2s;
        }
        .event-item:hover {
            opacity: 0.8;
        }
        .btn-nav {
            padding: 8px 16px;
        }
        .month-year {
            font-size: 20px;
            font-weight: bold;
        }
        .loading {
            text-align: center;
            padding: 20px;
        }
        .modal-event-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 10px;
        }
</style>

@endsection


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
            gap: 10px;
            flex-wrap: wrap;
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
        .event-span {
            position: absolute;
            font-size: 11px;
            padding: 3px 6px;
            border-radius: 3px;
            color: white;
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: opacity 0.2s;
            z-index: 10;
            left: 8px;
            right: 8px;
        }
        .event-span:hover {
            opacity: 0.8;
        }
        .btn-nav {
            padding: 8px 16px;
        }
        .month-year {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            flex: 1;
            min-width: 150px;
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

        /* Responsive styles */
        @media (max-width: 768px) {
            .calendar-container {
                padding: 10px;
            }
            .calendar-header {
                padding: 10px;
            }
            .btn-nav {
                padding: 6px 12px;
                font-size: 14px;
            }
            .btn-nav i {
                margin: 0;
            }
            .btn-nav .btn-text {
                display: none;
            }
            .month-year {
                font-size: 16px;
            }
            .calendar-day-header {
                padding: 8px 4px;
                font-size: 12px;
            }
            .calendar-day {
                min-height: 80px;
                padding: 4px;
            }
            .day-number {
                font-size: 12px;
            }
            .event-item, .event-span {
                font-size: 9px;
                padding: 2px 4px;
            }
        }

        @media (max-width: 480px) {
            .calendar-container {
                padding: 5px;
            }
            .calendar-header {
                padding: 8px;
            }
            .month-year {
                font-size: 14px;
            }
            .calendar-day-header {
                font-size: 10px;
                padding: 6px 2px;
            }
            .calendar-day {
                min-height: 60px;
                padding: 2px;
            }
            .day-number {
                font-size: 10px;
                margin-bottom: 2px;
            }
            .event-item, .event-span {
                font-size: 8px;
                padding: 1px 3px;
                margin-bottom: 2px;
            }
        }
    </style>
</head>
<body>
    <div class="calendar-container">
        <div class="calendar-header">
            <button class="btn btn-primary btn-nav" id="prevMonth">
                <i class="fas fa-chevron-left"></i>
                <span class="btn-text"> Previous</span>
            </button>
            <div class="month-year" id="monthYear"></div>
            <button class="btn btn-primary btn-nav" id="nextMonth">
                <span class="btn-text">Next </span>
                <i class="fas fa-chevron-right"></i>
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

    <script>
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();
        let eventsData = [];

        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

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

            dayNames.forEach(day => {
                const header = document.createElement('div');
                header.className = 'calendar-day-header';
                header.textContent = day;
                calendar.appendChild(header);
            });

            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const daysInPrevMonth = new Date(currentYear, currentMonth, 0).getDate();

            const today = new Date();
            const isCurrentMonth = today.getMonth() === currentMonth && 
                                   today.getFullYear() === currentYear;

            const dayElements = [];

            // Previous month days
            for (let i = firstDay - 1; i >= 0; i--) {
                const day = daysInPrevMonth - i;
                const dayEl = createDayElement(day, true);
                calendar.appendChild(dayEl);
                dayElements.push(null);
            }

            // Current month days
            for (let day = 1; day <= daysInMonth; day++) {
                const isToday = isCurrentMonth && today.getDate() === day;
                const dayEl = createDayElement(day, false, isToday);
                calendar.appendChild(dayEl);
                dayElements.push(dayEl);
            }

            // Next month days
            const totalCells = calendar.children.length - 7; 
            const remainingCells = (Math.ceil(totalCells / 7) * 7) - totalCells;
            for (let day = 1; day <= remainingCells; day++) {
                const dayEl = createDayElement(day, true);
                calendar.appendChild(dayEl);
                dayElements.push(null);
            }

            // Render events with spanning
            renderEvents(dayElements);
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

        function renderEvents(dayElements) {
            eventsData.forEach(event => {
                const startDate = new Date(event.start);
                const endDate = event.end ? new Date(event.end) : new Date(event.start);
                
                const startDay = startDate.getDate();
                const endDay = endDate.getDate();
                const startMonth = startDate.getMonth();
                const endMonth = endDate.getMonth();
                
                // Only render if event is in current month
                if (startMonth === currentMonth || endMonth === currentMonth) {
                    const daysDiff = Math.floor((endDate - startDate) / (1000 * 60 * 60 * 24));
                    
                    if (daysDiff > 0 && event.end) {
                        // Multi-day event - create spanning element
                        renderSpanningEvent(event, startDay, endDay, dayElements);
                    } else {
                        // Single day event
                        const dayIndex = startDay - 1;
                        if (dayElements[dayIndex]) {
                            const eventEl = document.createElement('div');
                            eventEl.className = 'event-item';
                            const shortTitle = event.title.length > 20 ? event.title.substring(0, 20) + '...' : event.title;
                            eventEl.style.backgroundColor = event.color;
                            eventEl.textContent = shortTitle;
                            eventEl.onclick = () => showEventDetails(event);
                            dayElements[dayIndex].appendChild(eventEl);
                        }
                    }
                }
            });
        }

        function renderSpanningEvent(event, startDay, endDay, dayElements) {
            const startIndex = startDay - 1;
            const endIndex = Math.min(endDay - 1, dayElements.length - 1);
            
            if (!dayElements[startIndex]) return;
            
            const startEl = dayElements[startIndex];
            const endEl = dayElements[endIndex];
            
            if (!endEl) return;
            
            // Calculate position
            const startRect = startEl.getBoundingClientRect();
            const endRect = endEl.getBoundingClientRect();
            
            const spanEl = document.createElement('div');
            spanEl.className = 'event-span';
            spanEl.style.backgroundColor = event.color;
            spanEl.textContent = event.title;
            spanEl.style.top = '25px';
            spanEl.onclick = () => showEventDetails(event);
            
            // Calculate width across days
            const dayWidth = startRect.width;
            const spanDays = endIndex - startIndex + 1;
            const width = (spanDays * dayWidth) + ((spanDays - 1) * 1); 
            
            spanEl.style.width = `${width}px`;
            startEl.appendChild(spanEl);
            startEl.style.position = 'relative';
        }

        function showEventDetails(event) {
            const modal = new bootstrap.Modal(document.getElementById('eventModal'));
            const title = event.title || '';
            const shortTitle = title.length > 20 ? title.substring(0, 20) + '...' : title;
            document.getElementById('eventModalTitle').textContent = shortTitle;

            const badgeClass = event.type === 'news' ? 'bg-success' : 'bg-primary';
            const typeText = event.type === 'news' ? 'News' : 'Workshop';

            const formattedStart = new Date(event.start).toLocaleDateString();
            const formattedEnd = event.end ? new Date(event.end).toLocaleDateString() : formattedStart;

            let modalContent = `
                <span class="modal-event-badge ${badgeClass}">${typeText}</span>
                <p><strong>Date:</strong> ${formattedStart}${event.end && formattedEnd !== formattedStart ? ' - ' + formattedEnd : ''}</p>
            `;

            if (event.location) {
                modalContent += `<p><strong>Location:</strong> ${event.location}</p>`;
            }

            if (event.time) {
                modalContent += `<p><strong>Time:</strong> ${event.time}</p>`;
            }

            let detailsUrl = '#';
            if (event.type === 'news') {
                detailsUrl = `/news/public/details/${event.event_id}`;
            } else if (event.type === 'workshop') {
                detailsUrl = `/workshops/${event.event_id}`;
            }

            modalContent += `
                <div class="mt-3 text-start">
                    <a href="${detailsUrl}" class="btn btn-sm btn-primary" target="_blank">
                        View Details
                    </a>
                </div>
            `;

            document.getElementById('eventModalBody').innerHTML = modalContent;
            modal.show();
        }
    </script>

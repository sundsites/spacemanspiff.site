// Calvin & Hobbes Calendar Component
// Date range: November 1985 - December 1995

const COMIC_START_YEAR = 1985;
const COMIC_END_YEAR = 1995;
const COMIC_START_MONTH = 11; // November (0-indexed would be 10, but we'll use 1-indexed)

let currentYear = new Date().getFullYear();
let currentMonth = new Date().getMonth() + 1; // 1-indexed
window.currentComicDate = null; // Track currently displayed comic date

// Ensure we start within valid range
if (currentYear < COMIC_START_YEAR || (currentYear === COMIC_START_YEAR && currentMonth < COMIC_START_MONTH)) {
    currentYear = COMIC_START_YEAR;
    currentMonth = COMIC_START_MONTH;
} else if (currentYear > COMIC_END_YEAR) {
    currentYear = COMIC_END_YEAR;
    currentMonth = 12;
}

function generateCalendar(year, month) {
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                       'July', 'August', 'September', 'October', 'November', 'December'];
    
    const daysInMonth = new Date(year, month, 0).getDate();
    const firstDay = new Date(year, month - 1, 1).getDay(); // 0 = Sunday
    
    let html = `
        <div class="calendar-header">
            <label for="year-select">Year</label>
            <select id="year-select"></select>
            <label for="month-select">Month</label>
            <select id="month-select"></select>
        </div>
        <div class="calendar-grid">
            <div class="calendar-day-header">Sun</div>
            <div class="calendar-day-header">Mon</div>
            <div class="calendar-day-header">Tue</div>
            <div class="calendar-day-header">Wed</div>
            <div class="calendar-day-header">Thu</div>
            <div class="calendar-day-header">Fri</div>
            <div class="calendar-day-header">Sat</div>
    `;
    
    // Leading days from previous month
    const prevMonthDays = firstDay;
    for (let i = prevMonthDays; i > 0; i--) {
        const day = new Date(year, month - 1, 1 - i);
        html += `<div class="calendar-day other-month">${day.getDate()}</div>`;
    }

    // Days of the current month
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const imageFile = `${year}/${String(month).padStart(2, '0')}/${year}${String(month).padStart(2, '0')}${String(day).padStart(2, '0')}.jpg`;
        const isValidDate = isDateInRange(year, month, day);
        const isSelected = window.currentComicDate === dateStr;
        const cls = isValidDate ? 'calendar-day available' : 'calendar-day unavailable';
        const selectedClass = isSelected ? ' selected' : '';
        const click = isValidDate ? `onclick="loadComic('${imageFile}', '${dateStr}')"` : '';
        html += `<div class="${cls}${selectedClass}" ${click} title="${dateStr}">${day}</div>`;
    }

    // Trailing days to complete week rows (up to full weeks)
    const totalCells = prevMonthDays + daysInMonth;
    const trailing = (7 - (totalCells % 7)) % 7;
    for (let i = 1; i <= trailing; i++) {
        const day = new Date(year, month, i);
        html += `<div class="calendar-day other-month">${day.getDate()}</div>`;
    }

    html += '</div>';
    
    document.getElementById('calendar-container').innerHTML = html;

    // Populate year/month selects and wire changes
    const yearSelect = document.getElementById('year-select');
    const monthSelect = document.getElementById('month-select');
    if (yearSelect && monthSelect) {
        yearSelect.innerHTML = '';
        for (let y = COMIC_START_YEAR; y <= COMIC_END_YEAR; y++) {
            const opt = document.createElement('option');
            opt.value = y;
            opt.textContent = y;
            if (y === year) opt.selected = true;
            yearSelect.appendChild(opt);
        }

        monthSelect.innerHTML = '';
        monthNames.forEach((name, idx) => {
            const m = idx + 1;
            const opt = document.createElement('option');
            opt.value = m;
            opt.textContent = name;
            if (m === month) opt.selected = true;
            monthSelect.appendChild(opt);
        });

        yearSelect.onchange = () => {
            currentYear = parseInt(yearSelect.value, 10);
            generateCalendar(currentYear, currentMonth);
        };
        monthSelect.onchange = () => {
            currentMonth = parseInt(monthSelect.value, 10);
            generateCalendar(currentYear, currentMonth);
        };
    }
}

function isDateInRange(year, month, day) {
    // Comics run from November 18, 1985 to December 31, 1995
    if (year < COMIC_START_YEAR || year > COMIC_END_YEAR) {
        return false;
    }
    
    if (year === COMIC_START_YEAR && month === COMIC_START_MONTH) {
        return day >= 18; // November 18, 1985 was the first strip
    }
    
    return true;
}

function loadComic(imageFile, dateStr) {
    // Track the requested comic date immediately so arrow navigation advances even if the image fails to load
    window.currentComicDate = dateStr;

    // Check if the image exists before loading
    const img = new Image();
    img.onload = function() {
        const comicImg = document.getElementById('comic-image');
        const comicLink = document.getElementById('comic-link');
        const comicDate = document.getElementById('comic-date');
        
        if (comicImg && comicLink) {
            comicImg.src = imageFile;
            comicLink.href = imageFile;
            
            if (comicDate) {
                const parts = dateStr.split('-').map(Number);
                const date = new Date(parts[0], parts[1] - 1, parts[2]); // local date to avoid timezone shift
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                comicDate.innerHTML = `<a target="_blank" href="${imageFile}">${date.toLocaleDateString('en-US', options)}</a>`;
            }
            
            // Scroll to the comic
            comicImg.scrollIntoView({ behavior: 'smooth', block: 'start' });

            // Re-render calendar to highlight selected day
            currentYear = parseInt(dateStr.slice(0,4), 10);
            currentMonth = parseInt(dateStr.slice(5,7), 10);
            generateCalendar(currentYear, currentMonth);
        }
    };
    
    img.onerror = function() {
        alert('Comic not available for this date.');
    };
    
    img.src = imageFile;
}

// Navigation buttons removed; dropdowns handle month/year changes

// Initialize calendar when page loads
document.addEventListener('DOMContentLoaded', function() {
    generateCalendar(currentYear, currentMonth);
    
    // Add keyboard navigation for comic images
    document.addEventListener('keydown', function(event) {
        if (!window.currentComicDate) return; // No comic loaded yet
        
        // Left arrow key - previous day
        if (event.key === 'ArrowLeft') {
            loadPreviousComic();
            event.preventDefault();
        }
        // Right arrow key - next day
        else if (event.key === 'ArrowRight') {
            loadNextComic();
            event.preventDefault();
        }
    });

    // Add touch swipe navigation for tablets/phones
    let touchStartX = 0;
    let touchStartY = 0;
    const threshold = 50; // minimum px to count as swipe

    document.addEventListener('touchstart', function(e) {
        // Only handle touches on the comic container area
        const target = e.target;
        if (target.id === 'comic-image' || target.closest('#comic-container')) {
            const t = e.changedTouches[0];
            touchStartX = t.clientX;
            touchStartY = t.clientY;
        }
    }, { passive: true });

    document.addEventListener('touchend', function(e) {
        if (!window.currentComicDate) return;
        
        const target = e.target;
        if (target.id === 'comic-image' || target.closest('#comic-container')) {
            const t = e.changedTouches[0];
            const dx = t.clientX - touchStartX;
            const dy = t.clientY - touchStartY;

            // Swipe must be more horizontal than vertical and exceed threshold
            if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > threshold) {
                e.preventDefault(); // Prevent default behavior like scrolling
                if (dx < 0) {
                    loadNextComic(); // swipe left -> next
                } else {
                    loadPreviousComic(); // swipe right -> previous
                }
            }
        }
    }, { passive: false }); // Must be non-passive to preventDefault
});

function loadPreviousComic() {
    stepComic(-1);
}

function loadNextComic() {
    stepComic(1);
}

function stepComic(step) {
    if (!window.currentComicDate) return;

    const parts = window.currentComicDate.split('-');
    if (parts.length !== 3) return;

    const year = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10);
    const day = parseInt(parts[2], 10);

    let date = new Date(year, month - 1, day);
    date.setDate(date.getDate() + step);

    const firstComic = new Date(1985, 10, 18); // Nov 18 1985
    const lastComic = new Date(1995, 11, 31);  // Dec 31 1995
    if (date < firstComic || date > lastComic) return;

    const y = date.getFullYear();
    const m = date.getMonth() + 1;
    const d = date.getDate();
    const imageFile = `${y}/${String(m).padStart(2, '0')}/${y}${String(m).padStart(2, '0')}${String(d).padStart(2, '0')}.jpg`;
    const dateStr = `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`;

    loadComic(imageFile, dateStr);
}

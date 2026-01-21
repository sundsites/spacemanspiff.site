# spacemanspiff.site
Calvin and Hobbes Archive

## Features

- Browse Calvin & Hobbes comics from 1985–1995
- Interactive calendar (year/month dropdowns, clickable days)
- Arrow keys and touch swipe navigation
- Chronological menu and slideshow mode
- Book info links

## Development (Virtual Environment)

This project supports a Python-based dev workflow. PHP is used in production, but for local development we provide a Python fallback server. PHP cannot be installed inside a Python venv; install PHP system-wide if you want full parity.

### Create and activate venv

```bash
python3 -m venv venv
source venv/bin/activate
```

No Python dependencies are required for the fallback static server.

### Run the dev server

```bash
# Default: tries PHP first; falls back to Python static server if PHP missing
python3 server.py

# Custom port
python3 server.py 8080

# Custom host and port
python3 server.py 8080 0.0.0.0
```

- When PHP is present: uses PHP’s built-in server to run `index.php`.
- When PHP is absent: serves static files (calendar, random comic work; PHP-only features like search may be limited).

### Install PHP (system-wide, optional)

- Ubuntu/Debian: `sudo apt-get install php`
- Fedora/RHEL: `sudo dnf install php`
- macOS: Download installer from php.net (or use Homebrew if available)
- Windows: Download from php.net

### Docker (alternative, cross‑platform)

```bash
docker compose up --build
```

## Notes

- Header title is a clickable link back to home.
- Images are responsive; toggle between half/full size via the toolbar link.
- Tablet swipe gestures: swipe left/right on the comic to navigate.

## Project Structure

- `index.php` — Main page (production)
- `index.html` — Static page (dev fallback)
- `js/calendar.js` — Calendar + navigation
- `css/candh.css` — Styles
- `server.py` — Dev server (PHP or Python fallback)
- `YYYY/MM/` — Comic images

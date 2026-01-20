# spacemanspiff.site
Calvin and Hobbes Archive

## Features

- Browse Calvin & Hobbes comics from 1985-1995
- Interactive calendar to select comics by date
- Search functionality for comic text
- Chronological browsing and slideshow mode
- Book information for each comic

## Development

### Quick Start with Virtual Environment (Recommended)

1. **Run the setup script:**
   ```bash
   ./setup.sh
   ```

2. **Activate the virtual environment:**
   ```bash
   source venv/bin/activate
   ```

3. **Start the server:**
   ```bash
   python app.py
   ```

4. **Visit:** `http://localhost:8000`

5. **When done, deactivate:**
   ```bash
   deactivate
   ```

### Manual Setup

If you prefer to set up manually:

```bash
# Create virtual environment
python3 -m venv venv

# Activate it
source venv/bin/activate

# Install dependencies
pip install -r requirements.txt

# Run the server
python app.py

# Custom port
python app.py 8080
```

### Running the Development Server

This project includes a Python server script for local development:

```bash
# Start the server (requires PHP to be installed)
python3 server.py

# Or make it executable and run directly
./server.py

# Specify a custom port (default is 8000)
python3 server.py 8080

# Specify custom host and port
python3 server.py 8080 0.0.0.0
```

The server will start at `http://localhost:8000`

### Prerequisites

- Python 3.7+ (for the Flask server)
- MySQL/MariaDB database (optional - site works without it for basic functionality)
- **No PHP required!**

### Calendar Feature

The calendar component allows users to:
- Navigate through months from November 1985 to December 1995
- Click on any date to view the comic for that day
- See visual feedback for available comics
- The calendar starts from November 18, 1985 (the first Calvin & Hobbes strip)

### Project Structure

- `index.php` - Main page
- `js/calendar.js` - Calendar component JavaScript
- `css/candh.css` - Styles including calendar styles
- `server.py` - Development server script
- `YYYY/MM/` - Comic images organized by year and month

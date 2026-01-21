#!/usr/bin/env python3
"""
Simple development server for Calvin & Hobbes site
Serves PHP files using PHP's built-in server
"""

import subprocess
import sys
import os
import signal
from pathlib import Path
from http.server import SimpleHTTPRequestHandler
from socketserver import TCPServer

def check_php_installed():
    """Check if PHP is installed and available"""
    try:
        result = subprocess.run(['php', '-v'], capture_output=True, text=True)
        if result.returncode == 0:
            print(f"✓ PHP found: {result.stdout.split()[1]}")
            return True
        else:
            print("✗ PHP not found in PATH")
            return False
    except FileNotFoundError:
        print("✗ PHP not found. Please install PHP to run this server.")
        print("\nInstallation instructions:")
        print("  macOS: Download from https://www.php.net/downloads.php or use Homebrew if available")
        print("  Ubuntu/Debian: sudo apt-get install php")
        print("  Fedora/RHEL: sudo dnf install php")
        print("  Windows: Download from https://www.php.net/downloads.php")
        return False

def start_server(host='localhost', port=8000):
    """Start the PHP built-in development server"""
    
    # Get the directory where this script is located
    script_dir = Path(__file__).parent.resolve()
    
    print(f"\n{'='*60}")
    print(f"Calvin & Hobbes Development Server")
    print(f"{'='*60}")
    print(f"Server starting at: http://{host}:{port}")
    print(f"Document root: {script_dir}")
    print(f"Press Ctrl+C to stop the server")
    print(f"{'='*60}\n")
    
    try:
        # Start PHP built-in server
        process = subprocess.Popen(
            ['php', '-S', f'{host}:{port}', '-t', str(script_dir)],
            cwd=str(script_dir),
            stdout=sys.stdout,
            stderr=sys.stderr
        )
        process.wait()
    except Exception:
        raise

def main():
    """Main entry point"""
    # Check if PHP is installed
    php_available = check_php_installed()
    
    # Parse command line arguments
    host = 'localhost'
    port = 8000
    
    if len(sys.argv) > 1:
        try:
            port = int(sys.argv[1])
        except ValueError:
            print(f"Invalid port number: {sys.argv[1]}")
            sys.exit(1)
    
    if len(sys.argv) > 2:
        host = sys.argv[2]
    
    if php_available:
        # Start PHP server
        start_server(host, port)
    else:
        # Fallback: start Python static server
        script_dir = Path(__file__).parent.resolve()
        os.chdir(str(script_dir))

        print(f"\n{'='*60}")
        print("Calvin & Hobbes Development Server (Python fallback)")
        print(f"{'='*60}")
        print(f"Server starting at: http://{host}:{port}")
        print(f"Document root: {script_dir}")
        print("Note: PHP is not installed; serving static files only.")
        print("The calendar and random comic will work; PHP-only features (search) may not.")
        print(f"{'='*60}\n")

        with TCPServer((host, port), SimpleHTTPRequestHandler) as httpd:
            try:
                httpd.serve_forever()
            except KeyboardInterrupt:
                print("\n\nShutting down server...")
                httpd.server_close()
                print("Server stopped.")

if __name__ == '__main__':
    main()

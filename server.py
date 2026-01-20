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
        print("  macOS: brew install php")
        print("  Ubuntu/Debian: sudo apt-get install php")
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
        
        # Wait for the server to run
        process.wait()
        
    except KeyboardInterrupt:
        print("\n\nShutting down server...")
        process.terminate()
        process.wait()
        print("Server stopped.")
    except Exception as e:
        print(f"\nError starting server: {e}")
        sys.exit(1)

def main():
    """Main entry point"""
    # Check if PHP is installed
    if not check_php_installed():
        sys.exit(1)
    
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
    
    # Start the server
    start_server(host, port)

if __name__ == '__main__':
    main()

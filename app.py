#!/usr/bin/env python3
"""
Flask-based development server for Calvin & Hobbes site
Serves static files and provides API endpoints for comics
"""

from flask import Flask, render_template, request, jsonify, send_from_directory, redirect
import mysql.connector
from mysql.connector import Error
import os
from datetime import datetime
from pathlib import Path
import random
import re

app = Flask(__name__, 
            static_folder='.',
            static_url_path='')

# Configuration
PAGE_SIZE = 30
COMIC_START_YEAR = 1985
COMIC_START_MONTH = 11
COMIC_START_DAY = 18
COMIC_END_YEAR = 1995
COMIC_END_MONTH = 12

def get_db_config():
    """Read database configuration from config file"""
    config_dir = Path(__file__).parent / 'config'
    docker_config = config_dir / 'docker.dbconfig.cfg'
    default_config = config_dir / 'dbconfig.cfg'
    
    # Try to parse PHP config files
    for config_file in [default_config, docker_config]:
        if config_file.exists():
            try:
                with open(config_file, 'r') as f:
                    content = f.read()
                    
                # Extract values from PHP array format
                import re
                db_config = {}
                
                patterns = {
                    'host': r"'dbhost'\s*=>\s*'([^']*)'",
                    'user': r"'dbuser'\s*=>\s*'([^']*)'",
                    'password': r"'dbpassword'\s*=>\s*'([^']*)'",
                    'database': r"'dbname'\s*=>\s*'([^']*)'"
                }
                
                for key, pattern in patterns.items():
                    match = re.search(pattern, content)
                    if match:
                        db_config[key] = match.group(1)
                
                if db_config:
                    return db_config
            except Exception as e:
                print(f"Error reading config file {config_file}: {e}")
                continue
    
    # Return None if no valid config found - we'll work without database
    return None

def get_db_connection():
    """Create database connection"""
    try:
        config = get_db_config()
        if not config:
            return None
        
        connection = mysql.connector.connect(
            host=config['host'],
            user=config['user'],
            password=config['password'],
            database=config['database']
        )
        return connection
    except Error as e:
        print(f"Database connection error: {e}")
        return None

@app.route('/')
def index():
    """Serve the main page"""
    return send_from_directory('.', 'index.html')

@app.route('/index.php')
def index_php():
    """Handle index.php requests"""
    return send_from_directory('.', 'index.html')

@app.route('/api/random-comic')
def random_comic():
    """Get a random comic"""
    connection = get_db_connection()
    if not connection:
        # Return a hardcoded random date if no database
        year = random.randint(COMIC_START_YEAR, COMIC_END_YEAR)
        month = random.randint(1, 12)
        day = random.randint(1, 28)
        
        image_file = f"{year}/{month:02d}/{year}{month:02d}{day:02d}.jpg"
        date_str = f"{year}-{month:02d}-{day:02d}"
        
        return jsonify({
            'date': date_str,
            'image': image_file,
            'books': ''
        })
    
    try:
        cursor = connection.cursor(dictionary=True)
        cursor.execute("SELECT * FROM ch ORDER BY RAND() LIMIT 1")
        row = cursor.fetchone()
        
        if row:
            date_obj = row['ch_date']
            if isinstance(date_obj, str):
                date_obj = datetime.strptime(date_obj, '%Y-%m-%d')
            
            image_file = date_obj.strftime('%Y/%m/%Y%m%d') + '.jpg'
            
            return jsonify({
                'date': date_obj.strftime('%Y-%m-%d'),
                'image': image_file,
                'books': row.get('ch_books', ''),
                'text': row.get('ch_text', '')
            })
    except Error as e:
        print(f"Database query error: {e}")
    finally:
        if connection:
            connection.close()
    
    return jsonify({'error': 'No comic found'}), 404

@app.route('/api/comic/<date>')
def get_comic(date):
    """Get comic by date"""
    try:
        date_obj = datetime.strptime(date, '%Y-%m-%d')
        image_file = date_obj.strftime('%Y/%m/%Y%m%d') + '.jpg'
        
        # Check if image exists
        if not os.path.exists(image_file):
            return jsonify({'error': 'Comic not found'}), 404
        
        connection = get_db_connection()
        if connection:
            try:
                cursor = connection.cursor(dictionary=True)
                cursor.execute("SELECT * FROM ch WHERE ch_date = %s", (date,))
                row = cursor.fetchone()
                
                if row:
                    return jsonify({
                        'date': date,
                        'image': image_file,
                        'books': row.get('ch_books', ''),
                        'text': row.get('ch_text', '')
                    })
            finally:
                connection.close()
        
        # Return basic info without database
        return jsonify({
            'date': date,
            'image': image_file,
            'books': '',
            'text': ''
        })
    except ValueError:
        return jsonify({'error': 'Invalid date format'}), 400

@app.route('/api/search')
def search():
    """Search comics"""
    query = request.args.get('q', '')
    page = int(request.args.get('page', 1))
    
    connection = get_db_connection()
    if not connection:
        return jsonify({'error': 'Database not available'}), 503
    
    try:
        cursor = connection.cursor(dictionary=True)
        
        if not query:
            # Return all records
            offset = (page - 1) * PAGE_SIZE
            cursor.execute(f"SELECT * FROM ch ORDER BY ch_date LIMIT {PAGE_SIZE + 1} OFFSET {offset}")
        else:
            # Full-text search
            offset = (page - 1) * PAGE_SIZE
            cursor.execute(
                f"SELECT *, MATCH (ch_text) AGAINST (%s IN NATURAL LANGUAGE MODE) AS score "
                f"FROM ch WHERE MATCH (ch_text) AGAINST (%s IN NATURAL LANGUAGE MODE) "
                f"LIMIT {PAGE_SIZE + 1} OFFSET {offset}",
                (query, query)
            )
        
        results = cursor.fetchall()
        
        # Convert dates to strings
        comics = []
        for row in results[:PAGE_SIZE]:
            date_obj = row['ch_date']
            if isinstance(date_obj, str):
                date_obj = datetime.strptime(date_obj, '%Y-%m-%d')
            
            comics.append({
                'date': date_obj.strftime('%Y-%m-%d'),
                'image': date_obj.strftime('%Y/%m/%Y%m%d') + '.jpg',
                'text': row.get('ch_text', ''),
                'books': row.get('ch_books', '')
            })
        
        has_more = len(results) > PAGE_SIZE
        
        return jsonify({
            'comics': comics,
            'page': page,
            'has_more': has_more
        })
    finally:
        connection.close()

@app.route('/<path:path>')
def serve_static(path):
    """Serve static files"""
    return send_from_directory('.', path)

def main():
    """Run the development server"""
    import sys
    
    port = 8000
    host = '127.0.0.1'
    
    if len(sys.argv) > 1:
        try:
            port = int(sys.argv[1])
        except ValueError:
            print(f"Invalid port number: {sys.argv[1]}")
            sys.exit(1)
    
    if len(sys.argv) > 2:
        host = sys.argv[2]
    
    print(f"\n{'='*60}")
    print(f"Calvin & Hobbes Development Server (Flask)")
    print(f"{'='*60}")
    print(f"Server starting at: http://{host}:{port}")
    print(f"Press Ctrl+C to stop the server")
    print(f"{'='*60}\n")
    
    app.run(host=host, port=port, debug=True)

if __name__ == '__main__':
    main()

#!/bin/bash
# Setup script for Calvin & Hobbes development environment

echo "=================================="
echo "Calvin & Hobbes Dev Environment"
echo "=================================="
echo ""

# Check if venv exists
if [ -d "venv" ]; then
    echo "✓ Virtual environment already exists"
else
    echo "Creating virtual environment..."
    python3 -m venv venv
    if [ $? -eq 0 ]; then
        echo "✓ Virtual environment created"
    else
        echo "✗ Failed to create virtual environment"
        exit 1
    fi
fi

# Activate virtual environment
echo ""
echo "Activating virtual environment..."
source venv/bin/activate

# Install dependencies
echo ""
echo "Installing dependencies..."
pip install --upgrade pip
pip install -r requirements.txt

if [ $? -eq 0 ]; then
    echo ""
    echo "=================================="
    echo "✓ Setup complete!"
    echo "=================================="
    echo ""
    echo "To start the development server:"
    echo "  1. Activate the virtual environment:"
    echo "     source venv/bin/activate"
    echo ""
    echo "  2. Run the server:"
    echo "     python app.py"
    echo ""
    echo "  3. Visit: http://localhost:8000"
    echo ""
    echo "To deactivate the virtual environment:"
    echo "     deactivate"
    echo ""
else
    echo "✗ Failed to install dependencies"
    exit 1
fi

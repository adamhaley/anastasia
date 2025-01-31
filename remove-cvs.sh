#!/bin/bash

# Find and remove all CVS directories under the current directory
find . -type d -name CVS -exec rm -rf {} +

echo "All CVS directories have been removed."

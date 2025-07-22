#!/bin/bash

# Test script to verify the Kana consolidation migration
DB_FILE="/tmp/test_azka_garden.db"

echo "Testing Kana product consolidation migration..."

# Clean up any existing test database
rm -f "$DB_FILE"

# Create database and table
echo "Creating database schema..."
sqlite3 "$DB_FILE" < database_schema.sql

# Insert initial data (with duplicates)
echo "Inserting initial data with duplicate Kana entries..."
sqlite3 "$DB_FILE" < initial_data.sql

# Check initial state
echo "Initial state - should show 2 Kana entries:"
sqlite3 "$DB_FILE" "SELECT id, name, description, stock, price, weight, image_url FROM products WHERE name = 'Kana';"

# Run migration to consolidate entries
echo "Running consolidation migration..."
sqlite3 "$DB_FILE" < consolidate_kana_migration.sql

# Check final state
echo "Final state - should show 1 Kana entry with updated details:"
sqlite3 "$DB_FILE" "SELECT id, name, description, stock, price, weight, image_url FROM products WHERE name = 'Kana';"

# Verify specific requirements
echo "Verifying requirements:"
KANA_COUNT=$(sqlite3 "$DB_FILE" "SELECT COUNT(*) FROM products WHERE name = 'Kana';")
echo "Number of Kana entries: $KANA_COUNT (should be 1)"

KANA_DETAILS=$(sqlite3 "$DB_FILE" "SELECT stock, price, weight, image_url FROM products WHERE name = 'Kana';")
echo "Kana details: $KANA_DETAILS"
echo "Expected: 25|Rp30,000|0.6|images/produk/kana.jpg"

# Check if description contains expected content
DESC_CHECK=$(sqlite3 "$DB_FILE" "SELECT CASE WHEN description LIKE '%Canna indica%' THEN 'PASS' ELSE 'FAIL' END FROM products WHERE name = 'Kana';")
echo "Description check: $DESC_CHECK"

# Clean up
rm -f "$DB_FILE"
echo "Test completed."
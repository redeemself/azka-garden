# azka-garden

Azka Garden Database - Product Management System

## Database Schema

This repository contains the database schema and migrations for managing garden products.

### Files Structure

- `database_schema.sql` - Main database schema with products table
- `initial_data.sql` - Initial product data (including duplicate Kana entries)
- `consolidate_kana_migration.sql` - Migration to consolidate duplicate Kana product entries
- `test_migration.sh` - Test script to verify the migration works correctly

### Running the Migration

To consolidate the duplicate Kana entries:

1. Create the database and schema:
   ```bash
   sqlite3 azka_garden.db < database_schema.sql
   ```

2. Insert initial data:
   ```bash
   sqlite3 azka_garden.db < initial_data.sql
   ```

3. Run the consolidation migration:
   ```bash
   sqlite3 azka_garden.db < consolidate_kana_migration.sql
   ```

### Testing

Run the test script to verify the migration:
```bash
./test_migration.sh
```

### Migration Details

The migration performs the following operations:
1. Removes the duplicate 'Kana' entry with image URL 'images/produk/kana.png'
2. Updates the remaining 'Kana' entry (with image URL 'images/produk/kana.jpg') to include:
   - **Description**: Canna indica plant description
   - **Stock**: 25
   - **Price**: Rp30,000
   - **Weight**: 0.60 kg
   - **Image URL**: images/produk/kana.jpg
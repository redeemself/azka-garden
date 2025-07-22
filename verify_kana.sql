-- Query to verify the final state of Kana products
-- This should return exactly one entry for 'Kana' with the consolidated details

SELECT 
    id,
    name,
    description,
    stock,
    price,
    weight,
    image_url,
    created_at,
    updated_at
FROM products 
WHERE name = 'Kana';

-- Count verification - should return 1
SELECT COUNT(*) as kana_count FROM products WHERE name = 'Kana';
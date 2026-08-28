-- ============================================================
-- BD Hotel · patch to speed up existing image loads
-- Run this ONCE if you already imported bdhotel.sql before the
-- image-URL fix. Copy-paste into phpMyAdmin's SQL tab OR run:
--     mysql -u root bd_hotel < database/patch-images.sql
-- ============================================================
USE bd_hotel;

UPDATE rooms
SET images = REPLACE(
                REPLACE(images,
                  'https://images.unsplash.com/photo-1629140727571-9b5c6f6267b4',
                  'https://images.unsplash.com/photo-1629140727571-9b5c6f6267b4?auto=format&fit=crop&w=1600&q=80'),
              'https://images.unsplash.com/photo-1611892440504-42a792e24d32',
              'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1600&q=80')
WHERE images LIKE '%unsplash.com%' AND images NOT LIKE '%auto=format%';

-- Simpler global patch: append ?w=1600 to any Unsplash URL that has no query string
UPDATE rooms
SET images = REGEXP_REPLACE(images,
    'https://images.unsplash.com/(photo-[a-z0-9-]+)([^?\r\n]*)(?=$|\n)',
    'https://images.unsplash.com/\\1?auto=format&fit=crop&w=1600&q=80')
WHERE images LIKE '%unsplash.com%' AND images NOT LIKE '%auto=format%';

UPDATE gallery
SET url = CONCAT(url, '?auto=format&fit=crop&w=1600&q=80')
WHERE url LIKE '%unsplash.com%' AND url NOT LIKE '%?%';

UPDATE gallery
SET url = CONCAT(url, '?auto=compress&cs=tinysrgb&w=1600')
WHERE url LIKE '%pexels.com%' AND url NOT LIKE '%?%';

SELECT 'Done. Refresh your browser (Ctrl+Shift+R).' AS message;

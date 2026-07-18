-- สร้างฐานข้อมูลทั้ง dev และ prod ตอน MySQL container init ครั้งแรก (dbdata volume ว่าง)
-- root@'%' มีสิทธิ์ทุก database อยู่แล้ว จึงไม่ต้อง GRANT เพิ่ม
CREATE DATABASE IF NOT EXISTS `paw-track-dev`  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS `paw-track-prod` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

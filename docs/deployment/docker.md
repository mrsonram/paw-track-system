# Deployment — Docker

> หมวด: **การ deploy (Deployment)**
> อัปเดตล่าสุด: 18 ก.ค. 2026

รัน PawTrack ผ่าน Docker

## ไฟล์ที่เกี่ยวข้อง

- `Dockerfile` — image ฐาน `php:8.1-fpm` + extension (`pdo_mysql`, `mbstring`, `zip`, `exif`, `pcntl`) + Composer
- `docker-compose.yml` — service `app` (container `pawtrack`), map port `8000:8000`
- `php/local.ini` — ค่า PHP เพิ่มเติม (mount เข้า container)
- `nginx/`, `mysql-config/`, `my.cnf` — config เสริม (ยังไม่ถูกใช้ใน compose ปัจจุบัน)

## วิธีรัน

```bash
docker compose up -d --build
```

แอปจะรันที่ http://localhost:8000 (PHP built-in server serve จาก `public/`)

## ⚠️ ข้อควรระวัง

1. **ยังไม่มี service ฐานข้อมูลใน compose** — service มีแค่ `app`
   ต้องเตรียม MySQL แยก แล้วตั้ง `DB_HOST` ใน `.env` ให้ชี้ไปถูก
   (ถ้า MySQL รันบน host ให้ใช้ `host.docker.internal`)
2. container รันด้วย `php -S` (built-in server) เหมาะกับ dev ไม่เหมาะ production
   สำหรับ production ควรใช้ nginx + php-fpm (config มีอยู่ในโฟลเดอร์ `nginx/` แล้ว)
3. อย่าลืม `php artisan key:generate` และตั้ง `APP_DEBUG=false` ตอน deploy จริง

## TODO

- [ ] เพิ่ม service `db` (MySQL) และ `nginx` ใน `docker-compose.yml`
- [ ] แยก config production / development

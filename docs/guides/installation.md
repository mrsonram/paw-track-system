# Guide — Installation (ติดตั้งและรัน)

> หมวด: **คู่มือ (Guide)**
> อัปเดตล่าสุด: 18 ก.ค. 2026

วิธีติดตั้งและรัน PawTrack ในเครื่อง (local)

## Requirements

- PHP >= 8.0 (แนะนำ 8.1) + extension: `pdo_mysql`, `mbstring`, `zip`, `exif`, `pcntl`, `gd`
- Composer 2.x
- Node.js + npm
- MySQL 5.7+ / 8.0

## ขั้นตอน

```bash
# 1) ติดตั้ง dependencies
composer install
npm install

# 2) ตั้งค่า environment
cp .env.example .env
php artisan key:generate

# 3) แก้ค่าฐานข้อมูลใน .env
#    DB_DATABASE=paw-track-dev
#    DB_USERNAME=root
#    DB_PASSWORD=<รหัสของคุณ>

# 4) สร้างฐานข้อมูลชื่อ paw-track-dev ใน MySQL แล้ว migrate + seed user เริ่มต้น
php artisan migrate --seed

# 5) build assets
npm run dev

# 6) รันเซิร์ฟเวอร์
php artisan serve
```

เปิด http://localhost:8000

> วิธีที่ง่ายกว่า (ไม่ต้องลง PHP/MySQL บนเครื่อง): รันผ่าน Docker — ดู [../deployment/docker.md](../deployment/docker.md)

## หมายเหตุ

- ผู้ใช้เริ่มต้นหลัง seed: **`admin@pawtrack.test`** / รหัส **`password`**
- หน้าแผนที่ต้องใส่ **Google Maps API Key** ใน Blade view จึงจะแสดงแผนที่ได้
- ถ้า assets ไม่อัปเดต ให้รัน `npm run watch` ค้างไว้ระหว่างพัฒนา
- ปัญหาสิทธิ์ storage: `php artisan storage:link` และตรวจสิทธิ์โฟลเดอร์ `storage/`

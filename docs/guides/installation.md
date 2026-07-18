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
#    DB_DATABASE=pawtrack
#    DB_USERNAME=root
#    DB_PASSWORD=<รหัสของคุณ>

# 4) สร้างฐานข้อมูลชื่อ pawtrack ใน MySQL แล้ว migrate
php artisan migrate

# 5) build assets
npm run dev

# 6) รันเซิร์ฟเวอร์
php artisan serve
```

เปิด http://localhost:8000

## หมายเหตุ

- หน้าแผนที่ต้องใส่ **Google Maps API Key** ใน Blade view จึงจะแสดงแผนที่ได้
- ถ้า assets ไม่อัปเดต ให้รัน `npm run watch` ค้างไว้ระหว่างพัฒนา
- ปัญหาสิทธิ์ storage: `php artisan storage:link` และตรวจสิทธิ์โฟลเดอร์ `storage/`

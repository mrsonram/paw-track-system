# Contributing — PawTrack

ขอบคุณที่ร่วมพัฒนา! เอกสารนี้อธิบายแนวทางการทำงานร่วมกันในโปรเจค

## เริ่มต้น

ทำตามขั้นตอนติดตั้งใน [`README.md`](README.md) ก่อน (composer install, npm install,
คัดลอก `.env`, `key:generate`, `migrate`)

## Workflow

1. สร้าง branch จาก `main`:
   ```bash
   git checkout -b feature/ชื่อฟีเจอร์
   ```
2. เขียนโค้ด + ทดสอบในเครื่อง (`php artisan serve`, `npm run watch`)
3. รันเทสต์ก่อน commit:
   ```bash
   php artisan test
   ```
4. Commit ด้วยข้อความที่สื่อความหมาย (ดูด้านล่าง)
5. เปิด Pull Request ไปที่ `main` พร้อมอธิบายสิ่งที่เปลี่ยน

## แนวทางการเขียนโค้ด (Coding standards)

- ทำตาม convention ของ Laravel และรูปแบบโค้ดเดิมในโปรเจค
- **ต้อง validate** ข้อมูลผู้ใช้ทุกครั้งก่อนบันทึกลงฐานข้อมูล (`$request->validate([...])`)
  — อย่าส่ง `$request->all()` เข้า `Model::create()` ตรง ๆ
- ตั้งชื่อ route/controller/method ให้สอดคล้องกับของเดิม
- View ใหม่ให้ extend layout เดิม (`layouts/app.blade.php` หรือ `layouts/main.blade.php`)
  และใช้ Bootstrap 4 เหมือนหน้าเดิม
- เมื่อแก้ตาราง `animals` ต้องอัปเดต migration **และ** ตรวจทุก model ที่ใช้ตารางนี้
  (`Pet`, `Admin`, `GoogleMap`)
- ลบโค้ดที่ comment ทิ้งและ import ที่ไม่ใช้ออก

## Commit message

รูปแบบสั้น ๆ อ่านเข้าใจง่าย เช่น:

```
Add validation to ContactController@store
Fix $fillable typo in Contact model
Update map view to use real animals columns
```

## สิ่งที่ห้าม commit

- `.env` (มี APP_KEY และรหัสผ่าน DB)
- `/vendor`, `/node_modules`
- ไฟล์ build / cache (`/public/hot`, `/public/storage`, `*.key`)

ทั้งหมดนี้อยู่ใน `.gitignore` แล้ว — อย่า force add

## ก่อนส่ง PR — เช็คลิสต์

- [ ] รันเทสต์ผ่าน (`php artisan test`)
- [ ] ทดสอบฟีเจอร์ในเบราว์เซอร์จริง
- [ ] เพิ่ม/แก้ validation ครบ
- [ ] ไม่มีไฟล์ที่ไม่ควร commit ติดไปด้วย
- [ ] อ่าน [`REVIEW.md`](REVIEW.md) เพื่อไม่ทำซ้ำปัญหาเดิม

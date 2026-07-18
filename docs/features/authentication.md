# Feature — Authentication (ระบบสมาชิก)

> สถานะ: ✅ ใช้งานได้ (Laravel UI scaffolding)
> อัปเดตล่าสุด: 18 ก.ค. 2026

ระบบสมัคร/เข้าสู่ระบบ ผ่าน package `laravel/ui`

## ภาพรวม

- ใช้ `Auth::routes()` ใน `routes/web.php` (login, register, password reset, verify)
- หน้า `/home` → `HomeController@index` (ต้องล็อกอิน)
- Views: `resources/views/auth/*` (login, register, verify, passwords/*)
- Model `App\Models\User` (ตาราง `users`, มาตรฐาน Laravel)

## Routes

| Route | คำอธิบาย |
| --- | --- |
| `/login`, `/register` | เข้าสู่ระบบ / สมัคร |
| `/password/*` | รีเซ็ตรหัสผ่าน |
| `/home` | หน้าหลังล็อกอิน (`->name('home')`) |

## ข้อควรปรับปรุง

- [ ] ใช้ระบบ auth นี้ป้องกันหน้า admin (ดู [admin-panel.md](admin-panel.md))
- [ ] แยก role (admin vs user) หากต้องจำกัดสิทธิ์
- [ ] ตั้งค่า email (SMTP) ให้ verify/reset ใช้งานได้จริง

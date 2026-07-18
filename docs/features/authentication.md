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

## ผู้ใช้เริ่มต้น (UserSeeder)

`database/seeders/UserSeeder.php` สร้าง admin เริ่มต้น (รันตอน `artisan migrate --seed` / `db:seed`):

| Email | Password |
| --- | --- |
| `admin@pawtrack.test` | `password` |

seeder ใช้ `updateOrCreate` จึง idempotent (รันซ้ำไม่สร้างซ้ำ)

## ข้อควรปรับปรุง

- [ ] ใช้ระบบ auth นี้ป้องกันหน้า admin (ดู [admin-panel.md](admin-panel.md))
- [ ] แยก role (admin vs user) หากต้องจำกัดสิทธิ์
- [ ] ตั้งค่า email (SMTP) ให้ verify/reset ใช้งานได้จริง
- [ ] เปลี่ยนรหัส admin เริ่มต้นก่อนใช้งานจริง

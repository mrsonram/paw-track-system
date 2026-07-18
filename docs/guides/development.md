# Guide — Development Workflow

> หมวด: **คู่มือ (Guide)**
> อัปเดตล่าสุด: 18 ก.ค. 2026

แนวทางพัฒนา PawTrack (ดู [../../CLAUDE.md](../../CLAUDE.md) และ [../../CONTRIBUTING.md](../../CONTRIBUTING.md) ประกอบ)

## คำสั่งที่ใช้บ่อย

```bash
php artisan serve          # dev server
npm run watch              # rebuild assets on change
php artisan migrate        # รัน migration
php artisan make:controller XxxController
php artisan make:model Xxx -m
php artisan test           # รันเทสต์
php artisan route:list     # ดู route ทั้งหมด
```

## เพิ่มฟีเจอร์ใหม่ — เช็คลิสต์

1. **Route** → `routes/web.php` (ใช้รูปแบบเดิม, เลี่ยง `Route::resource` ถ้าไม่ implement ครบ)
2. **Controller** → `app/Http/Controllers/` (implement เฉพาะ action ที่ใช้)
3. **Validate** input เสมอ → `$request->validate([...])` ก่อน `Model::create()`
   อย่าส่ง `$request->all()` เข้า `create()` ตรง ๆ
4. **Model** → `app/Models/` (ตั้ง `$table`, `$fillable`, `$primaryKey`, `$timestamps = false`)
5. **Migration** → `php artisan make:migration ...` แล้ว `php artisan migrate`
6. **View** → `resources/views/<area>/` extend `layouts/app.blade.php` หรือ `layouts/main.blade.php`

## ข้อควรระวังเฉพาะโปรเจกต์นี้

- Model `Pet`, `Admin`, `GoogleMap` ใช้ตาราง **`animals`** ร่วมกัน — แก้คอลัมน์ต้องดูทุกตัว
- `Contact` model มี typo `$fillbale` (ดู [../features/contact-form.md](../features/contact-form.md))
- `GoogleMap` อ้างคอลัมน์ที่ไม่มีจริง (ดู [../features/map-locations.md](../features/map-locations.md))

## โครงสร้างโฟลเดอร์ (ย่อ)

```
app/Http/Controllers/   Controller ต่าง ๆ
app/Models/             Pet, News, Contact, GoogleMap, Admin, User
database/migrations/    users, animals, contacts, news
resources/views/        pet/ admin/ google/ pages/ auth/ layouts/
routes/web.php          route ทั้งหมด
```

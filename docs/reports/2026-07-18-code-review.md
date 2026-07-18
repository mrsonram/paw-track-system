# Code Review — PawTrack (18 ก.ค. 2026)

> หมวด: **รายงาน (Report, point-in-time)** — ไม่ต้องอัปเดต
> ขอบเขต: อ่านโค้ดใน `app/`, `routes/`, `database/`

รีวิวรอบแรก ระบุปัญหา/ข้อควรปรับปรุงเรียงตามระดับความสำคัญ
แผนแก้ดูที่ [../plans/action-plan-2026-07-18.md](../plans/action-plan-2026-07-18.md)

---

## 🔴 High — ควรแก้ก่อน

### 1. ไม่มีการ validate ข้อมูลก่อนบันทึก (Mass Assignment)
```php
GoogleMap::create($request->all());   // GoogleMapController@add, @store
```
`$request->all()` ยัดทุก field โดยไม่ตรวจสอบ → เสี่ยงและไม่มีการเช็คความถูกต้อง
**แก้:** ใช้ `$request->validate([...])` + `$request->only([...])`

### 2. `Contact` model สะกด `$fillable` ผิด → mass assignment พัง
```php
protected $fillbale = ['name', 'email', 'title', 'message'];  // ❌
```
ควรเป็น `$fillable` — ตอนนี้ `Contact::create()` อาจ throw `MassAssignmentException`
นอกจากนี้ `protected $hidden;` ประกาศลอย ๆ ควรลบ

### 3. `GoogleMapController@store` redirect ไป route ที่ไม่มี
```php
return redirect('/maps')->...   // ไม่มี route /maps → 404
```
ควรเป็น `/map` หรือ `/google/add`

---

## 🟠 Medium

### 4. Model/คอลัมน์ไม่ตรงกับตารางจริง (`GoogleMap`)
- `$fillable` มี `city` แต่ตาราง `animals` ไม่มี
- `index()` อ่าน `$value->title` / `$value->description` ที่ไม่มีในตาราง

### 5. หลาย model แชร์ตาราง `animals` (`Pet`, `Admin`, `GoogleMap`)
สับสนและแก้ยาก — ควรรวมเป็น model เดียวหรือแยกตารางตามความหมาย

### 6. หน้า admin ไม่มี `auth` middleware
route `/dog`, `/message`, `/contact` เข้าถึง CRUD ได้โดยไม่ต้องล็อกอิน

### 7. Route ซ้ำ/import ไม่ใช้
- `Route::resource('/', 'PetController')` ทับ `Route::get('/', ...)` + สร้าง route ที่ไม่ implement
- `use Symfony\Component\Console\Input\Input;` ใน `routes/web.php` ไม่ถูกใช้

---

## 🟡 Low

- Laravel 8 หมด security support → วางแผนอัปเกรด
- `minimum-stability: dev` ควรเป็น `stable`
- resource method ที่เป็น stub (`//`) และโค้ด comment ทิ้ง — ควรลบ
- ตรวจว่า `.env` ไม่ถูก commit
- Google Maps API key ควรจำกัด referrer + ย้ายไป config

---

## ✅ จุดที่ทำได้ดี
- โครงสร้างโฟลเดอร์ตามมาตรฐาน Laravel
- แยก view ตามส่วนงานชัดเจน
- ใช้ `$fillable` ป้องกัน mass assignment (ยกเว้นจุด typo)
- ใช้ `findOrFail()` ในหน้ารายละเอียด

## สรุปลำดับการแก้
1. typo `$fillbale` → `$fillable` (ข้อ 2)
2. validation ทุก action ที่บันทึก (ข้อ 1)
3. redirect `/maps` (ข้อ 3)
4. `GoogleMap` column (ข้อ 4)
5. auth middleware หน้า admin (ข้อ 6)
6. เก็บกวาด route/import (ข้อ 7)

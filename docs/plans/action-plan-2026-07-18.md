# แผนงานถัดไป (Action Plan) — 18 ก.ค. 2026

> หมวด: **แผนงาน (Planning)**
> อัปเดตล่าสุด: 18 ก.ค. 2026

แผนแก้ไขเร่งด่วนจากผลรีวิว [../reports/2026-07-18-code-review.md](../reports/2026-07-18-code-review.md)

## สรุปสถานะรวม

| Phase | เรื่อง | สถานะ |
| --- | --- | --- |
| 0 | เปลี่ยนชื่อโปรเจค + จัดเอกสาร | ✅ เสร็จ |
| 1 | แก้บั๊กความถูกต้อง | ❌ ยังไม่เริ่ม |
| 2 | เพิ่ม validation + ป้องกัน admin | ❌ ยังไม่เริ่ม |
| 3 | เก็บกวาดโค้ด | ❌ ยังไม่เริ่ม |

## Phase 0: เปลี่ยนชื่อ + เอกสาร ✅
- [x] `composer.json` → `pawtrack/pawtrack`
- [x] `APP_NAME=PawTrack`, docker container `pawtrack`
- [x] เอกสาร root (README/CLAUDE/CONTRIBUTING/CHANGELOG) + `docs/` 6 หมวด

## Phase 1: แก้บั๊กความถูกต้อง ❌
1. [ ] `app/Models/Contact.php`: `$fillbale` → `$fillable`
2. [ ] `GoogleMapController@store`: `redirect('/maps')` → `redirect('/map')`
3. [ ] `GoogleMap` + `GoogleMapController@index`: ใช้คอลัมน์จริง (`name`, `location`) แทน `city`/`title`/`description`
4. [ ] ทดสอบ: หน้าแรก / รายละเอียดสุนัข / ข่าว / แผนที่ / ส่งฟอร์มติดต่อ / admin CRUD

## Phase 2: validation + ป้องกัน admin ❌
1. [ ] เพิ่ม `$request->validate([...])` ใน `ContactController@store`
2. [ ] เพิ่ม validation ใน `GoogleMapController@add/@store`
3. [ ] เพิ่ม validation ใน `AdminController` (dog CRUD) และ `NewsController`
4. [ ] ครอบ route admin ด้วย `Route::middleware('auth')->group(...)`
5. [ ] ตรวจ `.env` ไม่ถูก track (`git ls-files --error-unmatch .env`)

## Phase 3: เก็บกวาดโค้ด ❌
1. [ ] ลบ `use Symfony\Component\Console\Input\Input;` ใน `routes/web.php`
2. [ ] ลบ/ปรับ `Route::resource('/', 'PetController')` ที่ทับซ้อน
3. [ ] ลบ resource method stub + route ที่ชี้มา
4. [ ] ลบโค้ด comment ทิ้งใน `PetController@map`

## เกณฑ์ถือว่าเสร็จ (Definition of Done)
- [ ] ส่งฟอร์มติดต่อแล้วบันทึกลง DB ได้จริง
- [ ] หน้าแผนที่แสดงข้อมูลถูกต้อง ไม่ 404 หลังบันทึก
- [ ] ทุก action ที่บันทึกข้อมูลมี validation
- [ ] หน้า admin เข้าถึงได้เฉพาะผู้ล็อกอิน
- [ ] `php artisan test` ผ่าน

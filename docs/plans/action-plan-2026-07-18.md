# แผนงานถัดไป (Action Plan) — 18 ก.ค. 2026

> หมวด: **แผนงาน (Planning)**
> อัปเดตล่าสุด: 18 ก.ค. 2026

แผนแก้ไขเร่งด่วนจากผลรีวิว [../reports/2026-07-18-code-review.md](../reports/2026-07-18-code-review.md)

## สรุปสถานะรวม

| Phase | เรื่อง | สถานะ |
| --- | --- | --- |
| 0 | เปลี่ยนชื่อโปรเจค + จัดเอกสาร | ✅ เสร็จ |
| 1 | แก้บั๊กความถูกต้อง | ✅ เสร็จ |
| 2 | เพิ่ม validation + ป้องกัน admin | ✅ เสร็จ |
| 3 | เก็บกวาดโค้ด | ❌ ยังไม่เริ่ม |

## Phase 0: เปลี่ยนชื่อ + เอกสาร ✅
- [x] `composer.json` → `pawtrack/pawtrack`
- [x] `APP_NAME=PawTrack`, docker container `pawtrack`
- [x] เอกสาร root (README/CLAUDE/CONTRIBUTING/CHANGELOG) + `docs/` 6 หมวด

## Phase 1: แก้บั๊กความถูกต้อง ✅
1. [x] `app/Models/Contact.php`: `$fillbale` → `$fillable`
2. [x] `GoogleMapController@store`: `redirect('/maps')` → `redirect('/map')`
3. [x] `GoogleMap` + `GoogleMapController@index`: ใช้คอลัมน์จริง (`name`, `location`) แทน `city`/`title`/`description` (แก้ `resources/views/pages/google-map.blade.php` ให้ตรงกันด้วย)
4. [x] ทดสอบ: หน้าแรก / รายละเอียดสุนัข / ข่าว / แผนที่ / ส่งฟอร์มติดต่อ / admin CRUD (ผ่านทุกอย่างผ่าน docker stack)
5. [x] **พบเพิ่ม**: `ContactController` ครอบ `auth` middleware ทั้ง controller ทำให้ผู้เยี่ยมชมทั่วไปส่งฟอร์มติดต่อไม่ได้ (เด้งไป `/login`) — แก้เป็น `->except(['create', 'store'])` เพื่อให้ฟอร์มสาธารณะใช้งานได้ ตามเกณฑ์ DoD ข้อแรก

## Phase 2: validation + ป้องกัน admin ✅
1. [x] `ContactController@store` มี `$request->validate([...])` อยู่แล้ว (ตรวจสอบแล้ว ไม่ต้องแก้)
2. [x] เพิ่ม validation ใน `GoogleMapController@add/@store` (name/lat/lng) และเปลี่ยนจาก `$request->all()` เป็นค่าที่ validate แล้ว
3. [x] เพิ่ม validation ใน `AdminController` (dog CRUD: store/update) และ `NewsController` (store/update); เปลี่ยนจาก `$request->all()` เป็น `$request->only([...])`
   - หมายเหตุ: `image` เป็น `required` ตอน `store` (คอลัมน์ `animals.image` เป็น NOT NULL ไม่มี default — ก่อนหน้านี้ถ้าไม่แนบรูปจะพัง 500), เป็น `nullable` ตอน `update`
4. [x] ครอบ route admin ด้วย `Route::middleware('auth')->group(...)` — ใช้กับ `google/add` (GET+POST) ที่เดิมเปิดสาธารณะทั้งหมด ส่วน `dog`/`message`/`contact` มี `$this->middleware('auth')` ที่ controller อยู่แล้วจาก Phase ก่อน
5. [x] ตรวจ `.env` ไม่ถูก track (`git ls-files --error-unmatch .env` → not tracked, ผ่าน)
6. [x] **พบเพิ่มระหว่างทดสอบ**: `GoogleMapController@add/@store` ยังคง insert พังด้วย SQL error เพราะฟอร์มไม่ได้กรอกคอลัมน์ NOT NULL อื่น ๆ ของตาราง `animals` (species, gender, ฯลฯ) — ตัดสินใจร่วมกับผู้ใช้ให้ **บันทึกไว้เป็นข้อจำกัดที่รู้ (known limitation)** สำหรับ phase ถัดไป ไม่แก้ตอนนี้

## Phase 3: เก็บกวาดโค้ด ❌
1. [ ] ลบ `use Symfony\Component\Console\Input\Input;` ใน `routes/web.php`
2. [ ] ลบ/ปรับ `Route::resource('/', 'PetController')` ที่ทับซ้อน
3. [ ] ลบ resource method stub + route ที่ชี้มา
4. [ ] ลบโค้ด comment ทิ้งใน `PetController@map`

## เกณฑ์ถือว่าเสร็จ (Definition of Done)
- [x] ส่งฟอร์มติดต่อแล้วบันทึกลง DB ได้จริง
- [x] หน้าแผนที่แสดงข้อมูลถูกต้อง ไม่ 404 หลังบันทึก
- [x] ทุก action ที่บันทึกข้อมูลมี validation
- [x] หน้า admin เข้าถึงได้เฉพาะผู้ล็อกอิน
- [x] `php artisan test` ผ่าน

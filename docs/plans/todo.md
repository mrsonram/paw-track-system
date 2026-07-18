# PawTrack — Roadmap & TODO

> หมวด: **แผนงาน (Planning)**
> อัปเดตล่าสุด: 18 ก.ค. 2026

แผนงานหลักของโปรเจกต์ แยกเป็น phase — ✅ เสร็จ / 🔧 กำลังทำ / 💡 พิจารณา / ❌ ยังไม่เริ่ม

## สรุปสถานะรวม

| Phase | เรื่อง | สถานะ |
| --- | --- | --- |
| 0 | เปลี่ยนชื่อโปรเจค + จัดเอกสาร | ✅ เสร็จ |
| 1 | แก้บั๊ก/ความถูกต้อง (Correctness) | ✅ เสร็จ |
| 2 | ความปลอดภัย (Security & Validation) | 🔧 กำลังทำ |
| 3 | เก็บกวาดโค้ด (Cleanup) | ✅ เสร็จ |
| 4 | ต่อยอดฟีเจอร์ (Feature Enhancements) | 🔧 กำลังทำ |
| 5 | ยกระดับโครงสร้าง (Infra & Upgrade) | 💡 พิจารณา |

---

## 🟢 Phase 0: เปลี่ยนชื่อโปรเจค + จัดเอกสาร ✅
เป้าหมาย: ตั้งชื่อโปรเจกต์เป็น PawTrack และวางระบบเอกสาร

- [x] เปลี่ยนชื่อใน `composer.json` (`pawtrack/pawtrack`)
- [x] `APP_NAME=PawTrack` ใน `.env` / `.env.example`
- [x] เปลี่ยน docker `container_name`/`image` เป็น `pawtrack`
- [x] เขียน `README.md`, `CLAUDE.md`, `CONTRIBUTING.md`, `CHANGELOG.md`
- [x] วางโครงสร้าง `docs/` 6 หมวด + สารบัญ

---

## 🔴 Phase 1: แก้บั๊ก/ความถูกต้อง (Correctness) ✅
เป้าหมาย: ทำให้ flow ที่มีอยู่ทำงานถูกต้อง — ดูรายละเอียดใน [action-plan-2026-07-18.md](action-plan-2026-07-18.md)

- [x] **แก้ typo `$fillbale` → `$fillable`** ใน `app/Models/Contact.php` (ทำให้ contact form บันทึกได้)
- [x] **แก้ redirect `/maps` ที่ไม่มีอยู่** ใน `GoogleMapController@store` → ชี้ route จริง (`/map`)
- [x] **แก้ `GoogleMap` ให้ตรงกับคอลัมน์จริง** — เปลี่ยนจาก `city`/`title`/`description` เป็น `location`/`name` ให้ตรงตาราง `animals` (รวมถึง JS ใน `pages/google-map.blade.php`)
- [x] ทดสอบ flow: หน้าแรก, รายละเอียดสุนัข, ข่าว, แผนที่, ส่งฟอร์มติดต่อ, admin CRUD (ผ่าน docker stack ทั้งหมด)
- [x] **พบเพิ่มระหว่างทดสอบ**: `ContactController` ครอบ `auth` middleware ทั้ง controller ทำให้ผู้เยี่ยมชมส่งฟอร์มติดต่อไม่ได้ — แก้เป็น `->except(['create', 'store'])`

## 🟠 Phase 2: ความปลอดภัย (Security & Validation) 🔧
เป้าหมาย: ปิดช่องโหว่พื้นฐาน — ดู [../security/recommendations.md](../security/recommendations.md)

- [x] เพิ่ม `$request->validate([...])` ในทุก action ที่บันทึกข้อมูล
  - [x] `GoogleMapController@add`, `@store`
  - [x] `ContactController@store` (มีอยู่แล้วจากก่อนหน้านี้)
  - [x] `AdminController` (dog CRUD: store/update)
  - [x] `NewsController` (store/update)
- [x] เลิกใช้ `$request->all()` → ใช้ `$request->only([...])` หรือ validated data
- [x] ตรวจว่า `.env` (มี `APP_KEY`/รหัส DB) ไม่ถูก track ใน git (`git ls-files .env` → not tracked, ผ่าน)
- [x] จำกัดสิทธิ์เข้าถึงหน้า admin ด้วย middleware `auth` — เพิ่มให้ `google/add` (GET+POST) ที่เดิมเปิดสาธารณะทั้งหมด (`dog`/`message`/`contact` มีอยู่แล้ว)
- [ ] จำกัดสิทธิ์ Google Maps API key (HTTP referrer) + ย้ายไปอ่านจาก config/env — **ยังไม่ทำ**, ต้องตั้งค่าใน Google Cloud Console (นอกเหนือจากโค้ด)
- [x] **แก้แล้ว**: `GoogleMapController@add/@store` เคย insert พังด้วย SQL error เพราะฟอร์มไม่กรอกคอลัมน์ NOT NULL อื่น ๆ ของ `animals` — เพิ่ม migration `2026_07_18_000000_make_animal_profile_columns_nullable` ทำให้คอลัมน์ที่ไม่เกี่ยวกับ map pin (species/marking/gender/collar/age/status/vet/owner/image/location) เป็น nullable, เหลือแค่ `name`/`lat`/`lng` ที่ required ระดับ DB ทดสอบแล้วว่า insert ผ่านจริง

## 🟡 Phase 3: เก็บกวาดโค้ด (Cleanup) 🔧
เป้าหมาย: ลดหนี้ทางเทคนิค อ่านโค้ดง่ายขึ้น

- [x] ลบ import ที่ไม่ใช้ใน `routes/web.php` (`Symfony\Component\Console\Input\Input`, `App\Models\Pet`, `TesterController`, `AutoAddressController`)
- [x] ลบ `Route::resource('/', 'PetController')` ที่ทับซ้อน (เดิมสร้าง route `{}` พังเพราะไม่มีชื่อ parameter)
- [x] ลบ resource method ที่เป็น stub (`//`) และ route ที่ชี้มา — ขยายครอบคลุมทั้ง `PetController`, `ContactController` (ปรับเหลือ `->only(['index','store','show','destroy'])`), และ `GoogleMapController` (ลบทั้ง `index()` + view กำพร้า `pages/google-map.blade.php` ด้วย)
- [x] ลบโค้ดที่ comment ทิ้ง (เช่นใน `PetController@map`)
- [x] รวม/ลดจำนวน model ที่ชี้ตาราง `animals` ซ้ำ — ลบ `Pet`/`Admin`/`GoogleMap` (3 คลาสที่มี `$table`/`$primaryKey` เหมือนกันทุกตัว) เหลือ `Animal` เดียว, อัปเดต `PetController`/`AdminController`/`GoogleMapController` ให้ใช้ตัวเดียวกัน

## 🔵 Phase 4: ต่อยอดฟีเจอร์ (Feature Enhancements) 🔧
เป้าหมาย: เพิ่มคุณค่าให้ระบบ (เลือกทำตามเวลาที่มี)

- [x] ค้นหา/กรองสุนัขตามสายพันธุ์/สถานะ — เพิ่มฟอร์มค้นหาใน `/info` (species แบบ text + status
  แบบ dropdown ตาม enum จริงที่ใช้อยู่: มีชีวิตอยู่/เสียชีวิตแล้ว — ของเดิมที่ระบุ "adopted, lost,
  found" ไม่ตรงกับ schema จริงที่มีอยู่ จึงปรับให้เข้ากับคอลัมน์ `status` ที่มีจริง)
- [x] อัปโหลดรูปสุนัขจริง — ทำไปแล้วตอน Phase 2 (`AdminController@store/update` ใช้
  `$request->file('image')->store(...)` จริง ไม่ใช่ path string เปล่า)
- [x] Pagination หน้ารายการสุนัข/ข่าว — เพิ่มใน `/info` (9/หน้า), `/news` (9/หน้า, ยังคง layout
  carousel เดิม), และหน้า admin `/dog` (15/หน้า) ด้วย ใช้ Bootstrap pagination views
  (`Paginator::useBootstrap()` ใน `AppServiceProvider`)
- [x] แจ้งเตือนอีเมลเมื่อมีข้อความติดต่อใหม่ — เพิ่ม `App\Mail\NewContactMessage`, ส่งไปที่
  `config('mail.admin_address')` (env `MAIL_ADMIN_ADDRESS`, fallback เป็น `MAIL_FROM_ADDRESS`)
  หลัง `ContactController@store` บันทึกสำเร็จ; แก้ `.env`/`.env.example` ที่ `MAIL_MAILER=smtp`
  ชี้ไป mailhog ที่ไม่มีอยู่จริงในสแตก (ไม่มี service นี้ใน `docker-compose.yml`) เป็น
  `MAIL_MAILER=log` เพื่อให้ใช้งานได้จริงโดยไม่ต้องตั้งค่าเพิ่ม ทดสอบแล้วว่าอีเมลถูกเขียนลง log จริง
- [x] หน้า dashboard admin แสดงสถิติ — เพิ่มการ์ดสถิติใน `admin/home.blade.php`
  (`HomeController@index`): จำนวนสุนัขทั้งหมด/มีชีวิตอยู่/เสียชีวิตแล้ว, จำนวนข่าว, จำนวนข้อความติดต่อ
- [x] ปักหมุดตำแหน่งสุนัขบนแผนที่จากพิกัด `lat`/`lng` ที่มีอยู่แล้ว — **มีอยู่แล้วในโค้ดเดิม**
  (`PetController@map` + `pet/map.blade.php` วนลูป `$animals` และปักหมุดทุกตัวที่มี `lat` อยู่แล้ว)
  ตรวจสอบแล้วว่าทำงานถูกต้อง ไม่ต้องเขียนใหม่
- [x] **พบระหว่างทำ**: `pet/info.blade.php` และ `admin/dogs/dog.blade.php` มีบั๊ก variable
  shadowing (`@foreach($animals as $animals)` ทับตัวแปร collection เดิมด้วย item เดี่ยว) ทำให้
  เรียก `{{ $animals->links() }}` หลัง loop ไม่ได้ (error "Call to undefined method
  Animal::links()") — แก้โดยเปลี่ยนตัวแปรใน loop เป็น `$animal` (เอกพจน์)
- [x] **พบระหว่างทำ**: `AdminController@index` มีเงื่อนไข `orWhere("type", ...)` อ้างคอลัมน์
  `type` ที่ไม่มีในตาราง `animals` (query จะพังถ้ามีเงื่อนไข search) — ลบออกระหว่างปรับ query ให้
  รองรับ pagination

## 🟣 Phase 5: ยกระดับโครงสร้าง (Infra & Upgrade) 💡
เป้าหมาย: ความยั่งยืนระยะยาว

- [ ] วางแผนอัปเกรด Laravel 8 (หมด support) → เวอร์ชันที่ยัง support (จะปลดล็อกให้ใช้ PHP 8.1+ ได้)
- [ ] เปลี่ยน `minimum-stability` จาก `dev` เป็น `stable`
- [ ] เพิ่มเทสต์จริง (Feature test สำหรับหน้า public + admin)
- [ ] แยกตาราง `map_locations` ออกจาก `animals` ให้ชัดเจน
- [x] ~~ตั้งค่า database service ใน `docker-compose.yml`~~ ✅ ทำแล้ว — nginx + php-fpm(8.0) + mysql
      ครบ, สร้าง `paw-track-dev`/`paw-track-prod`, migrate + seed user (ดู [../deployment/docker.md](../deployment/docker.md))

---

**สถานะปัจจุบัน**: Phase 0-1-3 เสร็จแล้ว, Phase 2/4 เกือบเสร็จ (Phase 2 เหลือแค่จำกัดสิทธิ์ Google
Maps API key ซึ่งต้องตั้งค่านอกโค้ดใน Google Cloud Console; Phase 4 ทำครบทุกข้อในลิสต์เดิมแล้ว
เหลือแค่พิจารณาเพิ่มเติมถ้าต้องการ) — พร้อมพิจารณา Phase 5 ต่อ
**เอกสารอ้างอิง**:
- แผนแก้เร่งด่วน: [action-plan-2026-07-18.md](action-plan-2026-07-18.md)
- ผลรีวิว: [../reports/2026-07-18-code-review.md](../reports/2026-07-18-code-review.md)
- ความปลอดภัย: [../security/recommendations.md](../security/recommendations.md)

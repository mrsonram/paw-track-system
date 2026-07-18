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
| 3 | เก็บกวาดโค้ด (Cleanup) | 🔧 กำลังทำ |
| 4 | ต่อยอดฟีเจอร์ (Feature Enhancements) | 💡 พิจารณา |
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
- [ ] **พบเพิ่มระหว่างทดสอบ**: `GoogleMapController@add/@store` ยัง insert พังด้วย SQL error เพราะฟอร์มไม่กรอกคอลัมน์ NOT NULL อื่น ๆ ของ `animals` (species, gender, ฯลฯ) — บันทึกเป็นข้อจำกัดที่รู้ไว้ก่อน ไม่แก้ในรอบนี้

## 🟡 Phase 3: เก็บกวาดโค้ด (Cleanup) 🔧
เป้าหมาย: ลดหนี้ทางเทคนิค อ่านโค้ดง่ายขึ้น

- [x] ลบ import ที่ไม่ใช้ใน `routes/web.php` (`Symfony\Component\Console\Input\Input`, `App\Models\Pet`, `TesterController`, `AutoAddressController`)
- [x] ลบ `Route::resource('/', 'PetController')` ที่ทับซ้อน (เดิมสร้าง route `{}` พังเพราะไม่มีชื่อ parameter)
- [x] ลบ resource method ที่เป็น stub (`//`) และ route ที่ชี้มา — ขยายครอบคลุมทั้ง `PetController`, `ContactController` (ปรับเหลือ `->only(['index','store','show','destroy'])`), และ `GoogleMapController` (ลบทั้ง `index()` + view กำพร้า `pages/google-map.blade.php` ด้วย)
- [x] ลบโค้ดที่ comment ทิ้ง (เช่นใน `PetController@map`)
- [ ] รวม/ลดจำนวน model ที่ชี้ตาราง `animals` ซ้ำ (`Pet`/`Admin`/`GoogleMap`) — ยังไม่ทำ เป็นงานปรับโครงสร้างที่ใหญ่กว่ารอบนี้

## 🔵 Phase 4: ต่อยอดฟีเจอร์ (Feature Enhancements) 💡
เป้าหมาย: เพิ่มคุณค่าให้ระบบ (เลือกทำตามเวลาที่มี)

- [ ] ค้นหา/กรองสุนัขตามสายพันธุ์/สถานะ (adopted, lost, found)
- [ ] อัปโหลดรูปสุนัขจริง (ตอนนี้เก็บเป็น path string)
- [ ] Pagination หน้ารายการสุนัข/ข่าว
- [ ] แจ้งเตือนอีเมลเมื่อมีข้อความติดต่อใหม่
- [ ] หน้า dashboard admin แสดงสถิติ (จำนวนสุนัขแยกตามสถานะ)
- [ ] ปักหมุดตำแหน่งสุนัขบนแผนที่จากพิกัด `lat`/`lng` ที่มีอยู่แล้ว

## 🟣 Phase 5: ยกระดับโครงสร้าง (Infra & Upgrade) 💡
เป้าหมาย: ความยั่งยืนระยะยาว

- [ ] วางแผนอัปเกรด Laravel 8 (หมด support) → เวอร์ชันที่ยัง support (จะปลดล็อกให้ใช้ PHP 8.1+ ได้)
- [ ] เปลี่ยน `minimum-stability` จาก `dev` เป็น `stable`
- [ ] เพิ่มเทสต์จริง (Feature test สำหรับหน้า public + admin)
- [ ] แยกตาราง `map_locations` ออกจาก `animals` ให้ชัดเจน
- [x] ~~ตั้งค่า database service ใน `docker-compose.yml`~~ ✅ ทำแล้ว — nginx + php-fpm(8.0) + mysql
      ครบ, สร้าง `paw-track-dev`/`paw-track-prod`, migrate + seed user (ดู [../deployment/docker.md](../deployment/docker.md))

---

**สถานะปัจจุบัน**: Phase 0-1 เสร็จแล้ว, Phase 2/3 เกือบเสร็จ (เหลือ: จำกัดสิทธิ์ Google Maps API key, และรวมโมเดล `Pet`/`Admin`/`GoogleMap`) — พร้อมพิจารณา Phase 4/5 ต่อ
**เอกสารอ้างอิง**:
- แผนแก้เร่งด่วน: [action-plan-2026-07-18.md](action-plan-2026-07-18.md)
- ผลรีวิว: [../reports/2026-07-18-code-review.md](../reports/2026-07-18-code-review.md)
- ความปลอดภัย: [../security/recommendations.md](../security/recommendations.md)

# Security — Recommendations

> หมวด: **ความปลอดภัย (Security)**
> อัปเดตล่าสุด: 18 ก.ค. 2026

ข้อเสนอแนะด้านความปลอดภัยของ PawTrack พร้อมสถานะ — อ้างอิงจาก [../reports/2026-07-18-code-review.md](../reports/2026-07-18-code-review.md)

| # | ประเด็น | ระดับ | สถานะ |
| --- | --- | --- | --- |
| 1 | ไม่ validate input ก่อนบันทึก (`$request->all()`) | 🔴 สูง | ✅ แก้แล้ว |
| 2 | หน้า admin ไม่มี `auth` middleware ป้องกัน | 🔴 สูง | ✅ แก้แล้ว |
| 3 | `.env` มี `APP_KEY` + รหัส DB — ต้องไม่ถูก commit | 🟠 กลาง | ✅ ตรวจแล้ว ไม่ถูก track |
| 4 | Google Maps API key ฝังใน Blade | 🟠 กลาง | 🔧 แก้โค้ดแล้ว, เหลือขั้น Console |
| 5 | Laravel 8 หมด security support | 🟡 ต่ำ | 💡 วางแผน |

## 1. Validation & Mass Assignment 🔴 ✅ แก้แล้ว

หลาย controller เคยบันทึกข้อมูลผู้ใช้ตรง ๆ โดยไม่ validate — ตอนนี้ทุก action ที่บันทึกข้อมูล
(`GoogleMapController@add/@store`, `ContactController@store`, `AdminController`,
`NewsController`) มี `$request->validate([...])` และส่งเฉพาะ field ที่ validate แล้ว
(`$request->only([...])` หรือ array ที่ validate คืนมา) แทน `$request->all()`

## 2. ป้องกันหน้า Admin 🔴 ✅ แก้แล้ว

`AdminController`, `NewsController`, `ContactController` มี `$this->middleware('auth')`
ที่ constructor (เว้น action สาธารณะ เช่น `ContactController@store`), และ `google/add`
(GET+POST) ครอบด้วย `Route::middleware('auth')->group(...)` ใน `routes/web.php`

## 3. ป้องกัน secret หลุด 🟠 ✅ ตรวจแล้ว

- `.env` อยู่ใน `.gitignore` และตรวจแล้วว่าไม่เคยถูก track:
  `git ls-files --error-unmatch .env` → error (not tracked) ✅
- ห้ามใส่ `APP_KEY` / รหัสผ่าน DB จริงในเอกสารหรือ `.env.example`

## 4. Google Maps API Key 🟠

- [x] ย้าย key ไปอ่านจาก config/env แทน hardcode ใน Blade — เพิ่ม
  `config('services.google_maps.key')` (อ่านจาก `GOOGLE_MAPS_API_KEY` ใน `.env`) ใน
  `config/services.php`, แก้ 4 ไฟล์ที่เคย hardcode key ตรง ๆ
  (`resources/views/theme/mdb.blade.php`, `pet/map.blade.php`, `google/app.blade.php`,
  `google/view.blade.php`) ให้ใช้ค่าจาก config แทน ทดสอบแล้วว่า key ยัง render ถูกต้องบน
  live docker stack และเทสต์ทั้ง 41 ตัวยังผ่าน
- [ ] จำกัดสิทธิ์ key ด้วย HTTP referrer restriction บน Google Cloud Console — **ยังไม่ทำ**,
  ต้องทำนอกโค้ดโดยเจ้าของบัญชี Google Cloud ที่ออก key นี้:
  1. เปิด [Google Cloud Console](https://console.cloud.google.com/apis/credentials) →
     เลือกโปรเจกต์ที่ออก key `AIzaSyBaAcT7gUSkl38sCZazn96anMb6ivCLXYA`
  2. เปิดหน้า credential ของ key นี้ → **Application restrictions** → เลือก
     **HTTP referrers (web sites)**
  3. เพิ่ม referrer ที่อนุญาต เช่น `http://localhost:8000/*`, โดเมน/พอร์ตของ Docker ที่ใช้จริง
     (ดู [../deployment/docker.md](../deployment/docker.md)), และโดเมน production จริงถ้ามี
  4. (แนะนำ) ที่ **API restrictions** จำกัดให้ key นี้ใช้ได้เฉพาะ Maps JavaScript API +
     Places API เท่านั้น
  5. บันทึก แล้วทดสอบว่าแผนที่ยังโหลดได้จากโดเมนที่อนุญาต และถูกบล็อกจากโดเมนอื่น
  - หมายเหตุ: key เดิมถูก commit ไว้ในเอกสาร/`.env` มานาน ถือว่าเป็น public แล้ว —
    ถ้าต้องการความปลอดภัยสูงสุด ควรออก key ใหม่ใน Console แล้วตั้ง restriction ตั้งแต่ต้น
    แทนการจำกัด key เดิม

## 5. อัปเกรดเฟรมเวิร์ก 🟡

Laravel 8 สิ้นสุด security support แล้ว — วางแผนอัปเกรดเป็นเวอร์ชันที่ยัง support

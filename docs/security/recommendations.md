# Security — Recommendations

> หมวด: **ความปลอดภัย (Security)**
> อัปเดตล่าสุด: 18 ก.ค. 2026

ข้อเสนอแนะด้านความปลอดภัยของ PawTrack พร้อมสถานะ — อ้างอิงจาก [../reports/2026-07-18-code-review.md](../reports/2026-07-18-code-review.md)

| # | ประเด็น | ระดับ | สถานะ |
| --- | --- | --- | --- |
| 1 | ไม่ validate input ก่อนบันทึก (`$request->all()`) | 🔴 สูง | ❌ ยังไม่แก้ |
| 2 | หน้า admin ไม่มี `auth` middleware ป้องกัน | 🔴 สูง | ❌ ยังไม่แก้ |
| 3 | `.env` มี `APP_KEY` + รหัส DB — ต้องไม่ถูก commit | 🟠 กลาง | ⚠️ ตรวจสอบ |
| 4 | Google Maps API key ฝังใน Blade | 🟠 กลาง | ❌ ยังไม่แก้ |
| 5 | Laravel 8 หมด security support | 🟡 ต่ำ | 💡 วางแผน |

## 1. Validation & Mass Assignment 🔴

หลาย controller บันทึกข้อมูลผู้ใช้ตรง ๆ:
```php
GoogleMap::create($request->all());   // ไม่ validate, ไม่จำกัด field
```
**แก้:** ใช้ `$request->validate([...])` และส่งเฉพาะ field ที่ต้องการ
```php
$data = $request->validate([
    'name' => 'required|string|max:255',
    'lat'  => 'required|numeric',
    'lng'  => 'required|numeric',
]);
GoogleMap::create($data);
```
ใช้กับ: `GoogleMapController@add/@store`, `ContactController@store`, `AdminController`, `NewsController`

## 2. ป้องกันหน้า Admin 🔴

route กลุ่ม `/dog`, `/message`, `/contact` (admin) ไม่มี `auth` middleware → ใครก็เข้าถึง CRUD ได้
**แก้:**
```php
Route::middleware('auth')->group(function () {
    Route::resource('dog', 'AdminController');
    // ...
});
```

## 3. ป้องกัน secret หลุด 🟠

- `.env` อยู่ใน `.gitignore` แล้ว (ดี) — แต่ต้องตรวจว่าไม่เคยถูก commit:
  ```bash
  git ls-files --error-unmatch .env   # ถ้าไม่ error = ยังถูก track → git rm --cached .env
  ```
- ห้ามใส่ `APP_KEY` / รหัสผ่าน DB จริงในเอกสารหรือ `.env.example`

## 4. Google Maps API Key 🟠

- ย้าย key ไปอ่านจาก config/env แทน hardcode ใน Blade
- จำกัดสิทธิ์ key ด้วย HTTP referrer restriction บน Google Cloud Console

## 5. อัปเกรดเฟรมเวิร์ก 🟡

Laravel 8 สิ้นสุด security support แล้ว — วางแผนอัปเกรดเป็นเวอร์ชันที่ยัง support

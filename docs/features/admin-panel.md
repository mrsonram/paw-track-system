# Feature — Admin Panel (หน้าจัดการ)

> สถานะ: 📄 ใช้งานได้บางส่วน — ยังไม่มี auth guard + validation
> อัปเดตล่าสุด: 18 ก.ค. 2026

ส่วนผู้ดูแลระบบสำหรับจัดการข้อมูลสุนัข ข่าว และดูข้อความติดต่อ

## ภาพรวม

| ส่วน | Route | Controller | View |
| --- | --- | --- | --- |
| จัดการสุนัข | resource `/dog` | `AdminController` | `admin/dogs/*` |
| จัดการข่าว | resource `/message` | `NewsController` | `admin/news/*` |
| ดูข้อความติดต่อ | resource `/contact` | `ContactController` | `admin/contacts/*` |
| Dashboard | — | — | `admin/dashboard.blade.php` |

Model `Admin` map ไปตาราง `animals` (ใช้ร่วมกับ `Pet`, `GoogleMap`)

## 🔒 ความเสี่ยงที่ต้องแก้

1. **ไม่มี middleware `auth`** ครอบ route admin → ใครก็เข้าถึง CRUD ได้
   ควรจัดกลุ่ม route ด้วย `Route::middleware('auth')->group(...)` หรือ middleware ตรวจ role
2. **ไม่มี validation** ตอนสร้าง/แก้ไขข้อมูล
3. resource route หลาย method อาจยังเป็น stub — ตรวจว่า implement ครบ

## ข้อควรปรับปรุง

- [ ] ครอบ route admin ด้วย `auth` middleware (+ role admin ถ้ามี)
- [ ] เพิ่ม validation ทุก action ที่เขียนข้อมูล
- [ ] Dashboard แสดงสถิติ (จำนวนสุนัขแยกตาม `status`)
- [ ] ยืนยันก่อนลบ (confirm dialog)

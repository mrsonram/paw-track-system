# Feature — Contact Form (ฟอร์มติดต่อ)

> สถานะ: ⚠️ มีบั๊ก (typo `$fillable`) — ต้องแก้ก่อนใช้งานจริง
> อัปเดตล่าสุด: 18 ก.ค. 2026

ฟอร์มให้ผู้ใช้ส่งข้อความถึงผู้ดูแลระบบ

## ภาพรวม

- ข้อมูลเก็บในตาราง **`contacts`** ผ่าน model `App\Models\Contact`
- จัดการผ่าน resource route `/contact` (`ContactController`)
- ฝั่ง Admin ดูข้อความได้ที่ `resources/views/admin/contacts/*`

## Data model (`contacts`)

`name, email, title, message` — `$timestamps = false`

## 🐞 บั๊กที่ต้องแก้

1. **`app/Models/Contact.php` สะกด `$fillbale` ผิด** (ควรเป็น `$fillable`)
   ทำให้ mass assignment ไม่ทำงาน → `Contact::create()` อาจ throw `MassAssignmentException` หรือบันทึกไม่ครบ
   ```php
   // ❌ ปัจจุบัน
   protected $fillbale = ['name', 'email', 'title', 'message'];
   // ✅ ควรเป็น
   protected $fillable = ['name', 'email', 'title', 'message'];
   ```
2. `protected $hidden;` ประกาศไว้แต่ไม่กำหนดค่า → ควรลบหรือกำหนดเป็น array

## ข้อควรปรับปรุง

- [ ] แก้ typo `$fillable` (บล็อกการใช้งาน)
- [ ] เพิ่ม `$request->validate([...])` (`email` ต้องเป็นอีเมล, ทุก field required)
- [ ] แจ้งเตือนอีเมลถึงผู้ดูแลเมื่อมีข้อความใหม่
- [ ] ป้องกันสแปม (throttle / captcha)

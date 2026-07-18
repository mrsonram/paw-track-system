# Feature — News System (ข่าวสาร)

> สถานะ: ✅ ใช้งานได้
> อัปเดตล่าสุด: 18 ก.ค. 2026

ระบบข่าว/ประกาศ แสดงรายการและรายละเอียดข่าว

## ภาพรวม

- ข้อมูลเก็บในตาราง **`news`** ผ่าน model `App\Models\News`
- ฝั่งผู้ใช้: หน้า `/news` และ `/news/show/{id}`
- ฝั่งผู้ดูแล: จัดการผ่าน resource route `/message` (`NewsController`)

## Routes & Controller

| Route | Method | คำอธิบาย |
| --- | --- | --- |
| `/news` | `PetController@news` | รายการข่าว (`News::get()`) |
| `/news/show/{id}` | `PetController@message` | รายละเอียดข่าว (`News::findOrFail`) |
| `/message` | resource `NewsController` | CRUD ข่าว (Admin) |

Views: `resources/views/pet/news.blade.php`, `pet/news/show.blade.php`, `admin/news/*`

## Data model (`news`)

`title, subtitle, detail` — `$timestamps = false`

## ข้อควรปรับปรุง

- [ ] เพิ่ม validation ใน `NewsController@store/@update`
- [ ] Pagination หน้ารายการข่าว
- [ ] เพิ่มวันที่เผยแพร่ (เปิด `$timestamps` หรือเพิ่มคอลัมน์ `published_at`)

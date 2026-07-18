# Feature — Pet Catalog (รายการสุนัข)

> สถานะ: ✅ ใช้งานได้
> อัปเดตล่าสุด: 18 ก.ค. 2026

แสดงรายการสุนัขและรายละเอียดรายตัว เป็นฟีเจอร์หลักของ PawTrack

## ภาพรวม

- ข้อมูลเก็บในตาราง **`animals`** ผ่าน model `App\Models\Pet`
- หน้าแรก (`/`) แสดงรายการทั้งหมด, หน้า `/info` แสดงเรียงตามชื่อ
- หน้า `/pet/show/{id}` แสดงรายละเอียดรายตัว (ใช้ `findOrFail`)

## Routes & Controller

| Route | Method | คำอธิบาย |
| --- | --- | --- |
| `/` | `PetController@index` | รายการสุนัขทั้งหมด (`Pet::get()`) |
| `/info` | `PetController@info` | รายการเรียงตามชื่อ (`->sortBy('name')`) |
| `/pet/show/{id}` | `PetController@show` | รายละเอียด (`Pet::findOrFail($id)`) |

Views: `resources/views/pet/index.blade.php`, `pet/info.blade.php`, `pet/show.blade.php`

## Data model (`animals`)

`name, species, marking, gender, collar, age, status, vet, owner, image, location, lat, lng`

> หมายเหตุ: `Pet` ตั้ง `$timestamps = false` และมี `$fillable` ครบทุกคอลัมน์

## ข้อควรปรับปรุง

- [ ] เพิ่มการค้นหา/กรองตาม `species` และ `status`
- [ ] Pagination (`Pet::paginate(12)`) เมื่อข้อมูลเยอะ
- [ ] จัดการรูปภาพจริง (upload) แทนการเก็บ path เป็น string
- [ ] เพิ่ม validation ตอนสร้าง/แก้ไข (ผ่าน Admin panel)

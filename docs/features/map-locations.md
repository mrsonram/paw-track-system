# Feature — Map Locations (แผนที่ Google Maps)

> สถานะ: ⚠️ column ไม่ตรงกับตารางจริง + redirect เสีย
> อัปเดตล่าสุด: 18 ก.ค. 2026

แสดงตำแหน่งสุนัขบน Google Maps จากพิกัด `lat`/`lng`

## ภาพรวม

- Model `App\Models\GoogleMap` map ไปตาราง **`animals`** (ตารางเดียวกับ `Pet`)
- `GoogleMapController@index` แปลงข้อมูลเป็น GeoJSON (`FeatureCollection`) ส่งให้ view `pages/google-map`
- หน้า `/google/add` (GET) แสดงฟอร์ม, POST บันทึก, `/google/{id}` แสดงรายตัว
- หน้า `/map` (`PetController@map`) แสดงแผนที่ฝั่งผู้ใช้

## 🐞 ปัญหาที่ต้องแก้

1. **คอลัมน์ไม่ตรงกับตาราง `animals`**
   - `$fillable` มี `city` แต่ตาราง `animals` ไม่มีคอลัมน์นี้
   - `index()` อ่าน `$value->title` และ `$value->description` ซึ่งตารางไม่มี (มี `name`, `location`)
   - ผล: properties บนแผนที่ (`name`, `city`) จะว่าง/ผิด
2. **`store()` redirect ไป `/maps`** ซึ่งไม่มี route → 404 (ควรเป็น `/map` หรือ `/google/add`)

## แนวทางแก้

```php
// index(): ใช้คอลัมน์จริง
$properties = ['name' => $value->name, 'location' => $value->location];
// store(): redirect ที่ถูกต้อง + validate
$data = $request->validate([
    'name' => 'required|string',
    'lat'  => 'required|numeric',
    'lng'  => 'required|numeric',
]);
GoogleMap::create($data);
return redirect('/map')->with('success', 'Add map success!');
```

## ข้อควรปรับปรุง

- [ ] แก้ column/redirect ตามด้านบน
- [ ] พิจารณาแยกตาราง `map_locations` ออกจาก `animals`
- [ ] ย้าย Google Maps API key ไปอ่านจาก config/env + จำกัด referrer

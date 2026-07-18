# Code Review — PawTrack

ผลรีวิวโค้ดฉบับเต็มถูกย้ายไปจัดหมวดใน `docs/` แล้ว:

- 📋 รายงานรีวิวเต็ม (High/Medium/Low): [`docs/reports/2026-07-18-code-review.md`](docs/reports/2026-07-18-code-review.md)
- 🔐 ข้อเสนอแนะความปลอดภัย + สถานะ: [`docs/security/recommendations.md`](docs/security/recommendations.md)
- 🗺️ แผนแก้ไขเร่งด่วน: [`docs/plans/action-plan-2026-07-18.md`](docs/plans/action-plan-2026-07-18.md)

## สรุปสิ่งที่ต้องแก้ก่อน (Top priority)

1. 🔴 `Contact` model สะกด `$fillbale` ผิด → contact form บันทึกไม่ได้
2. 🔴 หลาย controller บันทึก `$request->all()` โดยไม่ validate
3. 🔴 หน้า admin ไม่มี `auth` middleware ป้องกัน
4. 🔴 `GoogleMapController@store` redirect ไป `/maps` ที่ไม่มี → 404
5. 🟠 `GoogleMap` อ้างคอลัมน์ (`city`/`title`/`description`) ที่ไม่มีในตาราง `animals`

ดูสารบัญเอกสารทั้งหมดที่ [`docs/README.md`](docs/README.md)

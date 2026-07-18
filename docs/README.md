# 📚 Documentation Index — PawTrack 🐾

เอกสารทั้งหมดของโปรเจกต์ จัดเป็น 6 หมวด — **เอกสารใหม่ให้บันทึกลงโฟลเดอร์หมวดที่ตรง ห้ามวางที่ root ของ repo**
(ไฟล์ระดับ root มีเฉพาะ `README.md`, `CLAUDE.md`, `CONTRIBUTING.md`, `CHANGELOG.md`)

> โปรเจกต์: PawTrack — ระบบจัดการข้อมูลสุนัขจรจัด/ในชุมชน (Laravel 8, VRU VET Project)
> จัดโครงสร้างเอกสารเมื่อ 18 ก.ค. 2026

## 🗺️ plans/ — แผนงานและ TODO

| ไฟล์ | เนื้อหา |
| --- | --- |
| [todo.md](plans/todo.md) | Roadmap กลางของโปรเจกต์ แยกเป็น phase (สถานะปัจจุบัน + งานที่ควรทำต่อ) |
| [action-plan-2026-07-18.md](plans/action-plan-2026-07-18.md) | แผนแก้บั๊ก/ปรับปรุงเร่งด่วนจากผลรีวิว (validation, typo, redirect) |

## ⚙️ features/ — วิเคราะห์/ออกแบบรายฟีเจอร์ (สถานะอยู่หัวไฟล์)

| ไฟล์ | เนื้อหา | สถานะ |
| --- | --- | --- |
| [pet-catalog.md](features/pet-catalog.md) | รายการ/รายละเอียดสุนัข (ตาราง `animals`) | ✅ ใช้งานได้ |
| [news-system.md](features/news-system.md) | ระบบข่าวสาร | ✅ ใช้งานได้ |
| [contact-form.md](features/contact-form.md) | ฟอร์มติดต่อ | ⚠️ มีบั๊ก (typo `$fillable`) |
| [map-locations.md](features/map-locations.md) | แผนที่ Google Maps แสดงตำแหน่งสุนัข | ⚠️ column ไม่ตรง |
| [admin-panel.md](features/admin-panel.md) | หน้าจัดการฝั่งผู้ดูแล (CRUD) | 📄 บางส่วน |
| [authentication.md](features/authentication.md) | ระบบสมาชิก (`laravel/ui`) | ✅ ใช้งานได้ |

## 🧭 guides/ — คู่มือใช้งานและติดตั้ง

| ไฟล์ | เนื้อหา |
| --- | --- |
| [installation.md](guides/installation.md) | ติดตั้งและรันในเครื่อง (composer, npm, migrate, serve) |
| [development.md](guides/development.md) | Workflow การพัฒนา + วิธีเพิ่ม route/controller/model/view |

## 🚀 deployment/ — การ deploy และโครงสร้างพื้นฐาน

| ไฟล์ | เนื้อหา |
| --- | --- |
| [docker.md](deployment/docker.md) | รันผ่าน Docker (`Dockerfile`, `docker-compose.yml`) + ข้อควรระวังเรื่อง DB |

## 🔐 security/ — ความปลอดภัย

| ไฟล์ | เนื้อหา |
| --- | --- |
| [recommendations.md](security/recommendations.md) | ข้อเสนอแนะความปลอดภัย + สถานะ (validation, mass assignment, .env, API key) |

## 📋 reports/ — รายงานประวัติศาสตร์ (point-in-time, ไม่ต้องอัปเดต)

| ไฟล์ | เนื้อหา |
| --- | --- |
| [2026-07-18-code-review.md](reports/2026-07-18-code-review.md) | ผลรีวิวโค้ดรอบแรก (High/Medium/Low) |

---

## กติกาการเขียนเอกสาร

1. **ห้ามใส่ credential จริง** (รหัสผ่าน, API key, APP_KEY, อีเมลส่วนตัว) — ใช้ placeholder หรือ `<ดูใน .env>` เสมอ
2. เอกสารฟีเจอร์ใหม่ → `features/` หนึ่งไฟล์ต่อหนึ่งฟีเจอร์ (analysis + design + checklist รวมในไฟล์เดียว)
3. ใส่บรรทัด `> สถานะ:` และ `> อัปเดตล่าสุด:` ที่หัวไฟล์
4. รายงาน point-in-time → `reports/` ตั้งชื่อขึ้นต้นด้วยวันที่ `YYYY-MM-DD-`
5. แผนงาน/TODO → `plans/` เท่านั้น

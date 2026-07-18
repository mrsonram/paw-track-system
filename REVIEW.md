# Code Review — PawTrack

ผลรีวิวโค้ดฉบับเต็มถูกย้ายไปจัดหมวดใน `docs/` แล้ว:

- 📋 รายงานรีวิวเต็ม (High/Medium/Low): [`docs/reports/2026-07-18-code-review.md`](docs/reports/2026-07-18-code-review.md)
- 🔐 ข้อเสนอแนะความปลอดภัย + สถานะ: [`docs/security/recommendations.md`](docs/security/recommendations.md)
- 🗺️ แผนแก้ไขเร่งด่วน: [`docs/plans/action-plan-2026-07-18.md`](docs/plans/action-plan-2026-07-18.md)

## สถานะล่าสุด

Phase 1-3 ของ [แผนแก้ไขเร่งด่วน](docs/plans/action-plan-2026-07-18.md) เสร็จแล้วทั้งหมด
(bug ความถูกต้อง, validation + auth, เก็บกวาดโค้ด + รวมโมเดล `Pet`/`Admin`/`GoogleMap`
เป็น `Animal` เดียว) ดูรายละเอียดที่ [`docs/plans/todo.md`](docs/plans/todo.md)

ที่ยังเหลือ:
- 🟠 Google Maps API key ยัง hardcode ใน Blade — ต้องย้ายไป config/env และตั้ง HTTP
  referrer restriction บน Google Cloud Console
- 🟡 Laravel 8 หมด security support แล้ว — ยังไม่ได้วางแผนอัปเกรด

ดูสารบัญเอกสารทั้งหมดที่ [`docs/README.md`](docs/README.md)

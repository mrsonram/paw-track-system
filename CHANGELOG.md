# Changelog

บันทึกการเปลี่ยนแปลงที่สำคัญของโปรเจคนี้
รูปแบบอ้างอิงตาม [Keep a Changelog](https://keepachangelog.com/)

## [Unreleased]

### Changed
- **เปลี่ยนชื่อโปรเจคเป็น PawTrack** — `composer.json` (`pawtrack/pawtrack`),
  `APP_NAME=PawTrack`, docker container/image เป็น `pawtrack`

### Added
- เอกสารโปรเจค root: `README.md`, `CLAUDE.md`, `REVIEW.md`, `CONTRIBUTING.md`, `CHANGELOG.md`
- โครงสร้างเอกสาร `docs/` 6 หมวด (plans, features, guides, deployment, security, reports)
  พร้อมสารบัญ `docs/README.md`
- Project skill สำหรับ Claude Code: `.claude/skills/laravel-dev/SKILL.md`
- Docker support: `Dockerfile`, `docker-compose.yml`, config ของ nginx / php / mysql

### Known issues
ดูรายละเอียดทั้งหมดใน [`docs/reports/2026-07-18-code-review.md`](docs/reports/2026-07-18-code-review.md) — สรุปที่ควรแก้:
- `Contact` model สะกด `$fillable` ผิดเป็น `$fillbale`
- หลาย controller บันทึกข้อมูลโดยไม่ validate
- `GoogleMapController@store` redirect ไป `/maps` ที่ไม่มีอยู่จริง
- `GoogleMap` อ้างถึงคอลัมน์ที่ไม่มีในตาราง `animals`

---

## ประวัติเดิม (จาก git log)

- `Fix 0.3` — ปรับ layout, การโหลดหน้า, และรายละเอียด footer
- `Patch 0.3` — ปรับ layout และแก้ไขฟอร์ม
- เริ่มต้นโปรเจค VRU VET Project 2021 บน Laravel 8

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
- Docker stack ครบ 3 services (nginx + php-fpm + mysql) + `.dockerignore`
- ฐานข้อมูล 2 ชุด `paw-track-dev` / `paw-track-prod` ผ่าน `mysql-config/init/01-create-databases.sql`
- `UserSeeder` — สร้าง admin เริ่มต้น (`admin@pawtrack.test` / `password`)

### Fixed (Docker)
- ใช้ **PHP 8.0** แทน 8.1 — แก้ปัญหา Laravel 8 แปลง deprecation เป็น fatal ตอน boot บน PHP 8.1
- เปลี่ยน app image เป็น php-fpm ให้ตรงกับ nginx fastcgi + `composer install` ใน image
- host port ของ nginx/db เป็น **auto** (กันชนกับ MySQL/แอปอื่นบนเครื่อง) — override ได้ผ่าน
  `APP_FORWARD_PORT` / `DB_FORWARD_PORT` ใน `.env`

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

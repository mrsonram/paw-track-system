# PawTrack 🐾

ระบบจัดการและแสดงข้อมูลสุนัขจรจัด/สุนัขในชุมชน (Stray & Community Dog Management System)
พัฒนาด้วย **Laravel 8** เป็นโปรเจคของมหาวิทยาลัย (VRU VET Project)

เว็บไซต์มีทั้งฝั่งผู้ใช้ทั่วไป (ดูข้อมูลสุนัข ข่าวสาร แผนที่ ติดต่อ) และฝั่งผู้ดูแลระบบ (Admin) สำหรับจัดการข้อมูล

---

## ✨ Features

| ส่วน | รายละเอียด |
|------|-----------|
| ข้อมูลสุนัข (Pets) | แสดงรายการสุนัข พร้อมรายละเอียด: สายพันธุ์ เพศ อายุ ลักษณะ สถานะ สัตวแพทย์ เจ้าของ รูปภาพ ตำแหน่ง |
| ข่าวสาร (News) | รายการข่าว/ประกาศ พร้อมหน้ารายละเอียด |
| แผนที่ (Map) | แสดงตำแหน่งสุนัขบน Google Maps (พิกัด lat/lng) |
| ติดต่อ (Contact) | ฟอร์มติดต่อ ส่งข้อความถึงผู้ดูแล |
| เกี่ยวกับ (About) | หน้าแนะนำโปรเจค |
| Admin Panel | CRUD ข้อมูลสุนัข, ข่าว และดูข้อความติดต่อ |
| Authentication | สมัคร/เข้าสู่ระบบ ผ่าน `laravel/ui` |

---

## 🧰 Tech Stack

- **Backend:** PHP 8.1 (รองรับ 7.3+), Laravel 8.40
- **Frontend:** Blade templates, Bootstrap 4.6, jQuery 3.6, Vue 2.6 (via Laravel Mix)
- **Database:** MySQL
- **Maps:** Google Maps JavaScript API
- **Build tools:** Laravel Mix (Webpack), npm
- **Container:** Docker (PHP-FPM 8.1) + docker-compose

---

## 📦 Requirements

- PHP >= 8.0 (แนะนำ 8.1) พร้อม extension: `pdo_mysql`, `mbstring`, `zip`, `exif`, `pcntl`, `gd`
- Composer 2.x
- Node.js + npm
- MySQL 5.7+ / 8.0
- (ทางเลือก) Docker + Docker Compose

---

## 🚀 Getting Started (Local)

```bash
# 1) ติดตั้ง dependencies
composer install
npm install

# 2) ตั้งค่า environment
cp .env.example .env
php artisan key:generate

# 3) แก้ค่าฐานข้อมูลใน .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 4) รัน migration สร้างตาราง
php artisan migrate

# 5) build assets
npm run dev        # หรือ npm run watch ระหว่างพัฒนา

# 6) รันเซิร์ฟเวอร์
php artisan serve
```

เปิดเบราว์เซอร์ที่ http://localhost:8000

> **หมายเหตุ:** ต้องใส่ Google Maps API Key ในไฟล์ Blade ของหน้าแผนที่จึงจะแสดงแผนที่ได้

---

## 🐳 Getting Started (Docker)

```bash
docker compose up -d --build
```

แอปจะรันที่ http://localhost:8000 (PHP built-in server serve จากโฟลเดอร์ `public`)

> docker-compose ปัจจุบันรันเฉพาะ container `app` — ต้องเตรียม MySQL แยก แล้วชี้ `DB_HOST` ใน `.env` ให้ถูกต้อง

---

## 🗺️ Routes หลัก

| Method | URI | ปลายทาง | คำอธิบาย |
|--------|-----|---------|----------|
| GET | `/` | `PetController@index` | หน้าแรก แสดงรายการสุนัข |
| GET | `/info` | `PetController@info` | รายการสุนัข (เรียงตามชื่อ) |
| GET | `/news` | `PetController@news` | ข่าวสาร |
| GET | `/map` | `PetController@map` | แผนที่ |
| GET | `/pet/show/{id}` | `PetController@show` | รายละเอียดสุนัข |
| GET | `/news/show/{id}` | `PetController@message` | รายละเอียดข่าว |
| GET | `/about` | closure | เกี่ยวกับ |
| resource | `/dog` | `AdminController` | จัดการสุนัข (Admin) |
| resource | `/contact` | `ContactController` | ติดต่อ |
| resource | `/message` | `NewsController` | จัดการข่าว |
| GET/POST | `/google/add` | `GoogleMapController` | เพิ่ม/แสดงตำแหน่งบนแผนที่ |
| — | `/login`, `/register`, `/home` | Auth | ระบบสมาชิก |

ดูทั้งหมดที่ `routes/web.php`

---

## 🗃️ Database Schema (สรุป)

- **animals** — ข้อมูลสุนัข: `name, species, marking, gender, collar, age, status, vet, owner, image, location, lat, lng`
- **news** — ข่าว: `title, subtitle, detail`
- **contacts** — ข้อความติดต่อ: `name, email, title, message`
- **users** — ผู้ใช้ระบบ (Laravel default)

Model `Pet`, `Admin`, `GoogleMap` ทั้งหมด map ไปยังตาราง `animals`

---

## 📁 โครงสร้างโปรเจค (ย่อ)

```
app/
  Http/Controllers/   PetController, AdminController, NewsController,
                      ContactController, GoogleMapController, HomeController
  Models/             Pet, News, Contact, GoogleMap, Admin, User
database/migrations/  users, animals, contacts, news
resources/views/      pet/  admin/  google/  pages/  auth/  layouts/
routes/web.php        นิยาม route ทั้งหมด
```

---

## 🧪 Testing

```bash
php artisan test        # หรือ ./vendor/bin/phpunit
```

---

## 📄 เอกสารเพิ่มเติม

เอกสารทั้งหมดจัดหมวดไว้ในโฟลเดอร์ [`docs/`](docs/README.md) — ดูสารบัญที่ [`docs/README.md`](docs/README.md)

- [`docs/plans/`](docs/plans/) — แผนงานแยก phase + TODO
- [`docs/features/`](docs/features/) — เอกสารรายฟีเจอร์
- [`docs/guides/`](docs/guides/) — คู่มือติดตั้ง/พัฒนา
- [`docs/deployment/`](docs/deployment/) — การ deploy (Docker)
- [`docs/security/`](docs/security/) — ข้อเสนอแนะความปลอดภัย
- [`docs/reports/`](docs/reports/) — รายงานรีวิวโค้ด (point-in-time)

ไฟล์ระดับ root: [`CLAUDE.md`](CLAUDE.md) · [`CONTRIBUTING.md`](CONTRIBUTING.md) · [`CHANGELOG.md`](CHANGELOG.md)

---

## 📜 License

โปรเจคใช้โครงสร้าง Laravel ซึ่งเป็น open-source ภายใต้ [MIT license](https://opensource.org/licenses/MIT)

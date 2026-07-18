# Deployment — Docker

> หมวด: **การ deploy (Deployment)**
> อัปเดตล่าสุด: 18 ก.ค. 2026

รัน PawTrack ผ่าน Docker แบบ production-like: **nginx + php-fpm + mysql** (3 services)

## สถาปัตยกรรม

```
[ host:auto ] → nginx (alpine) → fastcgi → app (php-fpm 8.0 :9000) → db (mysql:8.0 :3306)
                    serve public/          รันโค้ด Laravel           paw-track-dev / paw-track-prod
```

| Service | Image | หน้าที่ | Port ภายใน | Port บน host |
| --- | --- | --- | --- | --- |
| `nginx` | nginx:alpine | เสิร์ฟ `public/` + ส่ง PHP ให้ fpm | 80 | **auto** (ดูวิธีหาด้านล่าง) |
| `app` | build จาก `Dockerfile` (php:8.0-fpm) | ประมวลผล PHP (php-fpm) | 9000 | — (ไม่เปิดออก host) |
| `db` | mysql:8.0 | ฐานข้อมูล | 3306 | **auto** |

> **สำคัญ — ใช้ PHP 8.0 ไม่ใช่ 8.1:** Laravel 8.47 (เวอร์ชันในโปรเจกต์) แปลง `E_DEPRECATED`
> เป็น exception ตอนโหลด class บน PHP 8.1 → boot ตายทั้ง web และ artisan
> (`Return type of Collection::offsetExists ... During inheritance of ArrayAccess`)
> composer.lock ก็ล็อก `phpspec/prophecy` ที่ต้องการ php `<8.1` อยู่แล้ว → **PHP 8.0 คือเวอร์ชันที่ถูกต้อง**

## ฐานข้อมูล — dev / prod

MySQL container สร้าง 2 database ตอน init ครั้งแรก ผ่านสคริปต์ `mysql-config/init/01-create-databases.sql`:

- **`paw-track-dev`** — ใช้ตอนพัฒนา (ค่า default ของแอป จาก `DB_DATABASE` ใน `.env`)
- **`paw-track-prod`** — เตรียมไว้สำหรับ production

สลับ database ที่แอปใช้ได้โดยเปลี่ยน `DB_DATABASE` ใน `.env` แล้ว `docker compose up -d`

## ไฟล์ที่เกี่ยวข้อง

- `Dockerfile` — php:8.0-fpm + extension (`pdo_mysql`, `mbstring`, `zip`, `exif`, `pcntl`, `gd`) + `composer install` (รวม dev deps เพราะ `bootstrap/cache/packages.php` อ้าง provider ของ ignition/sail/collision)
- `docker-compose.yml` — 3 services, named volume `dbdata` (ข้อมูล DB) + `vendor` (deps)
- `.dockerignore` — กัน `.git`, `node_modules`, `vendor`, **`.env`** ไม่ให้เข้า image
- `nginx/default.conf` — `fastcgi_pass app:9000`, root `public/`
- `php/local.ini` — upload 40M + ปิด `E_DEPRECATED` (กัน log ถูกท่วม)
- `mysql-config/my.cnf` — `max_allowed_packet=64M`
- `mysql-config/init/01-create-databases.sql` — สร้าง dev + prod

## วิธีรัน (ครั้งแรก)

```bash
# 1) เตรียม .env
cp .env.example .env
# ตั้งค่า:  DB_DATABASE=paw-track-dev  DB_USERNAME=root  DB_PASSWORD=<รหัสของคุณ>
php artisan key:generate   # หรือรันในคอนเทนเนอร์ทีหลัง

# 2) build + start ทั้ง stack
docker compose up -d --build

# 3) migrate + seed (สร้างตาราง + user เริ่มต้น)
docker compose exec app php artisan migrate --seed --force
# ต้องการเตรียม prod ด้วย:
docker compose exec -e DB_DATABASE=paw-track-prod app php artisan migrate --seed --force
```

### หา URL / port ที่ Docker สุ่มให้

```bash
docker compose port nginx 80     # → เช่น 0.0.0.0:32774  → เปิด http://localhost:32774
docker compose port db 3306      # → เช่น 0.0.0.0:32773  → ต่อ SQL client ที่พอร์ตนี้
```

> host port เป็น **auto** (host port `0` = Docker เลือกที่ว่างให้ กันชนกับ MySQL/แอปอื่นบนเครื่อง)
> อยากปักหมุด: ตั้ง `APP_FORWARD_PORT=8000` และ/หรือ `DB_FORWARD_PORT=3310` ใน `.env`

## ผู้ใช้เริ่มต้น (จาก UserSeeder)

| Email | Password |
| --- | --- |
| `admin@pawtrack.test` | `password` |

seed ทั้ง dev และ prod (idempotent — รันซ้ำได้ไม่สร้างซ้ำ) ดู `database/seeders/UserSeeder.php`

## เชื่อมต่อฐานข้อมูลด้วย SQL client (TablePlus/DBeaver)

| ช่อง | ค่า |
| --- | --- |
| Host | `127.0.0.1` |
| Port | ผลจาก `docker compose port db 3306` |
| User / Password | `root` / ตาม `DB_PASSWORD` ใน `.env` |
| Database | `paw-track-dev` หรือ `paw-track-prod` |

## คำสั่งที่ใช้บ่อย

```bash
docker compose ps                              # สถานะ + port
docker compose logs -f app                     # log php-fpm
docker compose exec app php artisan <cmd>      # รัน artisan
docker compose exec app php artisan migrate:fresh --seed --force   # ล้าง+สร้างใหม่+seed
docker compose down                            # หยุด (ข้อมูล DB ยังอยู่)
docker compose down -v                         # หยุด + ลบ volume (dbdata + vendor)
```

## หมายเหตุสำคัญ

- **แก้ dependency (composer) แล้วต้อง `docker compose down -v`** ก่อน `up --build` เพราะ named
  volume `vendor` ถูก seed จาก image แค่ตอนสร้างครั้งแรก การ rebuild เฉย ๆ จะยังใช้ vendor เดิม
- **`DB_HOST` ถูกบังคับเป็น `db`** ผ่าน `environment` ใน compose — ไม่ต้องแก้ `.env` เอง
- ค่ารหัส/ชื่อ DB มาจาก `.env` (`${DB_DATABASE}`, `${DB_PASSWORD}`) แหล่งเดียว → app กับ db ตรงกันเสมอ
- assets (CSS/JS) build ด้วย `npm run dev/prod` บน host ตามปกติ (ไม่ได้อยู่ใน container)

## TODO (production จริง)

- [ ] multi-stage build สำหรับ assets (node) + `composer install --no-dev` (พร้อมล้าง bootstrap/cache)
- [ ] ตั้ง `APP_ENV=production`, `APP_DEBUG=false`, `php artisan config:cache`
- [ ] แยก `docker-compose.prod.yml` (ไม่ bind mount โค้ด ใช้ image ที่ build แล้ว)

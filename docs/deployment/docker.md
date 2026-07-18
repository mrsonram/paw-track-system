# Deployment — Docker

> หมวด: **การ deploy (Deployment)**
> อัปเดตล่าสุด: 18 ก.ค. 2026

รัน PawTrack ผ่าน Docker แบบ production-like: **nginx + php-fpm + mysql** (3 services)

## สถาปัตยกรรม

```
[ host :8000 ] → nginx (alpine) → fastcgi → app (php-fpm :9000) → db (mysql:8.0 :3306)
                     serve public/          รันโค้ด Laravel        เก็บข้อมูล
```

| Service | Image | หน้าที่ | Port |
| --- | --- | --- | --- |
| `nginx` | nginx:alpine | เว็บเซิร์ฟเวอร์ เสิร์ฟ `public/` + ส่ง PHP ให้ fpm | `8000:80` |
| `app` | build จาก `Dockerfile` (php:8.1-fpm) | ประมวลผล PHP (php-fpm) | ภายใน `9000` |
| `db` | mysql:8.0 | ฐานข้อมูล | `3306:3306` |

## ไฟล์ที่เกี่ยวข้อง

- `Dockerfile` — php:8.1-fpm + extension (`pdo_mysql`, `mbstring`, `zip`, `exif`, `pcntl`, `gd`) + `composer install`
- `docker-compose.yml` — นิยาม 3 services, named volume `dbdata` (ข้อมูล DB) และ `vendor` (deps)
- `.dockerignore` — กัน `.git`, `node_modules`, `vendor`, **`.env`** ไม่ให้เข้า image
- `nginx/default.conf` — `fastcgi_pass app:9000`, root `public/`
- `php/local.ini` — `upload_max_filesize` / `post_max_size` = 40M
- `mysql-config/my.cnf` — `max_allowed_packet=64M`

## วิธีรัน

```bash
# 1) เตรียม .env (ครั้งแรก)
cp .env.example .env
# ตั้งค่าใน .env:  DB_DATABASE=pawtrack  DB_USERNAME=root  DB_PASSWORD=pawtrack
#   (ถ้าไม่ตั้ง compose จะใช้ค่า default: pawtrack ทั้งชื่อ DB และรหัส root)

# 2) build + start ทั้ง stack
docker compose up -d --build

# 3) generate app key (ถ้ายังไม่มีใน .env) + รัน migration
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

เปิดแอปที่ http://localhost:8000

## คำสั่งที่ใช้บ่อย

```bash
docker compose ps                              # สถานะ services
docker compose logs -f app                     # ดู log
docker compose exec app php artisan <cmd>      # รัน artisan
docker compose exec app composer install       # ติดตั้ง deps ใหม่
docker compose down                            # หยุด (ข้อมูล DB ยังอยู่ใน volume)
docker compose down -v                         # หยุด + ลบข้อมูล DB (เริ่มใหม่หมด)
```

## หมายเหตุสำคัญ

- **`DB_HOST` ถูกบังคับเป็น `db`** ผ่าน `environment` ใน compose — Laravel จะเชื่อม service `db`
  โดยอัตโนมัติ (immutable dotenv ไม่ทับค่า env ที่ตั้งจาก container) ไม่ต้องแก้ `.env` เอง
- ค่ารหัสผ่าน/ชื่อ DB มาจาก `.env` (`${DB_DATABASE}`, `${DB_PASSWORD}`) แหล่งเดียว — app กับ db ตรงกันเสมอ
- named volume `vendor` เก็บ dependencies ที่ติดตั้งใน image ไม่ให้ bind mount `./` ทับ →
  แก้โค้ดบน host แล้วเห็นผลทันที โดยไม่ต้องมี `vendor/` บน host
- ครั้งแรก db อาจใช้เวลา init สักครู่ — `app` มี `depends_on: condition: service_healthy` รอ db พร้อมก่อน
- assets (CSS/JS) ยัง build ด้วย `npm run dev/prod` บน host ตามปกติ (ไม่ได้อยู่ใน container)

## TODO (production จริง)

- [ ] เพิ่ม multi-stage build สำหรับ assets (node) และ `composer install --no-dev`
- [ ] ตั้ง `APP_ENV=production`, `APP_DEBUG=false` และ `php artisan config:cache`
- [ ] แยกไฟล์ `docker-compose.prod.yml` (ไม่ bind mount โค้ด, ใช้ image ที่ build แล้ว)

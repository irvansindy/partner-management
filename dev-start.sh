#!/bin/bash
set -euo pipefail

### dev-start.sh
# Smart startup untuk project Partner Management
# - pastikan docker jalan
# - pastikan network & volumes ada
# - start/rebuild container apabila perlu
# - cek PHP version di container app (>= 8.2)
# - fix permission storage & bootstrap/cache
# - composer install, migrate, seed, clear cache
# - cek status container akhir (harus: Started untuk semua)

# ----- CONFIG -----
PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
NETWORK_NAME="partnernet"
MYSQL_VOLUME="partner_mysql_data"
REDIS_VOLUME="partner_redis_data"
APP_CONTAINER="partner_app"
MYSQL_CONTAINER="partner_mysql"
REDIS_CONTAINER="partner_redis"
PMA_CONTAINER="partner_pma"
NGINX_CONTAINER="partner_nginx"
PHP_MIN_MAJOR=8
PHP_MIN_MINOR=2
# timeout (detik) untuk menunggu service ready
WAIT_TIMEOUT=120

# ----- HELPERS -----
info(){ echo -e "\e[34m[INFO]\e[0m $*"; }
ok(){ echo -e "\e[32m[OK]\e[0m $*"; }
warn(){ echo -e "\e[33m[WARN]\e[0m $*"; }
err(){ echo -e "\e[31m[ERROR]\e[0m $*"; exit 1; }

cd "$PROJECT_DIR"

# 1. Pastikan Docker aktif
if ! docker info > /dev/null 2>&1; then
    err "Docker daemon tidak berjalan. Buka Docker Desktop / start docker service lalu jalankan ulang skrip ini."
fi
info "Docker daemon aktif."

# 2. Pastikan network ada
if ! docker network inspect "$NETWORK_NAME" >/dev/null 2>&1; then
    info "Network '$NETWORK_NAME' tidak ditemukan. Membuat..."
    docker network create "$NETWORK_NAME"
    ok "Network $NETWORK_NAME dibuat."
else
    info "Network $NETWORK_NAME sudah ada."
fi

# 3. Pastikan volumes ada
for V in "$MYSQL_VOLUME" "$REDIS_VOLUME"; do
    if ! docker volume inspect "$V" >/dev/null 2>&1; then
        info "Volume '$V' tidak ditemukan. Membuat..."
        docker volume create "$V" >/dev/null
        ok "Volume $V dibuat."
    else
        info "Volume $V sudah ada."
    fi
done

# 4. Down dulu untuk memastikan clean state minimal (tanpa hapus external network/volume)
info "Men-stop container compose lama (jika ada)..."
docker compose down --remove-orphans || true

# 5. Build & up (jika perlu rebuild, tambahkan --build)
info "Membangun & menjalankan container (docker compose up -d --build)..."
docker compose up -d --build

# 6. Tunggu semua container penting muncul "Up"
info "Menunggu container penting muncul (timeout ${WAIT_TIMEOUT}s)..."
deadline=$((SECONDS + WAIT_TIMEOUT))
while [ $SECONDS -lt $deadline ]; do
    up_count=0
    for C in "$APP_CONTAINER" "$MYSQL_CONTAINER" "$REDIS_CONTAINER" "$PMA_CONTAINER" "$NGINX_CONTAINER"; do
        if docker ps --format '{{.Names}}\t{{.Status}}' | awk '{print $1}' | grep -q -x "$C"; then
        status=$(docker ps --filter "name=^/${C}$" --format '{{.Status}}' || echo "")
        if echo "$status" | grep -qi "Up"; then
            up_count=$((up_count+1))
        fi
        fi
    done
    if [ "$up_count" -eq 5 ]; then
        ok "Semua container utama sudah Up."
        break
    fi
    sleep 1
done

if [ "$up_count" -ne 5 ]; then
    warn "Beberapa container belum sepenuhnya Up. Lanjutkan pengecekan..."
fi

# 7. Periksa versi PHP di container app (jika container tersedia)
if docker ps --format '{{.Names}}' | grep -q "^${APP_CONTAINER}$"; then
    php_ver=$(docker exec -u www-data "$APP_CONTAINER" php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION; ' 2>/dev/null || true)
    if [ -z "$php_ver" ]; then
        # coba sebagai root
        php_ver=$(docker exec "$APP_CONTAINER" php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION; ' 2>/dev/null || true)
    fi

    if [ -z "$php_ver" ]; then
        warn "Tidak bisa membaca versi PHP di $APP_CONTAINER. Abaikan jika tidak perlu."
    else
        info "Versi PHP di container app: $php_ver"
        major=${php_ver%%.*}
        minor=${php_ver#*.}
        if [ "$major" -lt "$PHP_MIN_MAJOR" ] || { [ "$major" -eq "$PHP_MIN_MAJOR" ] && [ "$minor" -lt "$PHP_MIN_MINOR" ]; }; then
        warn "Versi PHP di container ($php_ver) < ${PHP_MIN_MAJOR}.${PHP_MIN_MINOR}. Akan rebuild image app (no-cache) agar sesuai requirement."
        docker compose build --no-cache app
        docker compose up -d app
        ok "Rebuild app selesai."
        fi
    fi
    else
    warn "Container $APP_CONTAINER tidak ditemukan. Pastikan docker compose up berjalan."
fi

# 8. Tunggu MySQL ready (cek mysqladmin ping)
info "Menunggu MySQL siap (timeout ${WAIT_TIMEOUT}s)..."
deadline=$((SECONDS + WAIT_TIMEOUT))
mysql_ready=false
while [ $SECONDS -lt $deadline ]; do
    if docker exec "$MYSQL_CONTAINER" mysqladmin ping -h 127.0.0.1 -uroot -proot --silent >/dev/null 2>&1; then
        mysql_ready=true
        break
    fi
    sleep 1
done

if ! $mysql_ready; then
    warn "MySQL tidak merespon dalam ${WAIT_TIMEOUT}s. Cek logs: docker logs $MYSQL_CONTAINER"
else
    ok "MySQL siap."
fi

# 9. Pastikan .env & konfigurasi Redis/CACHE benar
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        info ".env tidak ada -> copy dari .env.example"
        cp .env.example .env
    else
        warn ".env dan .env.example tidak ditemukan. Pastikan .env ada."
    fi
fi

# Set recommended env values (tidak overwrite jika ada)
set_env_if_missing() {
    key="$1"
    val="$2"
    if ! grep -q "^${key}=" .env 2>/dev/null; then
        echo "${key}=${val}" >> .env
        info "Menambahkan ${key} ke .env"
    fi
}
set_env_if_missing "CACHE_DRIVER" "redis"
set_env_if_missing "SESSION_DRIVER" "redis"
set_env_if_missing "QUEUE_CONNECTION" "redis"
set_env_if_missing "REDIS_HOST" "redis"
set_env_if_missing "REDIS_PASSWORD" "null"
set_env_if_missing "REDIS_PORT" "6379"

# 10. Composer install inside app (skip if vendor exists)
if [ ! -d vendor ]; then
    info "Menjalankan composer install di container app..."
    docker exec -u www-data "$APP_CONTAINER" composer install --no-interaction --prefer-dist || \
        docker exec "$APP_CONTAINER" composer install --no-interaction --prefer-dist
else
    info "Direktori vendor sudah ada. Memastikan composer dependencies up-to-date..."
    docker exec -u www-data "$APP_CONTAINER" composer install --no-interaction --prefer-dist || true
fi

# 11. Set permission untuk storage & bootstrap/cache
info "Set permission storage & bootstrap/cache..."
docker exec "$APP_CONTAINER" chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
docker exec "$APP_CONTAINER" chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true
ok "Permissions set."

# 12. Generate APP_KEY jika belum ada
if ! grep -q "^APP_KEY=" .env >/dev/null 2>&1 || grep -q "^APP_KEY=$" .env >/dev/null 2>&1; then
    info "Men-generate APP_KEY..."
    docker exec -u www-data "$APP_CONTAINER" php artisan key:generate || docker exec "$APP_CONTAINER" php artisan key:generate
fi

# 13. Migrate & seed (force)
info "Menjalankan migrations & seed (force)..."
docker exec -u www-data "$APP_CONTAINER" php artisan migrate --force || docker exec "$APP_CONTAINER" php artisan migrate --force
docker exec -u www-data "$APP_CONTAINER" php artisan db:seed --force || true

# 14. Clear config/cache/route/view
info "Clear Laravel caches..."
docker exec -u www-data "$APP_CONTAINER" php artisan optimize:clear || docker exec "$APP_CONTAINER" php artisan optimize:clear

# 15. Final check: semua container harus Up
info "Final check container statuses..."
all_ok=true
for C in "$APP_CONTAINER" "$MYSQL_CONTAINER" "$REDIS_CONTAINER" "$PMA_CONTAINER" "$NGINX_CONTAINER"; do
    status=$(docker ps --filter "name=^/${C}$" --format '{{.Status}}' || echo "")
    if echo "$status" | grep -qi "Up"; then
        ok "$C  Started"
    else
        warn "$C  NOT Started (status: ${status:-not found})"
        all_ok=false
    fi
done

if $all_ok; then
    ok "Semua container diperkirakan berjalan dengan baik."
    echo
    docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
    exit 0
else
    warn "Ada container yang belum Start. Periksa logs untuk container yg bermasalah."
    echo "Contoh perintah cek logs: docker logs <container_name>"
    docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
    exit 2
fi

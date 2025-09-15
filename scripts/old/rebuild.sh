#!/bin/bash

# Direktori proyek Laravel di WSL (akses dari drive C)
PROJECT_DIR="/mnt/c/Users/irvan sindy/partner-management"
cd "$PROJECT_DIR" || exit

echo "♻️ Rebuilding Laravel Docker containers..."

# 1. Stop dan hapus container lama
docker compose down --remove-orphans

# 2. Hapus network lama jika ada (optional)
docker network rm partnernet 2>/dev/null

# 3. Build ulang container dari Dockerfile
docker compose build

# 4. Jalankan ulang container
docker compose up -d

echo "✅ Rebuild dan start ulang selesai."

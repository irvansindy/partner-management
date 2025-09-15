#!/bin/bash

# Direktori proyek Laravel di WSL (akses dari drive C)
PROJECT_DIR="/mnt/c/Users/irvan sindy/partner-management"
cd "$PROJECT_DIR" || exit

echo "🚀 WSL Startup Check: Partner Management Laravel Docker"

# 1. Pastikan Docker aktif
if ! docker info > /dev/null 2>&1; then
  echo "❌ Docker belum aktif. Silakan buka Docker Desktop di Windows terlebih dahulu."
  exit 1
fi

# 2. Cek dan buat network jika belum ada
if ! docker network ls | grep -q partnernet; then
  echo "🔧 Network 'partnernet' belum ada. Membuat ulang..."
  docker network create partnernet
fi

# 3. Cek apakah container utama sudah berjalan
if ! docker ps --format '{{.Names}}' | grep -q partner_app; then
  echo "🟢 Menjalankan Docker Compose (tanpa rebuild)..."
  docker compose up -d
else
  echo "✅ Container sudah berjalan. Tidak perlu dijalankan ulang."
fi

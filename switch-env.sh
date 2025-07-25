# #!/bin/bash

# if [ "$1" == "docker" ]; then
#     cp .env.docker .env
#     echo "✅ Switched to Docker environment."
# elif [ "$1" == "xampp" ]; then
#     cp .env.xampp .env
#     echo "✅ Switched to XAMPP environment."
# else
#     echo "Usage: ./switch-env.sh [docker|xampp]"
# fi
#!/bin/bash

#!/bin/bash

echo "🔍 Checking environment..."

# Deteksi apakah Docker container Laravel sedang aktif
is_docker_running=$(docker ps --filter "name=partner_app" --filter "status=running" -q)

if [ -n "$is_docker_running" ]; then
  echo "🛠️  Docker detected → using .env.docker"
  cp .env.docker .env
else
  echo "💻 XAMPP detected → using .env.xampp"
  cp .env.xampp .env
fi

# Jalankan Laravel config cache
echo "🧹 Clearing Laravel config cache..."
php artisan config:clear > /dev/null 2>&1
php artisan config:cache > /dev/null 2>&1

echo "✅ Environment switched successfully!"

# 🎓 Sistem Informasi BEM IT Del

Proyek Laravel berbasis Docker (Laravel Sail) untuk mendukung pengelolaan organisasi mahasiswa di IT Del secara berkelanjutan dan konsisten di semua perangkat developer.

---

## ⚙️ Setup Developer (Langkah Awal)

```bash
# Clone project
git clone https://github.com/username/API_SERVICE_BEM.git
cd API_SERVICE_BEM

# Salin konfigurasi
cp .env.example .env

# (Hanya untuk Windows - 1x setup WSL)
wsl --install
wsl --set-default-version 2
# Restart komputer setelah ini

# Jalankan Docker Desktop

# Install dependency (tanpa Composer lokal)
docker run --rm -v $(pwd):/app -w /app laravelsail/php82-composer:latest composer install

# Jalankan Sail
./vendor/bin/sail up -d

# Generate app key & migrasi database
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail build #ini untuk build docker baru

# (Opsional) Tambah alias biar cukup ketik `sail`
echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.bashrc
source ~/.bashrc

# Edit .env kalau bentrok
APP_PORT=8080

# manajament docker
sail up -d     # Menyalakan container
sail down      # Matikan container
sail down -v   # Hapus container & volume
sail restart   # Restart container
sail ps        # Cek status container

sail artisan migrate              # Migrasi database
sail artisan db:seed             # Seeder data awal
sail artisan migrate:fresh --seed # Ulang semua data
sail npm install                 # Install frontend (jika ada)
sail npm run dev                 # Jalankan frontend
sail mysql -u sail -p            # Masuk ke MySQL (password: password)




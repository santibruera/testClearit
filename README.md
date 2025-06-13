# TestClearit



## Requisitos

- Git  
- Docker Desktop  
- Composer (opcional)

## Instalación 

```bash
git clone git@github.com:santibruera/testClearit.git
cd testClearit
cp .env.example .env

# Iniciar servicios
./vendor/bin/sail up -d

# Instalar dependencias
./vendor/bin/sail composer install

# Generar clave y migrar
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh --seed

# Enlace a storage
./vendor/bin/sail artisan storage:link

# Instalar frontend
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev

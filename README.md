TestClearit

Una aplicación de gestión de tickets de soporte desarrollada con Laravel, Livewire y Alpine.js, utilizando Docker y Sail para un entorno de desarrollo consistente.
Tabla de Contenidos

    Requisitos Previos

    Instalación

    Configuración Inicial

    Uso

    Licencia

Requisitos Previos

Asegúrate de tener instalado lo siguiente en tu sistema:

    Git: Para clonar el repositorio.

    Docker Desktop: Incluye Docker Engine y Docker Compose, necesarios para ejecutar los servicios de la aplicación.

    Composer (opcional, si no utilizas el helper sail para su instalación inicial): Puedes descargarlo desde getcomposer.org.

Instalación

Sigue estos pasos para poner en marcha el proyecto localmente:

    Clonar el repositorio:
    Abre tu terminal y ejecuta:

    git clone https://github.com/santibruera/testClearit.git testClearit
    cd testClearit

    (Si lo deseas, puedes reemplazar testClearit por el nombre que prefieras para la carpeta de tu proyecto).

    Copiar el archivo de entorno:
    Crea tu archivo .env a partir del ejemplo:

    cp .env.example .env

    Importante: Edita el archivo .env que acabas de crear. Configura tus credenciales de base de datos (DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.), la URL de la aplicación (APP_URL=http://localhost) y la configuración de correo (MAIL_*) según tu entorno local. Para probar las notificaciones, puedes usar un servicio como Mailtrap.

    Iniciar Docker y Dependencias:
    Inicia Docker Desktop. Luego, en la terminal, inicia los servicios de la aplicación (PHP, MySQL, Nginx, etc.):

    ./vendor/bin/sail up -d

    Esto construirá e iniciará los contenedores de Docker en segundo plano. La primera vez puede tardar unos minutos.

    Instalar dependencias de Composer:
    Una vez que los contenedores estén funcionando, instala las dependencias de PHP de tu proyecto:

    ./vendor/bin/sail composer install

    Generar la clave de la aplicación:
    Genera una clave de aplicación única para tu .env (crucial para la seguridad de Laravel):

    ./vendor/bin/sail artisan key:generate

    Ejecutar migraciones y seeders:
    Crea las tablas de la base de datos y pobla con datos de prueba (usuarios user y agent, tickets, etc.):

    ./vendor/bin/sail artisan migrate:fresh --seed

    Advertencia: Este comando eliminará todos los datos de tu base de datos actual antes de recrearla y sembrarla. Úsalo solo en entornos de desarrollo.

    Crear el enlace de almacenamiento público:
    Para que los archivos adjuntos a los tickets sean accesibles vía web, crea el enlace simbólico:

    ./vendor/bin/sail artisan storage:link

    Instalar y Compilar dependencias de NPM (Frontend):
    Si tu proyecto utiliza Tailwind CSS, Vite u otros activos de frontend, necesitarás compilar los recursos:

    ./vendor/bin/sail npm install
    ./vendor/bin/sail npm run dev # Para desarrollo (observa cambios y recarga automáticamente)
    # o
    # ./vendor/bin/sail npm run build # Para producción (compila para un despliegue optimizado)

Uso

Una vez completada la instalación, tu aplicación debería estar accesible en la URL configurada en tu .env (normalmente http://localhost).

Puedes iniciar sesión con los usuarios de prueba creados por los seeders:

    Usuario Creador de Tickets (user rol):

        Correo: user@example.com

        Contraseña: password

    Agente de Soporte (agent rol):

        Correo: agent@example.com

        Contraseña: password

Explora las funcionalidades de creación de tickets, visualización de tickets por rol y edición de tickets para usuarios.

Comandos útiles de Sail:

    ./vendor/bin/sail up -d: Inicia los contenedores en segundo plano.

    ./vendor/bin/sail stop: Detiene los contenedores.

    ./vendor/bin/sail down: Detiene y elimina los contenedores (elimina volúmenes anónimos por defecto).

    ./vendor/bin/sail artisan [comando]: Ejecuta comandos Artisan dentro del contenedor PHP.

    ./vendor/bin/sail composer [comando]: Ejecuta comandos Composer dentro del contenedor PHP.

    ./vendor/bin/sail npm [comando]: Ejecuta comandos NPM dentro del contenedor Node.js.

    ./vendor/bin/sail bash: Abre una sesión bash dentro del contenedor PHP.

Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo LICENSE para más detalles.

# 🐾 Animal Hospital API

<div align="justify">
API RESTful desarrollada en Laravel para la gestión integral de una clínica veterinaria. Permite administrar clientes, mascotas, especies, citas médicas y otros recursos esenciales para el funcionamiento de un hospital animal.
</div>

<div align="justify">
Este proyecto está diseñado como práctica de arquitectura backend moderna, aplicando buenas prácticas como separación de responsabilidades, uso de relaciones Eloquent, validaciones, control de errores y autenticación basada en roles.
</div>

## 🚀 Tecnologías utilizadas
* PHP 8+
* Laravel
* MySQL / MariaDB
* Eloquent ORM
* REST API
* Laravel Migrations & Seeders
* Soft Deletes
* Enum Types (Roles y otros estados)
* Postman (testing)

## Características principales
* Gestión de clientes y usuarios relacionados
* Registro y administración de mascotas
* Catálogo de especies y razas
* Sistema de roles de usuario (Enum Role)
* Relación 1 a 1 entre Client y User
* Eliminación lógica (Soft Deletes)
* Validaciones centralizadas
* API estructurada por módulos
* Manejo de errores estandarizado en JSON

## 🧱 Arquitectura del sistema

El proyecto sigue una arquitectura RESTful con separación por capas:

* Controllers → lógica HTTP
* Models → lógica de datos
* Requests → validación
* Enums → estados y roles
* Migrations → estructura de base de datos
* Seeders & Factories → datos de prueba

## 📡 Endpoints principales
### 👤 Usuarios

| Método | Endpoint | Descripción |
| :--- | :--- | :--- |
| **GET** | `/api/users` | Listar usuarios |
| **GET** | `/api/users/{id}` | Obtener usuario |
| **POST** | `/api/users` | Crear usuario |
| **PATCH** | `/api/users/{id}` | Actualizar usuario |
| **DELETE** | `/api/users/{id}` | Eliminar usuario (soft delete) |


### 🧑 Clientes

| Método | Endpoint | Descripción |
| :--- | :--- | :--- |
| **GET** | `/api/clients` | Listar clientes |
| **GET** | `/api/clients/{id}` | Detalle de cliente |
| **POST** | `/api/clients` | Crear cliente con usuario asociado |
| **PATCH** | `/api/clients/{id}` | Actualizar cliente |
| **DELETE** | `/api/clients/{id}` | Eliminar cliente (soft delete) |


### 🐶 Mascotas (Pets)

| Método | Endpoint | Descripción |
| :--- | :--- | :--- |
| **GET** | `/api/pets` | Listar mascotas |
| **GET** | `/api/pets/{id}` | Detalle de mascota |
| **POST** | `/api/pets` | Registrar mascota |
| **PATCH** | `/api/pets/{id}` | Actualizar mascota |
| **DELETE** | `/api/pets/{id}` | Eliminar mascota |

### 🧬 Especies

| Método | Endpoint | Descripción |
| :--- | :--- | :--- |
| **GET** | `/api/species` | Listar especies |
| **GET** | `/api/species/{id}` | Listar especies |



### 🐇 Razas

| Método | Endpoint | Descripción |
| :--- | :--- | :--- |
| **GET** | `/api/breeds` | Listar razas |
| **GET** | `/api/breeds/{id}` | Listar razas |
| **POST** | `/api/breeds` | Registrar razas |
| **PATCH** | `/api/breeds/{id}` | Actualizar razas |
| **DELETE** | `/api/breeds/{id}` | Eliminar razas |

###  👩‍💼 CLiente - 🐱  Mascota

| Método | Endpoint | Descripción |
| :--- | :--- | :--- |
| **GET** | `/api/client/{client}/pets` | Listar mascotas de un cliente |


## Relaciones del sistema
* User 1 → 1 Client
* Client 1 → N Pets
* Species 1 → N Breeds
* Breed 1 → N Pets
* Species 1 → N Pets

## ⚙️ Instalación del proyecto
```bash
# 1. Clonar el repositorio
git clone https://github.com/omaroaburto/animalhospital.git

# 2. Acceder al directorio
cd animalhospital

# 3. Instalar dependencias de PHP
composer install

# 4. Crear archivo de entorno ambiental
cp .env.example .env

# 5. Generar la clave de aplicación de Laravel
php artisan key:generate

# 6. Ejecutar migraciones e insertar datos de prueba
php artisan migrate --seed

# 7. Levantar el servidor local
php artisan serve
```

## 📁 Estructura del proyecto

```
app/
 ├── Http/
 │    ├── Controllers/
 │    ├── Requests/ 
 │    ├── Resource/ 
 ├── Models/
 ├── Enums/
 ├── Providers/
database/
 ├── data/
 ├── migrations/
 ├── seeders/
 ├── factories/
routes/
 ├── api.php
```

## 🧠 Decisiones de diseño

* Uso de Soft Deletes para mantener historial de registros eliminados
* Separación de lógica usando Requests para validación
* Uso de Enums para roles, mejorando escalabilidad
* API pensada para consumo por frontend o mobile app
* Relaciones Eloquent para integridad de datos

 


## 🚀 Estado del Proyecto: API en Desarrollo

> ⚠️ **Nota:** Esta API se encuentra actualmente en fase activa de desarrollo. Las funcionalidades base están siendo construidas y el sistema evolucionará constantemente.

---

## 🛠️ Próximas Funcionalidades (Roadmap)

El núcleo del sistema incorporará herramientas avanzadas de seguridad, autenticación y gestión de archivos:

- 🔒 **Trabajo con JWT**: Implementación de JSON Web Tokens para una autenticación segura y sin estado.
- 👥 **Gestión de Roles**: Control de acceso basado en roles (RBAC) para proteger rutas y recursos.
- 🧑‍💻 **Registro de Usuarios**: Sistema completo para que nuevos perfiles se unan a la plataforma.
- 🔑 **Login & Logout**: Flujos seguros de inicio y cierre de sesión de usuarios.
- 📧 **Validación de Correo**: Verificación obligatoria de cuentas mediante enlaces por email.
- 🔄 **Recuperación de Correos**: Sistema de restablecimiento de contraseñas olvidadas de forma segura.
- 📝 **Creación de Documentos**: Generación y exportación de archivos y reportes del sistema.
- 🖼️ **Trabajo con Imágenes**: Procesamiento, carga y almacenamiento de archivos multimedia.

---

## 📦 Módulos del Sistema por Implementar

La arquitectura se dividirá en módulos específicos para cubrir las necesidades del negocio y la atención al cliente:

### 🗃️ Módulo de Inventario
- Control de stock en tiempo real.
- Registro de productos, proveedores y alertas de almacenamiento bajo.

### 🛍️ Módulo de Ventas
- Gestión de transacciones y procesamiento de pagos de clientes.
- Historial de compras y facturación modular.

### 🩺 Módulo de Reserva de Atención Médica Veterinaria
- **Gestión de Clientes (Dueños)**: Perfiles detallados de las personas asociadas a las cuentas.
- **🐾 Razas y Animales**: Registro clínico y control de mascotas según su especie y raza (caninos, felinos, etc.).
- **📅 Agenda Médica**: Sistema de turnos, citas y calendario para el personal veterinario.
 


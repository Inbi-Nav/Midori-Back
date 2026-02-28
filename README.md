# REST API – Sistema de Gestión E-commerce (Midori)
Esta API REST implementa un sistema de gestión para Midori con control de
  - usuarios 
  - productos
  - pedidos
  - pagos

La autenticación se realiza mediante Bearer Tokens (OAuth2).

## Requisitos

- PHP - 8.2
- Composer
- Laravel 12
- SQLite 
- Laravel Passport
---
##  Cómo iniciar el proyecto

  - Clonar el repositorio:
  - git clone **url-repo**
  - cd Midori-Back
  - composer install
  - cp .env.example .env
  - php artisan key:generate
  - CREATE DATABASE midori o New-Item database/database.sqlite -ItemType File
  - php artisan migrate:fresh --seed
  <!-- - php artisan passport:keys -->
  - php artisan passport:install
  - php artisan passport:client --personal
  - php artisan serve 

## HTTP HEADERS
- Accept: application/json
- Content-Type: application/json


# Ejecutar proyecto con Docker

## Requisitos
- Docker 
- Docker Compose 
(No es necesario instalar PHP ni Composer en tu máquina)

En caso de error de permisos
- app chmod 600 storage/oauth-public.key

##  Cómo iniciar el proyecto

- git clone url-repo
- docker compose build
- docker compose up 

Aplicación Laravel (puerto 8000)

MySQL 8 

## Ejecutar los test:
- php artisan test

Incluye tests funcionales para:

  - Autenticación

  - Roles

  - Productos

  - Pedidos

  - Administración

---
## Credenciales de administrador 
 - **email**: admin@midori.com
 - **password**: midori2026

 ## Registro
Al registrarse por primera vez, el usuario obtiene el rol CLIENT por defecto.
  - **POST - /api/register**
- Campos obligatorios 
  - **name, email, password** 

  ## Login
- Una vez registrado correctamente, el usuario puede autenticarse. 
    - **POST - /api/login**
 - credenciales 
    - **email, password** 

 ## Roles del sistema
La API maneja tres tipos de usuarios:

 **CLIENTE**

 Puede:
  - Ver productos
  - Crear pedidos
  - Realizar pagos
  - Consultar sus pedidos
  - Solicitar convertirse en proveedor

---

**PROVEEDOR** - Responsable de gestionar productos y pedidos

Puede:
- Crear / modificar / eliminar productos
- Gestionar categorías
- Consultar pedidos
- Cambiar el estado de los pedidos

El proveedor no se crea directamente. Debe ser aprobado por el administrador.

---

**ADMIN** - Usuario con privilegios de supervision

Puede:

- Supervisar sistema
- Aprobar solicitudes de proveedores
- Consultar estadísticas 
- Gestionar usuarios

## Solicitar convertirse en proveedor

1. El cliente envía la solicitud:
 - POST /api/users/request-provider
2. El administrador consulta las solicitudes:
- GET /api/provider-request
3. El administrador aprueba la solicitud:
- PATCH /api/users/{id}/approve-provider 

## Endpoints de productos
- GET /api/products
- GET /api/products/{id}
- POST /api/products - (proveedor)
- PUT /api/products/{id} - (proveedor)
- DELETE /api/products/{id} - (proveedor)

## Endpoints de pedidos
- POST /api/orders - (cliente)
- GET /api/orders/me - (cliente)
- GET /api/orders - (proveedor)
- PATCH /api/orders/{id}/status - (proveedor)

## Endpoints de pago
- POST /api/payments - (cliente)

## Endpoints de administrador
- GET /api/users 
- GET /api/users/{id}
- PUT /api/users/{id}
- DELETE /api/users/{id}
- GET /api/provider-request
- PATCH /api/users/{id}/approve-request

## SEGURIDAD
- Todas las rutas protegidas usan auth:api
- El acceso se restringe por rol mediante middleware
- La autenticación se basa en **Bearer Tokens**
- Sin token -> 401
- Token sin permisos -> 403 

## API Documentation

La documentación completa de la API está disponible mediante Laravel Scribe:

http://localhost:8000/docs

Incluye:
  - Endpoints
  - Métodos HTTP
  - Parámetros
  - Autenticación
  - Ejemplos de respuesta
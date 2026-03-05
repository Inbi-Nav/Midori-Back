#  Midori Backend – E-commerce API REST

REST API for the Midori e-commerce management system, built with Laravel 12 + PHP 8.2. Handles users, products, orders, and payments with OAuth2 authentication via Laravel Passport.
---

##  Technologies Used

| Category       | Technology                              |
|----------------|------------------------------------------|
| Framework      | Laravel 12                               |
| Language       | PHP 8.2                                  |
| Database       | SQLITE / MySQL  (Docker)                 |
| Authentication | Laravel Passport                         |
| Testing        | PHPUnit                                  |
| API Docs       | Laravel Scribe                           |
| Containers     | Docker + Docker Compose                  |
---

##  Instalation steps
```bash

git clone https://github.com/Inbi-Nav/Midori-Back.git
cd Midori-Back
composer install
cp .env.example .env
php artisan key:generate
New-Item database/database.sqlite -ItemType File   
php artisan db:seed
php artisan passport:install
php artisan passport:client --personal
php artisan serve
```
---
##  Docker Setup
```bash
git clone https://github.com/Inbi-Nav/Midori-Back.git
cd Midori-Back
cp .env.production.example .env
docker compose up --build
php artisan migrate --seed
php artisan passport:install
```

###  Accessing Adminer
```bash

Adminer is a  database management tool included in the Docker setup. Once containers are running:
1. Open **http://localhost:8080** in your browser
2. Fill in the login form:
| Field    | Value              |
|----------|--------------------|
| System   | MySQL              |
| Server   | `mysql`            |
| Username | `midori`           |
| Password | `midori`           |
| Database | `midori`           |
```


> If you there is OAuth key permission errors:
> ```bash
> chmod 600 storage/oauth-public.key

---

## Production Deployment (Railway)
The backend is deployed on **[Railway](https://midori-back-production.up.railway.app/)** for production.
### Prerequisites
The repository connected to Railway via GitHub
-  On every push to main, Railway:
    - Builds the Docker image using Dockerfile.railway
    - Runs database migrations automatically
    - Starts the application on the assigned $PORT

```

Once deployed, the API will be available at:
https://midori-back-production.up.railway.app/api

```
 ### Environment Variables
Production environment variables are configured directly in the Railway dashboard:
- APP_ENV=production
- APP_DEBUG=false
- DB_HOST=mysql.railway.internal
- DB_DATABASE=railway
- DB_USERNAME=root
- DB_PASSWORD=******

##  Required HTTP Headers
```
Accept: application/json
Content-Type: application/json
Authorization: Bearer {token}   
```
##  Test Credentials
| Rol    | Email              | Password     |
|--------|--------------------|--------------|
| Admin  | admin@midori.com   | midori2026   |


---
##  System Roles
When a user register for the first time they are assigned as **Client** role by default.

###  Client (Regular User)
- Browse and search products
- Create orders and make payments
- View order history and cancel pending orders
- Request to become a provider
- Edit profile and change password

###  Provider (Seller)
- Create, edit, and delete their own products
- Manage categories
- View received orders
- Update order status (`processing` → `shipped` → `delivered`)
- The provider role is not assigned directly. It must be approved by an administrator.

###  Admin (Administrator)
- Full system oversight
- Manage users and roles
- Approve/decline provider requests
- View general statistics

---

##  API Endpoints

### Authentication

| Method | Endpoint         | Description                                        | Access  |
|--------|------------------|----------------------------------------------------|---------|
| POST   | `/api/register`  | Register a new user (Client role by default)       | Public  |
| POST   | `/api/login`     | Log in and receive a Bearer Token                  | Public  |
| POST   | `/api/logout`    | Log out and invalidate token                       | Auth    |
**Registro** — Campos obligatorios: `name`, `email`, `password`
**Register** — Required fields: `name`, `email`, `password`
**Login** — Campos obligatorios: `email`, `password`
**Login** — Required fields: `email`, `password`

---

### Products

| Method | Endpoint              | Description                    | Access   |
|--------|-----------------------|--------------------------------|----------|
| GET    | `/api/products`       | List all products              | Public   |
| GET    | `/api/products/{id}`  | View product details           | Public   |
| POST   | `/api/products`       | Create a new product           | Provider |
| PUT    | `/api/products/{id}`  | Update own product             | Provider |
| DELETE | `/api/products/{id}`  | Delete own product             | Provider |

---

### Orders

| Method | Endpoint                    | Description                          | Access   |
|--------|-----------------------------|--------------------------------------|----------|
| POST   | `/api/orders`               | Create a new order                   | Client   |
| GET    | `/api/orders/me`            | View my orders as a client           | Client   |
| PATCH  | `/api/orders/{id}/cancel`   | Cancel a pending order               | Client   |
| GET    | `/api/orders`               | View received orders as a provider   | Provider |
| PATCH  | `/api/orders/{id}/status`   | Update order status                  | Provider |

---

### Payments

| Method | Endpoint         | Description              | Access |
|--------|------------------|--------------------------|--------|
| POST   | `/api/payments`  | Make a payment for order | Client |

---

### User / Profile

| Method | Endpoint                        | Description                                        | Access |
|--------|---------------------------------|----------------------------------------------------|--------|
| GET    | `/api/users/me`                 | View authenticated user's profile                  | Auth   |
| PUT    | `/api/users/me`                 | Update profile (name, email, phone, etc.)          | Auth   |
| POST   | `/api/users/request-provider`   | Request to become a provider                       | Client |

---

### Administration

| Method | Endpoint                              | Description                            | Access |
|--------|---------------------------------------|----------------------------------------|--------|
| GET    | `/api/users`                          | List all users                         | Admin  |
| GET    | `/api/users/{id}`                     | View user details                      | Admin  |
| PUT    | `/api/users/{id}`                     | Update user data/role                  | Admin  |
| DELETE | `/api/users/{id}`                     | Delete a user                          | Admin  |
| GET    | `/api/provider-request`               | View pending provider requests         | Admin  |
| PATCH  | `/api/users/{id}/approve-provider`    | Approve provider request               | Admin  |
| PATCH  | `/api/users/{id}/decline-provider`    | Decline provider request               | Admin  |

---

### Provider Request Flow

1. Client → POST /api/users/request-provider
2. Admin  → GET  /api/provider-request        (view pending requests)
3. Admin  → PATCH /api/users/{id}/approve-provider  (approve)
         → PATCH /api/users/{id}/decline-provider  (decline)

### Run all tests
php artisan test

### With Docker
docker exec -it midori-app php artisan test

### Test Includes:


```
- Authentication (register, login, logout)
- Role-based authorization (client, provider, admin)
- Product CRUD operations
- Order management
- Administration features
```


##  Documentation
```
Full API documentation generated with **Laravel Scribe**:
http://127.0.0.1:8000/docs
```

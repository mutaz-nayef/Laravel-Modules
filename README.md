<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<h1 align="center">Laravel Modules</h1>

<p align="center">
Modular Laravel application with API authentication using Sanctum
</p>

---

## 🚀 About The Project

This is a Laravel-based application built using a **modular architecture** with an integrated **authentication system**.

The project focuses on organizing code into reusable modules while providing a secure and scalable foundation for
building modern web applications.

---

## 🔐 Authentication Features

The project currently includes API authentication using Laravel Sanctum:

* User Registration
* User Login
* User Logout
* Password Reset
* Email Verification

---

## 🧩 Modular Structure

The application is structured into modules to improve scalability and maintainability.
Each module built in Domain Driven Design approach
---

## ⚙️ Running the Project

This is a standard Laravel project.

If you already have a local environment that supports Laravel (PHP, Composer, MySQL, etc.), you can run it directly on
your machine.

Follow these steps to set up and run the Laravel project locally:

1. Clone the repository

````
git clone --branch=auth https://github.com/mutaz-nayef/modules-api
cd your-repo
````

2. Install dependencies

````
composer install
````

3. Setup environment

````
cp .env.example .env
php artisan key:generate
````

4. Configure database

Edit the .env file and update your database credentials:

````
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password
````

5. Run migrations

````
php artisan migrate --seed
````

6. Start the server

````
php artisan serve
````

Now open your browser at:
http://127.0.0.1:8000

If not, you can use my Docker setup for Laravel projects:

👉 https://github.com/mutaz-nayef/laravel-docker

---

## 🐳 Docker (Alternative Setup)

Using Docker:

1. Clone the repository

```bash
git clone https://github.com/mutaz-nayef/laravel-docker src
```

2. run this command

```
docker compose up -d --build
```

2. migrate tables and seed

```
docker compose exec app php artisan migrate:fresh --seed
```

Open your browser at:

http://localhost:8085

and access to database, phpmyadmin
http://localhost:8086

---

## 📄 License

This project is open-sourced under the MIT license.

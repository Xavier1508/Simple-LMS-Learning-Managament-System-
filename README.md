# Simple LMS (Learning Management System)

Welcome to Simple LMS. This project is a lightweight Learning Management System developed as a submission for our Secure Programming course. The primary goal of this project is to build a functional web application while focusing on fundamental security principles.

A key requirement for this course is a peer-security audit, where other teams will review our code to identify potential vulnerabilities. For this reason, the initial version of this application is intentionally built using native PHP, HTML, CSS, and JavaScript. This approach ensures that the core logic is transparent and accessible for auditing, without the abstraction of modern frameworks. The long-term plan is to refactor this project using the Laravel framework after the initial security assessment is complete.

## Project Evolution: From Native PHP to Laravel

Before diving into the setup, it's important to understand the move from Native PHP to the Laravel framework. Think of it like building a house:

  * **Native PHP**: This is like buying raw materials—wood, nails, cement, and bricks. You build everything from scratch, from the foundation to the roof. You have complete freedom, but it takes longer, is prone to errors, and can become disorganized without deep expertise.
  * **Laravel**: This is like buying a pre-fabricated home with a solid foundation, frame, and tested plumbing & electrical systems already in place. Your job is to design the interior, paint the walls, and add furniture. It's significantly faster, more secure, and the final structure is robust because it follows established architectural standards (known as *best practices* in coding).

Technically, Laravel provides a powerful, pre-built structure that includes:

  * **MVC Architecture (Model-View-Controller)**: Separates application logic, user interface, and database interactions, making the code cleaner and easier to manage.
  * **Eloquent ORM**: An intuitive way to work with your database. You can interact with tables as if they were simple PHP objects, eliminating the need for complex SQL queries.
  * **Built-in Security**: Provides out-of-the-box protection against common web vulnerabilities like SQL Injection and Cross-Site Scripting (XSS).
  * **Rich Ecosystem**: Access to thousands of ready-to-use packages and tools, including the **TALL Stack** (Tailwind CSS, Alpine.js, Laravel, Livewire) which we are using for a modern, full-stack development experience.

## Key Features

  - **User Authentication**: A secure registration and login system for users.
  - **Modern Project Structure**: Implements the Front Controller pattern (`public/index.php`) to create a single point of entry, enhancing security and organization.
  - **Modular Views**: Utilizes a layout system (`header.php`, `footer.php`) for efficient code reuse and easier maintenance across the application.
  - **Responsive Design**: The user interface is designed to be functional across various device sizes.

-----

## Technology Stack

This project is built with a focus on core web technologies:

  - **Backend**: PHP
  - **Frontend**: HTML, CSS, JavaScript
  - **Color Theme**: White-Orange

-----

## Getting Started

To run this project on your local machine, please follow one of the two methods below.

## Part 1: Running `Simple-LMS-App` (Native PHP)

### Method 1: Using the PHP Built-in Server (Recommended)

This is the quickest way to get the application running without needing additional software like XAMPP, as long as you have PHP installed on your system.

1.  **Clone the Repository**
    Open your terminal or Git Bash and run the following command:

    ```bash
    git clone [https://github.com/YOUR-URL/Simple-LMS-Learning-Managament-System-.git](https://github.com/YOUR-URL/Simple-LMS-Learning-Managament-System-.git)
    ```

2.  **Navigate to the Project Directory**

    ```bash
    cd Simple-LMS-Learning-Managament-System-
    ```

3.  **Start the Server**
    This command starts the server and sets the `public` directory as the document root, which is crucial for security and proper asset loading.

    ```bash
    php -S localhost:8000 -t public
    ```

4.  **Access the Application**
    Open your web browser and go to `http://localhost:8000`. The login page should be displayed.

### Method 2: Using XAMPP

This method is suitable if you prefer a full server environment with Apache and MySQL.

1.  **Ensure XAMPP is Installed**
    If you don't have it, download and install [XAMPP](https://www.apachefriends.org/index.html).

2.  **Move the Project to `htdocs`**
    Place the entire project folder inside the `htdocs` directory within your XAMPP installation folder (e.g., `C:\xampp\htdocs\Simple-LMS-Learning-Managament-System-`).

3.  **Start the Apache Server**
    Open the **XAMPP Control Panel** and click the **Start** button for the **Apache** module.

4.  **Access the Application**
    Open your web browser and go to `http://localhost/Simple-LMS-Learning-Managament-System-/public`.

## Part 2: Running `lms-laravel` (Laravel Framework)

This version is a modern, full-stack application. The setup requires a few more steps but provides a much more powerful development environment.

### Step 0: Prerequisites 🔧

Before you begin, ensure you have the following software installed on your machine:

  * **XAMPP / WAMP / MAMP**: Provides Apache, MySQL, and PHP. Make sure the Apache and MySQL services are running.
  * **Composer**: The package manager for PHP. If you don't have it, download and install it from [getcomposer.org](https://getcomposer.org).
  * **Node.js and NPM**: Required for managing and compiling frontend assets like CSS and JavaScript. Download and install it from [nodejs.org](https://nodejs.org).

### Step 1: Navigate to the Laravel Project Directory

From the root of the cloned repository, move into the `lms-laravel` directory.

```bash
cd lms-laravel
```

### Step 2: Install Dependencies

You need to install both backend (PHP) and frontend (JavaScript) dependencies.

1.  **Install PHP Dependencies**
    This command reads the `composer.json` file and downloads all necessary PHP packages, including Laravel itself.

    ```bash
    composer install
    ```

2.  **Install Frontend Dependencies**
    This command reads the `package.json` file and downloads all JavaScript packages, including Tailwind CSS and Livewire assets.

    ```bash
    npm install
    ```

### Step 3: Environment Configuration

This is a crucial step to connect your application to the database.

1.  **Create the Environment File**
    Copy the example environment file to create your own local configuration file.

    ```bash
    cp .env.example .env
    ```

2.  **Generate Application Key**
    This command generates a unique, secure key for your application.

    ```bash
    php artisan key:generate
    ```

3.  **Configure the Database**

      * Open **phpMyAdmin** (usually via the XAMPP control panel).
      * Create a new, empty database. Let's name it `lms_laravel`.
      * Now, open the `.env` file you just created in your code editor.
      * Find the database section and update the credentials to match your new database. It should look like this:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=lms_laravel
        DB_USERNAME=root
        DB_PASSWORD=
        ```

### Step 4: Run the Database Migrations

Migrations are like version control for your database. They allow you to define your table structures in PHP files. This command will read all migration files and create the necessary tables in your `lms_laravel` database.

```bash
php artisan migrate
```

Your application is now configured\!

### Step 5: Run the Servers (Two Terminals Required)

To run the Laravel application locally, you need two terminal sessions running concurrently from the `lms-laravel` directory.

  * **In your first terminal**, start the Laravel development server. This handles all the backend logic.
    ```bash
    php artisan serve
    ```
  * **In your second terminal**, start the Vite frontend development server. This compiles your CSS and JavaScript and enables hot-reloading.
    ```bash
    npm run dev
    ```
    Keep both terminals running while you are developing.

### Step 6: Access the Application

Open your web browser and go to the URL provided by `php artisan serve` (usually `http://127.0.0.1:8000`). You should see the Laravel welcome page with "Log in" and "Register" links.

-----

## Next Steps: Managing the Database Schema

During development, you will often need to modify your database structure.

  * **Modifying Tables**: You can edit the migration files located in `database/migrations/`. For example, you can add new columns to the `users` table.
  * **Creating New Tables**: Use the Artisan command to create new models and their corresponding migration files. For example:
    ```bash
    php artisan make:model Course -m
    ```
  * **Refreshing the Database**: After making changes to your migration files, you can use the following command to delete all existing tables and re-run all migrations from scratch. **Warning**: This command will delete all data in your database.
    ```bash
    php artisan migrate:fresh
    ```
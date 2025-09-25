# Simple LMS (Learning Management System)

Welcome to Simple LMS. This project is a lightweight Learning Management System developed as a submission for our Secure Programming course. The primary goal of this project is to build a functional web application while focusing on fundamental security principles.

A key requirement for this course is a peer-security audit, where other teams will review our code to identify potential vulnerabilities. For this reason, the initial version of this application is intentionally built using native PHP, HTML, CSS, and JavaScript. This approach ensures that the core logic is transparent and accessible for auditing, without the abstraction of modern frameworks. The long-term plan is to refactor this project using the Laravel framework after the initial security assessment is complete.

![image](https://i.imgur.com/your-image-link.png) ## Key Features

- **User Authentication**: A secure registration and login system for users.
- **Modern Project Structure**: Implements the Front Controller pattern (`public/index.php`) to create a single point of entry, enhancing security and organization.
- **Modular Views**: Utilizes a layout system (`header.php`, `footer.php`) for efficient code reuse and easier maintenance across the application.
- **Responsive Design**: The user interface is designed to be functional across various device sizes.

---

## Technology Stack

This project is built with a focus on core web technologies:

- **Backend**: PHP
- **Frontend**: HTML, CSS, JavaScript
- **Color Theme**: Dark-Blue

---

## Getting Started

To run this project on your local machine, please follow one of the two methods below.

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

---

Thank you for reviewing our project!

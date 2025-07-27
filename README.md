# 🌐 Affiliate Product Showcase

A simple and responsive affiliate website with a backend admin dashboard. Built with HTML, CSS, JavaScript, and PHP. It allows you to display affiliate products and manage them through a secure login-protected admin panel.

---

## 📁 Project Structure

```

your-website/
├── admin/                # Admin panel with login, dashboard, logout
│   ├── admin.php
│   ├── login.php
│   └── logout.php
├── assets/
│   ├── css/
│   │   └── styles.css
│   └── js/
│       └── script.js
├── includes/
│   ├── db.php           
│   └── fetch\_products.php
├── index.html
├── products.html
├── about.html
└── contact.html

````

---

## ✅ Features

- ⚡ Dynamic product loading from MySQL
- 🔐 Admin login with session authentication
- ➕ Add, edit, and delete products
- 💅 Responsive and modern UI design
- 🛍 Affiliate link support
- 🛡 Basic security measures (session checks, input sanitization)

---

## 🚀 Getting Started

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/affiliatemarketing.git
   cd affiliatemarketing
````

2. **Set up the database**

   * Create a MySQL database
   * Create a `products` table with appropriate fields like:
     CREATE TABLE products (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       description TEXT NOT NULL,
       image_url TEXT NOT NULL,
       affiliate_url TEXT NOT NULL,
       price_original DECIMAL(10,2) NOT NULL,
       price_discounted DECIMAL(10,2) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );


     * `id`, `name`, `description`, `image_url`, `affiliate_url`, `price_original`, `price_discounted`, `created_at`



3. **Create `includes/db.php`**
   This file is excluded from GitHub for security. Use the following template:

   ```php
   <?php
   // Prevent direct access
   if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
       http_response_code(403);
       exit('Access denied');
   }

   $servername = "your_host";
   $username = "your_username";
   $password = "your_password";
   $dbname = "your_database";

   $conn = new mysqli($servername, $username, $password, $dbname);

   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }

   $conn->set_charset("utf8mb4");
   ?>
   ```

4. **Set admin credentials in `admin/login.php`**
   Update the username and securely hash the password:

   ```php
   $correct_username = 'your_username';  
   $correct_password = 'your_strong_pasword'; 
   ```

   Generate a hash like this in any PHP file:

   ```php
   echo password_hash('your_password', PASSWORD_DEFAULT);
   ```

---

## 💡 Credits

Created by [Rijan Dhakal](https://rijandhakal0.com.np)

```

## 📄 License

This project is licensed under the [MIT License](LICENSE).

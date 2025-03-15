This project is a comprehensive website designed for brand ambassadors to register, manage their accounts, and earn bonuses by promoting the company. Built on WordPress with custom PHP and MySQL, the platform features a seamless user experience, secure password recovery via SMTP email, and a robust promo code system that rewards ambassadors for every purchase made using their unique code.

Key Features
1. User Registration
Ambassadors can create an account and receive a unique promo code.

User details and promo codes are securely stored in the ambasador_pmb_users table.

2. Promo Code Functionality
Ambassadors share their promo code with customers.

For every purchase made using the promo code, the ambassador earns 200 PLN.

The system automatically tracks and calculates bonuses in real-time.

3. Bonus Management
Bonuses are stored in the current_bonus field and can be reset by administrators.

A dedicated "Bonuses" section in the WordPress admin panel allows for easy management of ambassador earnings.

Administrators can transfer bonuses from current_bonus to paid_bonus once payments are processed.

4. Password Recovery
Users can reset their passwords securely via SMTP email.

Passwords are hashed using password_hash() for enhanced security.

5. Automated Workflows with SQL Triggers
Two custom SQL triggers ensure seamless bonus calculations and data synchronization:

after_purchase_insert: Automatically updates the ambassador's bonus when a new purchase is recorded.

after_pmb_zamowienia_insert: Syncs order data to the purchases table when a new order is placed.

Technical Stack
Frontend: HTML, CSS, JavaScript

Backend: PHP, WordPress

Database: MySQL

Email: SMTP for secure password recovery and notifications

Security: Password hashing (password_hash()), input sanitization, and validation

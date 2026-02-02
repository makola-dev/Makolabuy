# Makola

A complete multi-vendor marketplace website similar to Jumia, eBay, or AliExpress built with PHP, MySQL, HTML, CSS, JavaScript, and Bootstrap 5.

## Features

### User System
- User registration with role selection (Buyer, Seller, Admin)
- Secure login with password hashing
- Seller verification system
- Session management
- Role-based access control

### Seller Features
- Seller dashboard with statistics
- Add products with image upload
- Manage products (edit/delete)
- Product approval system (admin approval required)
- View orders and earnings
- Commission tracking

### Buyer Features
- Browse and search products
- Category filtering
- Product details page
- Shopping cart (localStorage-based)
- Checkout process
- Order history
- Order tracking

### Admin Features
- Admin dashboard with statistics
- Approve/reject seller products
- Manage sellers (verify/unverify)
- Manage orders
- Commission tracking

### Payment Integration
- Paystack payment integration (ready for production)
- Payment verification
- Order status management

## Installation

### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- XAMPP/WAMP/LAMP (for local development)

### Setup Instructions

1. **Clone or download the project**
   ```bash
   cd C:\xampp\htdocs\Makola
   ```

2. **Create the database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `makola_db`
   - Import the `database.sql` file to create all tables
   - **If you already have a database**: Run `migration_add_subcategories.sql` to add subcategories support

3. **Configure database connection**
   - Open `config/db.php`
   - Update database credentials if needed:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'makola_db');
     ```

4. **Set up file permissions**
   - Ensure the `assets/img/products/` directory is writable
   - On Windows, this should work by default
   - On Linux/Mac: `chmod 755 assets/img/products/`

5. **Configure Paystack (Optional)**
   - Open `controllers/paymentController.php`
   - Replace the placeholder keys with your Paystack keys:
     ```php
     define('PAYSTACK_PUBLIC_KEY', 'pk_test_your_public_key_here');
     define('PAYSTACK_SECRET_KEY', 'sk_test_your_secret_key_here');
     ```
   - Get your keys from https://paystack.com

6. **Access the application**
   - Start XAMPP/WAMP
   - Navigate to: `http://localhost/Makola/`

## Default Login Credentials

After importing the database, you can login with:

- **Admin Account:**
  - Email: `admin@makola.com`
  - Password: `admin123`

## Project Structure

```
/makola
│── /assets
│     ├── css/
│     │   └── style.css
│     ├── js/
│     │   ├── main.js
│     │   └── cart.js
│     ├── img/
│     │   └── products/ (product images)
│
│── /config
│     └── db.php (database configuration)
│
│── /includes
│     ├── header.php
│     ├── footer.php
│     ├── auth.php (authentication helpers)
│
│── /controllers
│     ├── authController.php
│     ├── productController.php
│     ├── orderController.php
│     ├── paymentController.php
│
│── /sellers
│     ├── dashboard.php
│     ├── add-product.php
│     ├── edit-product.php
│     ├── manage-products.php
│     ├── orders.php
│
│── /admin
│     ├── dashboard.php
│     ├── approve-products.php
│     ├── seller-requests.php
│     ├── manage-orders.php
│
│── /buyers
│     ├── cart.php
│     ├── checkout.php
│     ├── orders.php
│     ├── order-details.php
│     ├── payment.php
│     ├── payment-verify.php
│
│── index.php (homepage)
│── login.php
│── register.php
│── product.php (product details)
│── database.sql (database schema)
│── README.md
```

## Database Schema

The database includes the following tables:
- `users` - User accounts (buyers, sellers, admins)
- `categories` - Product categories
- `products` - Product listings
- `orders` - Customer orders
- `order_items` - Order line items with commission calculations
- `commissions` - Commission tracking

## Key Features Implementation

### Cart System
- Uses JavaScript localStorage for cart persistence
- Cart persists across page reloads
- Real-time cart count updates

### Commission System
- 10% commission rate (configurable)
- Commission calculated per order item
- Seller earnings tracked separately
- Commission records stored for reporting

### Security Features
- Password hashing with `password_hash()`
- Prepared statements to prevent SQL injection
- Session-based authentication
- Role-based access control
- Input validation and sanitization

## Development Notes

### Adding New Features
- Follow the existing MVC-like structure
- Use prepared statements for all database queries
- Include proper error handling
- Update this README with new features

### Customization
- Modify `assets/css/style.css` for styling
- Update Bootstrap theme in `includes/header.php`
- Adjust commission rate in `controllers/orderController.php`

## Troubleshooting

### Images not uploading
- Check `assets/img/products/` directory permissions
- Ensure directory exists
- Check PHP upload settings in `php.ini`

### Database connection errors
- Verify database credentials in `config/db.php`
- Ensure MySQL service is running
- Check database name matches

### Session issues
- Ensure `session_start()` is called
- Check PHP session configuration
- Clear browser cookies if needed

## License

This project is open source and available for educational purposes.

## Support

For issues or questions, please check the code comments or create an issue in the repository.

## Future Enhancements

- Email notifications
- Product reviews and ratings
- Wishlist functionality
- Advanced search filters
- Seller analytics dashboard
- Multi-currency support
- Shipping integration
- Inventory management alerts


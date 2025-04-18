# UgandaMove - Public Transport E-Ticketing System

A digital platform designed to simplify the process of booking tickets for buses, trains, and other public transport services in Uganda.

![UgandaMove](https://source.unsplash.com/random/1200x600/?bus,train)

## Overview

UgandaMove leverages mobile money platforms like MTN Mobile Money and Airtel Money, which are widely used by over 80% of Ugandans. By enabling passengers to book tickets online, the system eliminates long queues at ticket counters, reduces reliance on cash transactions, and provides real-time updates on travel schedules and delays.

## Features

- **Online Ticket Booking**: Book bus and train tickets quickly from anywhere
- **Real-time Journey Tracking**: Monitor vehicle location and get ETA updates
- **Mobile Money Integration**: Pay easily using MTN Mobile Money and Airtel Money
- **Secure User Accounts**: Manage your bookings, save favorite routes, and more
- **Multi-platform Support**: Access via web or mobile app
- **Digital Tickets**: No need for paper tickets, all accessible from your phone

## Technologies Used

- HTML5
- CSS3
- JavaScript
- PHP 7.4+ 
- MySQL 5.7+
- Font Awesome for icons
- Google Fonts

## Installation and Setup

### Prerequisites
- XAMPP, WAMP, MAMP, or any PHP development environment (PHP 7.4+ recommended)
- MySQL 5.7 or higher
- Web browser (Chrome, Firefox, Safari, or Edge)

### Step 1: Clone the Repository
```bash
git clone https://github.com/yourusername/ugandamove.git
# Or download and extract the ZIP file to your htdocs folder
```

### Step 2: Database Setup
1. Start your Apache and MySQL services in XAMPP/WAMP
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Create a new database named `uganda_move`
4. Import the database schema:
   - Select the `uganda_move` database
   - Click the "Import" tab
   - Select the file `database.sql` from the project 
   - Click "Go" to execute the SQL script

### Step 3: Configure the Application
1. Open `config/config.php` and update database credentials if needed:
```php
$db_host = "localhost";
$db_username = "root";     // Change if using different MySQL username
$db_password = "";         // Add your MySQL password if set
$db_name = "uganda_move";
```

2. Ensure the BASE_URL constant matches your environment:
```php
define('BASE_URL', 'http://localhost/assignments/ticketing/');
```

### Step 4: Permissions
Ensure your web server has write permissions for:
- The entire project directory (for sessions)
- Any upload directories if implemented

### Step 5: Run the Application
1. Navigate to http://localhost/assignments/ticketing/ in your browser
2. The application should load with sample data pre-populated

### Troubleshooting
- If you encounter database connection errors, verify your database credentials in `config/config.php`
- Check your web server error logs for PHP errors
- Make sure all required PHP extensions are enabled (mysqli, mbstring, etc.)
- If tables are missing, confirm that the database import completed successfully

## Sample Usage

### Booking a Ticket
1. Go to the "Book Tickets" section
2. Select your travel type (bus/train), departure city, destination, date, and number of passengers
3. Click "Search Available Tickets"
4. Choose from available options and select your preferred route
5. Complete payment using mobile money or card
6. Receive your digital ticket by email and SMS

### Tracking a Journey
1. Go to the "Track Journey" section
2. Enter your booking ID
3. View the real-time location of your vehicle, ETA, and journey updates

## Project Structure

- `index.php` - Main controller file that includes all components
- `download_ticket.php` - Handles ticket download functionality
- `styles.css` - CSS styles for the application
- `assets/` - Contains CSS and other static assets
- `config/` - Contains configuration files
  - `config.php` - Database settings and global constants
- `data/` - Contains data files
  - `routes.php` - Route information and utility functions
- `handlers/` - Contains form processing logic
  - `auth_handler.php` - Authentication functionality (login/signup)
  - `booking_handler.php` - Ticket search and booking functionality
  - `tracking_handler.php` - Journey tracking functionality
- `includes/` - Contains modular UI components
  - `header.php` - HTML head and navigation
  - `footer.php` - Footer and JavaScript
  - `hero.php` - Hero and features sections
  - `booking.php` - Booking form and search results
  - `routes_tracking.php` - Popular routes and journey tracking
  - `about.php` - About section and app download
  - `modals.php` - Modal dialogs (login, signup, payment)

## Future Enhancements

- Integration with real payment gateways
- Actual GPS tracking using Google Maps API
- Mobile app development
- Multi-language support
- Offline functionality
- Advanced analytics for transport operators

## Contributors

- [Your Name] - Designer and Developer

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- KaCyber's success in digitizing travel tickets
- O-CITY's contactless payment implementation in Kenya
- Research on transportation management systems efficiency gains
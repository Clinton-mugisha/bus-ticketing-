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
- Font Awesome for icons
- Google Fonts

## Getting Started

1. Clone the repository or download the code
2. Open `index.html` in a web browser
3. Explore the interface and test the functionality

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
# Authentication API

This repository provides a PHP API for user authentication, offering features like registration, login, logout, email verification, and password reset.

## Features
- **Registration:** Enables users to create new accounts with username, email, and password.
- **Login:** Authenticates users based on email and password.
- **Logout:** Logs users out of their session.
- **Email Verification:** Sends verification emails to newly registered users.
- **Password Reset:** Allows users to reset their passwords via email.
- **Secure Password Handling:** Uses password hashing to protect user passwords.
- **Error Handling:** Provides informative error messages for invalid inputs or failed operations.

## Requirements
- PHP 7.4 or higher
- Composer
- A mail server configured for sending emails (e.g., SMTP)

## Installation
1. Clone the repository: bash git clone https://github.com/your-username/authentication-api.git


Insert code
2. Install dependencies:
bash cd authentication-api composer install


Insert code
3. Configure the email settings:
   - Open `AuthController.php` and replace the placeholder values in the `sendEmail` function with your actual email server settings (host, username, password, etc.).

## Usage
The API is designed to be used with a web server. You can access the API endpoints using HTTP requests.

### Endpoints:
- **/register:** Register a new user (POST)
- **/login:** Login a user (POST)
- **/logout:** Logout a user (GET)
- **/verify-email:** Verify email address (POST)
- **/reset-password:** Request password reset (POST)

### Example Request (Register):
```
json { "username": "johndoe", "email": "johndoe@example.com", "password": "password123", "confirm_password": "password123" }
```

### Example Request (Login):
```
json { "email": "johndoe@example.com", "password": "password123" }
```

Insert code
### Example Request (Logout):
```
json { "email": "johndoe@example.com" }
```

### Example Response (Success):
```
{ "message": "User registered successfully" }
```

### Example Response (Error):
```
{ "error": "Invalid email or password" }
```

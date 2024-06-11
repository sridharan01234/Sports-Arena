# E-commerce Backend API

This repository contains the backend code for an e-commerce application. It provides APIs for user authentication, product management, cart operations, and more.

## Features

### User Management:
- **Registration**: Allows users to create new accounts.
- **Login**: Authenticates users using their credentials.
- **Email Verification**: Sends verification emails and handles verification.
- **Password Reset**: Enables users to reset their passwords.
- **Profile Management**: Allows users to view, update, and delete their profiles.
- **Profile Picture Upload**: Enables users to upload their profile pictures.

### Product Management:
- **Product Listing**: Retrieves a list of all products.
- **Product Details**: Retrieves details of a specific product.

### Cart Management:
- **Add to Cart**: Adds products to the user's cart.
- **Remove from Cart**: Removes products from the user's cart.
- **Clear Cart**: Empties the user's cart.

### Other Features:
- **Country, State, and City Retrieval**: Provides APIs to retrieve lists of countries, states, and cities.

## Technologies
- **PHP**: The backend is written in PHP.
- **MySQL**: The database is MySQL.
- **JSON**: Data is exchanged in JSON format.

## Installation
1. Clone the repository.
2. Configure the database connection in `config.php`.
3. Run the database migration scripts to create the necessary tables.

## Usage
The API endpoints are documented in the code comments.

## Contributing
Contributions are welcome! Please open an issue or submit a pull request.

## License
This project is licensed under the MIT License.

## Example Usage

### User Registration:
```
POST http://localhost/api/register -d '{"name": "John Doe", "email": "john.doe@example.com", "password": "password123"}'
```

### User Login:
```
POST http://localhost/api/login -d '{"email": "john.doe@example.com", "password": "password123"}'
```

### Email Verification:
```
GET http://localhost/api/verify/token
```

### Product Listing:
```
GET http://localhost/api/product/all
```
### Cart Management:

## Add to Cart:
```
POST http://localhost/api/cart/add -d '{"product_id": 1, "quantity": 2}'
```

## Get Countries:
```
GET http://localhost/api/countries/get
```


## Get States:
```
GET http://localhost/api/states/get
```


## Get Cities:
```
GET http://localhost/api/cities/get
```

# Simple Shop

This project is a simple API for catalog of products and a cart to add the products.

## Setup
It's based on Symfony and therefore requires symfony tools.

1. Make sure your PHP version is 8.0+, install PostgreSQL 13+
2. Install Symfony CLI: https://symfony.com/download
3. Install Composer: https://getcomposer.org/download
4. Check requirements: 
   ```bash
   symfony check:requirements
   ```
5. Fill actual database credentials in `.env.local` (override `DATABASE_URL`)
6. Run composer: 
   ```bash
   symfony composer install
   ```
7. Run the server:
   ```bash
   symfony server:start
   ```

You may use Postman collection of requests (`./Simple Shop API.postman_collection.json`) to see how to operate the API

## Testing
1. Fill new database credentials and a database name in `.env.test.local` (override `DATABASE_URL`)
2. Create a test database and fill it with data:
   ```bash
   symfony composer test:create-env
   ```
3. Then you will be able to run Unit, Functional, or both tests:
   ```bash
   symfony composer test:unit
   symfony composer test:functional
   symfony composer test:all
   ```
   
## TODO
- Add Docker
- Add more tests
- Hide the Product API under some authentication system
- Add Swagger
- Something else

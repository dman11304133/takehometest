# takehometest
canoe take home test

Sure! Here are the revised instructions for running and testing your Laravel project without using Docker, including instructions for accessing the code via a web browser:

**Running the Solution:**

1. Clone the project repository to your local machine.

2. Make sure you have PHP and Composer installed on your system.

3. Open a terminal or command prompt and navigate to the project directory.

4. Install the project dependencies by running the following command:
   ```bash
   composer install
   ```

5. Create a new PostgreSQL database for the project.

6. Rename the `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
   ```

7. Update the `.env` file with the necessary configuration values for your project. Specifically, make sure to set the values for the following variables:
   - `DB_HOST`: PostgreSQL database host
   - `DB_PORT`: PostgreSQL database port
   - `DB_DATABASE`: PostgreSQL database name
   - `DB_USERNAME`: PostgreSQL database username
   - `DB_PASSWORD`: PostgreSQL database password

8. Generate a new application key by running the following command:
   ```bash
   php artisan key:generate
   ```

9. Run the database migrations and seed the database with initial data by running the following command:
   ```bash
   php artisan migrate --seed
   ```

10. Start the development server by running the following command:
    ```bash
    php artisan serve
    ```

11. The Laravel application should now be running on `http://localhost:8000`.

**Accessing the Code via a Web Browser:**

1. Open a web browser and navigate to `http://localhost:8000/funds`.

2. You should see the home page of your Laravel application.

3. Explore the different pages and features of the application through the user interface provided by the routes and views.

**Testing the Solution:**

1. Open a web browser or use an API testing tool like Postman.

2. Make requests to the API endpoints to interact with the funds functionality. Here are some example endpoints:

   - To create a new fund:
     ```
     POST http://localhost:8000/api/funds
     Body: {
       "name": "My Fund",
       "manager_id": 1,
       "aliases": [
         "Alias 1",
         "Alias 2"
       ],
       "company_ids": [
         1,
         2
       ]
     }
     ```

   - To retrieve all funds:
     ```
     GET http://localhost:8000/api/funds
     ```

   - To retrieve a specific fund:
     ```
     GET http://localhost:8000/api/funds/{fund_id}
     ```

   - To update a fund:
     ```
     PUT http://localhost:8000/api/funds/{fund_id}
     Body: {
       "name": "Updated Fund",
       "manager_id": 2,
       "aliases": [
         "New Alias"
       ],
       "company_ids": [
         3
       ]
     }
     ```

   - To delete a fund:
     ```
     DELETE http://localhost:8000/api/funds/{fund_id}
     ```

3. Send the requests and verify the responses to ensure the API endpoints are working correctly.

4. You can also check the Laravel application logs for any error messages or additional information that might be helpful for troubleshooting.

That's it! You should now have the solution up and running, allowing you to test and interact with the funds functionality using the provided API endpoints, as well as access the code via a web browser.

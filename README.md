<p align="center"><a href="https://www.saloodo.com/" target="_blank"><img src="https://www.saloodo.com/wp-content/uploads/2021/05/logo-saloodo-dark.svg" width="200" alt="Saloodo"></a></p>

# About Saloodo Challenge

This is the working repository for the backend challenge for a Senior PHP Developer position at Saloodo.

- ## Brief

    A private delivery service company in Cologne handles the collection and delivery of parcels for people.
- ## Deliverables
    Create a PHP-based API server responsible for mocking authentication of the senders and the bikers, and for serving mock data, via REST API.

# Dependencies

This project is set up to be built with docker instead of installing any software you don't need on your machine, you should have [`docker`](https://docs.docker.com/get-docker/) and [`docker-compose`](https://docs.docker.com/compose/) installed on your system.

# Run the application

Kindly follow the next steps in order to be able to run the application: 


1. clone and cd to the root path of this repository.
    ```
    cd saloodo-challenge
    ```

2. if you're using a windows based system copy the `.env.example` into `.env` manually or using the command :

    ```
    copy .env.example .env
    ```

    OR if you're using a unix/linux based system: 
    
    ```
    cp .env.example .env
    ```

    (optional) replace the credentials or ports if you need to; in case deploying to a production environment or leave as is in case of development environment 

    by default ports 8000, 4306 are not used by any service on your machine, if you run any services through these ports you can change the `DOCKER_APP_PORT` and `DOCKER_DB_PORT`
    

3. build and run the docker containers defined at the `docker-compose.yml` file:
    
    ```
    docker-compose up -d --build
    ```
    Now the application is available for use on http://localhost:8000 or the port you chose


<!-- > postman collection for the progect is present within the `.postman` directory     -->

# Database Migration and Seeders
By default the database migrations are run along with the container build process, so now we need only to seed the database through the following command:

```
docker-compose exec app php artisan db:seed
```

As per requirements I have added 5 senders and 10 bikers to the initial database seeding, I have also created factories for `Customer`, `Biker`, `Parcel` models to seed the database on the go. 

# Testing the application


To run the tests for the application to make sure everything is okay you need to run the followng command: 

```
docker-compose exec app php artisan test
```

# Comments

- For the business case to be content, I've designed the flow for bikers to 
    - (first) send a request to reserve a parcel; providing expected pickup, and delivery times to be seen by the parcel owner. 
    I planned to implement a cancel action for both bikers and customers in case of any sudden change of plans, I had no time to implement it yet it's very doable.
    - (second) send a request to pickup the parcel in order for the system to observe the actual pickup time.
    - (lastly) send a request to mark the parcel as delivered and update the respective timestamps on the model.  


- I have created a simple authentication and authorization flow with the help of the multi guard authentication features provided by laravel with roles `customer` and `biker` and both are authenticated with a `jwt` token.

- By default the application initialization is done with the container build through the containers entrypoint script located within `docker-compose/startup/build.sh` file that contains the following commands
 
    ```
    composer install --no-interaction --optimize-autoloader
    php artisan key:generate
    php artisan jwt:secret --always-no
    php artisan migrate --force
    ```
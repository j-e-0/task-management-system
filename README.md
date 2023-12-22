# task-managment-system

## Introduction
This is a Task Managment application using the Laminas MVC layer and module
systems.

## Configure Enviroment Variables
Edit the file `.env` to configure database parameters.

## Schema Database
The schema Database is on path `/data/schema.sql`, always the container is start, the mysql read this file an run migrations.

## Running the Application with ***docker-compose***

Build and start the image and container using:

```bash
$ docker-compose up -d --build
```
After build and start de application access:
```
localhost:8080
```
## Running Unit Tests


- Once testing support is present, you can run the tests using:

  ```bash
  $ docker compose run -u root ws composer test
  ```

If you need to make local modifications for the PHPUnit test setup, copy
`phpunit.xml.dist` to `phpunit.xml` and edit the new file; the latter has
precedence over the former when running tests, and is ignored by version
control. (If you want to make the modifications permanent, edit the
`phpunit.xml.dist` file.)

## Running Psalm Static Analysis

- Once psalm support is present, you can run the static analysis using:

    ```bash
    $ docker compose run -u root ws composer static-analysis
    ```
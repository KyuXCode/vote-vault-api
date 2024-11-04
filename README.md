# Vote Vault API

This project used php with Laravel 11, see below instructions to replicate a local enviroment for testing


## Install Prerequisites

- ### Install PostgreSQL
- ### Install php & Composer
  If you don't have PHP and Composer installed on your local machine, the following commands will install PHP, Composer, and the Laravel installer on macOS, Windows, or Linux: 
    ```
    /bin/bash -c "$(curl -fsSL https://php.new/install/mac)"
    ```
  After running one of the commands above, you should restart your terminal session. To update PHP, Composer, and the Laravel installer after installing them via php.new, you can re-run the command in your terminal. 
  If you already have PHP and Composer installed, you may install the Laravel installer via Composer:

    ```
    composer global require laravel/installer
    ```



## Setup Local Environment

1. Create ```.env``` file in the project directory if not already
2. Find ```.env.example``` in the project directory
3. Find copy the content over to your ```.env``` file
4. Find this section in ```.env``` file
    ```
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=vote_vault_api
    DB_USERNAME=root
    DB_PASSWORD=
    ```
5. Change these variables to match your local database

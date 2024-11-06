# Vote Vault API

This project used php with Laravel 11 & PostgreSQL 16, see below instructions to replicate a local environment for testing.

## Install Prerequisites

### Install Docker Desktop
- Install Docker Desktop on your machine from this [documentation](https://docs.docker.com/get-started/get-docker/) or the link below.
    - [Mac](https://docs.docker.com/desktop/install/mac-install/)
    - [Windows](https://docs.docker.com/desktop/install/windows-install/)
    - [Linux](https://docs.docker.com/desktop/install/linux/)

### Setup
- Open the Docker Desktop app before the next stage ![Docker Desktop Logo](./InstructionScreenshots/Docker_Desktop_logo.png)

## Start the Project Locally
### 1. Start ```vote-vault-api```
Make sure you're in the ```vote-vault-api``` directory, start the docker container for the api
```
docker compose up --build
```
Go to your browser and enter this link: [http://127.0.0.1:8000/](http://127.0.0.1:8000/), you should see a default Laravel page ![Laravel Index Page](./InstructionScreenshots/laravel_landing_page.png)

## API End Points
```
{base_URL}/api/vendors
{base_URL}/api/certifications
{base_URL}/api/components
{base_URL}/api/counties
{base_URL}/api/contracts
{base_URL}/api/expenses
{base_URL}/api/inventory_units
{base_URL}/api/storage_locations
{base_URL}/api/dispositions
```

## Common Errors
### Error 1: Connection to server at "postgresql" failed: connection refused
- Sometimes ```vote_vault_dev``` image finish before ```postgres-db``` image which will throw the error like below when you ran ```docker compose up --build``` in ```vote-vault-api``` folder. ![docker issue](./InstructionScreenshots/docker_starting_order_issue.png)
- **Solution 1:** Go to docker decktop and find your ```vote-vault-api``` container and start the ```vote_vault_dev``` image again by clicking the highlighted play button ![Restart Docker Image](./InstructionScreenshots/restart_docker_image.png)
- **Solution 2:** sometime your local port at ```5432``` is taken so the container couldn't use the port to connect to the PostgreSQL database, run the following command (or equivalent on window) to check if the port is currently in use
- In your terminal, do this to see if it's in used
  ```
  sudo lsof -i :5432
  ```
  ![pid example](./InstructionScreenshots/pid_example.png)
- Kill all the processes that are running under this port
  ```
  sudo kill -9 <pid>
  ```
- Run the command again to verify no process is running now
  ```
  sudo lsof -i :5432
  ```

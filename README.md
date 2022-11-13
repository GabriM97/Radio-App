# Radio.co app
## Build and Run the application

### Requirements
- **PHP 8.1** (temporary)
- **Docker** (and **docker compose**). [Get Docker here](https://docs.docker.com/get-docker/).
- **Composer** (temporary). [Get Composer here](https://getcomposer.org/download/).

### Start the application
The application will run on `localhost:8080`. You can change the port number from the `docker-file.yaml` file.
Run the following commands from the root of the project:
```sh
> composer install
> docker compose -f setup/docker-compose.yaml up --build
> php bin/console doctrine:migrations:migrate --no-interaction
```
Go to [http://localhost:8080](http://localhost:8080) to access the homepage.
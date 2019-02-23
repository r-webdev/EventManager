# Event Manager

This repository provides:
- `Backend` framework for REST api endpoints to serve the frontend applications
- `Frontend` blades to utilise the REST api endpoints
- `Docker` containers for local development

## How do I get set up?
- clone this repo and switch into the `/source` folder
- composer install to get the PHP packages install
- copy the `.env.example` to `.env` and set the parameters
- switch to the `/docker` folder and `docker-compose up` the docker containers and access on `localhost:8080`
- Access the docker contain with `docker exec -it eventmanager-phpfpm bash`
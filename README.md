## How to setup the backend service

### Requirements

- Download and install Docker and Docker compose
- Download [Git](https://git-scm.com/download/) , if you do not have this already installed.
- Clone the "dev" branch of the repo.
- Download and install Mysql or use an existing mysql server.

### Database Setup

On the mysql server, run the script file "database/sqlScripts.sql" to create the required database and tables. If you are using an existing database then ignore the database creation script from the sql file and just run all commands starting from line 8.

### Databae Connection Configuration

You will need to change the database connection configuration in the file "config.php"

- DB_USERNAME
- DB_PASSWORD
- DB_HOST
- DB_NAME

### Installation

- Build a docker image `docker-compose up --build`. This will build and run the docker container.
- To list the running containers `docker ps`. This will display `CONTAINER ID`
- To kill a running container `docker kill <container-id>`

### Start a server

- To start the docker container `docker-compose up`
- App server available at - `http://localhost:8080/` (this api url is what needs to be configured and used from frontend application)

### Troubleshooting

If there are issues and you want to delete all your volumes and start fresh.

- `docker system prune`
- `docker container prune`
- `docker image prune`
- `docker network prune`
- `docker volume prune`

### Remove docker volume and unused containers

- `docker rmi $(docker images -q)`
- `docker volume prune`

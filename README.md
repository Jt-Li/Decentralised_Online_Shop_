# Excited Frog
## Overview
It's a Dencentralized App, user information and production information is stored in the server database,  while transactions and transaction history will happen in the block chain. 
In terms of the backend, PHP laravel and Docker are used.

## Docker setup

First, go to the website to install docker. For example, if you are a Mac user, go to [Docker For Mac](https://docs.docker.com/docker-for-mac/install/#install-and-run-docker-for-mac).
After installation, check if docker compose is installed. Noted: in some environments, docker compose is automatically installed.
Run
```
docker-compose 
```
to see if command is not found.
### Docker machine check
After Docker is installed, run
```
docker-machine ls
```
And you should see this.
![Imgur](https://i.imgur.com/XQTxT9h.png)
If you cannot see this, go the the Docker machine setup.
If you successfully see this, run the following command to configure your shell so that the program will run on this IP address.
```
eval "$(docker-machine env default)"
```
After this, you can skip the docker machine setup.

### Docker machine setup
However, the new docker may not install it automatically, we will need to create the virtual machine manually. To do this, 
```
docker-machine create --driver virtualbox default
```
However, it may give you the error similar like
'virtualbox is not installed'.
Then you will need to install virtualbox.
Try
```
brew cask install virtualbox
```
But different environments may have different situations, if the command above failed, try Google.
After virtualbox is installed, run the previous command to create virtualbox. 
After the docker machine is created successfully, you can go back to the docker-machine check section.
If there are any other problems, check the official document.
[Docker Document](https://docs.docker.com/machine/get-started/#create-a-machine).
## PHP Environment
PHP 7 is used in our project, so install PHP 7.0 or above.
The package management tool is Composer, install that.

# Backend
After setting up the docker and PHP, you should be able to run the program.
firstly, go to the directory where docker-compose.yaml is in, run
```
docker-compose build
```
This command will build the container detailed in the docker-compose.yaml.
After build is finished, run 
```
docker-compose up
```
This will start the database and nginx server.
Open another terminal and go to the folder laravel-backend,
first run the command 
```
composer install
```
run the command
```
php artisan migrate
```
This command will insert the tables detailed in the /database/migration/* into the database.
However, you might have error messages like 'pdo extension not found'. This is because the library for postgres of php is not in use, to solve this problem, try [PDO Not Found](https://help.guebs.eu/how-to-enable-postgresql-extension-for-php/) . However, different computers have different situations, if you still fail, try google.
After the migration is executed, run
```
php migrate db:seed
```
This will insert mock data to the database. 
Greate, all set! Go to the brower [Main Page](http://192.168.99.100/indexD.html#/login).
# Authors
Have a look at all the authors. They are awesome!
[Aaron Su](https://github.com/AaronSuAu)
[Rex Yang](https://github.com/yangyjrex)
[JT Li](https://github.com/Jt-Li)
[Freya Song](https://github.com/freyasong) 

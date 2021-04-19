# Advisor service

#### For starting service following actions are needed 
* Edit /docker/docker-compese.yml - change volumes pathes to correct ones (to where project is located)
* run "composer install" in app/ folder
* run "docker-compose up -d" from /docker folder
* go to {adq-challenge.loc/apidoc}

#### Implemented features:
* /apidoc endpoint - shows a table of parsed docblocs from controller actions in Advisor controller
* CRUD for Advisor
* endpoint for accessing Advisor images

#### Not implemented features
* filtering on Advisor language
* image resizing
* Not hosted

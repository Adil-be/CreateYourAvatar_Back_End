# --------------------------------#
# Makefile for the "make" command
# --------------------------------#
# ----- Programs -----
COMPOSER = composer
PHP = php
SYMFONY = symfony
SYMFONY_CONSOLE = $(PHP) bin/console
PHP_UNIT = $(PHP) bin/phpunit
NPM = npm
## ----- Symfony -----
start: ## Start the project
	$(SYMFONY) server:start
	
stop: ## Stop the project
	$(SYMFONY_CONSOLE) server:stop

db-create: ## Create the database
	$(SYMFONY_CONSOLE) doctrine:database:create 


db-drop: ## Drop the database
	$(SYMFONY_CONSOLE) doctrine:database:drop --if-exists --force 

migrate: ## Migrate the database
	$(SYMFONY_CONSOLE) doctrine:migrations:migrate -n

fixtures: ## Load the fixtures
	$(SYMFONY_CONSOLE) doctrine:fixtures:load -n

regenerate:
	$(MAKE) db-drop
	$(MAKE) db-create
	$(MAKE) migrate
	$(MAKE) fixtures
sail := ./vendor/bin/sail

.PHONY: start
start:
	@$(sail) up -d

.PHONY: stop
stop:
	@$(sail) stop

.PHONY: restart
restart: stop start

.PHONY: shell
shell: start
	@$(sail) shell

.PHONY: tinker
tinker: start
	@$(sail) artisan tinker

.PHONY: migrate
migrate: start
	@$(sail) artisan migrate

.PHONY: fixtures
fixtures: start
	@$(sail) artisan migrate:fresh --seed

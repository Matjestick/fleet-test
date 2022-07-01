behat: vendor
	$(DCR) app vendor/bin/behat
php-cs-fixer:
	$(DCR) qa php-cs-fixer fix
php-cs-fixer-dry-run:
	$(DCR) qa php-cs-fixer fix --diff --verbose --dry-run
phpstan:
	$(DCR) qa phpstan
symfony-lint:
	$(CONSOLE) lint:container
	$(CONSOLE) lint:yaml config/*.yaml config/*/*.yaml src/*/Infra/Symfony/DependencyInjection/*.yaml
cloc:
	docker run --rm -v $(PROJECT_DIR):/tmp aldanial/cloc --exclude-dir=vendor,var .
tests: vendor php-cs-fixer-dry-run symfony-lint phpstan behat
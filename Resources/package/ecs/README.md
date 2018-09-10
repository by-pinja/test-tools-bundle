# What is this

This is part of [protacon/test-tools-bundle](https://packagist.org/packages/protacon/test-tools-bundle)

## ECS

Easiest way to start using PHP CS Fixer and PHP_CodeSniffer with 0-knowledge

* [GitHub](https://github.com/Symplify/EasyCodingStandard)

This package includes also following:

* [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
* [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

## Usage

To run this tool use following command:

```bash
./vendor/bin/ecs check src
```

Additional command(s):

```bash
./vendor/bin/phpcs --standard=PSR2 --colors -p src
```

## Configuration

ECS configuration is located in `easy-coding-standard.yaml` file in your project root directory. You can change this
as you like to fit your project needs.

PHP CodeSniffer configuration is located in `.php_cs.dist` file in your project root directory. You can change this 
as you like to fit your project needs.

## Makefile 

If you're using `Makefile` in your Symfony project you could use following in your `Makefile`:

```bash
###> ecs ###
ecs: ## Runs The Easiest Way to Use Any Coding Standard
	@echo "\033[32mRunning EasyCodingStandard\033[39m"
	@php ./vendor/bin/ecs --version
	@php -d error_reporting=0 ./vendor/bin/ecs --clear-cache --no-progress-bar check src
###< ecs ###

###> phpcs ###
phpcs: ## Runs PHP CodeSniffer
	@echo "\033[32mRunning PhpCodeSniffer\033[39m"
	@php ./vendor/bin/phpcs --version
	@php ./vendor/bin/phpcs --standard=PSR2 --colors -p src
###< phpcs ###
```

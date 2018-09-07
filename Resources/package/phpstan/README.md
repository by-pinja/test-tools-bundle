# What is this

This is part of [protacon/test-tools-bundle](https://packagist.org/packages/protacon/test-tools-bundle)

## PHP Static Analysis Tool (PHPStan)

PHPStan focuses on finding errors in your code without actually running it. It catches whole classes of bugs even 
before you write tests for the code.

* [GitHub](https://github.com/phpstan/phpstan)

## Usage

To run this tool use following command:

```bash
 ./vendor/bin/phpstan analyze --level 7 src
```

## Configuration

_no needed configuration_

## Makefile 

If you're using `Makefile` in your Symfony project you could use following in your `Makefile`:

```bash
###> phpstan ###
phpstan: ## Runs PHPStan static analysis tool
	@echo "\033[32mRunning PHPStan - PHP Static Analysis Tool\033[39m"
	@./vendor/bin/phpstan --version
	@./vendor/bin/phpstan analyze --level 7 src
###< phpstan ###
```

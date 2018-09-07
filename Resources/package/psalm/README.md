# What is this

This is part of [protacon/test-tools-bundle](https://packagist.org/packages/protacon/test-tools-bundle)

## Psalm

A static analysis tool for finding errors in PHP applications

* [Homepage](https://getpsalm.org/)
* [GitHub](https://github.com/vimeo/psalm)

## Usage

To run this tool use following command:

```bash
 ./vendor/bin/psalm
```

## Configuration

Psalm configuration is located in `psalm.xml` file in your project root directory. You can change this
as you like to fit your project needs.

## Makefile 

If you're using `Makefile` in your Symfony project you could use following in your `Makefile`:

```bash
###> psalm ###
psalm: ## Runs Psalm static analysis tool
	@echo "\033[32mRunning Psalm - A static analysis tool for PHP\033[39m"
	@php ./vendor/bin/psalm --version
	@php ./vendor/bin/psalm --no-cache
###< psalm ###
```

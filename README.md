# What is this

[![MIT licensed](https://img.shields.io/badge/license-MIT-blue.svg)](./LICENSE)
[![Build Status](https://travis-ci.org/protacon/test-tools-bundle.png?branch=master)](https://travis-ci.org/protacon/test-tools-bundle)
[![Total Downloads](https://poser.pugx.org/protacon/test-tools-bundle/downloads)](https://packagist.org/packages/protacon/test-tools-bundle)

Testing and static analysis tools bundle for Symfony projects

## Table of Contents

* [What is this?](#what-is-this)
  * [Table of Contents](#table-of-contents)
  * [Requirements](#requirements)
  * [Installation](#installation)
  * [Usage](#usage)
  * [Development](#development)
    * [IDE](#ide)
    * [Testing](#testing)
  * [Authors](#authors)
  * [License](#license)

## Requirements

* PHP 7.1 or higher
* [Composer](https://getcomposer.org/)

## Installation

The recommended way to install this library is with Composer. Composer is a dependency management 
tool for PHP that allows you to declare the dependencies your project needs and installs them into 
your project.

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

You can add this library as a dependency to your project using following command:

```bash
composer require protacon/test-tools-bundle
```

## Usage

Bundle exposes few Symfony commands in the `test-tools` namespace you can utilize

```bash
./bin/console test-tools:init    Initialize test tools
./bin/console test-tools:check   Check outdated vendor dependencies
```

## Development

* [PSR-2: Coding Style Guide](http://www.php-fig.org/psr/psr-2/)

### IDE

I highly recommend that you use "proper"
[IDE](https://en.wikipedia.org/wiki/Integrated_development_environment)
to development your application. Below is short list of some popular IDEs that
you could use.

* [PhpStorm](https://www.jetbrains.com/phpstorm/)
* [NetBeans](https://netbeans.org/)
* [Sublime Text](https://www.sublimetext.com/)
* [Visual Studio Code](https://code.visualstudio.com/)

If you're using [PhpStorm](https://www.jetbrains.com/phpstorm/) following links
will help you to get things rolling.

* [Using PHP Code Sniffer Tool](https://www.jetbrains.com/help/phpstorm/10.0/using-php-code-sniffer-tool.html)
* [PHP Code Sniffer in PhpStorm](https://confluence.jetbrains.com/display/PhpStorm/PHP+Code+Sniffer+in+PhpStorm)

### Testing

Library uses [PHPUnit](https://phpunit.de/) for testing. You can run all tests
by following command:

```bash
./vendor/bin/phpunit
```

Or you could easily configure your IDE to run those for you.

### Environment

Bundle provides a Dockerfile and docker-compose configuration to develop the bundle in the container with the all tools necessary installed

````bash
docker-compose up -d
docker-compose exec app sh
````

## Authors

- [Atte Tarvainen](https://github.com/tarvainen)
- [Tarmo Leppänen](https://github.com/tarlepp)

## License

[The MIT License (MIT)](LICENSE)

Copyright (c) 2019 Protacon

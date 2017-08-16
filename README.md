
### Basics

* A sandbox for functional concepts in PHP.

### Info

* uses [this](https://hub.docker.com/r/phpunit/phpunit/) Docker image
    * as of AUG 2017, uses PHPUnit 6.0.x and PHP 7.0
* uses [Composer](https://getcomposer.org)

### Composer

* install Composer
* run the following:

```
php ~/path/composer.phar install
```

### Composer Sidebar
r
* the `composer.json` was originally populated with (many braincells were lost trying to discover this):

```
php ~/path/composer.phar require widmogrod/php-functional:dev-master
php ~/path/composer.phar require phpunit/phpunit
```

### Docker Setup

* install Docker
* in Docker window, `cd` to appropriate directory, then:

<pre>
docker pull phpunit/phpunit
</pre>

### To run tests

* `./run_tests.sh`


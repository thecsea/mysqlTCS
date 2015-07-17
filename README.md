# mysqltcs
Build status: [![Build Status](https://travis-ci.org/thecsea/mysqltcs.svg?branch=master)](https://travis-ci.org/thecsea/mysqltcs) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thecsea/mysqltcs/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/thecsea/mysqltcs/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/thecsea/mysqltcs/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/thecsea/mysqltcs/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/thecsea/mysqltcs/badges/build.png?b=master)](https://scrutinizer-ci.com/g/thecsea/mysqltcs/build-status/master)

A simple library for mysql written in php

In this class you can use the same db connection in more than one instances

This library allow you to make common database operations immediately 

# Download, install and use

## Download

### via git
Clone repository

`git clone https://github.com/thecsea/mysqltcs.git`

### via composer
add the following dependence 

`"thecsea/mysqltcs": "dev-master"`

##Install/Updated
Execute composer (download composer here https://getcomposer.org/)
###Install

`php composer.phar install`

###Update

`php composer.phar update`

you have to perform an update when a new version is released

##How to use

When composer installation is finished you will see `vendor/autoload.php` that is the auload generated by composer. If you have set `mysqltcs` as composer dependence the autoload loads both mysqltcs and other dependecies. So you just have to include autload in each file where you want use mysqltcs and create the mysqtcs object in the following way:

`$connection = new it\thecsea\mysqltcs\Mysqltcs(...)`

or

`use it\thecsea\mysqltcs\Mysqltcs;` and `$connection = new Mystcs(...)`


# Tests
Change db data in `tests/config.php`

Import `tests/mysqltcs.sql`

Execute the unit tests:

1. Go in the root directory
2. Type `phpunit` or if you have downloaded the phar `php phpunit-xxx.phar`

In fact `phpunit.xml` contains the correcttest configuration

**CAUTION**: each time tests are executed, the database must be in the initial state, like the import has just been executed (you should have a empty  table, only the db structure)

# By [thecsea.it](http://www.thecsea.it)

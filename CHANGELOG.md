# Changelog
This file contains highlights of what changes on each version of the [Free Mobile for Yii](https://github.com/cedx/yii2-free-mobile) library.

## Version 2.3.0
- Added the `jsonSerialize()` and `toJSON()` methods.
- Fixed a bug in the constructor.

## Version 2.2.0
- Added a fluent interface to the setters.
- Renamed the `userName` property to `username`.
- Updated the package dependencies.

## Version 2.1.0
- Externalized the core features.
- Set the export interval to `1`.

## Version 2.0.1
- Updated the package dependencies.

## Version 2.0.0
- Replaced the [cURL](https://secure.php.net/manual/en/book.curl.php) functions by the [Guzzle](http://guzzlephp.org) HTTP client.
- Breaking change: no exception is thrown when a network error occurs.
- Breaking change: removed the `$throwExceptions` parameter from the `export` method.
- Breaking change: using asynchronous requests to send the logs to the remote service.

## Version 1.0.1
- Code optimization.
- Updated the package dependencies.

## Version 1.0.0
- First stable release.
- Updated the package dependencies.

## Version 0.5.2
- Replaced [Doxygen](http://www.doxygen.org) documentation generator by [phpDocumentor](https://www.phpdoc.org).

## Version 0.5.1
- Replaced [SonarQube](http://www.sonarqube.org) code analyzer by [Codacy](https://www.codacy.com) service.
- Renamed the project according to its [Composer](https://getcomposer.org) package name.
- Updated the package dependencies.

## Version 0.5.1
- Removed dependency on external [PHP Mess Detector](https://phpmd.org) and [PHPUnit](https://phpunit.de) interfaces.
- Updated the package dependencies.

## Version 0.5.0
- Breaking change: using [PHP 7](https://secure.php.net/manual/en/migration70.new-features.php) features, like scalar and return type declarations.

## Version 0.4.4
- Added unit tests.
- Added code coverage.
- Updated the development dependencies.

## Version 0.4.3
- Changed licensing for the [Apache License Version 2.0](http://www.apache.org/licenses/LICENSE-2.0).

## Version 0.4.2
- Added a lint task based on [PHP Mess Detector](http://phpmd.org) code analyzer.
- Added support for [SonarQube](http://www.sonarqube.org) code analyzer.

## Version 0.4.1
- Replaced the custom build scripts by [Phing](https://www.phing.info).

## Version 0.4.0
- Dropped the development dependencies based on [Node.js](https://nodejs.org).
- Replaced the build system by custom scripts.
- Replaced the documentation system by [Doxygen](http://www.doxygen.org).

## Version 0.3.1
- Updated the development dependencies.

## Version 0.3.0
- Breaking change: ported the library API to [Yii](http://www.yiiframework.com) version 2.

## Version 0.2.0
- Breaking change: ported the library API to [namespaces](https://secure.php.net/manual/en/language.namespaces.php).
- Lowered the required [PHP](https://secure.php.net) version.

## Version 0.1.1
- Using [Gulp.js](http://gulpjs.com) as build system.

## Version 0.1.0
- Initial release.

# Changelog

## Version [11.0.0](https://github.com/cedx/yii2-free-mobile/compare/v10.0.0...v11.0.0)

## Version [10.0.0](https://github.com/cedx/yii2-free-mobile/compare/v9.3.0...v10.0.0)
- Breaking change: using camelcase instead of all caps for constants.
- Updated the package dependencies.

## Version [9.3.0](https://github.com/cedx/yii2-free-mobile/compare/v9.2.0...v9.3.0)
- Modified the package layout.
- Reverted the handling of URIs to [Guzzle](https://github.com/guzzle/psr7) library.
- The `ClientException` class now extends from `yii\base\Exception`.
- Updated the package dependencies.

## Version [9.2.0](https://github.com/cedx/yii2-free-mobile/compare/v9.1.0...v9.2.0)
- Replaced [Guzzle](https://github.com/guzzle/psr7) library by [PHP League](https://uri.thephpleague.com) for URIs.
- Updated the package dependencies.

## Version [9.1.0](https://github.com/cedx/yii2-free-mobile/compare/v9.0.0...v9.1.0)
- Added an example code.
- Dropped the dependency on [PHPUnit-Expect](https://dev.belin.io/phpunit-expect).
- Replaced the [Phing](https://www.phing.info) build system by [Robo](https://robo.li).
- Updated the package dependencies.

## Version [9.0.0](https://github.com/cedx/yii2-free-mobile/compare/v8.2.0...v9.0.0)
- Breaking change: raised the required [PHP](https://www.php.net) version.
- Updated the package dependencies.

## Version [8.2.0](https://github.com/cedx/yii2-free-mobile/compare/v8.1.0...v8.2.0)
- Added new unit tests.
- Optimized the build system.
- Updated the package dependencies.

## Version [8.1.0](https://github.com/cedx/yii2-free-mobile/compare/v8.0.0...v8.1.0)
- Added a user guide based on [MkDocs](http://www.mkdocs.org).
- Added the `ClientException` class.
- Updated the package dependencies.

## Version [8.0.0](https://github.com/cedx/yii2-free-mobile/compare/v7.0.0...v8.0.0)
- Breaking change: raised the required [PHP](https://www.php.net) version.
- Breaking change: renamed the `Client::EVENT_BEFORE_SEND` constant to `EVENT_REQUEST`.
- Breaking change: renamed the `Client::EVENT_AFTER_SEND` constant to `EVENT_RESPONSE`.
- Breaking change: using PHP 7.1 features, like nullable types and void functions.

## Version [7.0.0](https://github.com/cedx/yii2-free-mobile/compare/v6.0.0...v7.0.0)
- Breaking change: removed the `jsonSerialize()` and `__toString()` methods.
- Updated the package dependencies.

## Version [6.0.0](https://github.com/cedx/yii2-free-mobile/compare/v5.1.0...v6.0.0)
- Breaking change: the `Client::$endPoint` property is now an instance of [`Psr\Http\Message\UriInterface`](http://www.php-fig.org/psr/psr-7/#35-psrhttpmessageuriinterface) interface.
- Added new unit tests.
- Changed licensing for the [MIT License](https://opensource.org/licenses/MIT).
- Updated the package dependencies.

## Version [5.1.0](https://github.com/cedx/yii2-free-mobile/compare/v5.0.0...v5.1.0)
- Enabled the strict typing.
- Replaced [phpDocumentor](https://www.phpdoc.org) documentation generator by [ApiGen](https://github.com/ApiGen/ApiGen).
- Updated the package dependencies.

## Version [5.0.0](https://github.com/cedx/yii2-free-mobile/compare/v4.1.1...v5.0.0)
- Breaking change: removed the `RequestEvent` and `RequestResponse` classes.
- Breaking change: renamed the `Client::EVENT_REQUEST` to `EVENT_BEFORE_SEND`.
- Breaking change: renamed the `Client::EVENT_RESPONSE` to `EVENT_AFTER_SEND`.
- Breaking change: replaced most of getters and setters by properties.
- Added the `Client::endPoint` property.
- Added the `Client::DEFAULT_ENDPOINT` constant.
- Dropped the dependency on the `cedx/freemobile` module.
- Moved the initializations from the constructors to the `init()` methods.
- Ported the unit test assertions from [TDD](https://en.wikipedia.org/wiki/Test-driven_development) to [BDD](https://en.wikipedia.org/wiki/Behavior-driven_development).
- Updated the package dependencies.

## Version [4.1.1](https://github.com/cedx/yii2-free-mobile/compare/v4.1.0...v4.1.1)
- Fixed the [issue #1](https://github.com/cedx/yii2-free-mobile/issues/1): unable to use an application component ID to initialize the `LogTarget::client` property.
- Improved the code coverage.

## Version [4.1.0](https://github.com/cedx/yii2-free-mobile/compare/v4.0.0...v4.1.0)
- Replaced the [Codacy](https://www.codacy.com) code coverage service by the [Coveralls](https://coveralls.io) one.
- Updated the package dependencies.

## Version [4.0.0](https://github.com/cedx/yii2-free-mobile/compare/v3.0.0...v4.0.0)
- Breaking: changed the root namespace to `yii\freemobile`.
- Breaking: renamed the `FreeMobileTarget` class to `LogTarget`.
- Added the `Client` component.
- Added the `RequestEvent` and `ResponseEvent` events.

## Version [3.0.0](https://github.com/cedx/yii2-free-mobile/compare/v2.3.0...v3.0.0)
- Breaking change: removed the `toJSON()` method.
- Removed the `final` modifier from the `jsonSerialize()` method.
- Updated the package dependencies.

## Version [2.3.0](https://github.com/cedx/yii2-free-mobile/compare/v2.2.0...v2.3.0)
- Added the `jsonSerialize()` and `toJSON()` methods.
- Fixed a bug in the constructor.

## Version [2.2.0](https://github.com/cedx/yii2-free-mobile/compare/v2.1.0...v2.2.0)
- Added a fluent interface to the setters.
- Renamed the `userName` property to `username`.
- Updated the package dependencies.

## Version [2.1.0](https://github.com/cedx/yii2-free-mobile/compare/v2.0.1...v2.1.0)
- Externalized the core features.
- Set the export interval to `1`.

## Version [2.0.1](https://github.com/cedx/yii2-free-mobile/compare/v2.0.0...v2.0.1)
- Updated the package dependencies.

## Version [2.0.0](https://github.com/cedx/yii2-free-mobile/compare/v1.0.1...v2.0.0)
- Replaced the [cURL](https://www.php.net/manual/en/book.curl.php) functions by the [Guzzle](http://guzzlephp.org) HTTP client.
- Breaking change: no exception is thrown when a network error occurs.
- Breaking change: removed the `$throwExceptions` parameter from the `export` method.
- Breaking change: using asynchronous requests to send the logs to the remote service.

## Version [1.0.1](https://github.com/cedx/yii2-free-mobile/compare/v1.0.0...v1.0.1)
- Code optimization.
- Updated the package dependencies.

## Version [1.0.0](https://github.com/cedx/yii2-free-mobile/compare/v0.5.2...v1.0.0)
- First stable release.
- Updated the package dependencies.

## Version [0.5.2](https://github.com/cedx/yii2-free-mobile/compare/v0.5.1...v0.5.2)
- Replaced [Doxygen](http://www.doxygen.org) documentation generator by [phpDocumentor](https://www.phpdoc.org).

## Version [0.5.1](https://github.com/cedx/yii2-free-mobile/compare/v0.5.0...v0.5.1)
- Removed dependency on external [PHP Mess Detector](https://phpmd.org) and [PHPUnit](https://phpunit.de) interfaces.
- Renamed the project according to its [Composer](https://getcomposer.org) package name.
- Replaced [SonarQube](http://www.sonarqube.org) code analyzer by [Codacy](https://www.codacy.com) service.
- Updated the package dependencies.

## Version [0.5.0](https://github.com/cedx/yii2-free-mobile/compare/v0.4.4...v0.5.0)
- Breaking change: using [PHP 7](https://www.php.net/manual/en/migration70.new-features.php) features, like scalar and return type declarations.

## Version [0.4.4](https://github.com/cedx/yii2-free-mobile/compare/v0.4.3...v0.4.4)
- Added unit tests.
- Added code coverage.
- Updated the development dependencies.

## Version [0.4.3](https://github.com/cedx/yii2-free-mobile/compare/v0.4.2...v0.4.3)
- Changed licensing for the [Apache License Version 2.0](http://www.apache.org/licenses/LICENSE-2.0).

## Version [0.4.2](https://github.com/cedx/yii2-free-mobile/compare/v0.4.1...v0.4.2)
- Added a lint task based on [PHP Mess Detector](http://phpmd.org) code analyzer.
- Added support for [SonarQube](http://www.sonarqube.org) code analyzer.

## Version [0.4.1](https://github.com/cedx/yii2-free-mobile/compare/v0.4.0...v0.4.1)
- Replaced the custom build scripts by [Phing](https://www.phing.info).

## Version [0.4.0](https://github.com/cedx/yii2-free-mobile/compare/v0.3.1...v0.4.0)
- Dropped the development dependencies based on [Node.js](https://nodejs.org).
- Replaced the build system by custom scripts.
- Replaced the documentation system by [Doxygen](http://www.doxygen.org).

## Version [0.3.1](https://github.com/cedx/yii2-free-mobile/compare/v0.3.0...v0.3.1)
- Updated the development dependencies.

## Version [0.3.0](https://github.com/cedx/yii2-free-mobile/compare/v0.2.0...v0.3.0)
- Breaking change: ported the library API to [Yii](https://www.yiiframework.com) version 2.

## Version [0.2.0](https://github.com/cedx/yii2-free-mobile/compare/v0.1.1...v0.2.0)
- Breaking change: ported the library API to [namespaces](https://www.php.net/manual/en/language.namespaces.php).
- Lowered the required [PHP](https://www.php.net) version.

## Version [0.1.1](https://github.com/cedx/yii2-free-mobile/compare/v0.1.0...v0.1.1)
- Using [Gulp](https://gulpjs.com) as build system.

## Version 0.1.0
- Initial release.

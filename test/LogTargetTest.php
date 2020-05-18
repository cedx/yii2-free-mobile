<?php declare(strict_types=1);
namespace yii\freemobile;

use PHPUnit\Framework\{Assert, TestCase};
use yii\base\{InvalidConfigException};
use yii\log\{Logger};
use function PHPUnit\Framework\{assertThat, equalTo, identicalTo, isInstanceOf, logicalAnd};

/** @testdox yii\freemobile\LogTarget */
class LogTargetTest extends TestCase {

	/** @testdox ->formatMessage() */
	function testFormatMessage(): void {
		// It should return a formatted message including the log level and category.
		$message = ["Hello World!", Logger::LEVEL_ERROR, "tests", time()];
		assertThat((new LogTarget)->formatMessage($message), equalTo("tests: Hello World!"));
	}

	/** @testdox ->init() */
	function testInit(): void {
		// It should throw an exception if the client is unavailable.
		try {
			\Yii::$app->set("freemobile", null);
			new LogTarget;
			Assert::fail("Exception not thrown");
		}

		catch (\Throwable $e) {
			assertThat($e, isInstanceOf(InvalidConfigException::class));
		}

		// It should not throw an exception if the client is available.
		try {
			\Yii::$app->set("freemobile", new Client(["username" => "anonymous", "password" => "secret"]));
			new LogTarget;
		}

		catch (\Throwable $e) {
			Assert::fail($e->getMessage());
		}

		// It should allow a customized client component ID.
		\Yii::$app->set("freemobileTest", [
			"class" => Client::class,
			"username" => "anonymous",
			"password" => "secret"
		]);

		assertThat((new LogTarget(["client" => "freemobileTest"]))->client, logicalAnd(
			isInstanceOf(Client::class),
			identicalTo(\Yii::$app->get("freemobileTest"))
		));
	}

	/** @before This method is called before each test. */
	protected function setUp(): void {
		\Yii::$app->set("freemobile", new Client(["username" => "anonymous", "password" => "secret"]));
	}
}

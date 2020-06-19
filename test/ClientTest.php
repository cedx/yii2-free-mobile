<?php declare(strict_types=1);
namespace yii\freemobile;

use PHPUnit\Framework\{Assert, TestCase};
use yii\base\InvalidConfigException;
use yii\httpclient\RequestEvent;
use function PHPUnit\Framework\{assertThat, isInstanceOf, stringStartsWith};

/** @testdox yii\freemobile\Client */
class ClientTest extends TestCase {

	/** @testdox ->init() */
	function testInit(): void {
		// It should throw an exception if the username or password is empty.
		$this->expectException(InvalidConfigException::class);
		new Client;
	}

	/** @testdox ->sendMessage() */
	function testSendMessage(): void {
		// It should throw a `ClientException` if a network error occurred.
		try {
			$config = ["username" => "anonymous", "password" => "secret", "endPoint" => "http://localhost:10000/"];
			(new Client($config))->sendMessage("Hello World!");
			Assert::fail("Exception not thrown");
		}

		catch (\Throwable $e) {
			assertThat($e, isInstanceOf(ClientException::class));
		}

		// It should trigger events.
		$client = new Client(["username" => getenv("FREEMOBILE_USERNAME"), "password" => getenv("FREEMOBILE_PASSWORD")]);
		$client->on(Client::eventRequest, function(RequestEvent $event) {
			assertThat($event->request->getFullUrl(), stringStartsWith("https://smsapi.free-mobile.fr/sendmsg?"));
		});

		// It should send SMS messages if credentials are valid.
		try { $client->sendMessage("Bonjour Cédric, à partir du Yii Framework !"); }
		catch (\Throwable $e) { Assert::fail($e->getMessage()); }
	}
}

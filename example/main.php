<?php declare(strict_types=1);
use yii\freemobile\{Client, ClientException, LogTarget};

/** Sends an SMS notification to a Free Mobile account. */
function sendNotification(): void {
	try {
		$client = new Client([
			"username" => "your account identifier", // e.g. "12345678"
			"password" => "your API key" // e.g. "a9BkVohJun4MA"
		]);

		$client->sendMessage("Hello World!");
		echo "The message was sent successfully";
	}

	catch (Throwable $e) {
		echo "An error occurred: ", $e->getMessage(), PHP_EOL;
		if ($e instanceof ClientException) echo "From: ", $e->getUri(), PHP_EOL;
	}
}

/** Logs a message by using an SMS notification. */
function logMessage(): void {
	Yii::$app->log->targets[] = new LogTarget([
		"categories" => ["app*"],
		"levels" => ["error"],
		"client" => [
			"class" => Client::class,
			"username" => "your account identifier", // e.g. "12345678"
			"password" => "your API key" // e.g. "a9BkVohJun4MA"
		]
	]);

	Yii::error("An error occurred.");
}

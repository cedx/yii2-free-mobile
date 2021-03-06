<?php declare(strict_types=1);
namespace yii\freemobile;

use Psr\Http\Message\UriInterface;
use yii\base\Exception;

/** An exception caused by an error in a `Client` request. */
class ClientException extends Exception {

	/** @var UriInterface|null The URL of the HTTP request or response that failed. */
	private ?UriInterface $uri;

	/**
	 * Creates a new client exception.
	 * @param string $message A message describing the error.
	 * @param UriInterface|null $uri The URL of the HTTP request or response that failed.
	 * @param \Throwable|null $previous The previous exception used for the exception chaining.
	 */
	function __construct(string $message, ?UriInterface $uri = null, ?\Throwable $previous = null) {
		parent::__construct($message, 0, $previous);
		$this->uri = $uri;
	}

	/**
	 * Gets the user-friendly name of this exception.
	 * @return string The user-friendly name of this exception.
	 */
	function getName(): string {
		return "Free Mobile Client Exception";
	}

	/**
	 * Gets the URL of the HTTP request or response that failed.
	 * @return UriInterface|null The URL of the HTTP request or response that failed.
	 */
	function getUri(): ?UriInterface {
		return $this->uri;
	}
}

<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Exceptions\RequestFailedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;


class Request
{

	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';
	const METHOD_DELETE = 'DELETE';

	const ERROR_MESSAGE_KEY = 'message';

	/** @var Client */
	private $client;


	/**
	 * @param Client $client
	 */
	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	/**
	 * @param string $path
	 * @return array|NULL response parsed into associative array or NULL if response is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function performGet($path)
	{
		return $this->parseJson($this->sendRequest(self::METHOD_GET, $path));
	}

	/**
	 * @param string $path
	 * @param array $content body of POST request
	 * @return array|NULL response parsed into associative array or NULL if response is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function performPost($path, array $content = [])
	{
		return $this->parseJson($this->sendRequest(self::METHOD_POST, $path, $content));
	}

	/**
	 * @param string $path
	 * @return array|NULL response parsed into associative array or NULL if response is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function performDelete($path)
	{
		return $this->parseJson($this->sendRequest(self::METHOD_DELETE, $path));
	}

	/**
	 * @param string $method
	 * @param string $path
	 * @param array $content
	 * @return ResponseInterface
	 * @throws RequestFailedException when request failed for some reason
	 */
	private function sendRequest($method, $path, array $content = [])
	{
		try {
			$options = $content !== [] ? ['json' => $content] : [];
			return $this->client->request($method, $path, $options);
		}
		catch (RequestException $ex) {
			$message = $this->getErrorMessage($ex->getResponse());
			throw new RequestFailedException($message, $ex->getCode(), $ex);
		}
	}

	/**
	 * @param ResponseInterface|NULL $response
	 * @return NULL|string
	 */
	private function getErrorMessage(ResponseInterface $response = NULL)
	{
		if ($response !== NULL) {
			$json = $this->parseJson($response);
			return is_array($json) && isset($json[self::ERROR_MESSAGE_KEY])
				? $json[self::ERROR_MESSAGE_KEY]
				: NULL;
		}
		return NULL;
	}

	/**
	 * @param ResponseInterface $response
	 * @return array|NULL
	 */
	private function parseJson(ResponseInterface $response)
	{
		return json_decode($response->getBody(), true);
	}

}

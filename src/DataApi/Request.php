<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Exceptions\RequestFailedException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Stream\Stream;


class Request
{

	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';
	const METHOD_DELETE = 'DELETE';

	const ERROR_MESSAGE_KEY = 'message';

	/** @var GuzzleClient */
	private $client;

	/** @var array */
	private $requestOptions = [];


	/**
	 * @param GuzzleClient $client
	 * @param array $requestOptions array of GuzzleClient request options
	 */
	public function __construct(GuzzleClient $client, array $requestOptions = [])
	{
		$this->client = $client;
		$this->requestOptions = $requestOptions;
	}

	/**
	 * @param string $url
	 * @return array|NULL response parsed into associative array or NULL if response is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function performGet($url)
	{
		return $this->parseJson($this->sendRequest(self::METHOD_GET, $url));
	}

	/**
	 * @param string $url
	 * @param array $content body of POST request
	 * @return array|NULL response parsed into associative array or NULL if response is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function performPost($url, array $content = [])
	{
		return $this->parseJson($this->sendRequest(self::METHOD_POST, $url, $content));
	}

	/**
	 * @param string $url
	 * @return array|NULL response parsed into associative array or NULL if response is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function performDelete($url)
	{
		return $this->parseJson($this->sendRequest(self::METHOD_DELETE, $url));
	}

	/**
	 * @param string $method
	 * @param string $url
	 * @param array $content
	 * @return ResponseInterface
	 * @throws RequestFailedException when request failed for some reason
	 */
	private function sendRequest($method, $url, array $content = [])
	{
		try {
			$request = $this->client->createRequest($method, $url, $this->requestOptions);
			$request->setHeader('Content-Type', 'application/json; charset=utf-8');
			if (!empty($content)) {
				$request->setBody(Stream::factory(json_encode($content)));
			};
			return $this->client->send($request);
		}
		catch (RequestException $ex) {
			$response = $ex->getResponse();
			if ($response !== NULL) {
				$message = $this->getErrorMessage($response);
			}
			else {
				$message = $ex->getMessage();
			}
			throw new RequestFailedException($message, $ex->getCode(), $ex);
		}
	}

	/**
	 * @param ResponseInterface $response
	 * @return NULL|string
	 */
	private function getErrorMessage(ResponseInterface $response)
	{
		$json = $this->parseJson($response);
		return is_array($json) && isset($json[self::ERROR_MESSAGE_KEY])
			? $json[self::ERROR_MESSAGE_KEY]
			: NULL;
	}

	/**
	 * @param ResponseInterface $response
	 * @return array|NULL
	 * @throws RequestFailedException when json parsing failed
	 */
	private function parseJson(ResponseInterface $response)
	{
		try {
			return $this->isJsonResponse($response) ? $response->json() : NULL;
		}
		catch (\RuntimeException $ex) {
			throw new RequestFailedException($ex->getMessage(), $ex->getCode(), $ex);
		}
	}

	/**
	 * @param ResponseInterface $response
	 * @return boolean
	 */
	private function isJsonResponse(ResponseInterface $response)
	{
		return strpos(strtolower($response->getHeader('content-type')), 'application/json') !== false;
	}

}

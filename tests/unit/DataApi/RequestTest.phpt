<?php

namespace DataBreakers\DataApi;

use DataBreakers\TestCase;
use GuzzleHttp\Client;
use Mockery\MockInterface;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Message\RequestInterface;

require_once __DIR__ . '/../bootstrap.php';


class RequestTest extends TestCase
{

	const URL = 'https://api.databreakers.com/v1/foo/bar';
	const ERROR_MESSAGE = 'Foo bar baz error';

	/** @var Request */
	private $request;

	/** @var Client|MockInterface */
	private $client;

	/** @var array */
	private $responseBody = ['foo' => 10, 'bar' => 'baz'];


	protected function setUp()
	{
		$this->client = \Mockery::mock('GuzzleHttp\Client');
		$this->request = new Request($this->client);
	}

	public function testGetRequest()
	{
		$this->mockClientRequest(Request::METHOD_GET, self::URL);
		$this->request->performGet(self::URL);
	}

	public function testPostRequestWithoutContent()
	{
		$this->mockClientRequest(Request::METHOD_POST, self::URL);
		$this->request->performPost(self::URL);
	}

	public function testPostRequestWithContent()
	{
		$content = ['bar' => 152, 'baz' => 'foo'];
		$this->mockClientRequest(Request::METHOD_POST, self::URL, $content);
		$this->request->performPost(self::URL, $content);
	}

	public function testDeleteRequest()
	{
		$this->mockClientRequest(Request::METHOD_DELETE, self::URL);
		$this->request->performDelete(self::URL);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\RequestFailedException
	 */
	public function testThrowingRequestFailedException()
	{
		$response = $this->createMockResponse([Request::ERROR_MESSAGE_KEY => self::ERROR_MESSAGE]);
		$this->mockFailedClientRequest(Request::METHOD_GET, self::URL, $response);
		$this->request->performGet(self::URL);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\RequestFailedException
	 */
	public function testThrowingRequestFailedExceptionWhenResponseIsNull()
	{
		$this->mockFailedClientRequest(Request::METHOD_GET, self::URL);
		$this->request->performGet(self::URL);
	}

	/**
	 * @param string $method
	 * @param string $url
	 * @param array $content
	 * @return void
	 */
	private function mockClientRequest($method, $url, array $content = [])
	{
		$request = $this->createMockRequest($content);
		$this->client->shouldReceive('createRequest')
			->once()
			->with($method, $url)
			->andReturn($request);
		$this->client->shouldReceive('send')
			->once()
			->with($request)
			->andReturn($this->createMockResponse($this->responseBody));
	}

	/**
	 * @param string $method
	 * @param string $url
	 * @param ResponseInterface|NULL $response
	 * @return void
	 */
	private function mockFailedClientRequest($method, $url, ResponseInterface $response = NULL)
	{
		$request = $this->createMockRequest();
		$requestException = \Mockery::mock('GuzzleHttp\Exception\RequestException');
		$requestException->shouldReceive('getResponse')->andReturn($response);
		$this->client->shouldReceive('createRequest')
			->once()
			->with($method, $url)
			->andReturn($request);
		$this->client->shouldReceive('send')
			->once()
			->with($request)
			->andThrow($requestException);
	}

	/**
	 * @param array $content
	 * @return MockInterface|RequestInterface
	 */
	private function createMockRequest(array $content = [])
	{
		$request = \Mockery::mock('GuzzleHttp\Message\RequestInterface');
		$request->shouldReceive('setHeader')->once();
		if ($content !== []) {
			$request->shouldReceive('setBody')->once();
		}
		return $request;
	}

	/**
	 * @param array $body
	 * @return MockInterface|ResponseInterface
	 */
	private function createMockResponse(array $body)
	{
		$response = \Mockery::mock('GuzzleHttp\Message\ResponseInterface');
		$response->shouldReceive('json')->once()->andReturn($body);
		return $response;
	}

}

(new RequestTest())->run();

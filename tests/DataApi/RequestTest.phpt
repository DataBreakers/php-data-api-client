<?php

namespace DataBreakers\DataApi;

use DataBreakers\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Mockery\MockInterface;
use Psr\Http\Message\ResponseInterface;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


class RequestTest extends TestCase
{

	const PATH = '/foo/bar';
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
		$this->mockClientRequest(Request::METHOD_GET, self::PATH);
		$this->request->performGet(self::PATH);
	}

	public function testPostRequestWithoutContent()
	{
		$this->mockClientRequest(Request::METHOD_POST, self::PATH);
		$this->request->performPost(self::PATH);
	}

	public function testPostRequestWithContent()
	{
		$content = ['bar' => 152, 'baz' => 'foo'];
		$this->mockClientRequest(Request::METHOD_POST, self::PATH, $content);
		$this->request->performPost(self::PATH, $content);
	}

	public function testDeleteRequest()
	{
		$this->mockClientRequest(Request::METHOD_DELETE, self::PATH);
		$this->request->performDelete(self::PATH);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\RequestFailedException
	 */
	public function testThrowingRequestFailedException()
	{
		$response = $this->createMockResponse([Request::ERROR_MESSAGE_KEY => self::ERROR_MESSAGE]);
		$this->mockFailedClientRequest(Request::METHOD_GET, self::PATH, $response);
		$this->request->performGet(self::PATH);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\RequestFailedException
	 */
	public function testThrowingRequestFailedExceptionWhenResponseIsNull()
	{
		$this->mockFailedClientRequest(Request::METHOD_GET, self::PATH);
		$this->request->performGet(self::PATH);
	}

	/**
	 * @param string $method
	 * @param string $path
	 * @param array $content
	 * @return void
	 */
	private function mockClientRequest($method, $path, array $content = [])
	{
		$options = $content !== [] ? ['json' => $content] : [];
		$this->client->shouldReceive('request')
				->once()
				->with($method, $path, $options)
				->andReturn($this->createMockResponse($this->responseBody));
	}

	/**
	 * @param string $method
	 * @param string $path
	 * @param ResponseInterface|NULL $response
	 * @return void
	 */
	private function mockFailedClientRequest($method, $path, ResponseInterface $response = NULL)
	{
		$requestException = \Mockery::mock('GuzzleHttp\Exception\RequestException');
		$requestException->shouldReceive('getResponse')->andReturn($response);
		$this->client->shouldReceive('request')
				->once()
				->with($method, $path, [])
				->andThrow($requestException);
	}

	/**
	 * @param array $body
	 * @return MockInterface|ResponseInterface
	 */
	private function createMockResponse(array $body)
	{
		$response = \Mockery::mock('Psr\Http\Message\ResponseInterface');
		$response->shouldReceive('getBody')->once()->andReturn(json_encode($body));
		return $response;
	}

}

(new RequestTest())->run();

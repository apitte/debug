<?php declare(strict_types = 1);

namespace Apitte\Debug\Negotiation\Transformer;

use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\Negotiation\Transformer\AbstractTransformer;
use Tracy\Debugger;
use function GuzzleHttp\Psr7\stream_for;

class DebugDataTransformer extends AbstractTransformer
{

	/** @var int */
	private $maxDepth;

	/** @var int */
	private $maxLength;

	public function __construct(int $maxDepth = 10, int $maxLength = 1500)
	{
		$this->maxDepth = $maxDepth;
		$this->maxLength = $maxLength;
	}

	/**
	 * @param mixed[] $context
	 */
	public function transform(ApiRequest $request, ApiResponse $response, array $context = []): ApiResponse
	{
		Debugger::$maxDepth = $this->maxDepth;
		Debugger::$maxLength = $this->maxLength;

		$tmp = clone $response;

		if (isset($context['exception'])) {
			// Handle and display exception
			Debugger::exceptionHandler($context['exception']);
			exit;
		}

		$response = $response->withHeader('Content-Type', 'text/html')
			->withBody(stream_for())
			->withStatus(599);

		$response->getBody()->write(Debugger::dump($tmp->getEntity(), true));

		return $response;
	}

}

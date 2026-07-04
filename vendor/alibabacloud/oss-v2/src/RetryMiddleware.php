<?php

namespace AlibabaCloud\Oss\V2;

use GuzzleHttp\Promise as P;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Middleware that retries requests based on the boolean result of
 * invoking the provided "decider" function.
 * Class RetryMiddleware
 * @package AlibabaCloud\Oss\V2
 * @final
 */
class RetryMiddleware
{
    /**
     * @var callable(RequestInterface, array): PromiseInterface
     */
    private $nextHandler;

    /**
     * @var callable
     */
    private $decider;

    /**
     * @var callable(int)
     */
    private $delay;

    /**
     * @param callable $decider
     * @param callable|null $delay
     * @return callable
     */
    public static function create(callable $decider, ?callable $delay = null): callable
    {
        return static function (callable $handler) use ($decider, $delay): RetryMiddleware {
            return new self($decider, $handler, $delay);
        };
    }

    /**
     * @param callable $decider Function that accepts the number of retries,
     * a request, [response], and [exception] and returns true if the request is to be retried.
     * @param callable(RequestInterface, array): PromiseInterface $nextHandler Next handler to invoke.
     * @param callable|null $delay
     */
    public function __construct(callable $decider, callable $nextHandler, ?callable $delay = null)
    {
        $this->decider = $decider;
        $this->nextHandler = $nextHandler;
        $this->delay = $delay ?: __CLASS__ . '::exponentialDelay';
    }

    /**
     * Default exponential backoff delay function.
     *
     * @param int $retries
     * @param array $options
     * @return int milliseconds.
     */
    public static function exponentialDelay(int $retries, array $options): int
    {
        return (int)2 ** ($retries - 1) * 1000;
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return PromiseInterface
     */
    public function __invoke(RequestInterface $request, array $options): PromiseInterface
    {
        if (!isset($options['retries'])) {
            $options['retries'] = 0;
        }

        $fn = $this->nextHandler;

        return $fn($request, $options)
            ->then(
                $this->onFulfilled($request, $options),
                $this->onRejected($request, $options)
            );
    }

    /**
     * Execute fulfilled closure
     * @param RequestInterface $request
     * @param array $options
     * @return callable
     */
    private function onFulfilled(RequestInterface $request, array $options): callable
    {
        return function ($value) use ($request, $options) {
            return $value;
        };
    }

    /**
     * Execute rejected closure
     * @param RequestInterface $req
     * @param array $options
     * @return callable
     */
    private function onRejected(RequestInterface $req, array $options): callable
    {
        return function ($reason) use ($req, $options) {
            if (!($this->decider)(
                $options['retries'],
                $req,
                $reason,
                $options
            )) {
                return P\Create::rejectionFor($reason);
            }

            return $this->doRetry($req, $options);
        };
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @param ResponseInterface|null $response
     * @return PromiseInterface
     */
    private function doRetry(RequestInterface $request, array $options, ?ResponseInterface $response = null): PromiseInterface
    {
        $options['delay'] = ($this->delay)(++$options['retries'], $options);

        return $this($request, $options);
    }
}

<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Paginator;

use AlibabaCloud\Oss\V2\Client;


/**
 * Class Paginator
 * @package AlibabaCloud\Oss\V2\Paginator
 */
class Paginator
{
    /**
     * Oss Client
     * @var Client
     */
    protected Client $client;

    /**
     * @var int|null
     */
    protected ?int $limit;

    public function __construct(
        $client,
        array $args = []
    )
    {
        $this->client = $client;
        if (
            isset($args['limit']) &&
            is_int($args['limit'])
        ) {
            $this->limit = $args['limit'];
        }
    }
}

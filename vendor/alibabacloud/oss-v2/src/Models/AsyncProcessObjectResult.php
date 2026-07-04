<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the AsyncProcessObject operation.
 * Class AsyncProcessObjectResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class AsyncProcessObjectResult extends ResultModel
{
    /**
     * The event id.
     * @var string|null
     */
    public ?string $eventId;

    /**
     * The task id.
     * @var string|null
     */
    public ?string $taskId;

    /**
     * The process request id.
     * @var string|null
     */
    public ?string $processRequestId;

    /**
     * AsyncProcessObjectResult constructor.
     * @param string|null $eventId The event id.
     * @param string|null $taskId The task id.
     * @param string|null $processRequestId The process request id.
     */
    public function __construct(
        ?string $eventId = null,
        ?string $taskId = null,
        ?string $processRequestId = null
    )
    {
        $this->eventId = $eventId;
        $this->taskId = $taskId;
        $this->processRequestId = $processRequestId;
    }
}

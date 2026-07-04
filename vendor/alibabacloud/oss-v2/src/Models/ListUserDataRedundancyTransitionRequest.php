<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;

/**
 * The request for the ListUserDataRedundancyTransition operation.
 * Class ListUserDataRedundancyTransitionRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListUserDataRedundancyTransitionRequest extends RequestModel
{   
    /**
     * @var string|null
     */
    #[TagProperty(tag: '', position: 'query', rename: 'continuation-token', type: 'string')]
    public ?string $continuationToken;

    /** 
     * @var int|null
     */
    #[TagProperty(tag: '', position: 'query', rename: 'max-keys', type: 'int')]
    public ?int $maxKeys;

    /**
     * ListUserDataRedundancyTransitionRequest constructor.
     * 
     * @param string|null $continuationToken
     * @param int|null $maxKeys
     * @param array|null $options
     */
    public function __construct( 
        ?string $continuationToken = null,
        ?int $maxKeys = null,
        ?array $options = null
    )
    {   
        $this->continuationToken = $continuationToken;
        $this->maxKeys = $maxKeys;
        parent::__construct($options);
    }
}
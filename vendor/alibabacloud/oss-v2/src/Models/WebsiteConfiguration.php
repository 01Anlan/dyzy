<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class WebsiteConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'WebsiteConfiguration')]
final class WebsiteConfiguration extends Model
{
    /**
     * The container that stores the default homepage.  You must specify at least one of the following containers: IndexDocument, ErrorDocument, and RoutingRules.
     * @var IndexDocument|null
     */
    #[XmlElement(rename: 'IndexDocument', type: IndexDocument::class)]
    public ?IndexDocument $indexDocument;

    /**
     * The container that stores the default 404 page.  You must specify at least one of the following containers: IndexDocument, ErrorDocument, and RoutingRules.
     * @var ErrorDocument|null
     */
    #[XmlElement(rename: 'ErrorDocument', type: ErrorDocument::class)]
    public ?ErrorDocument $errorDocument;

    /**
     * The container that stores the redirection rules.  You must specify at least one of the following containers: IndexDocument, ErrorDocument, and RoutingRules.
     * @var RoutingRules|null
     */
    #[XmlElement(rename: 'RoutingRules', type: RoutingRules::class)]
    public ?RoutingRules $routingRules;


    /**
     * WebsiteConfiguration constructor.
     * @param IndexDocument|null $indexDocument The container that stores the default homepage.
     * @param ErrorDocument|null $errorDocument The container that stores the default 404 page.
     * @param RoutingRules|null $routingRules The container that stores the redirection rules.
     */
    public function __construct(
        ?IndexDocument $indexDocument = null,
        ?ErrorDocument $errorDocument = null,
        ?RoutingRules $routingRules = null
    )
    {
        $this->indexDocument = $indexDocument;
        $this->errorDocument = $errorDocument;
        $this->routingRules = $routingRules;
    }
}
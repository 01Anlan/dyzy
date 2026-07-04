<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class CloudBox
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'CloudBox')]
final class CloudBox extends ResultModel
{
    /**
     * The cloud box id.
     * @var string|null
     */
    #[XmlElement(rename: 'ID', type: 'string')]
    public ?string $id;

    /**
     * The cloud box name.
     * @var string|null
     */
    #[XmlElement(rename: 'Name', type: 'string')]
    public ?string $name;

    /**
     * The cloud box region.
     * @var string|null
     */
    #[XmlElement(rename: 'Region', type: 'string')]
    public ?string $region;

    /**
     * The cloud box control endpoint.
     * @var string|null
     */
    #[XmlElement(rename: 'ControlEndpoint', type: 'string')]
    public ?string $controlEndpoint;

    /**
     * The cloud box data endpoint.
     * @var string|null
     */
    #[XmlElement(rename: 'DataEndpoint', type: 'string')]
    public ?string $dataEndpoint;

    /**
     * CloudBox constructor.
     * @param string|null $id The cloud box id.
     * @param string|null $name The cloud box name.
     * @param string|null $region The cloud box region.
     * @param string|null $controlEndpoint The cloud box control endpoint.
     * @param string|null $dataEndpoint The cloud box data endpoint.
     */
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?string $region = null,
        ?string $controlEndpoint = null,
        ?string $dataEndpoint = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->region = $region;
        $this->controlEndpoint = $controlEndpoint;
        $this->dataEndpoint = $dataEndpoint;
    }
}

<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;
use AlibabaCloud\Oss\V2\Types\Model;

/**
 * Class ListAccessPoints
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ListAccessPointsResult')]
final class ListAccessPoints extends Model
{
    /**
     * Indicates whether the returned list is truncated. Valid values: * true: indicates that not all results are returned. * false: indicates that all results are returned.
     * @var bool|null
     */
    #[XmlElement(rename: 'IsTruncated', type: 'bool')]
    public ?bool $isTruncated;

    /**
     * Indicates that this ListAccessPoints request does not return all results that can be listed. You can use NextContinuationToken to continue obtaining list results.
     * @var string|null
     */
    #[XmlElement(rename: 'NextContinuationToken', type: 'string')]
    public ?string $nextContinuationToken;

    /**
     * The ID of the Alibaba Cloud account to which the access point belongs.
     * @var string|null
     */
    #[XmlElement(rename: 'AccountId', type: 'string')]
    public ?string $accountId;

    /**
     * The container that stores the information about all access point.
     * @var AccessPoints|null
     */
    #[XmlElement(rename: 'AccessPoints', type: AccessPoints::class)]
    public ?AccessPoints $accessPoints;

    /**
     * The maximum number of results set for this enumeration operation.
     * @var int|null
     */
    #[XmlElement(rename: 'MaxKeys', type: 'int')]
    public ?int $maxKeys;

    /**
     * ListAccessPointsResult constructor.
     * @param bool|null $isTruncated Indicates whether the returned list is truncated.
     * @param string|null $nextContinuationToken Indicates that this ListAccessPoints request does not return all results that can be listed.
     * @param string|null $accountId The ID of the Alibaba Cloud account to which the access point belongs.
     * @param AccessPoints|null $accessPoints The container that stores the information about all access points.
     * @param int|null $maxKeys The maximum number of results set for this enumeration operation.
     */
    public function __construct(
        ?bool $isTruncated = null,
        ?string $nextContinuationToken = null,
        ?string $accountId = null,
        ?AccessPoints $accessPoints = null,
        ?int $maxKeys = null
    )
    {
        $this->isTruncated = $isTruncated;
        $this->nextContinuationToken = $nextContinuationToken;
        $this->accountId = $accountId;
        $this->accessPoints = $accessPoints;
        $this->maxKeys = $maxKeys;
    }
}
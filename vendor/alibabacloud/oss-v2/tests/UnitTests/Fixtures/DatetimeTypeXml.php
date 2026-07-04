<?php

namespace UnitTests\Fixtures;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;


#[XmlRoot(name:'DatetimeType')]
class DatetimeTypeXml  extends Model
{
    #[XmlElement(rename:'DateTimeValue', type: 'DateTime')]
    public ?\DateTime $isotimeValue;

    #[XmlElement(rename:'DateTimeImmutableValue', type: 'DateTimeImmutable')]
    public ?\DateTimeImmutable $dateTimeImmutableValue;

    #[XmlElement(rename:'UnixtimeValue', type: 'DateTime', format: 'unixtime')]
    public ?\DateTime $unixtimeValue;

    #[XmlElement(rename:'HttptimeValue', type: 'DateTime', format: 'httptime')]
    public ?\DateTime $httptimeValue;

    public function __construct(
        ?\DateTime $isotimeValue = null,
        ?\DateTimeImmutable $dateTimeImmutableValue = null,
        ?\DateTime $unixtimeValue = null,
        ?\DateTime $httptimeValue = null
    ) {
        $this->isotimeValue = $isotimeValue;
        $this->dateTimeImmutableValue = $dateTimeImmutableValue;
        $this->unixtimeValue = $unixtimeValue;
        $this->httptimeValue = $httptimeValue;
    }
}
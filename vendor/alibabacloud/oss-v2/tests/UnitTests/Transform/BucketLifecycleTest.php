<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Models\NoncurrentVersionTransition;
use AlibabaCloud\Oss\V2\Transform\BucketLifecycle;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;
use DateTime;
use DateTimeZone;

class BucketLifecycleTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketLifecycle()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketLifecycleRequest();
            $input = BucketLifecycle::fromPutBucketLifecycle($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketLifecycleRequest('bucket-123');
            $input = BucketLifecycle::fromPutBucketLifecycle($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, lifecycleConfiguration", (string)$e);
        }

        // demo1
        $request = new Models\PutBucketLifecycleRequest('bucket-123');
        $rule = new Models\LifecycleRule(prefix: '', id: 'rule', expiration: new Models\LifecycleRuleExpiration(expiredObjectDeleteMarker: true), noncurrentVersionExpiration: new Models\NoncurrentVersionExpiration(noncurrentDays: 5), status: 'Enabled');
        $request->lifecycleConfiguration = new Models\LifecycleConfiguration(
            array(
                $rule
            )
        );
        $input = BucketLifecycle::fromPutBucketLifecycle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><LifecycleConfiguration><Rule><Prefix></Prefix><ID>rule</ID><Expiration><ExpiredObjectDeleteMarker>true</ExpiredObjectDeleteMarker></Expiration><NoncurrentVersionExpiration><NoncurrentDays>5</NoncurrentDays></NoncurrentVersionExpiration><Status>Enabled</Status></Rule></LifecycleConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo2
        $request = new Models\PutBucketLifecycleRequest('bucket-123');
        $rule = new Models\LifecycleRule(prefix: 'log/', id: 'rule', expiration: new Models\LifecycleRuleExpiration(days: 90), status: 'Enabled');
        $request->lifecycleConfiguration = new Models\LifecycleConfiguration(
            array(
                $rule
            )
        );
        $input = BucketLifecycle::fromPutBucketLifecycle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><LifecycleConfiguration><Rule><Prefix>log/</Prefix><ID>rule</ID><Expiration><Days>90</Days></Expiration><Status>Enabled</Status></Rule></LifecycleConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo3
        $request = new Models\PutBucketLifecycleRequest('bucket-123');
        $rule = new Models\LifecycleRule(prefix: 'log/', transitions: array(
            new Models\LifecycleRuleTransition(days: 30, storageClass: 'IA'),
            new Models\LifecycleRuleTransition(days: 60, storageClass: 'Archive'),
        ), id: 'rule', expiration: new Models\LifecycleRuleExpiration(days: 3600), status: 'Enabled');
        $request->lifecycleConfiguration = new Models\LifecycleConfiguration(
            array(
                $rule
            )
        );
        $input = BucketLifecycle::fromPutBucketLifecycle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><LifecycleConfiguration><Rule><Prefix>log/</Prefix><Transition><Days>30</Days><StorageClass>IA</StorageClass></Transition><Transition><Days>60</Days><StorageClass>Archive</StorageClass></Transition><ID>rule</ID><Expiration><Days>3600</Days></Expiration><Status>Enabled</Status></Rule></LifecycleConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo4
        $request = new Models\PutBucketLifecycleRequest('bucket-123');
        $rule = new Models\LifecycleRule(prefix: '', id: 'rule', expiration: new Models\LifecycleRuleExpiration(expiredObjectDeleteMarker: true), noncurrentVersionExpiration: new Models\NoncurrentVersionExpiration(noncurrentDays: 5), status: 'Enabled');
        $request->lifecycleConfiguration = new Models\LifecycleConfiguration(
            array(
                $rule
            )
        );
        $input = BucketLifecycle::fromPutBucketLifecycle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><LifecycleConfiguration><Rule><Prefix></Prefix><ID>rule</ID><Expiration><ExpiredObjectDeleteMarker>true</ExpiredObjectDeleteMarker></Expiration><NoncurrentVersionExpiration><NoncurrentDays>5</NoncurrentDays></NoncurrentVersionExpiration><Status>Enabled</Status></Rule></LifecycleConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo5
        $request = new Models\PutBucketLifecycleRequest('bucket-123');
        $rule = new Models\LifecycleRule(prefix: '', transitions: array(
            new Models\LifecycleRuleTransition(days: 30, storageClass: 'Archive'),
        ), filter: new Models\LifecycleRuleFilter(nots: array(new Models\LifecycleRuleNot(tag: new Models\Tag(key: 'key1', value: 'value1'), prefix: 'log')
        )), id: 'rule', expiration: new Models\LifecycleRuleExpiration(expiredObjectDeleteMarker: true), noncurrentVersionExpiration: new Models\NoncurrentVersionExpiration(noncurrentDays: 5), status: 'Enabled');
        $request->lifecycleConfiguration = new Models\LifecycleConfiguration(
            array(
                $rule
            )
        );
        $input = BucketLifecycle::fromPutBucketLifecycle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><LifecycleConfiguration><Rule><Prefix></Prefix><Transition><Days>30</Days><StorageClass>Archive</StorageClass></Transition><Filter><Not><Tag><Key>key1</Key><Value>value1</Value></Tag><Prefix>log</Prefix></Not></Filter><ID>rule</ID><Expiration><ExpiredObjectDeleteMarker>true</ExpiredObjectDeleteMarker></Expiration><NoncurrentVersionExpiration><NoncurrentDays>5</NoncurrentDays></NoncurrentVersionExpiration><Status>Enabled</Status></Rule></LifecycleConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        $request = new Models\PutBucketLifecycleRequest('bucket-123');
        $rule = new Models\LifecycleRule(
            prefix: '',
            transitions: array(
                new Models\LifecycleRuleTransition(days: 30, storageClass: 'Archive'),
            ),
            filter: new Models\LifecycleRuleFilter(
                nots: array(
                    new Models\LifecycleRuleNot(tag: new Models\Tag(key: 'key1', value: 'value1'), prefix: 'log'),
                    new Models\LifecycleRuleNot(tag: new Models\Tag(key: 'key2', value: 'value2'), prefix: 'pre')
                )),
            id: 'rule',
            expiration: new Models\LifecycleRuleExpiration(expiredObjectDeleteMarker: true),
            noncurrentVersionExpiration: new Models\NoncurrentVersionExpiration(noncurrentDays: 5),
            status: 'Enabled'
        );
        $request->lifecycleConfiguration = new Models\LifecycleConfiguration(
            array(
                $rule
            )
        );
        $input = BucketLifecycle::fromPutBucketLifecycle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><LifecycleConfiguration><Rule><Prefix></Prefix><Transition><Days>30</Days><StorageClass>Archive</StorageClass></Transition><Filter><Not><Tag><Key>key1</Key><Value>value1</Value></Tag><Prefix>log</Prefix></Not><Not><Tag><Key>key2</Key><Value>value2</Value></Tag><Prefix>pre</Prefix></Not></Filter><ID>rule</ID><Expiration><ExpiredObjectDeleteMarker>true</ExpiredObjectDeleteMarker></Expiration><NoncurrentVersionExpiration><NoncurrentDays>5</NoncurrentDays></NoncurrentVersionExpiration><Status>Enabled</Status></Rule></LifecycleConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo6
        $request = new Models\PutBucketLifecycleRequest('bucket-123');
        $rule = new Models\LifecycleRule(prefix: '', transitions: array(
            new Models\LifecycleRuleTransition(days: 30, storageClass: 'Archive', isAccessTime: true, returnToStdWhenVisit: true),
        ), status: 'Enabled');
        $request->lifecycleConfiguration = new Models\LifecycleConfiguration(
            array(
                $rule
            )
        );
        $input = BucketLifecycle::fromPutBucketLifecycle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><LifecycleConfiguration><Rule><Prefix></Prefix><Transition><Days>30</Days><StorageClass>Archive</StorageClass><IsAccessTime>true</IsAccessTime><ReturnToStdWhenVisit>true</ReturnToStdWhenVisit></Transition><Status>Enabled</Status></Rule></LifecycleConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo7
        $request = new Models\PutBucketLifecycleRequest('bucket-123');
        $rule = new Models\LifecycleRule(prefix: '', id: 'rule', abortMultipartUpload: new Models\LifecycleRuleAbortMultipartUpload(days: 30), status: 'Enabled');
        $request->lifecycleConfiguration = new Models\LifecycleConfiguration(
            array(
                $rule
            )
        );
        $input = BucketLifecycle::fromPutBucketLifecycle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><LifecycleConfiguration><Rule><Prefix></Prefix><ID>rule</ID><AbortMultipartUpload><Days>30</Days></AbortMultipartUpload><Status>Enabled</Status></Rule></LifecycleConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo8
        $request = new Models\PutBucketLifecycleRequest('bucket-123');
        $request->lifecycleConfiguration = new Models\LifecycleConfiguration(
            array(
                new Models\LifecycleRule(prefix: 'dir1', id: 'rule1', expiration: new Models\LifecycleRuleExpiration(days: 180), status: 'Enabled'),
                new Models\LifecycleRule(prefix: 'dir1/dir2/', id: 'rule2', expiration: new Models\LifecycleRuleExpiration(days: 30), status: 'Enabled'),
            ),
        );
        $input = BucketLifecycle::fromPutBucketLifecycle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><LifecycleConfiguration><Rule><Prefix>dir1</Prefix><ID>rule1</ID><Expiration><Days>180</Days></Expiration><Status>Enabled</Status></Rule><Rule><Prefix>dir1/dir2/</Prefix><ID>rule2</ID><Expiration><Days>30</Days></Expiration><Status>Enabled</Status></Rule></LifecycleConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo9
        $request = new Models\PutBucketLifecycleRequest('bucket-123');
        $request->lifecycleConfiguration = new Models\LifecycleConfiguration(
            array(
                new Models\LifecycleRule(prefix: 'prefix0', id: 'r0', expiration: new Models\LifecycleRuleExpiration(days: 40, expiredObjectDeleteMarker: false), status: 'Enabled'),
                new Models\LifecycleRule(prefix: 'prefix1', filter: new Models\LifecycleRuleFilter(objectSizeGreaterThan: 500, objectSizeLessThan: 64500), id: 'r1', expiration: new Models\LifecycleRuleExpiration(days: 40, expiredObjectDeleteMarker: false), status: 'Enabled'),
                new Models\LifecycleRule(prefix: 'prefix3', transitions: array(new Models\LifecycleRuleTransition(days: 30, storageClass: 'IA', isAccessTime: false)), id: 'r3', expiration: new Models\LifecycleRuleExpiration(days: 40, expiredObjectDeleteMarker: false), status: 'Enabled'),
                new Models\LifecycleRule(prefix: 'prefix4', transitions: array(new Models\LifecycleRuleTransition(days: 30, storageClass: 'IA', isAccessTime: false)), noncurrentVersionTransitions: array(new NoncurrentVersionTransition(noncurrentDays: 10, storageClass: 'IA', isAccessTime: true, returnToStdWhenVisit: true)), id: 'r4', abortMultipartUpload: new Models\LifecycleRuleAbortMultipartUpload(createdBeforeDate: new DateTime('2015-11-11T00:00:00.000Z', new DateTimeZone('UTC'))), status: 'Enabled'),
                new Models\LifecycleRule(prefix: 'pre_', expiration: new Models\LifecycleRuleExpiration(days: 40, expiredObjectDeleteMarker: false), status: 'Enabled'),
            ),
        );
        $input = BucketLifecycle::fromPutBucketLifecycle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><LifecycleConfiguration><Rule><Prefix>prefix0</Prefix><ID>r0</ID><Expiration><Days>40</Days><ExpiredObjectDeleteMarker>false</ExpiredObjectDeleteMarker></Expiration><Status>Enabled</Status></Rule><Rule><Prefix>prefix1</Prefix><Filter><ObjectSizeGreaterThan>500</ObjectSizeGreaterThan><ObjectSizeLessThan>64500</ObjectSizeLessThan></Filter><ID>r1</ID><Expiration><Days>40</Days><ExpiredObjectDeleteMarker>false</ExpiredObjectDeleteMarker></Expiration><Status>Enabled</Status></Rule><Rule><Prefix>prefix3</Prefix><Transition><Days>30</Days><StorageClass>IA</StorageClass><IsAccessTime>false</IsAccessTime></Transition><ID>r3</ID><Expiration><Days>40</Days><ExpiredObjectDeleteMarker>false</ExpiredObjectDeleteMarker></Expiration><Status>Enabled</Status></Rule><Rule><Prefix>prefix4</Prefix><Transition><Days>30</Days><StorageClass>IA</StorageClass><IsAccessTime>false</IsAccessTime></Transition><NoncurrentVersionTransition><NoncurrentDays>10</NoncurrentDays><StorageClass>IA</StorageClass><IsAccessTime>true</IsAccessTime><ReturnToStdWhenVisit>true</ReturnToStdWhenVisit></NoncurrentVersionTransition><ID>r4</ID><AbortMultipartUpload><CreatedBeforeDate>2015-11-11T00:00:00Z</CreatedBeforeDate></AbortMultipartUpload><Status>Enabled</Status></Rule><Rule><Prefix>pre_</Prefix><Expiration><Days>40</Days><ExpiredObjectDeleteMarker>false</ExpiredObjectDeleteMarker></Expiration><Status>Enabled</Status></Rule></LifecycleConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketLifecycle()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketLifecycle::toPutBucketLifecycle($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketLifecycle::toPutBucketLifecycle($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketLifecycle()
    {
        // miss required field
        try {
            $request = new Models\GetBucketLifecycleRequest();
            $input = BucketLifecycle::fromGetBucketLifecycle($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketLifecycleRequest('bucket-123');
        $input = BucketLifecycle::fromGetBucketLifecycle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketLifecycle()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketLifecycle::toGetBucketLifecycle($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<LifecycleConfiguration>
  <Rule>
    <ID>delete after one day</ID>
    <Prefix>logs1/</Prefix>
    <Status>Enabled</Status>
    <Expiration>
      <Days>1</Days>
    </Expiration>
  </Rule>
  <Rule>
    <ID>mtime transition1</ID>
    <Prefix>logs2/</Prefix>
    <Status>Enabled</Status>
    <Transition>
      <Days>30</Days>
      <StorageClass>IA</StorageClass>
    </Transition>
  </Rule>
  <Rule>
    <ID>mtime transition2</ID>
    <Prefix>logs3/</Prefix>
    <Status>Enabled</Status>
    <Transition>
      <Days>30</Days>
      <StorageClass>IA</StorageClass>
      <IsAccessTime>false</IsAccessTime>
    </Transition>
  </Rule>
</LifecycleConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketLifecycle::toGetBucketLifecycle($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(3, count($result->lifecycleConfiguration->rules));
        $this->assertEquals('delete after one day', $result->lifecycleConfiguration->rules[0]->id);
        $this->assertEquals('logs1/', $result->lifecycleConfiguration->rules[0]->prefix);
        $this->assertEquals('Enabled', $result->lifecycleConfiguration->rules[0]->status);
        $this->assertEquals(1, $result->lifecycleConfiguration->rules[0]->expiration->days);

        $this->assertEquals('mtime transition1', $result->lifecycleConfiguration->rules[1]->id);
        $this->assertEquals('logs2/', $result->lifecycleConfiguration->rules[1]->prefix);
        $this->assertEquals('Enabled', $result->lifecycleConfiguration->rules[1]->status);
        $this->assertEquals(1, count($result->lifecycleConfiguration->rules[1]->transitions));
        $this->assertEquals(30, $result->lifecycleConfiguration->rules[1]->transitions[0]->days);
        $this->assertEquals('IA', $result->lifecycleConfiguration->rules[1]->transitions[0]->storageClass);

        $this->assertEquals('mtime transition2', $result->lifecycleConfiguration->rules[2]->id);
        $this->assertEquals('logs3/', $result->lifecycleConfiguration->rules[2]->prefix);
        $this->assertEquals('Enabled', $result->lifecycleConfiguration->rules[2]->status);
        $this->assertEquals(1, count($result->lifecycleConfiguration->rules[2]->transitions));
        $this->assertEquals(30, $result->lifecycleConfiguration->rules[2]->transitions[0]->days);
        $this->assertEquals('IA', $result->lifecycleConfiguration->rules[2]->transitions[0]->storageClass);
        $this->assertFalse($result->lifecycleConfiguration->rules[2]->transitions[0]->isAccessTime);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<LifecycleConfiguration>
  <Rule>
    <ID>atime transition1</ID>
    <Prefix>logs1/</Prefix>
    <Status>Enabled</Status>
    <Transition>
      <Days>30</Days>
      <StorageClass>IA</StorageClass>
      <IsAccessTime>true</IsAccessTime>
      <ReturnToStdWhenVisit>false</ReturnToStdWhenVisit>
    </Transition>
    <AtimeBase>1631698332</AtimeBase>
  </Rule>
  <Rule>
    <ID>atime transition2</ID>
    <Prefix>logs2/</Prefix>
    <Status>Enabled</Status>
    <NoncurrentVersionTransition>
      <NoncurrentDays>10</NoncurrentDays>
      <StorageClass>IA</StorageClass>
      <IsAccessTime>true</IsAccessTime>
      <ReturnToStdWhenVisit>false</ReturnToStdWhenVisit>
    </NoncurrentVersionTransition>
    <AtimeBase>1631698332</AtimeBase>
  </Rule>
</LifecycleConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketLifecycle::toGetBucketLifecycle($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(2, count($result->lifecycleConfiguration->rules));
        $this->assertEquals('atime transition1', $result->lifecycleConfiguration->rules[0]->id);
        $this->assertEquals('logs1/', $result->lifecycleConfiguration->rules[0]->prefix);
        $this->assertEquals('Enabled', $result->lifecycleConfiguration->rules[0]->status);
        $this->assertEquals(1, count($result->lifecycleConfiguration->rules[0]->transitions));
        $this->assertEquals(30, $result->lifecycleConfiguration->rules[0]->transitions[0]->days);
        $this->assertEquals('IA', $result->lifecycleConfiguration->rules[0]->transitions[0]->storageClass);
        $this->assertFalse($result->lifecycleConfiguration->rules[0]->transitions[0]->returnToStdWhenVisit);
        $this->assertTrue($result->lifecycleConfiguration->rules[0]->transitions[0]->isAccessTime);
        $this->assertEquals(1631698332, $result->lifecycleConfiguration->rules[0]->atimeBase);

        $this->assertEquals('atime transition2', $result->lifecycleConfiguration->rules[1]->id);
        $this->assertEquals('logs2/', $result->lifecycleConfiguration->rules[1]->prefix);
        $this->assertEquals('Enabled', $result->lifecycleConfiguration->rules[1]->status);
        $this->assertEquals(10, $result->lifecycleConfiguration->rules[1]->noncurrentVersionTransitions[0]->noncurrentDays);
        $this->assertEquals('IA', $result->lifecycleConfiguration->rules[1]->noncurrentVersionTransitions[0]->storageClass);
        $this->assertFalse($result->lifecycleConfiguration->rules[1]->noncurrentVersionTransitions[0]->returnToStdWhenVisit);
        $this->assertTrue($result->lifecycleConfiguration->rules[1]->noncurrentVersionTransitions[0]->isAccessTime);
        $this->assertEquals(1631698332, $result->lifecycleConfiguration->rules[1]->atimeBase);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<LifecycleConfiguration>
    <Rule>
        <Prefix></Prefix>
        <Transition>
            <Days>30</Days>
            <StorageClass>Archive</StorageClass>
        </Transition>
        <Filter>
            <Not>
                <Tag>
                    <Key>key1</Key>
                    <Value>value1</Value>
                </Tag>
                <Prefix>log</Prefix>
            </Not>
        </Filter>
        <ID>rule</ID>
        <Expiration>
            <ExpiredObjectDeleteMarker>true</ExpiredObjectDeleteMarker>
        </Expiration>
        <NoncurrentVersionExpiration>
            <NoncurrentDays>5</NoncurrentDays>
        </NoncurrentVersionExpiration>
        <Status>Enabled</Status>
    </Rule>
</LifecycleConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketLifecycle::toGetBucketLifecycle($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(1, count($result->lifecycleConfiguration->rules));
        $this->assertEquals('rule', $result->lifecycleConfiguration->rules[0]->id);
        $this->assertEquals('', $result->lifecycleConfiguration->rules[0]->prefix);
        $this->assertEquals('Enabled', $result->lifecycleConfiguration->rules[0]->status);
        $this->assertEquals(1, count($result->lifecycleConfiguration->rules[0]->transitions));
        $this->assertEquals(30, $result->lifecycleConfiguration->rules[0]->transitions[0]->days);
        $this->assertEquals('Archive', $result->lifecycleConfiguration->rules[0]->transitions[0]->storageClass);
        $this->assertEquals(1, count($result->lifecycleConfiguration->rules[0]->filter->nots));
        $this->assertEquals('log', $result->lifecycleConfiguration->rules[0]->filter->nots[0]->prefix);
        $this->assertEquals('key1', $result->lifecycleConfiguration->rules[0]->filter->nots[0]->tag->key);
        $this->assertEquals('value1', $result->lifecycleConfiguration->rules[0]->filter->nots[0]->tag->value);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<LifecycleConfiguration>
    <Rule>
        <Prefix></Prefix>
        <Transition>
            <Days>30</Days>
            <StorageClass>Archive</StorageClass>
        </Transition>
        <Filter>
            <Not>
                <Tag>
                    <Key>key1</Key>
                    <Value>value1</Value>
                </Tag>
                <Prefix>log</Prefix>
            </Not>
            <Not>
                <Tag>
                    <Key>key2</Key>
                    <Value>value2</Value>
                </Tag>
                <Prefix>pre</Prefix>
            </Not>
        </Filter>
        <ID>rule</ID>
        <Expiration>
            <ExpiredObjectDeleteMarker>true</ExpiredObjectDeleteMarker>
        </Expiration>
        <NoncurrentVersionExpiration>
            <NoncurrentDays>5</NoncurrentDays>
        </NoncurrentVersionExpiration>
        <Status>Enabled</Status>
    </Rule>
</LifecycleConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketLifecycle::toGetBucketLifecycle($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(1, count($result->lifecycleConfiguration->rules));
        $this->assertEquals('rule', $result->lifecycleConfiguration->rules[0]->id);
        $this->assertEquals('', $result->lifecycleConfiguration->rules[0]->prefix);
        $this->assertEquals('Enabled', $result->lifecycleConfiguration->rules[0]->status);
        $this->assertEquals(1, count($result->lifecycleConfiguration->rules[0]->transitions));
        $this->assertEquals(30, $result->lifecycleConfiguration->rules[0]->transitions[0]->days);
        $this->assertEquals('Archive', $result->lifecycleConfiguration->rules[0]->transitions[0]->storageClass);
        $this->assertEquals(2, count($result->lifecycleConfiguration->rules[0]->filter->nots));
        $this->assertEquals('log', $result->lifecycleConfiguration->rules[0]->filter->nots[0]->prefix);
        $this->assertEquals('key1', $result->lifecycleConfiguration->rules[0]->filter->nots[0]->tag->key);
        $this->assertEquals('value1', $result->lifecycleConfiguration->rules[0]->filter->nots[0]->tag->value);
        $this->assertEquals('pre', $result->lifecycleConfiguration->rules[0]->filter->nots[1]->prefix);
        $this->assertEquals('key2', $result->lifecycleConfiguration->rules[0]->filter->nots[1]->tag->key);
        $this->assertEquals('value2', $result->lifecycleConfiguration->rules[0]->filter->nots[1]->tag->value);
    }

    public function testFromDeleteBucketLifecycle()
    {
        // miss required field
        try {
            $request = new Models\DeleteBucketLifecycleRequest();
            $input = BucketLifecycle::fromDeleteBucketLifecycle($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\DeleteBucketLifecycleRequest('bucket-123');
        $input = BucketLifecycle::fromDeleteBucketLifecycle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToDeleteBucketLifecycle()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketLifecycle::toDeleteBucketLifecycle($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = BucketLifecycle::toDeleteBucketLifecycle($output);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}

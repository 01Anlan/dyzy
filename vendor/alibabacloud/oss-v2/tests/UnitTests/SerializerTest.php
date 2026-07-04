<?php

namespace UnitTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'BaiscTypeXml.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'BaiscTypeListXml.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'MixedTypeXml.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'MixedTypeListXml.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'BaiscTypeLackAnnotationXml.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'DatetimeTypeXml.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'SubConfiguration.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'RootConfiguration.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'PutApiRequest.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'PutApiARequest.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'PutApiBRequest.php';

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\Serializer;
use AlibabaCloud\Oss\V2\Utils;

use UnitTests\Fixtures\BaiscTypeXml;
use UnitTests\Fixtures\BaiscTypeListXml;
use UnitTests\Fixtures\MixedTypeXml;
use UnitTests\Fixtures\MixedTypeListXml;
use UnitTests\Fixtures\BaiscTypeLackAnnotationXml;
use UnitTests\Fixtures\DatetimeTypeXml;

use UnitTests\Fixtures\SubConfiguration;
use UnitTests\Fixtures\RootConfiguration;
use UnitTests\Fixtures\PutApiRequest;
use UnitTests\Fixtures\PutApiARequest;
use UnitTests\Fixtures\PutApiBRequest;


class SerializerTest extends \PHPUnit\Framework\TestCase
{
    public function testSerializeBaiscTypeXml()
    {
        // empty 
        $obj = new BaiscTypeXml();
        $str = Serializer::serializeXml($obj);
        $this->assertNotEmpty($str);
        $this->assertStringContainsString('<BasicType/>', $str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(0, $xml->count());
        $this->assertFalse(isset($xml->StrValue));
        $this->assertFalse(isset($xml->IntValue));
        $this->assertFalse(isset($xml->BoolValue));
        $this->assertFalse(isset($xml->FloatValue));

        // all
        $obj = new BaiscTypeXml(
            strValue: 'str',
            intValue: 1234,
            boolValue: true,
            floatValue: 3.14,
        );
        $str = Serializer::serializeXml($obj);
        $this->assertNotEmpty($str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(4, $xml->count());
        $this->assertEquals('str', $xml->StrValue);
        $this->assertEquals('1234', $xml->IntValue);
        $this->assertEquals('true', $xml->BoolValue);
        $this->assertEquals('3.14', $xml->FloatValue);

        // part
        $obj = new BaiscTypeXml(
            strValue: 'str-123',
            floatValue: 2.14,
        );
        $str = Serializer::serializeXml($obj);
        $this->assertNotEmpty($str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(2, $xml->count());
        $this->assertEquals('str-123', $xml->StrValue);
        $this->assertFalse(isset($xml->IntValue));
        $this->assertFalse(isset($xml->BoolValue));
        $this->assertEquals('2.14', $xml->FloatValue);

        // part 2
        $obj = new BaiscTypeXml(
            strValue: 'str-123',
            boolValue: false,
        );
        $str = Serializer::serializeXml($obj);
        $this->assertNotEmpty($str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(2, $xml->count());
        $this->assertEquals('str-123', $xml->StrValue);
        $this->assertFalse(isset($xml->IntValue));
        $this->assertEquals('false', $xml->BoolValue);
        $this->assertFalse(isset($xml->FloatValue));
    }

    public function testSerializeBaiscTypeListXml(): void
    {
        // empty
        $obj = new BaiscTypeListXml();
        $str = Serializer::serializeXml($obj);
        $this->assertStringContainsString('<BasicTypeList/>', $str);

        // all
        $obj = new BaiscTypeListXml(
            strValues: ['str1', 'str2'],
            intValues: [1234, 2234],
            boolValues: [true, true, false],
            floatValues: [3.14],
        );
        $str = Serializer::serializeXml($obj);
        $this->assertNotEmpty($str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(8, $xml->count());
        $this->assertEquals(2, count($xml->StrValue));
        $this->assertEquals('str1', $xml->StrValue[0]);
        $this->assertEquals('str2', $xml->StrValue[1]);
        $this->assertEquals(2, count($xml->IntValue));
        $this->assertEquals('1234', $xml->IntValue[0]);
        $this->assertEquals('2234', $xml->IntValue[1]);
        $this->assertEquals(3, count($xml->BoolValue));
        $this->assertEquals('true', $xml->BoolValue[0]);
        $this->assertEquals('true', $xml->BoolValue[1]);
        $this->assertEquals('false', $xml->BoolValue[2]);
        $this->assertEquals(1, count($xml->FloatValue));
        $this->assertEquals('3.14', $xml->FloatValue[0]);
    }

    public function testSerializeMixedTypeXml(): void
    {
        // empty
        $obj = new MixedTypeXml();
        $str = Serializer::serializeXml($obj);
        $this->assertStringContainsString('<MixedType/>', $str);

        // all
        $obj = new MixedTypeXml(
            strValue: 'str-111',
            intValue: 12,
            xmlValue: new BaiscTypeXml(
                strValue: 'str',
                intValue: 123,
                boolValue: true,
                floatValue: 1.14,
            ),
            xmlListValue: new BaiscTypeListXml(
                strValues: ['str1', 'str2'],
                intValues: [1234, 2234],
                boolValues: [true, true, false],
                floatValues: [3.14],
            ),
        );

        $str = Serializer::serializeXml($obj);
        $this->assertNotEmpty($str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(4, $xml->count());
        $this->assertEquals('str-111', $xml->StrValue);
        $this->assertEquals('12', $xml->IntValue);

        $this->assertEquals(1, ($xml->BasicTypeFiled)->count());
        $this->assertEquals('str', $xml->BasicTypeFiled->StrValue);
        $this->assertEquals('123', $xml->BasicTypeFiled->IntValue);
        $this->assertEquals('true', $xml->BasicTypeFiled->BoolValue);
        $this->assertEquals('1.14', $xml->BasicTypeFiled->FloatValue);

        $this->assertEquals(1, ($xml->BasicTypeListFiled)->count());
        $this->assertEquals(2, ($xml->BasicTypeListFiled->StrValue)->count());
        $this->assertEquals('str1', $xml->BasicTypeListFiled->StrValue[0]);
        $this->assertEquals('str2', $xml->BasicTypeListFiled->StrValue[1]);
        $this->assertEquals(2, ($xml->BasicTypeListFiled->IntValue)->count());
        $this->assertEquals('1234', $xml->BasicTypeListFiled->IntValue[0]);
        $this->assertEquals('2234', $xml->BasicTypeListFiled->IntValue[1]);
        $this->assertEquals(3, ($xml->BasicTypeListFiled->BoolValue)->count());
        $this->assertEquals('true', $xml->BasicTypeListFiled->BoolValue[0]);
        $this->assertEquals('true', $xml->BasicTypeListFiled->BoolValue[1]);
        $this->assertEquals('false', $xml->BasicTypeListFiled->BoolValue[2]);
        $this->assertEquals(1, ($xml->BasicTypeListFiled->FloatValue)->count());
        $this->assertEquals('3.14', $xml->BasicTypeListFiled->FloatValue[0]);
    }

    public function testSerializeMixedTypeListXml(): void
    {
        // empty
        $obj = new MixedTypeListXml();
        $str = Serializer::serializeXml($obj);
        $this->assertStringContainsString('<MixedTypeList/>', $str);

        // all
        $obj = new MixedTypeListXml(
            strValue: 'str-111',
            intValue: 12,
            xmlValues: [
                new BaiscTypeXml(
                    strValue: 'str',
                    intValue: 123,
                    boolValue: true,
                    floatValue: 1.14,
                ),
                new BaiscTypeXml(
                    strValue: 'str-1',
                    intValue: 223,
                    boolValue: false,
                ),
            ],
        );

        $str = Serializer::serializeXml($obj);
        $this->assertNotEmpty($str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(4, $xml->count());
        $this->assertEquals('str-111', $xml->StrValue);
        $this->assertEquals('12', $xml->IntValue);

        $this->assertEquals(2, ($xml->BasicTypeFiled)->count());
        $this->assertEquals('str', $xml->BasicTypeFiled[0]->StrValue);
        $this->assertEquals('123', $xml->BasicTypeFiled[0]->IntValue);
        $this->assertEquals('true', $xml->BasicTypeFiled[0]->BoolValue);
        $this->assertEquals('1.14', $xml->BasicTypeFiled[0]->FloatValue);

        $this->assertEquals('str-1', $xml->BasicTypeFiled[1]->StrValue);
        $this->assertEquals('223', $xml->BasicTypeFiled[1]->IntValue);
        $this->assertEquals('false', $xml->BasicTypeFiled[1]->BoolValue);
        $this->assertFalse(isset($xml->BasicTypeFiled[1]->FloatValue));
    }

    public function testSerializeWithSpecialRootName()
    {
        $obj = new BaiscTypeXml();
        $str = Serializer::serializeXml($obj);
        $this->assertNotEmpty($str);
        $this->assertStringContainsString('<BasicType/>', $str);

        // empty 
        $obj = new BaiscTypeXml();
        $str = Serializer::serializeXml($obj, 'MyRoot');
        $this->assertNotEmpty($str);
        $this->assertStringContainsString('<MyRoot/>', $str);
    }

    public function testSerializeLackXmlAnnotation()
    {
        //xml root
        $obj = new BaiscTypeLackAnnotationXml();
        $str = Serializer::serializeXml($obj);
        $this->assertNotEmpty($str);
        $this->assertStringContainsString('<BaiscTypeLackAnnotationXml/>', $str);

        $obj = new BaiscTypeLackAnnotationXml();
        $str = Serializer::serializeXml($obj, 'MyRoot');
        $this->assertNotEmpty($str);
        $this->assertStringContainsString('<MyRoot/>', $str);

        //xml element
        $obj = new BaiscTypeLackAnnotationXml(
            strValue: 'str-123',
            intValue: 1234,
            boolValue: true,
            floatValue: 3.14,
        );
        $str = Serializer::serializeXml($obj);
        $this->assertNotEmpty($str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(2, $xml->count());
        $this->assertEquals('str-123', $xml->StrValue);
        $this->assertEquals('1234', $xml->IntValue);
        $this->assertFalse(isset($xml->BoolValue));
        $this->assertFalse(isset($xml->FloatValue));
    }

    public function testSerializeDateTimeTypeXml()
    {
        //xml root
        $obj = new DatetimeTypeXml();
        $str = Serializer::serializeXml($obj);
        $this->assertNotEmpty($str);
        $this->assertStringContainsString('<DatetimeType/>', $str);

        $date = new \DateTime();
        $date->setTimestamp(1702783809);

        $date1 = new \DateTimeImmutable();
        $date1 = $date1->setTimestamp(1702783819);

        //xml element
        $obj = new DatetimeTypeXml(
            isotimeValue: $date,
            dateTimeImmutableValue: $date1,
            httptimeValue: $date,
            unixtimeValue: $date,
        );

        $str = Serializer::serializeXml($obj);
        $this->assertNotEmpty($str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(4, $xml->count());
        $this->assertEquals('2023-12-17T03:30:09Z', $xml->DateTimeValue);
        $this->assertEquals('2023-12-17T03:30:19Z', $xml->DateTimeImmutableValue);
        $this->assertEquals('1702783809', $xml->UnixtimeValue);
        $this->assertEquals('Sun, 17 Dec 2023 03:30:09 GMT', $xml->HttptimeValue);
    }

    public function testSerializeInput(): void
    {
        $datetimeUtc = new \DateTime();
        $datetimeUtc->setTimestamp(1702783809);

        $datetime2Utc = new \DateTime();
        $datetime2Utc->setTimestamp(1702783819);

        $request = new PutApiRequest(
            bucket: "bucket-124",
            #key: "key-1",
            strHeader: "str_header",
            intHeader: 123,
            boolHeader: true,
            floatHeader: 2.5,
            isotimeHeader: $datetimeUtc,
            httptimeHeader: $datetimeUtc,
            unixtimeHeader: $datetimeUtc,
            strParam: "str_param",
            intParam: 456,
            boolParam: false,
            floatParam: 4.5,
            isotimeParam: $datetime2Utc,
            httptimeParam: $datetime2Utc,
            unixtimeParam: $datetime2Utc,
            configuration: new RootConfiguration(
                id: "id-124",
                text: "just for test",
                subConfiguration: [
                    new SubConfiguration(
                        strField: 'str-1',
                        intField: 111,
                    ),
                    new SubConfiguration(
                        strField: 'str-2',
                        intField: 222,
                    ),
                ],
            )
        );

        # miss required field
        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
        );

        try {
            Serializer::serializeInput($request, $input);
            $this->assertTrue(false, "shoud not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        } catch (\Exception $e) {
            $this->assertTrue(false, "shoud not here");
        }

        #normal case
        $request->setKey('key');
        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
        );
        Serializer::serializeInput($request, $input);
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals('123', $input->getHeaders()['x-oss-int']);
        $this->assertEquals('true', $input->getHeaders()['x-oss-bool']);
        $this->assertEquals('2.5', $input->getHeaders()['x-oss-float']);
        $this->assertEquals('2023-12-17T03:30:09Z', $input->getHeaders()['x-oss-isotime']);
        $this->assertEquals('Sun, 17 Dec 2023 03:30:09 GMT', $input->getHeaders()['x-oss-httptime']);
        $this->assertEquals('1702783809', $input->getHeaders()['x-oss-unixtime']);

        $this->assertEquals('str_param', $input->getParameters()['param-str']);
        $this->assertEquals('456', $input->getParameters()['param-int']);
        $this->assertEquals('false', $input->getParameters()['param-bool']);
        $this->assertEquals('4.5', $input->getParameters()['param-float']);
        $this->assertEquals('2023-12-17T03:30:19Z', $input->getParameters()['param-isotime']);
        $this->assertEquals('Sun, 17 Dec 2023 03:30:19 GMT', $input->getParameters()['param-httptime']);
        $this->assertEquals('1702783819', $input->getParameters()['param-unixtime']);

        $str = $input->getBody()->getContents();
        $this->assertStringContainsString('<Configuration>', $str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(4, $xml->count());
        $this->assertEquals('id-124', $xml->Id);
        $this->assertEquals('just for test', $xml->Text);
        $this->assertEquals(2, ($xml->SubConfiguration)->count());
        $this->assertEquals('str-1', $xml->SubConfiguration[0]->StrField);
        $this->assertEquals('111', $xml->SubConfiguration[0]->IntField);
        $this->assertEquals('str-2', $xml->SubConfiguration[1]->StrField);
        $this->assertEquals('222', $xml->SubConfiguration[1]->IntField);
    }

    public function testSerializeInputWithHeadersAndParameters(): void
    {
        //case 1, input has headers and parameters
        $request = new PutApiARequest(
            bucket: "bucket-124",
            key: "key-1",
            strHeader: "str_header",
            strParam: "str_param",
        );

        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
            headers: ['Key' => 'value', 'key-1' => 'value-1'],
            parameters: ['Key' => 'value', 'key-1' => 'value-1'],
        );
        Serializer::serializeInput($request, $input);
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals('value', $input->getHeaders()['key']);
        $this->assertEquals('value-1', $input->getHeaders()['key-1']);
        $this->assertEquals('str_param', $input->getParameters()['param-str']);
        $this->assertEquals('value', $input->getParameters()['Key']);
        $this->assertEquals('value-1', $input->getParameters()['key-1']);

        //case 2, input and request has same headers and parameters
        $request = new PutApiARequest(
            bucket: "bucket-124",
            key: "key-1",
            strHeader: "str_header",
            strParam: "str_param",
        );

        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
            headers: ['X-OSS-str' => 'value'],
            parameters: ['param-str' => 'value', 'param-int' => '123'],
        );
        Serializer::serializeInput($request, $input);
        $this->assertEquals(1, count($input->getHeaders()));
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals(2, count($input->getParameters()));
        $this->assertEquals('str_param', $input->getParameters()['param-str']);
        $this->assertEquals('123', $input->getParameters()['param-int']);

        //case 3, request has headers and parameters, request.headers and request.parameters
        $request = new PutApiARequest(
            bucket: "bucket-124",
            key: "key-1",
            strHeader: "str_header",
            strParam: "str_param",
        );

        $request->setHeaders(['X-Oss-header1' => 'value-1', 'x-oss-header2' => 'value-2']);
        $request->setParameters(['X-Oss-param1' => 'value-1', 'x-oss-param2' => 'value-2']);

        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
            headers: ['Key' => 'value', 'key-1' => 'value-1'],
            parameters: ['Key' => 'value', 'key-1' => 'value-1'],
        );
        Serializer::serializeInput($request, $input);
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals('value', $input->getHeaders()['key']);
        $this->assertEquals('value-1', $input->getHeaders()['key-1']);
        $this->assertEquals('value-1', $input->getHeaders()['x-oss-header1']);
        $this->assertEquals('value-2', $input->getHeaders()['x-oss-header2']);
        $this->assertEquals('str_param', $input->getParameters()['param-str']);
        $this->assertEquals('value', $input->getParameters()['Key']);
        $this->assertEquals('value-1', $input->getParameters()['key-1']);
        $this->assertEquals('value-1', $input->getParameters()['X-Oss-param1']);
        $this->assertEquals('value-2', $input->getParameters()['x-oss-param2']);
    }

    public function testSerializeInputWithBody(): void
    {
        #case 1, request with body
        $request = new PutApiARequest(
            bucket: "bucket-124",
            key: "key-1",
            strHeader: "str_header",
            strParam: "str_param",
            configuration: "hello world"
        );

        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
        );
        Serializer::serializeInput($request, $input);
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals('str_param', $input->getParameters()['param-str']);

        $str = $input->getBody()->getContents();
        $this->assertEquals('hello world', $str);

        #case 2, request without body
        $request = new PutApiARequest(
            bucket: "bucket-124",
            key: "key-1",
            strHeader: "str_header",
            strParam: "str_param",
        );

        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
        );
        Serializer::serializeInput($request, $input);
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals('str_param', $input->getParameters()['param-str']);
        $this->assertEquals(null, $input->getBody());

        #case 3, input with body
        $request = new PutApiARequest(
            bucket: "bucket-124",
            key: "key-1",
            strHeader: "str_header",
            strParam: "str_param",
        );

        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
            body: Utils::streamFor('1234'),
        );
        Serializer::serializeInput($request, $input);
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals('str_param', $input->getParameters()['param-str']);

        $str = $input->getBody()->getContents();
        $this->assertEquals('1234', $str);

        #case 4, input & request with body
        $request = new PutApiARequest(
            bucket: "bucket-124",
            key: "key-1",
            strHeader: "str_header",
            strParam: "str_param",
            configuration: "hello world abc",
        );

        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
            body: Utils::streamFor('1234'),
        );
        Serializer::serializeInput($request, $input);
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals('str_param', $input->getParameters()['param-str']);

        $str = $input->getBody()->getContents();
        $this->assertEquals('hello world abc', $str);

        #case 5, request.paylaod
        $request = new PutApiARequest(
            bucket: "bucket-124",
            key: "key-1",
            strHeader: "str_header",
            strParam: "str_param",
        );

        $request->setPayload(Utils::streamFor('just a payload'));

        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
        );
        Serializer::serializeInput($request, $input);
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals('str_param', $input->getParameters()['param-str']);

        $str = $input->getBody()->getContents();
        $this->assertEquals('just a payload', $str);

        #case 6, request.paylaod and input.body
        $request = new PutApiARequest(
            bucket: "bucket-124",
            key: "key-1",
            strHeader: "str_header",
            strParam: "str_param",
        );

        $request->setPayload(Utils::streamFor('payload-1234'));

        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
            body: Utils::streamFor('body-1234'),
        );
        Serializer::serializeInput($request, $input);
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals('str_param', $input->getParameters()['param-str']);

        $str = $input->getBody()->getContents();
        $this->assertEquals('payload-1234', $str);

        #case 6, request.paylaod and request.body
        $request = new PutApiARequest(
            bucket: "bucket-124",
            key: "key-1",
            strHeader: "str_header",
            strParam: "str_param",
            configuration: 'request body',
        );

        $request->setPayload(Utils::streamFor('payload-1234'));

        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
            body: Utils::streamFor('body-1234'),
        );
        Serializer::serializeInput($request, $input);
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals('str_param', $input->getParameters()['param-str']);

        $str = $input->getBody()->getContents();
        $this->assertEquals('request body', $str);
    }

    public function testSerializeInputWithArrayHeader(): void
    {
        # case 1
        $request = new PutApiBRequest(
            bucket: "bucket-124",
            key: "key-1",
            strHeader: "str_header",
            strParam: "str_param",
            arrayHeader: ['a' => 'value-1', 'b' => 'value-2'],
        );

        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
        );
        Serializer::serializeInput($request, $input);
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals('value-1', $input->getHeaders()['x-oss-prefix-a']);
        $this->assertEquals('value-2', $input->getHeaders()['x-oss-prefix-b']);
        $this->assertEquals('str_param', $input->getParameters()['param-str']);

        # case 1
        $request = new PutApiBRequest(
            bucket: "bucket-124",
            key: "key-1",
            strHeader: "str_header",
            strParam: "str_param",
        );

        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
        );
        Serializer::serializeInput($request, $input);
        $this->assertEquals(1, count($input->getHeaders()));
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals('str_param', $input->getParameters()['param-str']);
    }

    public static function addTestHeader($request, OperationInput $input): void
    {
        $input->setHeader('Serializer-Header', '12345');
    }

    public function testSerializeInputWithCustomSerializer(): void
    {
        # case 1
        $request = new PutApiBRequest(
            bucket: "bucket-124",
            key: "key-1",
            strHeader: "str_header",
            strParam: "str_param",
            arrayHeader: ['a' => 'value-1', 'b' => 'value-2'],
        );

        $input = new OperationInput(
            opName: 'TestApi',
            method: 'GET',
            bucket: $request->getBucket(),
            key: $request->getKey(),
        );

        $customSerializer = [
            static function ($request, OperationInput $input) {
                $input->setHeader('my-header', 'just test');
            },
            [self::class, 'addTestHeader'],
        ];

        Serializer::serializeInput($request, $input, $customSerializer);
        $this->assertEquals('str_header', $input->getHeaders()['x-oss-str']);
        $this->assertEquals('value-1', $input->getHeaders()['x-oss-prefix-a']);
        $this->assertEquals('value-2', $input->getHeaders()['x-oss-prefix-b']);
        $this->assertEquals('just test', $input->getHeaders()['my-header']);
        $this->assertEquals('12345', $input->getHeaders()['serializer-header']);
        $this->assertEquals('str_param', $input->getParameters()['param-str']);
    }
}

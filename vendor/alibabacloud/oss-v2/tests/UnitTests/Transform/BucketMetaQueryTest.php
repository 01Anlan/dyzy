<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketMetaQuery;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketMetaQueryTest extends \PHPUnit\Framework\TestCase
{
    public function testFromOpenMetaQuery()
    {
        // miss required field
        try {
            $request = new Models\OpenMetaQueryRequest();
            $input = BucketMetaQuery::fromOpenMetaQuery($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\OpenMetaQueryRequest('bucket-123');
        $input = BucketMetaQuery::fromOpenMetaQuery($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('add', $input->getParameters()['comp']);
        $this->assertEquals('', $input->getParameters()['metaQuery']);

        $request = new Models\OpenMetaQueryRequest('bucket-123', 'basic');
        $input = BucketMetaQuery::fromOpenMetaQuery($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('add', $input->getParameters()['comp']);
        $this->assertEquals('', $input->getParameters()['metaQuery']);
        $this->assertEquals('basic', $input->getParameters()['mode']);

        $request = new Models\OpenMetaQueryRequest('bucket-123', 'semantic');
        $input = BucketMetaQuery::fromOpenMetaQuery($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('add', $input->getParameters()['comp']);
        $this->assertEquals('', $input->getParameters()['metaQuery']);
        $this->assertEquals('semantic', $input->getParameters()['mode']);
    }

    public function testToOpenMetaQuery()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketMetaQuery::toOpenMetaQuery($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketMetaQuery::toOpenMetaQuery($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetMetaQueryStatus()
    {
        // miss required field
        try {
            $request = new Models\GetMetaQueryStatusRequest();
            $input = BucketMetaQuery::fromGetMetaQueryStatus($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetMetaQueryStatusRequest('bucket-123');
        $input = BucketMetaQuery::fromGetMetaQueryStatus($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('', $input->getParameters()['metaQuery']);
    }

    public function testToGetMetaQueryStatus()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketMetaQuery::toGetMetaQueryStatus($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<MetaQueryStatus>
  <State>Running</State>
  <Phase>FullScanning</Phase>
  <CreateTime>2021-08-02T10:49:17.289372919+08:00</CreateTime>
  <UpdateTime>2021-08-02T10:49:17.289372919+08:00</UpdateTime>
</MetaQueryStatus>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            body: Utils::streamFor($body)
        );
        $result = BucketMetaQuery::toGetMetaQueryStatus($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('Running', $result->metaQueryStatus->state);
        $this->assertEquals('FullScanning', $result->metaQueryStatus->phase);
        $this->assertEquals('2021-08-02T10:49:17.289372919+08:00', $result->metaQueryStatus->createTime);
        $this->assertEquals('2021-08-02T10:49:17.289372919+08:00', $result->metaQueryStatus->updateTime);
    }

    public function testFromDoMetaQuery()
    {
        // miss required field
        try {
            $request = new Models\DoMetaQueryRequest();
            $input = BucketMetaQuery::fromDoMetaQuery($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\DoMetaQueryRequest('bucket-123');
            $input = BucketMetaQuery::fromDoMetaQuery($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, metaQuery", (string)$e);
        }

        $request = new Models\DoMetaQueryRequest('bucket-123', new Models\MetaQuery(
            maxResults: 5,
            query: "{'Field': 'Size','Value': '1048576','Operation': 'gt'}",
            sort: 'Size',
            order: Models\MetaQueryOrderType::ASC,
            aggregations: new Models\MetaQueryAggregations(
            [
                new Models\MetaQueryAggregation(
                    field: 'Size',
                    operation: 'sum'
                ),
                new Models\MetaQueryAggregation(
                    field: 'Size',
                    operation: 'max'
                ),
            ]
        ),
            nextToken: 'MTIzNDU2Nzg6aW1tdGVzdDpleGFtcGxlYnVja2V0OmRhdGFzZXQwMDE6b3NzOi8vZXhhbXBsZWJ1Y2tldC9zYW1wbGVvYmplY3QxLmpw****'
        ));
        $input = BucketMetaQuery::fromDoMetaQuery($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><MetaQuery><MaxResults>5</MaxResults><Query>{'Field': 'Size','Value': '1048576','Operation': 'gt'}</Query><Sort>Size</Sort><Order>asc</Order><Aggregations><Aggregation><Field>Size</Field><Operation>sum</Operation></Aggregation><Aggregation><Field>Size</Field><Operation>max</Operation></Aggregation></Aggregations><NextToken>MTIzNDU2Nzg6aW1tdGVzdDpleGFtcGxlYnVja2V0OmRhdGFzZXQwMDE6b3NzOi8vZXhhbXBsZWJ1Y2tldC9zYW1wbGVvYmplY3QxLmpw****</NextToken></MetaQuery>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        $request = new Models\DoMetaQueryRequest('bucket-123', new Models\MetaQuery(
            maxResults: 99,
            query: "Overlook the snow-covered forest",
            nextToken: 'MTIzNDU2Nzg6aW1tdGVzdDpleGFtcGxlYnVja2V0OmRhdGFzZXQwMDE6b3NzOi8vZXhhbXBsZWJ1Y2tldC9zYW1wbGVvYmplY3QxLmpw****',
            mediaTypes: new Models\MetaQueryMediaTypes('image'),
            simpleQuery: "{'Operation':'gt', 'Field': 'Size', 'Value': '30'}"
        ), mode: 'semantic');
        $input = BucketMetaQuery::fromDoMetaQuery($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><MetaQuery><MaxResults>99</MaxResults><Query>Overlook the snow-covered forest</Query><NextToken>MTIzNDU2Nzg6aW1tdGVzdDpleGFtcGxlYnVja2V0OmRhdGFzZXQwMDE6b3NzOi8vZXhhbXBsZWJ1Y2tldC9zYW1wbGVvYmplY3QxLmpw****</NextToken><MediaTypes><MediaType>image</MediaType></MediaTypes><SimpleQuery>{'Operation':'gt', 'Field': 'Size', 'Value': '30'}</SimpleQuery></MetaQuery>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToDoMetaQuery()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketMetaQuery::toDoMetaQuery($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<MetaQuery>
  <NextToken>MTIzNDU2Nzg6aW1tdGVzdDpleGFtcGxlYnVja2V0OmRhdGFzZXQwMDE6b3NzOi8vZXhhbXBsZWJ1Y2tldC9zYW1wbGVvYmplY3QxLmpw****</NextToken>
  <Files>
    <File>
      <Filename>exampleobject.txt</Filename>
      <Size>120</Size>
      <FileModifiedTime>2021-06-29T15:04:05.000000000Z07:00</FileModifiedTime>
      <OSSObjectType>Normal</OSSObjectType>
      <OSSStorageClass>Standard</OSSStorageClass>
      <ObjectACL>default</ObjectACL>
      <ETag>"fba9dede5f27731c9771645a3986****"</ETag>
      <OSSCRC64>4858A48BD1466884</OSSCRC64>
      <OSSTaggingCount>2</OSSTaggingCount>
      <OSSTagging>
        <Tagging>
          <Key>owner</Key>
          <Value>John</Value>
        </Tagging>
        <Tagging>
          <Key>type</Key>
          <Value>document</Value>
        </Tagging>
      </OSSTagging>
      <OSSUserMeta>
        <UserMeta>
          <Key>x-oss-meta-location</Key>
          <Value>hangzhou</Value>
        </UserMeta>
      </OSSUserMeta>
    </File>
  </Files>
  <Aggregations>
    <Aggregation>
      <Field>Size</Field>
      <Operation>sum</Operation>
      <Value>4859250309</Value>
    </Aggregation>
    <Aggregation>
      <Field>Size</Field>
      <Operation>max</Operation>
      <Value>2235483240</Value>
    </Aggregation>
  </Aggregations>
</MetaQuery>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            body: Utils::streamFor($body)
        );
        $result = BucketMetaQuery::toDoMetaQuery($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('MTIzNDU2Nzg6aW1tdGVzdDpleGFtcGxlYnVja2V0OmRhdGFzZXQwMDE6b3NzOi8vZXhhbXBsZWJ1Y2tldC9zYW1wbGVvYmplY3QxLmpw****', $result->nextToken);

        $this->assertEquals(1, count($result->files->file));
        $this->assertEquals('exampleobject.txt', $result->files->file[0]->filename);
        $this->assertEquals(120, $result->files->file[0]->size);
        $this->assertEquals('2021-06-29T15:04:05.000000000Z07:00', $result->files->file[0]->fileModifiedTime);
        $this->assertEquals('Normal', $result->files->file[0]->ossObjectType);
        $this->assertEquals('Standard', $result->files->file[0]->ossStorageClass);
        $this->assertEquals('default', $result->files->file[0]->objectACL);
        $this->assertEquals('"fba9dede5f27731c9771645a3986****"', $result->files->file[0]->etag);
        $this->assertEquals(2, $result->files->file[0]->ossTaggingCount);
        $this->assertEquals('owner', $result->files->file[0]->ossTagging->taggings[0]->key);
        $this->assertEquals('John', $result->files->file[0]->ossTagging->taggings[0]->value);
        $this->assertEquals('type', $result->files->file[0]->ossTagging->taggings[1]->key);
        $this->assertEquals('document', $result->files->file[0]->ossTagging->taggings[1]->value);
        $this->assertEquals(1, count($result->files->file[0]->ossUserMeta->userMetas));
        $this->assertEquals('x-oss-meta-location', $result->files->file[0]->ossUserMeta->userMetas[0]->key);
        $this->assertEquals('hangzhou', $result->files->file[0]->ossUserMeta->userMetas[0]->value);
        $this->assertEquals('Size', $result->aggregations->aggregations[0]->field);
        $this->assertEquals('sum', $result->aggregations->aggregations[0]->operation);
        $this->assertEquals(4859250309, $result->aggregations->aggregations[0]->value);
        $this->assertEquals('Size', $result->aggregations->aggregations[1]->field);
        $this->assertEquals('max', $result->aggregations->aggregations[1]->operation);
        $this->assertEquals(2235483240, $result->aggregations->aggregations[1]->value);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<MetaQuery>
  <NextToken></NextToken>
  <Aggregations>
    <Aggregation>
      <Field>Size</Field>
      <Operation>sum</Operation>
      <Value>30930054</Value>
    </Aggregation>
    <Aggregation>
      <Field>Size</Field>
      <Operation>group</Operation>
      <Groups>
        <Group>
          <Value>1536000</Value>
          <Count>1</Count>
        </Group>
        <Group>
          <Value>5472362</Value>
          <Count>1</Count>
        </Group>
        <Group>
          <Value>10354204</Value>
          <Count>1</Count>
        </Group>
        <Group>
          <Value>1890304</Value>
          <Count>3</Count>
        </Group>
        <Group>
          <Value>2632192</Value>
          <Count>3</Count>
        </Group>
      </Groups>
    </Aggregation>
  </Aggregations>
</MetaQuery>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            body: Utils::streamFor($body)
        );
        $result = BucketMetaQuery::toDoMetaQuery($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals(2, count($result->aggregations->aggregations));
        $this->assertEquals('Size', $result->aggregations->aggregations[0]->field);
        $this->assertEquals('sum', $result->aggregations->aggregations[0]->operation);
        $this->assertEquals(30930054, $result->aggregations->aggregations[0]->value);
        $this->assertEquals('Size', $result->aggregations->aggregations[1]->field);
        $this->assertEquals('group', $result->aggregations->aggregations[1]->operation);
        $this->assertEquals(5, count($result->aggregations->aggregations[1]->groups->groups));
        $this->assertEquals(1, $result->aggregations->aggregations[1]->groups->groups[0]->count);
        $this->assertEquals('1536000', $result->aggregations->aggregations[1]->groups->groups[0]->value);
        $this->assertEquals(1, $result->aggregations->aggregations[1]->groups->groups[1]->count);
        $this->assertEquals('5472362', $result->aggregations->aggregations[1]->groups->groups[1]->value);
        $this->assertEquals(1, $result->aggregations->aggregations[1]->groups->groups[2]->count);
        $this->assertEquals('10354204', $result->aggregations->aggregations[1]->groups->groups[2]->value);
        $this->assertEquals(3, $result->aggregations->aggregations[1]->groups->groups[3]->count);
        $this->assertEquals('1890304', $result->aggregations->aggregations[1]->groups->groups[3]->value);
        $this->assertEquals(3, $result->aggregations->aggregations[1]->groups->groups[4]->count);
        $this->assertEquals('2632192', $result->aggregations->aggregations[1]->groups->groups[4]->value);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<MetaQuery>
  <Files>
    <File>
      <URI>oss://bucket/sample-object.jpg</URI>
      <Filename>sample-object.jpg</Filename>
      <Size>1000</Size>
      <ObjectACL>default</ObjectACL>
      <FileModifiedTime>2021-06-29T14:50:14.011643661+08:00</FileModifiedTime>
      <ServerSideEncryption>AES256</ServerSideEncryption>
      <ServerSideEncryptionCustomerAlgorithm>SM4</ServerSideEncryptionCustomerAlgorithm>
      <ETag>"1D9C280A7C4F67F7EF873E28449****"</ETag>
      <OSSCRC64>559890638950338001</OSSCRC64>
      <ProduceTime>2021-06-29T14:50:15.011643661+08:00</ProduceTime>
      <ContentType>image/jpeg</ContentType>
      <MediaType>image</MediaType>
      <LatLong>30.134390,120.074997</LatLong>
      <Title>test</Title>
      <OSSExpiration>2024-12-01T12:00:00.000Z</OSSExpiration>
      <AccessControlAllowOrigin>https://aliyundoc.com</AccessControlAllowOrigin>
      <AccessControlRequestMethod>PUT</AccessControlRequestMethod>
      <ServerSideDataEncryption>SM4</ServerSideDataEncryption>
      <ServerSideEncryptionKeyId>9468da86-3509-4f8d-a61e-6eab1eac****</ServerSideEncryptionKeyId>
      <CacheControl>no-cache</CacheControl>
      <ContentDisposition>attachment; filename=test.jpg</ContentDisposition>
      <ContentEncoding>UTF-8</ContentEncoding>
      <ContentLanguage>zh-CN</ContentLanguage>
      <ImageHeight>500</ImageHeight>
      <ImageWidth>270</ImageWidth>
      <VideoWidth>1080</VideoWidth>
      <VideoHeight>1920</VideoHeight>
      <VideoStreams>
        <VideoStream>
          <CodecName>h264</CodecName>
          <Language>en</Language>
          <Bitrate>5407765</Bitrate>
          <FrameRate>25/1</FrameRate>
          <StartTime>0</StartTime>
          <Duration>22.88</Duration>
          <FrameCount>572</FrameCount>
          <BitDepth>8</BitDepth>
          <PixelFormat>yuv420p</PixelFormat>
          <ColorSpace>bt709</ColorSpace>
          <Height>720</Height>
          <Width>1280</Width>
        </VideoStream>
        <VideoStream>
          <CodecName>h264</CodecName>
          <Language>en</Language>
          <Bitrate>5407765</Bitrate>
          <FrameRate>25/1</FrameRate>
          <StartTime>0</StartTime>
          <Duration>22.88</Duration>
          <FrameCount>572</FrameCount>
          <BitDepth>8</BitDepth>
          <PixelFormat>yuv420p</PixelFormat>
          <ColorSpace>bt709</ColorSpace>
          <Height>720</Height>
          <Width>1280</Width>
        </VideoStream>
      </VideoStreams>
      <AudioStreams>
        <AudioStream>
          <CodecName>aac</CodecName>
          <Bitrate>1048576</Bitrate>
          <SampleRate>48000</SampleRate>
          <StartTime>0.0235</StartTime>
          <Duration>3.690667</Duration>
          <Channels>2</Channels>
          <Language>en</Language>
        </AudioStream>
      </AudioStreams>
      <Subtitles>
        <Subtitle>
          <CodecName>mov_text</CodecName>
          <Language>en</Language>
          <StartTime>0</StartTime>
          <Duration>71.378</Duration>
        </Subtitle>
        <Subtitle>
          <CodecName>mov_text</CodecName>
          <Language>en</Language>
          <StartTime>72</StartTime>
          <Duration>71.378</Duration>
        </Subtitle>
      </Subtitles>
      <Bitrate>5407765</Bitrate>
      <Artist>Jane</Artist>
      <AlbumArtist>Jenny</AlbumArtist>
      <Composer>Jane</Composer>
      <Performer>Jane</Performer>
      <Album>FirstAlbum</Album>
      <Duration>71.378</Duration>
      <Addresses>
        <Address>
          <AddressLine>中国浙江省杭州市余杭区文一西路969号</AddressLine>
          <City>杭州市</City>
          <Country>中国</Country>
          <District>余杭区</District>
          <Language>zh-Hans</Language>
          <Province>浙江省</Province>
          <Township>文一西路</Township>
        </Address>
        <Address>
          <AddressLine>中国浙江省杭州市余杭区文一西路970号</AddressLine>
          <City>杭州市</City>
          <Country>中国</Country>
          <District>余杭区</District>
          <Language>zh-Hans</Language>
          <Province>浙江省</Province>
          <Township>文一西路</Township>
        </Address>
      </Addresses>
      <OSSObjectType>Normal</OSSObjectType>
      <OSSStorageClass>Standard</OSSStorageClass>
      <OSSTaggingCount>2</OSSTaggingCount>
      <OSSTagging>
        <Tagging>
          <Key>key</Key>
          <Value>val</Value>
        </Tagging>
        <Tagging>
          <Key>key2</Key>
          <Value>val2</Value>
        </Tagging>
      </OSSTagging>
      <OSSUserMeta>
        <UserMeta>
          <Key>key</Key>
          <Value>val</Value>
        </UserMeta>
      </OSSUserMeta>
    </File>
  </Files>
</MetaQuery>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            body: Utils::streamFor($body)
        );
        $result = BucketMetaQuery::toDoMetaQuery($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals(1, count($result->files->file));
        $this->assertEquals('oss://bucket/sample-object.jpg', $result->files->file[0]->uri);
        $this->assertEquals('sample-object.jpg', $result->files->file[0]->filename);
        $this->assertEquals(1000, $result->files->file[0]->size);
        $this->assertEquals('2021-06-29T14:50:14.011643661+08:00', $result->files->file[0]->fileModifiedTime);
        $this->assertEquals('AES256', $result->files->file[0]->serverSideEncryption);
        $this->assertEquals('SM4', $result->files->file[0]->serverSideEncryptionCustomerAlgorithm);
        $this->assertEquals('"1D9C280A7C4F67F7EF873E28449****"', $result->files->file[0]->etag);
        $this->assertEquals('559890638950338001', $result->files->file[0]->ossCrc64);
        $this->assertEquals('2021-06-29T14:50:15.011643661+08:00', $result->files->file[0]->produceTime);
        $this->assertEquals('image/jpeg', $result->files->file[0]->contentType);
        $this->assertEquals('image', $result->files->file[0]->mediaType);
        $this->assertEquals('30.134390,120.074997', $result->files->file[0]->latLong);
        $this->assertEquals('test', $result->files->file[0]->title);
        $this->assertEquals('2024-12-01T12:00:00.000Z', $result->files->file[0]->ossExpiration);
        $this->assertEquals('https://aliyundoc.com', $result->files->file[0]->accessControlAllowOrigin);
        $this->assertEquals('PUT', $result->files->file[0]->accessControlRequestMethod);
        $this->assertEquals('SM4', $result->files->file[0]->serverSideDataEncryption);
        $this->assertEquals('9468da86-3509-4f8d-a61e-6eab1eac****', $result->files->file[0]->serverSideEncryptionKeyId);
        $this->assertEquals('no-cache', $result->files->file[0]->cacheControl);
        $this->assertEquals('attachment; filename=test.jpg', $result->files->file[0]->contentDisposition);
        $this->assertEquals('UTF-8', $result->files->file[0]->contentEncoding);
        $this->assertEquals('zh-CN', $result->files->file[0]->contentLanguage);
        $this->assertEquals(500, $result->files->file[0]->imageHeight);
        $this->assertEquals(270, $result->files->file[0]->imageWidth);
        $this->assertEquals(1920, $result->files->file[0]->videoHeight);
        $this->assertEquals(1080, $result->files->file[0]->videoWidth);
        $this->assertEquals(2, count($result->files->file[0]->videoStreams->videoStream));
        $this->assertEquals('h264', $result->files->file[0]->videoStreams->videoStream[0]->codecName);
        $this->assertEquals('en', $result->files->file[0]->videoStreams->videoStream[0]->language);
        $this->assertEquals(5407765, $result->files->file[0]->videoStreams->videoStream[0]->bitrate);
        $this->assertEquals(8, $result->files->file[0]->videoStreams->videoStream[0]->bitDepth);
        $this->assertEquals('25/1', $result->files->file[0]->videoStreams->videoStream[0]->frameRate);
        $this->assertEquals(0, $result->files->file[0]->videoStreams->videoStream[0]->startTime);
        $this->assertEquals(22.88, $result->files->file[0]->videoStreams->videoStream[0]->duration);
        $this->assertEquals(572, $result->files->file[0]->videoStreams->videoStream[0]->frameCount);
        $this->assertEquals('yuv420p', $result->files->file[0]->videoStreams->videoStream[0]->pixelFormat);
        $this->assertEquals('bt709', $result->files->file[0]->videoStreams->videoStream[0]->colorSpace);
        $this->assertEquals(720, $result->files->file[0]->videoStreams->videoStream[0]->height);
        $this->assertEquals(1280, $result->files->file[0]->videoStreams->videoStream[0]->width);
        $this->assertEquals('h264', $result->files->file[0]->videoStreams->videoStream[1]->codecName);
        $this->assertEquals('en', $result->files->file[0]->videoStreams->videoStream[1]->language);
        $this->assertEquals(5407765, $result->files->file[0]->videoStreams->videoStream[1]->bitrate);
        $this->assertEquals('25/1', $result->files->file[0]->videoStreams->videoStream[1]->frameRate);
        $this->assertEquals(0, $result->files->file[0]->videoStreams->videoStream[1]->startTime);
        $this->assertEquals(22.88, $result->files->file[0]->videoStreams->videoStream[1]->duration);
        $this->assertEquals(572, $result->files->file[0]->videoStreams->videoStream[1]->frameCount);
        $this->assertEquals(8, $result->files->file[0]->videoStreams->videoStream[1]->bitDepth);
        $this->assertEquals('yuv420p', $result->files->file[0]->videoStreams->videoStream[1]->pixelFormat);
        $this->assertEquals('bt709', $result->files->file[0]->videoStreams->videoStream[1]->colorSpace);
        $this->assertEquals(720, $result->files->file[0]->videoStreams->videoStream[1]->height);
        $this->assertEquals(1280, $result->files->file[0]->videoStreams->videoStream[1]->width);

        $this->assertEquals(1, count($result->files->file[0]->audioStreams->audioStream));
        $this->assertEquals('aac', $result->files->file[0]->audioStreams->audioStream[0]->codecName);
        $this->assertEquals(1048576, $result->files->file[0]->audioStreams->audioStream[0]->bitrate);
        $this->assertEquals(48000, $result->files->file[0]->audioStreams->audioStream[0]->sampleRate);
        $this->assertEquals(0.0235, $result->files->file[0]->audioStreams->audioStream[0]->startTime);
        $this->assertEquals(3.690667, $result->files->file[0]->audioStreams->audioStream[0]->duration);
        $this->assertEquals(2, $result->files->file[0]->audioStreams->audioStream[0]->channels);
        $this->assertEquals('en', $result->files->file[0]->audioStreams->audioStream[0]->language);

        $this->assertEquals(2, count($result->files->file[0]->subtitles->subtitle));
        $this->assertEquals('mov_text', $result->files->file[0]->subtitles->subtitle[0]->codecName);
        $this->assertEquals('en', $result->files->file[0]->subtitles->subtitle[0]->language);
        $this->assertEquals(0, $result->files->file[0]->subtitles->subtitle[0]->startTime);
        $this->assertEquals(71.378, $result->files->file[0]->subtitles->subtitle[0]->duration);
        $this->assertEquals('mov_text', $result->files->file[0]->subtitles->subtitle[1]->codecName);
        $this->assertEquals('en', $result->files->file[0]->subtitles->subtitle[1]->language);
        $this->assertEquals(72, $result->files->file[0]->subtitles->subtitle[1]->startTime);
        $this->assertEquals(71.378, $result->files->file[0]->subtitles->subtitle[1]->duration);

        $this->assertEquals(5407765, $result->files->file[0]->bitrate);
        $this->assertEquals('Jane', $result->files->file[0]->artist);
        $this->assertEquals('Jenny', $result->files->file[0]->albumArtist);
        $this->assertEquals('Jane', $result->files->file[0]->composer);
        $this->assertEquals('Jane', $result->files->file[0]->performer);
        $this->assertEquals('FirstAlbum', $result->files->file[0]->album);
        $this->assertEquals(71.378, $result->files->file[0]->duration);

        $this->assertEquals(2, count($result->files->file[0]->addresses->address));
        $this->assertEquals('中国浙江省杭州市余杭区文一西路969号', $result->files->file[0]->addresses->address[0]->addressLine);
        $this->assertEquals('杭州市', $result->files->file[0]->addresses->address[0]->city);
        $this->assertEquals('中国', $result->files->file[0]->addresses->address[0]->country);
        $this->assertEquals('余杭区', $result->files->file[0]->addresses->address[0]->district);
        $this->assertEquals('zh-Hans', $result->files->file[0]->addresses->address[0]->language);
        $this->assertEquals('浙江省', $result->files->file[0]->addresses->address[0]->province);
        $this->assertEquals('文一西路', $result->files->file[0]->addresses->address[0]->township);

        $this->assertEquals('中国浙江省杭州市余杭区文一西路970号', $result->files->file[0]->addresses->address[1]->addressLine);
        $this->assertEquals('杭州市', $result->files->file[0]->addresses->address[1]->city);
        $this->assertEquals('中国', $result->files->file[0]->addresses->address[1]->country);
        $this->assertEquals('余杭区', $result->files->file[0]->addresses->address[1]->district);
        $this->assertEquals('zh-Hans', $result->files->file[0]->addresses->address[1]->language);
        $this->assertEquals('浙江省', $result->files->file[0]->addresses->address[1]->province);
        $this->assertEquals('文一西路', $result->files->file[0]->addresses->address[1]->township);

        $this->assertEquals('Normal', $result->files->file[0]->ossObjectType);
        $this->assertEquals('Standard', $result->files->file[0]->ossStorageClass);
        $this->assertEquals(2, $result->files->file[0]->ossTaggingCount);
        $this->assertEquals('key', $result->files->file[0]->ossTagging->taggings[0]->key);
        $this->assertEquals('val', $result->files->file[0]->ossTagging->taggings[0]->value);
        $this->assertEquals('key2', $result->files->file[0]->ossTagging->taggings[1]->key);
        $this->assertEquals('val2', $result->files->file[0]->ossTagging->taggings[1]->value);
        $this->assertEquals(1, count($result->files->file[0]->ossUserMeta->userMetas));
        $this->assertEquals('key', $result->files->file[0]->ossUserMeta->userMetas[0]->key);
        $this->assertEquals('val', $result->files->file[0]->ossUserMeta->userMetas[0]->value);
    }

    public function testFromCloseMetaQuery()
    {
        // miss required field
        try {
            $request = new Models\CloseMetaQueryRequest();
            $input = BucketMetaQuery::fromCloseMetaQuery($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\CloseMetaQueryRequest('bucket-123');
        $input = BucketMetaQuery::fromCloseMetaQuery($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketMetaQuery()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketMetaQuery::toCloseMetaQuery($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
        );
        $result = BucketMetaQuery::toCloseMetaQuery($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}

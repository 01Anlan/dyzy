<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;
use SebastianBergmann\Type\ObjectType;

class ClientObjectBasicTest extends TestIntegration
{
    public function testObjectBasic()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();

        // put object
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $body = 'hi oss';
        $putObjRequest->body = Oss\Utils::streamFor($body);
        $result = $client->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // get object
        $result = $client->getObject(new Oss\Models\GetObjectRequest(
            $bucketName,
            $key
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // copy object
        $destKey = self::randomObjectName() . '-dest';
        $copyObjRequest = new Oss\Models\CopyObjectRequest(
            $bucketName,
            $destKey,
        );
        $copyObjRequest->sourceKey = $key;
        $result = $client->copyObject($copyObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // append object
        $appendKey = self::randomObjectName() . '-append';
        $appendObjRequest = new Oss\Models\AppendObjectRequest(
            $bucketName,
            $appendKey,
            0
        );
        $result = $client->appendObject($appendObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // head object
        $headObjRequest = new Oss\Models\HeadObjectRequest(
            $bucketName,
            $appendKey,
        );
        $result = $client->headObject($headObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // get object meta
        $getObjMetaRequest = new Oss\Models\GetObjectMetaRequest(
            $bucketName,
            $appendKey,
        );
        $result = $client->getObjectMeta($getObjMetaRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // restore object
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $putObjRequest->storageClass = Oss\Models\StorageClassType::DEEP_COLD_ARCHIVE;
        $result = $client->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        $restoreObjRequest = new Oss\Models\RestoreObjectRequest(
            $bucketName,
            $key,
        );
        $restoreObjRequest->restoreRequest = new Oss\Models\RestoreRequest(3);
        $result = $client->restoreObject($restoreObjRequest);
        $this->assertEquals(202, $result->statusCode);
        $this->assertEquals('Accepted', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // clean restored object
        try {
            $result = $client->cleanRestoredObject(new Oss\Models\CleanRestoredObjectRequest(
                $bucketName,
                $key,
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error CleanRestoredObject', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('ArchiveRestoreNotFinished', $se->getErrorCode());
                $this->assertEquals(409, $se->getStatusCode());
            }
        }

        // delete object
        $delObjRequest = new Oss\Models\DeleteObjectRequest(
            $bucketName,
            $key,
        );
        $result = $client->deleteObject($delObjRequest);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // delete objects
        $delObjsRequest = new Oss\Models\DeleteMultipleObjectsRequest(
            $bucketName,
        );
        $delArray = [];
        for ($i = 1; $i <= 10; $i++) {
            // put object
            $putObjRequest = new Oss\Models\PutObjectRequest(
                $bucketName,
                $key . $i,
            );
            $delArray[] = new Oss\Models\ObjectIdentifier($key . $i);
            $body = 'hi oss';
            $putObjRequest->body = Oss\Utils::streamFor($body);
            $result = $client->putObject($putObjRequest);
            $this->assertEquals(200, $result->statusCode);
        }
        $delObjsRequest->delete = new Oss\Models\Delete($delArray);
        $result = $client->deleteMultipleObjects($delObjsRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
    }

    public function testObjectBasicFail()
    {
        $client = $this->getInvalidAkClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();
        try {
            $putObjRequest = new Oss\Models\PutObjectRequest(
                $bucketName,
                $key,
            );
            $body = 'hi oss';
            $putObjRequest->body = Oss\Utils::streamFor($body);
            $result = $client->putObject($putObjRequest);
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutObject', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        try {
            $result = $client->getObject(new Oss\Models\GetObjectRequest(
                $bucketName,
                $key
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetObject', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        try {
            $destKey = self::randomObjectName() . '-dest';
            $copyObjRequest = new Oss\Models\CopyObjectRequest(
                $bucketName,
                $destKey,
            );
            $copyObjRequest->sourceKey = $key;
            $result = $client->copyObject($copyObjRequest);
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error CopyObject', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        // append object
        try {
            $appendKey = self::randomObjectName() . '-append';
            $appendObjRequest = new Oss\Models\AppendObjectRequest(
                $bucketName,
                $appendKey,
                0
            );
            $result = $client->appendObject($appendObjRequest);
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error AppendObject', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        // seal append object
        try {
            $sealAppendObjRequest = new Oss\Models\SealAppendObjectRequest(
                $bucketName,
                $appendKey,
                0
            );
            $result = $client->sealAppendObject($sealAppendObjRequest);
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error SealAppendObject', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        // head object
        try {
            $headObjRequest = new Oss\Models\HeadObjectRequest(
                $bucketName,
                $key,
            );
            $result = $client->headObject($headObjRequest);
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error HeadObject', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        try {
            $metaObjRequest = new Oss\Models\GetObjectMetaRequest(
                $bucketName,
                $key,
            );
            $result = $client->getObjectMeta($metaObjRequest);
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetObjectMeta', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        try {
            $result = $client->restoreObject(new Oss\Models\RestoreObjectRequest(
                $bucketName,
                $key,
                null,
                new Oss\Models\RestoreRequest(3)
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error RestoreObject', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        try {
            $result = $client->cleanRestoredObject(new Oss\Models\CleanRestoredObjectRequest(
                $bucketName,
                $key,
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error CleanRestoredObject', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        // delete object
        try {
            $delObjRequest = new Oss\Models\DeleteObjectRequest(
                $bucketName,
                $key,
            );
            $result = $client->deleteObject($delObjRequest);
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteObject', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        // delete objects
        try {
            $delObjRequest = new Oss\Models\DeleteMultipleObjectsRequest(
                $bucketName,
            );
            $delObjRequest->delete = new Oss\Models\Delete(
                [
                    new Oss\Models\ObjectIdentifier('key1'),
                    new Oss\Models\ObjectIdentifier('key2', 'version-id-2'),
                ], true
            );
            $result = $client->deleteMultipleObjects($delObjRequest);
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteMultipleObjects', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testObjectAcl()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();

        // put object
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $body = 'hi oss';
        $putObjRequest->body = Oss\Utils::streamFor($body);
        $result = $client->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // put object acl
        $result = $client->putObjectAcl(new Oss\Models\PutObjectAclRequest(
            $bucketName,
            $key,
            Oss\Models\ObjectACLType::PRIVATE
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // get object acl
        $result = $client->getObjectAcl(new Oss\Models\GetObjectAclRequest(
            $bucketName,
            $key
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertNull($result->versionId);
        $this->assertEquals(Oss\Models\ObjectACLType::PRIVATE, $result->accessControlList->grant);
    }

    public function testObjectAclFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName() . "-not-exist";
        try {
            $result = $client->putObjectAcl(new Oss\Models\PutObjectAclRequest(
                $bucketName,
                $key,
                Oss\Models\ObjectACLType::PRIVATE
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutObjectAcl', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchKey', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
            }
        }

        try {
            $result = $client->getObjectAcl(new Oss\Models\GetObjectAclRequest(
                $bucketName,
                $key
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetObjectAcl', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchKey', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
            }
        }
    }

    public function testObjectTagging()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();

        // put object
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $body = 'hi oss';
        $putObjRequest->body = Oss\Utils::streamFor($body);
        $result = $client->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // put object tagging
        $result = $client->putObjectTagging(new Oss\Models\PutObjectTaggingRequest(
            $bucketName,
            $key,
            new Oss\Models\Tagging(
                new Oss\Models\TagSet(
                    [new Oss\Models\Tag('k1', 'v1'), new Oss\Models\Tag('k2', 'v2')]
                )
            )
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // get object tagging
        $result = $client->getObjectTagging(new Oss\Models\GetObjectTaggingRequest(
            $bucketName,
            $key
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertNull($result->versionId);
        $this->assertEquals(2, count($result->tagSet->tags));

        // delete object tagging
        $result = $client->deleteObjectTagging(new Oss\Models\DeleteObjectTaggingRequest(
            $bucketName,
            $key
        ));
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
    }

    public function testObjectTaggingFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName() . "-not-exist";
        try {
            $result = $client->putObjectTagging(new Oss\Models\PutObjectTaggingRequest(
                $bucketName,
                $key,
                new Oss\Models\Tagging(
                    new Oss\Models\TagSet(
                        [new Oss\Models\Tag('k1', 'v1'), new Oss\Models\Tag('k2', 'v2')]
                    )
                )
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutObjectTagging', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchKey', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
            }
        }

        try {
            $result = $client->getObjectTagging(new Oss\Models\GetObjectTaggingRequest(
                $bucketName,
                $key
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetObjectTagging', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchKey', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
            }
        }

        try {
            $result = $client->deleteObjectTagging(new Oss\Models\DeleteObjectTaggingRequest(
                $bucketName,
                $key
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteObjectTagging', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchKey', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
            }
        }
    }

    public function testSymlink()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName() . '-target';

        // put object
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $body = 'hi oss';
        $putObjRequest->body = Oss\Utils::streamFor($body);
        $result = $client->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // put symlink
        $symlinkKey = self::randomObjectName() . '-symlink';
        $result = $client->putSymlink(new Oss\Models\PutSymlinkRequest(
            $bucketName,
            $symlinkKey,
            $key
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // get symlink
        $result = $client->getSymlink(new Oss\Models\GetSymlinkRequest(
            $bucketName,
            $symlinkKey
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
    }

    public function testSymlinkFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . '-not-exist';
        $key = self::randomObjectName() . "-target";
        $symlinkKey = self::randomObjectName() . '-symlink';
        try {
            $result = $client->putSymlink(new Oss\Models\PutSymlinkRequest(
                $bucketName,
                $symlinkKey,
                $key
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutSymlink', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
            }
        }

        try {
            $result = $client->getSymlink(new Oss\Models\GetSymlinkRequest(
                $bucketName,
                $symlinkKey,
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetSymlink', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
            }
        }
    }
}

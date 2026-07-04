<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Signer;

use DateTime;
use DateTimeZone;
use GuzzleHttp\Psr7\Query;

class SignerV4 implements SignerInterface
{
    const ALGORITHM_V4 = "OSS4-HMAC-SHA256";
    const UNSIGNED_PAYLOAD = "UNSIGNED-PAYLOAD";
    const CONTENT_SHA256_HEADER = "x-oss-content-sha256";

    /**
     * @param SigningContext $signingCtx
     * @throws \Exception
     */
    public function sign(SigningContext $signingCtx)
    {
        if ($signingCtx->credentials === null || !$signingCtx->credentials->hasKeys()) {
            throw new \InvalidArgumentException("SigningContext Credentials is null or empty.");
        }

        if (empty($signingCtx->region)) {
            throw new \InvalidArgumentException("SigningContext Region is empty.");
        }

        if ($signingCtx->request === null) {
            throw new \InvalidArgumentException("SigningContext Request is null.");
        }

        if ($signingCtx->authMethodQuery) {
            $this->authQuery($signingCtx);
        } else {
            $this->authHeader($signingCtx);
        }
    }

    /**
     * @param SigningContext $signingCtx
     * @throws \Exception
     */
    public function authQuery(SigningContext $signingCtx): void
    {
        $request = $signingCtx->request;
        $cred = $signingCtx->credentials;

        // Date
        $now = new DateTime('now', new DateTimeZone('UTC'));
        if (!isset($signingCtx->time)) {
            $signingCtx->time = clone $now;
            $signingCtx->time->modify('+' . self::DEFAULT_EXPIRES_DURATION . 'seconds');
        }

        if (isset($signingCtx->signTime)) {
            $now = $signingCtx->signTime;
        }
        $datetime = $now->format("Ymd\THis\Z");
        $date = $now->format("Ymd");
        $expires = $signingCtx->time->getTimestamp() - $now->getTimestamp();

        // Scope
        $region = $signingCtx->region;
        $product = $signingCtx->product;
        $scope = $this->buildScope($date, $region, $product);
        $additionalHeaders = $this->getCommonAdditionalHeaders($request->getHeaders(), $signingCtx->additionalHeaders);
        // Credentials information
        $query = Query::parse($request->getUri()->getQuery());
        if ((string)$cred->getSecurityToken() !== '') {
            $query['x-oss-security-token'] = $cred->getSecurityToken();
        }
        $query['x-oss-signature-version'] = self::ALGORITHM_V4;
        $query['x-oss-date'] = $datetime;
        $query['x-oss-expires'] = $expires;
        $query['x-oss-credential'] = sprintf("%s/%s", $cred->getAccessKeyId(), $scope);
        if (count($additionalHeaders) > 0) {
            $query['x-oss-additional-headers'] = implode(';', $additionalHeaders);
        }
        ksort($query);
        $signingCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query, '', '&', PHP_QUERY_RFC3986)));
        // CanonicalRequest
        $canonicalRequest = $this->calcCanonicalRequest($signingCtx, $additionalHeaders);
//        printf("canonicalRequest:%s" . PHP_EOL, $canonicalRequest);
        // StringToSign
        $stringToSign = $this->calcStringToSign($datetime, $scope, $canonicalRequest);
        $signingCtx->stringToSign = $stringToSign;
//        printf("stringToSign:%s" . PHP_EOL, $stringToSign);
        // Signature
        $signature = $this->calcSignature($cred->getAccessKeySecret(), $date, $region, $product, $stringToSign);
//        printf("signature:%s" . PHP_EOL, $signature);
        // Authorization query
        $query['x-oss-signature'] = $signature;
        ksort($query);
        $request = $request->withUri($request->getUri()->withQuery(http_build_query($query, '', '&', PHP_QUERY_RFC3986)));
        $signingCtx->request = $request;
    }

    /**
     * @param SigningContext $signingCtx
     * @throws \Exception
     */
    public function authHeader(SigningContext $signingCtx): void
    {
        $request = $signingCtx->request;
        $cred = $signingCtx->credentials;

        // Date
        if (!isset($signingCtx->time)) {
            $signingCtx->time = (new DateTime('now', new DateTimeZone('UTC')))->modify('+' . $signingCtx->clockOffset . 'seconds');
        }
        $datetime = $signingCtx->time->format("Ymd\THis\Z");
        $dateGmt = $signingCtx->time->format("D, d M Y H:i:s \G\M\T");
        $date = $signingCtx->time->format("Ymd");
        $request = $request->withHeader(self::OSS_DATE_HEADER, $datetime)
            ->withHeader(self::DATE_HEADER, $dateGmt);

        // Credentials information
        if ($cred->getSecurityToken() != '') {
            $request = $request->withHeader(self::SECURITY_TOKEN_HEADER, $cred->getSecurityToken());
        }

        if ($request->getHeaderLine(self::CONTENT_SHA256_HEADER) == "") {
            $request = $request->withHeader(self::CONTENT_SHA256_HEADER, self::UNSIGNED_PAYLOAD);
        }
        // Scope
        $region = $signingCtx->region;
        $product = $signingCtx->product;
        $scope = $this->buildScope($date, $region, $product);
        $additionalHeaders = $this->getCommonAdditionalHeaders($request->getHeaders(), $signingCtx->additionalHeaders);

        // CanonicalRequest
        $signingCtx->request = $request;
        $canonicalRequest = $this->calcCanonicalRequest($signingCtx, $additionalHeaders);
//        printf("canonicalRequest:%s" . PHP_EOL, $canonicalRequest);
        // StringToSign
        $stringToSign = $this->calcStringToSign($datetime, $scope, $canonicalRequest);
        $signingCtx->stringToSign = $stringToSign;
//        printf("stringToSign:%s" . PHP_EOL, $stringToSign);
        // Signature
        $signature = $this->calcSignature($cred->getAccessKeySecret(), $date, $region, $product, $stringToSign);
//        printf("signature:%s" . PHP_EOL, $signature);

        // credential
        $buf = 'OSS4-HMAC-SHA256 Credential=';
        $buf .= $cred->getAccessKeyId() . '/' . $scope;
        if (count($additionalHeaders) > 0) {
            $buf .= ',AdditionalHeaders=' . implode(';', $additionalHeaders);
        }
        $buf .= ",Signature=$signature";
        $request = $request->withHeader(self::AUTHORIZATION_HEADER, $buf);
        $signingCtx->request = $request;
    }

    /**
     * @param string $h
     * @return bool
     */
    public function isSignedHeader(string $h): bool
    {
        $lowerCaseKey = strtolower($h);
        return (strncmp($lowerCaseKey, self::OSS_HEADER_PREFIX, 6) == 0) ||
            ($lowerCaseKey === "content-type") ||
            ($lowerCaseKey === "content-md5");
    }

    /**
     * @param string $datetime
     * @param string $scope
     * @param string $canonicalRequest
     * @return string
     */
    private function calcStringToSign(string $datetime, string $scope, string $canonicalRequest): string
    {
        $hashValue = hash('sha256', $canonicalRequest);
        return "OSS4-HMAC-SHA256\n" .
            $datetime . "\n" .
            $scope . "\n" .
            $hashValue;
    }


    /**
     * @param string $sk
     * @param string $date
     * @param string $region
     * @param string $product
     * @param string $stringToSign
     * @return string
     */
    private function calcSignature(string $sk, string $date, string $region, string $product, string $stringToSign)
    {
        $signingKey = "aliyun_v4" . $sk;
        $h1Key = hash_hmac('sha256', $date, $signingKey, true);
        $h2Key = hash_hmac('sha256', $region, $h1Key, true);
        $h3Key = hash_hmac('sha256', $product, $h2Key, true);
        $h4Key = hash_hmac('sha256', 'aliyun_v4_request', $h3Key, true);
        $signatureBinary = hash_hmac('sha256', $stringToSign, $h4Key, true);
        return bin2hex($signatureBinary);
    }

    /**
     * @param array $headers
     * @param array $additionalHeaders
     * @return array
     */
    private function getCommonAdditionalHeaders(array $headers, array $additionalHeaders): array
    {
        $addHeaders = array();
        foreach ($additionalHeaders as $key) {
            $lowk = strtolower($key);
            if ($this->isSignedHeader($lowk)) {
                continue;
            }
            $addHeaders[$lowk] = '';
        }
        $keys = array();
        foreach ($headers as $key => $value) {
            $lowk = strtolower((string)$key);
            if (isset($addHeaders[$lowk])) {
                $keys[$lowk] = '';
            }
        }
        ksort($keys);
        return array_keys($keys);
    }

    /**
     * @param $date string
     * @param $region string
     * @param $product string
     * @return string
     */
    private function buildScope(string $date, string $region, string $product): string
    {
        return sprintf("%s/%s/%s/aliyun_v4_request", $date, $region, $product);
    }


    /**
     * @param SigningContext $signingCtx
     * @param array $additionalHeaders
     * @return string
     */
    private function calcCanonicalRequest(SigningContext $signingCtx, array $additionalHeaders): string
    {
        $request = $signingCtx->request;
        // Canonical Request
        // HTTP Verb + "\n" +
        // Canonical URI + "\n" +
        // Canonical Query String + "\n" +
        // Canonical Headers + "\n" +
        // Additional Headers + "\n" +
        // Hashed PayLoad

        // Canonical Uri
        $uri = "/";
        if (isset($signingCtx->bucket)) {
            $uri .= $signingCtx->bucket . "/";
        }
        if (isset($signingCtx->key)) {
            $uri .= $signingCtx->key;
        }
        $canonicalUri = str_replace(array('%2F'), array('/'), rawurlencode($uri));
        // Canonical Query
        $query = Query::parse($request->getUri()->getQuery(), false);
        ksort($query);
        $canonicalQuery = '';
        foreach ($query as $k => $v) {
            if (!empty($canonicalQuery)) {
                $canonicalQuery .= '&';
            }
            $canonicalQuery .= $k;
            if ($v != '') {
                $canonicalQuery .= '=' . $v;
            }
        }
        // Canonical Headers
        $headers = [];
        $addHeadersMap = array_map('strtolower', $additionalHeaders);
        foreach (array_keys($request->getHeaders()) as $k) {
            $lowk = strtolower((string)$k);
            if ($this->isSignedHeader($lowk)) {
                $headers[] = $lowk;
            } elseif (in_array($lowk, $addHeadersMap, true)) {
                $headers[] = $lowk;
            }
        }
        sort($headers);
        $canonicalHeaders = '';
        foreach ($headers as $k) {
            $headerValues = array_map('trim', $request->getHeader($k));
            $canonicalHeaders .= $k . ':' . implode(',', $headerValues) . "\n";
        }

        // Additional Headers
        $canonicalAdditionalHeaders = implode(';', $additionalHeaders);
        // Assuming unsignedPayload is defined somewhere in the class
        $hashPayload = self::UNSIGNED_PAYLOAD;
        if (!empty($request->getHeaderLine(self::CONTENT_SHA256_HEADER))) {
            $hashPayload = $request->getHeaderLine(self::CONTENT_SHA256_HEADER);
        }

        // Build Canonical Request
        $canonicalRequest =
            $request->getMethod() . "\n" .
            $canonicalUri . "\n" .
            $canonicalQuery . "\n" .
            $canonicalHeaders . "\n" .
            $canonicalAdditionalHeaders . "\n" .
            $hashPayload;

        return $canonicalRequest;
    }
}
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;

// parse args
$optsdesc = [
    "region" => ['help' => 'The region in which the bucket is located.', 'required' => True],
    "bucket" => ['help' => 'The name of the bucket', 'required' => True],
    "key" => ['help' => 'The name of the object', 'required' => True],
];
$longopts = \array_map(function ($key) {
    return "$key:";
}, array_keys($optsdesc));
$options = getopt("", $longopts);
foreach ($optsdesc as $key => $value) {
    if ($value['required'] === True && empty($options[$key])) {
        $help = $value['help'];
        echo "Error: the following arguments are required: --$key, $help";
        exit(1);
    }
}

$region = $options["region"];
$bucket = $options["bucket"];
$key = $options["key"];
$product = 'oss';

// Loading credentials values from the environment variables
$credentialsProvider = new Oss\Credentials\EnvironmentVariableCredentialsProvider();
$cred = $credentialsProvider->getCredentials();
$data = 'hi oss';
$utcTime = new DateTime('now', new DateTimeZone('UTC'));
$date = $utcTime->format('Ymd');
$expiration = clone $utcTime;
$expiration->add(new DateInterval('PT1H'));
$policyMap = [
    "expiration" => $expiration->format('Y-m-d\TH:i:s.000\Z'),
    "conditions" => [
        ["bucket" => $bucket],
        ["x-oss-signature-version" => "OSS4-HMAC-SHA256"],
        ["x-oss-credential" => sprintf("%s/%s/%s/%s/aliyun_v4_request",
            $cred->getAccessKeyId(), $date, $region, $product)],
        ["x-oss-date" => $utcTime->format('Ymd\THis\Z')],
        // other condition
        ["content-length-range", 1, 1024],
        // ["eq", "$success_action_status", "201"],
        // ["starts-with", "$key", "user/eric/"],
        // ["in", "$content-type", ["image/jpg", "image/png"]],
        // ["not-in", "$cache-control", ["no-cache"]],
    ],
];
$jsonOptions = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
$policy = json_encode($policyMap, $jsonOptions);
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("json_encode fail, err: " . json_last_error_msg());
    exit(1);
}
$stringToSign = base64_encode($policy);
$signingKey = "aliyun_v4" . $cred->getAccessKeySecret();
$h1Key = hmacSign($signingKey, $date);
$h2Key = hmacSign($h1Key, $region);
$h3Key = hmacSign($h2Key, $product);
$h4Key = hmacSign($h3Key, "aliyun_v4_request");

// Signature
$signature = hash_hmac('sha256', $stringToSign, $h4Key);

// Post Request
$bodyBuf = '';
$bodyWriter = new \CURLFileUpload();

// object info, key & metadata
$bodyWriter->addField('key', $key);
// meta-data
// $bodyWriter->addField('x-oss-', $value);
// Policy
$bodyWriter->addField('policy', $stringToSign);
// Signature
$bodyWriter->addField('x-oss-signature-version', 'OSS4-HMAC-SHA256');
$bodyWriter->addField('x-oss-credential', sprintf("%s/%s/%s/%s/aliyun_v4_request", $cred->getAccessKeyId(), $date, $region, $product));
$bodyWriter->addField('x-oss-date', $utcTime->format('Ymd\THis\Z'));
$bodyWriter->addField('x-oss-signature', $signature);

// Data
$bodyWriter->addFileFromString('file', $data);
$postData = $bodyWriter->getFormData();
$client = new \GuzzleHttp\Client();
$response = $client->post(sprintf("http://%s.oss-%s.aliyuncs.com/", $bucket, $region),
    [
        'headers' => [
            'content-type' => $bodyWriter->getContentType(),
        ],
        'body' => $postData
    ]
);
if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
    echo "Post Object Fail, status code:" . $response->getStatusCode() . ", reason: " . $response->getReasonPhrase();
    exit(1);
}
echo "post object done, status code:" . $response->getStatusCode() . ", request id:" . $response->getHeaderLine('x-oss-request-id') . PHP_EOL;

function hmacSign($key, $data)
{
    return hash_hmac('sha256', $data, $key, true);
}

class CURLFileUpload
{
    private $fields = [];
    private $files = [];
    private $boundary;

    public function __construct()
    {
        $this->boundary = uniqid();
    }

    public function addField($name, $value)
    {
        $this->fields[$name] = $value;
    }

    public function addFileFromString($name, $content)
    {
        $this->files[$name] = [
            'content' => $content,
            'filename' => $name,
            'type' => 'application/octet-stream'
        ];
    }

    public function getFormData()
    {
        $data = '';
        foreach ($this->fields as $name => $value) {
            $data .= "--{$this->boundary}\r\n";
            $data .= "Content-Disposition: form-data; name=\"$name\"\r\n\r\n";
            $data .= $value . "\r\n";
        }
        foreach ($this->files as $name => $file) {
            $data .= "--{$this->boundary}\r\n";
            $data .= "Content-Disposition: form-data; name=\"$name\"; filename=\"{$file['filename']}\"\r\n";
            $data .= "Content-Type: {$file['type']}\r\n\r\n";
            $data .= $file['content'] . "\r\n";
        }
        $data .= "--{$this->boundary}--\r\n";
        return $data;
    }

    public function getContentType()
    {
        return "multipart/form-data; boundary={$this->boundary}";
    }
}

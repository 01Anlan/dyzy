<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Crypto;

/**
 * Class MasterRsaCipher
 * @package AlibabaCloud\Oss\V2\Crypto
 */
final class MasterRsaCipher implements MasterCipherInterface
{
    /**
     * @var string|null
     */
    private ?string $matDesc;

    /**
     * @var string|null
     */
    private ?string $publicKey;

    /**
     * @var resource|null
     */
    private $publicKeyResource;

    /**
     * @var string|null
     */
    private ?string $privateKey;

    /**
     * @var resource
     */
    private $privateKeyResource;


    public function __construct(
        ?string $publicKey = null,
        ?string $privateKey = null,
        $matDesc = null
    )
    {
        $this->publicKey = $publicKey;
        $this->publicKeyResource = null;
        if ($publicKey != null) {
            $ret = openssl_pkey_get_public($publicKey);
            if ($ret === false) {
                throw new \InvalidArgumentException('RSA key format is not supported');
            }
            $this->publicKeyResource = $ret;
        }

        $this->privateKey = $privateKey;
        if ($privateKey != null) {
            $ret = openssl_pkey_get_private($privateKey);
            if ($ret === false) {
                throw new \InvalidArgumentException('RSA key format is not supported');
            }
            $this->privateKeyResource = $ret;
        }

        $this->matDesc = null;
        if (\is_array($matDesc)) {
            $val = json_encode($matDesc);
            if ($val !== false) {
                $this->matDesc = $val;
            }
        } else if (is_string($matDesc)) {
            $this->matDesc = $matDesc;
        }
    }

    public function encrypt(string $data): string
    {
        if ($this->publicKeyResource == null) {
            throw new \InvalidArgumentException('RSA public key is none or invalid.');
        }

        $encdata = "";

        if (!openssl_public_encrypt($data, $encdata, $this->publicKeyResource)) {
            throw new \RuntimeException('encrypt data error.');
        }

        return $encdata;
    }

    public function decrypt(string $data): string
    {
        if ($this->privateKeyResource == null) {
            throw new \InvalidArgumentException('RSA private key is none or invalid.');
        }

        $decdata = "";
        if (!openssl_private_decrypt($data, $decdata, $this->privateKeyResource)) {
            throw new \RuntimeException('decrypt data error.');
        }

        return $decdata;
    }

    public function getWrapAlgorithm(): string
    {
        return 'RSA/NONE/PKCS1Padding';
    }

    public function getMatDesc(): string
    {
        return $this->matDesc ?? '';
    }
}

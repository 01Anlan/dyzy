<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Crypto;

/**
 * Class AesCtrCipherBuilder
 * @package AlibabaCloud\Oss\V2\Crypto
 */
final class AesCtrCipherBuilder
{

    private MasterCipherInterface $masterCipher;

    public function __construct(MasterCipherInterface $masterCipher)
    {
        $this->masterCipher = $masterCipher;
    }

    public function createCipherData()
    {
        $key = AesCtr::randomKey();
        $iv = AesCtr::randomIv();

        $encIv = $this->masterCipher->encrypt($iv);
        $enckey = $this->masterCipher->encrypt($key);

        return new CipherData(
            $iv,
            $key,
            $encIv,
            $enckey,
            $this->masterCipher->getMatDesc(),
            $this->masterCipher->getWrapAlgorithm(),
            AesCtr::ALGORITHM
        );
    }

    public function fromCipherData(?CipherData $cipherData = null): ContentCipherInterface
    {   
        if ($cipherData == null) {
            $cipherData = $this->createCipherData();
        }
        return new AesCtrCipher($cipherData);
    }

    public function fromEnvelope(Envelope $envelope): ContentCipherInterface
    {
        $plainIv = $this->masterCipher->decrypt($envelope->iv);
        $plainKey = $this->masterCipher->decrypt($envelope->key);

        $cipherData = new CipherData(
            $plainIv,
            $plainKey,
            $envelope->iv,
            $envelope->key,
            $envelope->matDesc,
            $envelope->wrapAlg,
            $envelope->cekAlg
        );

        return $this->fromCipherData($cipherData);
    }
}

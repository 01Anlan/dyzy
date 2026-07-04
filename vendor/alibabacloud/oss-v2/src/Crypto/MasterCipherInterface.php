<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Crypto;

/**
 * Interface MasterCipherInterface
 * @package AlibabaCloud\Oss\V2\Crypto
 */
interface MasterCipherInterface
{
    /**
     * Encrypt the data according to the Provider's specifications.
     *
     * @param string $data the data.
     *
     * @return string
     */
    public function encrypt(string $data): string;

    /**
     * Decrypt the data according to the Provider's specifications.
     *
     * @param string $data the data.
     *
     * @return string
     */
    public function decrypt(string $data): string;

    /**
     * Returns the wrap algorithm name for this Provider.
     *
     * @return string
     */
    public function getWrapAlgorithm(): string;

    /**
     * Returns the decription for this Provider.
     *
     * @return string
     */
    public function getMatDesc(): string;

}

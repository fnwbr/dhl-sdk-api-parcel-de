<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Shipping\Model\ResponseType;

class ManifestDocument
{
    /**
     * @var string[]
     */
    private array $b64 = [];

    private ?string $zpl2 = null;

    private ?string $url = null;

    private ?string $fileFormat = null;

    private ?string $printFormat = null;

    /**
     * @return string[]
     */
    public function getB64(): array
    {
        return $this->b64;
    }

    public function getZpl2(): ?string
    {
        return $this->zpl2;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getFileFormat(): ?string
    {
        return $this->fileFormat;
    }

    public function getPrintFormat(): ?string
    {
        return $this->printFormat;
    }
}

<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Shipping\Model;

class ManifestResponse
{
    private ?ResponseType\Status $status = null;

    private ?string $manifestDate = null;

    private ?ResponseType\ManifestDocument $manifest = null;

    public function getStatus(): ?ResponseType\Status
    {
        return $this->status;
    }

    public function getManifestDate(): ?string
    {
        return $this->manifestDate;
    }

    public function getManifest(): ?ResponseType\ManifestDocument
    {
        return $this->manifest;
    }
}

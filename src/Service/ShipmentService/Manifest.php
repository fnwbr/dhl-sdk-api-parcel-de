<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Shipping\Service\ShipmentService;

use Dhl\Sdk\ParcelDe\Shipping\Api\Data\ManifestInterface;

class Manifest implements ManifestInterface
{
    /**
     * @param string[] $documents
     */
    public function __construct(
        private readonly string $manifestDate,
        private readonly array $documents
    ) {
    }

    public function getManifestDate(): string
    {
        return $this->manifestDate;
    }

    public function getDocuments(): array
    {
        return $this->documents;
    }
}

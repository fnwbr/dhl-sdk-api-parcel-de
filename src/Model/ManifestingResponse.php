<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Shipping\Model;

class ManifestingResponse
{
    private ?ResponseType\Status $status = null;

    /**
     * @var \Dhl\Sdk\ParcelDe\Shipping\Model\ResponseType\ManifestItem[]
     */
    private array $items = [];

    public function getStatus(): ?ResponseType\Status
    {
        return $this->status;
    }

    /**
     * @return \Dhl\Sdk\ParcelDe\Shipping\Model\ResponseType\ManifestItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}

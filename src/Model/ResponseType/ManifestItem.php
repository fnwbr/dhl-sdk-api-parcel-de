<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Shipping\Model\ResponseType;

class ManifestItem
{
    private ?string $shipmentNo = null;

    private Status $sstatus;

    public function getShipmentNo(): ?string
    {
        return $this->shipmentNo;
    }

    public function getStatus(): Status
    {
        return $this->sstatus;
    }
}

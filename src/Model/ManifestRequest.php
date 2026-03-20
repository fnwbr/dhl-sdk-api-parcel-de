<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Shipping\Model;

class ManifestRequest implements \JsonSerializable
{
    /**
     * @param string[] $shipmentNumbers
     */
    public function __construct(
        private readonly string $profile,
        private readonly array $shipmentNumbers = [],
        private readonly ?string $billingNumber = null
    ) {
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed[] Serializable object properties
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}

<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Shipping\Model\ResponseMapper;

use Dhl\Sdk\ParcelDe\Shipping\Model\ManifestingResponse;
use Dhl\Sdk\ParcelDe\Shipping\Model\ResponseType\ManifestItem;

class CreateManifestResponseMapper
{
    /**
     * Map the webservice data structure to response objects suitable for third-party consumption.
     *
     * @return string[]
     */
    public function map(ManifestingResponse $response): array
    {
        return array_map(
            fn(ManifestItem $responseItem): ?string => $responseItem->getShipmentNo(),
            array_filter(
                $response->getItems(),
                fn(ManifestItem $responseItem): bool => $responseItem->getStatus()->getStatusCode() === 200
            )
        );
    }
}

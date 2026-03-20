<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Shipping\Model\ResponseMapper;

use Dhl\Sdk\ParcelDe\Shipping\Api\Data\ManifestInterface;
use Dhl\Sdk\ParcelDe\Shipping\Model\ManifestResponse;
use Dhl\Sdk\ParcelDe\Shipping\Service\ShipmentService\Manifest;

class GetManifestResponseMapper
{
    /**
     * Map the webservice data structure to response objects suitable for third-party consumption.
     */
    public function map(ManifestResponse $response): ManifestInterface
    {
        $documents = [];

        if ($response->getManifest() !== null) {
            $documents = $response->getManifest()->getB64();
        }

        return new Manifest(
            $response->getManifestDate() ?? '',
            $documents
        );
    }
}

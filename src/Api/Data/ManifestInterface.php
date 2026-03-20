<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Shipping\Api\Data;

/**
 * @api
 */
interface ManifestInterface
{
    public function getManifestDate(): string;

    /**
     * @return string[]
     */
    public function getDocuments(): array;
}

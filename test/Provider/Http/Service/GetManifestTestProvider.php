<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Shipping\Test\Provider\Http\Service;

use Dhl\Sdk\ParcelDe\Shipping\Test\Provider\Http\Credentials\AuthenticationStorageProvider;

class GetManifestTestProvider
{
    /**
     * Provide request and response for the test case
     * - manifest document successfully retrieved.
     *
     * @return mixed[]
     */
    public static function getManifestSuccess(): array
    {
        $response = __DIR__ . '/../../_files/getmanifest/getManifestSuccess.json';

        $authStorage = AuthenticationStorageProvider::authSuccess();
        $responseBody = \file_get_contents($response);

        return [
            'get manifest success' => [$authStorage, $responseBody],
        ];
    }
}

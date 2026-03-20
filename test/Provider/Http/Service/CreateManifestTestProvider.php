<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Shipping\Test\Provider\Http\Service;

use Dhl\Sdk\ParcelDe\Shipping\Test\Provider\Http\Credentials\AuthenticationStorageProvider;

class CreateManifestTestProvider
{
    /**
     * Provide request and response for the test case
     * - shipment number(s) sent to the API, all shipment(s) successfully manifested.
     *
     * @return mixed[]
     */
    public static function createManifestsSuccess(): array
    {
        $singleResponse = __DIR__ . '/../../_files/createmanifest/singleShipmentSuccess.json';
        $multiResponse = __DIR__ . '/../../_files/createmanifest/multiShipmentSuccess.json';

        $authStorage = AuthenticationStorageProvider::authSuccess();

        $singleShipmentRequest = ['0034043333301010000010001'];
        $singleShipmentResponse = \file_get_contents($singleResponse);

        $multiShipmentRequest = ['0034043333301010000010002', '0034043333301010000010003'];
        $multiShipmentResponse = \file_get_contents($multiResponse);

        return [
            'single shipment success' => [$authStorage, $singleShipmentRequest, $singleShipmentResponse],
            'multi shipment success' => [$authStorage, $multiShipmentRequest, $multiShipmentResponse],
        ];
    }

    /**
     * Provide request and response for the test case
     * - shipment number(s) sent to the API, some shipment(s) successfully manifested.
     *
     * @return mixed[]
     */
    public static function createManifestsPartialSuccess(): array
    {
        $response = __DIR__ . '/../../_files/createmanifest/multiShipmentPartialSuccess.json';

        $authStorage = AuthenticationStorageProvider::authSuccess();

        $shipmentRequest = ['0034043333301010000010102', '0034043333301010000010004'];
        $shipmentResponse = \file_get_contents($response);

        return [
            'multi shipment partial success' => [$authStorage, $shipmentRequest, $shipmentResponse],
        ];
    }

    /**
     * Provide request and response for the test case
     * - shipment number(s) sent to the API, no shipment(s) successfully manifested.
     *
     * @return mixed[]
     */
    public static function createManifestsError(): array
    {
        $singleResponse = __DIR__ . '/../../_files/createmanifest/singleShipmentError.json';
        $multiResponse = __DIR__ . '/../../_files/createmanifest/multiShipmentError.json';

        $authStorage = AuthenticationStorageProvider::authSuccess();

        $singleShipmentRequest = ['0034043333301010000010101'];
        $singleShipmentResponse = \file_get_contents($singleResponse);

        $multiShipmentRequest = ['0034043333301010000010103', '0034043333301010000010104'];
        $multiShipmentResponse = \file_get_contents($multiResponse);

        return [
            'single shipment error' => [$authStorage, $singleShipmentRequest, $singleShipmentResponse],
            'multi shipment error' => [$authStorage, $multiShipmentRequest, $multiShipmentResponse],
        ];
    }
}

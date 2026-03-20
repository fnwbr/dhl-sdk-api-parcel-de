<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Shipping\Test\TestCase\Service;

use Dhl\Sdk\ParcelDe\Shipping\Api\Data\AuthenticationStorageInterface;
use Dhl\Sdk\ParcelDe\Shipping\Exception\ServiceException;
use Dhl\Sdk\ParcelDe\Shipping\Http\HttpServiceFactory;
use Dhl\Sdk\ParcelDe\Shipping\Test\Provider\Http\Service\GetManifestTestProvider;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

class GetManifestTest extends TestCase
{
    /**
     * @return mixed[]
     */
    public static function successDataProvider(): array
    {
        return GetManifestTestProvider::getManifestSuccess();
    }

    /**
     * Assert successful manifest retrieval.
     *
     * @throws ServiceException
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('successDataProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function getManifestSuccess(AuthenticationStorageInterface $authStorage, string $responseBody): void
    {
        $httpClient = new Client();
        $logger = new TestLogger();

        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $manifestResponse = $responseFactory
            ->createResponse(200, 'OK')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($streamFactory->createStream($responseBody));

        $httpClient->setDefaultResponse($manifestResponse);

        $serviceFactory = new HttpServiceFactory($httpClient, Client::class);
        $service = $serviceFactory->createShipmentService($authStorage, $logger, true);

        $result = $service->getManifests(null, '2024-01-15');

        $responseData = \json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);

        // assert that the manifest date matches
        self::assertSame($responseData['manifestDate'], $result->getManifestDate());

        // assert that documents are properly mapped
        self::assertSame($responseData['manifest']['b64'], $result->getDocuments());
    }
}

<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Shipping\Test\TestCase\Service;

use Dhl\Sdk\ParcelDe\Shipping\Api\Data\AuthenticationStorageInterface;
use Dhl\Sdk\ParcelDe\Shipping\Exception\AuthenticationException;
use Dhl\Sdk\ParcelDe\Shipping\Exception\DetailedServiceException;
use Dhl\Sdk\ParcelDe\Shipping\Exception\ServiceException;
use Dhl\Sdk\ParcelDe\Shipping\Http\HttpServiceFactory;
use Dhl\Sdk\ParcelDe\Shipping\Test\Expectation\CommunicationExpectation;
use Dhl\Sdk\ParcelDe\Shipping\Test\Provider\Http\Service\CreateManifestTestProvider;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

class CreateManifestTest extends TestCase
{
    /**
     * @return mixed[]
     */
    public static function successDataProvider(): array
    {
        return CreateManifestTestProvider::createManifestsSuccess();
    }

    /**
     * @return mixed[]
     */
    public static function partialSuccessDataProvider(): array
    {
        return CreateManifestTestProvider::createManifestsPartialSuccess();
    }

    /**
     * @return mixed[]
     */
    public static function errorDataProvider(): array
    {
        return CreateManifestTestProvider::createManifestsError();
    }

    /**
     * Test manifest creation success case (all shipments manifested, no issues).
     *
     * @param string[] $shipmentNumbers
     *
     * @throws AuthenticationException
     * @throws ServiceException
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('successDataProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function createManifestsSuccess(
        AuthenticationStorageInterface $authStorage,
        array $shipmentNumbers,
        string $responseBody
    ): void {
        $statusCode = count($shipmentNumbers) > 1 ? 207 : 200;
        $reasonPhrase = count($shipmentNumbers) > 1 ? 'Multi-status' : 'OK';

        $httpClient = new Client();
        $logger = new TestLogger();

        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $manifestResponse = $responseFactory
            ->createResponse($statusCode, $reasonPhrase)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($streamFactory->createStream($responseBody));

        $httpClient->setDefaultResponse($manifestResponse);

        $serviceFactory = new HttpServiceFactory($httpClient, Client::class);
        $service = $serviceFactory->createShipmentService($authStorage, $logger, true);

        $result = $service->createManifests(
            'STANDARD_GRUPPENPROFIL',
            $shipmentNumbers
        );

        $responseData = \json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);

        // assert all successfully manifested shipment numbers are in the result
        $expectedShipmentNumbers = array_map(
            fn(array $item) => $item['shipmentNo'],
            array_filter(
                $responseData['items'],
                fn(array $item) => $item['sstatus']['statusCode'] === 200
            )
        );

        self::assertSame(array_values($expectedShipmentNumbers), array_values($result));

        // assert successful communication gets logged.
        CommunicationExpectation::assertCommunicationLogged(
            (string) $httpClient->getLastRequest()->getBody(),
            $responseBody,
            $logger
        );
    }

    /**
     * Test manifest creation partial success case (some shipments manifested).
     *
     * @param string[] $shipmentNumbers
     *
     * @throws AuthenticationException
     * @throws ServiceException
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('partialSuccessDataProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function createManifestsPartialSuccess(
        AuthenticationStorageInterface $authStorage,
        array $shipmentNumbers,
        string $responseBody
    ): void {
        $statusCode = 207;
        $reasonPhrase = 'Multi-status';

        $httpClient = new Client();
        $logger = new TestLogger();

        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $manifestResponse = $responseFactory
            ->createResponse($statusCode, $reasonPhrase)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($streamFactory->createStream($responseBody));

        $httpClient->setDefaultResponse($manifestResponse);

        $serviceFactory = new HttpServiceFactory($httpClient, Client::class);
        $service = $serviceFactory->createShipmentService($authStorage, $logger, true);

        $result = $service->createManifests(
            'STANDARD_GRUPPENPROFIL',
            $shipmentNumbers
        );

        $responseData = \json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);

        // assert that some shipments were manifested but not all
        self::assertNotEmpty($result);
        self::assertLessThan(count($shipmentNumbers), count($result));

        // assert only successfully manifested shipments are in result
        $expectedShipmentNumbers = array_map(
            fn(array $item) => $item['shipmentNo'],
            array_filter(
                $responseData['items'],
                fn(array $item) => $item['sstatus']['statusCode'] === 200
            )
        );

        self::assertSame(array_values($expectedShipmentNumbers), array_values($result));

        CommunicationExpectation::assertCommunicationLogged(
            (string) $httpClient->getLastRequest()->getBody(),
            $responseBody,
            $logger
        );
    }

    /**
     * Test manifest creation failure case.
     *
     * @param string[] $shipmentNumbers
     *
     * @throws AuthenticationException
     * @throws ServiceException
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('errorDataProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function createManifestsError(
        AuthenticationStorageInterface $authStorage,
        array $shipmentNumbers,
        string $responseBody
    ): void {
        $statusCode = count($shipmentNumbers) > 1 ? 207 : 400;
        $reasonPhrase = count($shipmentNumbers) > 1 ? 'Multi-status' : 'Bad Request';

        $this->expectException(DetailedServiceException::class);
        $this->expectExceptionCode($statusCode);

        $httpClient = new Client();
        $logger = new TestLogger();

        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $manifestResponse = $responseFactory
            ->createResponse($statusCode, $reasonPhrase)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($streamFactory->createStream($responseBody));

        $httpClient->setDefaultResponse($manifestResponse);

        $serviceFactory = new HttpServiceFactory($httpClient, Client::class);
        $service = $serviceFactory->createShipmentService($authStorage, $logger, true);

        try {
            $service->createManifests(
                'STANDARD_GRUPPENPROFIL',
                $shipmentNumbers
            );
        } catch (DetailedServiceException $exception) {
            CommunicationExpectation::assertErrorsLogged(
                (string) $statusCode,
                $responseBody,
                $logger
            );

            throw $exception;
        }
    }
}

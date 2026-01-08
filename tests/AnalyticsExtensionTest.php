<?php

declare(strict_types=1);

namespace Heyday\Analytics\Tests;

use Heyday\Analytics\AnalyticsExtension;
use Heyday\Analytics\AnalyticsProviderInterface;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;

/**
 * Class AnalyticsExtensionTest
 * @package Heyday\Analytics\Tests
 */
class AnalyticsExtensionTest extends SapphireTest
{
    /**
     * Store original service to restore after tests
     */
    private $originalService = null;

    /**
     * Clean up after each test
     */
    protected function tearDown(): void
    {
        // Restore original service if it existed
        if ($this->originalService !== null) {
            Injector::inst()->registerService($this->originalService, 'AnalyticsService');
        } else {
            // Try to unregister if we registered a test service
            try {
                Injector::inst()->unregisterNamedObject('AnalyticsService');
            } catch (\Exception $e) {
                // Ignore if service doesn't exist
            }
        }

        parent::tearDown();
    }

    /**
     * Test that getAnalyticsCode returns code from AnalyticsService
     */
    public function testGetAnalyticsCodeReturnsCodeFromService()
    {
        $expectedCode = '<script>console.log("test analytics code");</script>';

        // Create a mock provider
        $mockProvider = $this->createMock(AnalyticsProviderInterface::class);
        $mockProvider->expects($this->once())
            ->method('getAnalyticsCode')
            ->willReturn($expectedCode);

        // Register the mock provider as AnalyticsService
        Injector::inst()->registerService($mockProvider, 'AnalyticsService');

        // Create extension instance
        $extension = new AnalyticsExtension();
        $result = $extension->getAnalyticsCode();

        $this->assertEquals($expectedCode, $result);
    }

    /**
     * Test that getAnalyticsCode returns empty string when service throws exception
     */
    public function testGetAnalyticsCodeReturnsEmptyStringOnException()
    {
        // Register a service that throws an exception
        $mockProvider = $this->createMock(AnalyticsProviderInterface::class);
        $mockProvider->expects($this->once())
            ->method('getAnalyticsCode')
            ->willThrowException(new \Exception('Test exception'));

        Injector::inst()->registerService($mockProvider, 'AnalyticsService');

        $extension = new AnalyticsExtension();
        $result = $extension->getAnalyticsCode();

        $this->assertEquals('', $result);
    }

    /**
     * Test that getAnalyticsCode returns empty string when service is not registered
     */
    public function testGetAnalyticsCodeReturnsEmptyStringWhenServiceNotRegistered()
    {
        // Unregister the service if it exists
        try {
            Injector::inst()->unregisterNamedObject('AnalyticsService');
        } catch (\Exception $e) {
            // Service may not be registered, which is fine
        }

        $extension = new AnalyticsExtension();
        $result = $extension->getAnalyticsCode();

        $this->assertEquals('', $result);
    }

    /**
     * Test that getTagManagerNoScript returns code from AnalyticsService
     */
    public function testGetTagManagerNoScriptReturnsCodeFromService()
    {
        $expectedCode = '<noscript><iframe src="test"></iframe></noscript>';

        // Create a service class that implements both methods
        $service = new class($expectedCode) implements AnalyticsProviderInterface {
            private $code;

            public function __construct($code)
            {
                $this->code = $code;
            }

            public function getAnalyticsCode(): string
            {
                return '';
            }

            public function getTagManagerNoScript(): string
            {
                return $this->code;
            }
        };

        Injector::inst()->registerService($service, 'AnalyticsService');

        $extension = new AnalyticsExtension();
        $result = $extension->getTagManagerNoScript();

        $this->assertEquals($expectedCode, $result);
    }

    /**
     * Test that getTagManagerNoScript returns empty string when service throws exception
     */
    public function testGetTagManagerNoScriptReturnsEmptyStringOnException()
    {
        // Create a service that throws exception when getTagManagerNoScript is called
        $service = new class extends \stdClass {
            public function getTagManagerNoScript()
            {
                throw new \Exception('Test exception');
            }
        };

        Injector::inst()->registerService($service, 'AnalyticsService');

        $extension = new AnalyticsExtension();
        $result = $extension->getTagManagerNoScript();

        $this->assertEquals('', $result);
    }

    /**
     * Test that getTagManagerNoScript returns empty string when service is not registered
     */
    public function testGetTagManagerNoScriptReturnsEmptyStringWhenServiceNotRegistered()
    {
        // Unregister the service if it exists
        try {
            Injector::inst()->unregisterNamedObject('AnalyticsService');
        } catch (\Exception $e) {
            // Service may not be registered, which is fine
        }

        $extension = new AnalyticsExtension();
        $result = $extension->getTagManagerNoScript();

        $this->assertEquals('', $result);
    }
}

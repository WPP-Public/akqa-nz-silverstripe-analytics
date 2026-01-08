<?php

declare(strict_types=1);

namespace Heyday\Analytics\Tests;

use Heyday\Analytics\GoogleAnalyticsProvider;
use SilverStripe\Control\Controller;
use SilverStripe\Dev\SapphireTest;

/**
 * Class GoogleAnalyticsProviderTest
 * @package Heyday\Analytics\Tests
 */
class GoogleAnalyticsProviderTest extends SapphireTest
{
    /**
     * Test that getAnalyticsCode returns empty string when no ID is provided
     */
    public function testGetAnalyticsCodeReturnsEmptyStringWhenNoId()
    {
        $provider = new GoogleAnalyticsProvider(null);
        $result = $provider->getAnalyticsCode();

        $this->assertEquals('', $result);
    }

    /**
     * Test that getAnalyticsCode returns empty string when empty ID is provided
     */
    public function testGetAnalyticsCodeReturnsEmptyStringWhenEmptyId()
    {
        $provider = new GoogleAnalyticsProvider('');
        $result = $provider->getAnalyticsCode();

        $this->assertEquals('', $result);
    }

    /**
     * Test that getAnalyticsCode returns correct analytics code with valid ID
     */
    public function testGetAnalyticsCodeReturnsCorrectCodeWithValidId()
    {
        $testId = 'UA-12345678-9';
        $provider = new GoogleAnalyticsProvider($testId);
        $result = $provider->getAnalyticsCode();

        $this->assertNotEmpty($result);
        $this->assertStringContainsString($testId, $result);
        $this->assertStringContainsString('GoogleAnalyticsObject', $result);
        $this->assertStringContainsString('ga(\'create\'', $result);
        $this->assertStringContainsString('ga(\'send\', \'pageview\')', $result);
        $this->assertStringContainsString('www.google-analytics.com/analytics.js', $result);
    }


    /**
     * Test that getAnalyticsID returns the correct ID
     */
    public function testGetAnalyticsIDReturnsCorrectId()
    {
        $testId = 'UA-12345678-9';
        $provider = new GoogleAnalyticsProvider($testId);

        $this->assertEquals($testId, $provider->getAnalyticsID());
    }
}

<?php

declare(strict_types=1);

namespace Heyday\Analytics;

use SilverStripe\Core\Extension;
use SilverStripe\Core\Injector\Injector;

/**
 * Class AnalyticsExtension
 *
 * @package Heyday\Analytics
 * @license MIT License https://github.com/heyday/silverstripe-analytics/LICENSE
 */
class AnalyticsExtension extends Extension
{
    /**
     * @var array
     */
    private static $casting = [
        'AnalyticsCode' => 'HTMLFragment',
        'TagManagerNoScript' => 'HTMLFragment'
    ];

    /**
     * @return string
     */
    public function getAnalyticsCode()
    {
        try {
            if ($analyticsService = Injector::inst()->get('AnalyticsService')) {
                return $analyticsService->getAnalyticsCode();
            }
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getTagManagerNoScript()
    {
        try {
            if ($analyticsService = Injector::inst()->get('AnalyticsService')) {
                return $analyticsService->getTagManagerNoScript();
            }
        } catch (\Exception $e) {
            return '';
        }

    }
}

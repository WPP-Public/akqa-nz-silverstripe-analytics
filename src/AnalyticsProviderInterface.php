<?php

declare(strict_types=1);

namespace Heyday\Analytics;

/**
 * Interface AnalyticsProviderInterface
 * @package Heyday\Analytics
 *
 * @license MIT License https://github.com/heyday/silverstripe-analytics/LICENSE
 **/
interface AnalyticsProviderInterface
{

    /**
     * @return string
     */
    public function getAnalyticsCode(): string;


}

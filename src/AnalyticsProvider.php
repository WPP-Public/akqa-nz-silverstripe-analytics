<?php

declare(strict_types=1);

namespace Heyday\Analytics;

use SilverStripe\Control\Director;

/**
 * Class AnalyticsProvider
 * @package Heyday\Analytics
 *
 * @license MIT License https://github.com/heyday/silverstripe-analytics/LICENSE
 *
 */
class AnalyticsProvider implements AnalyticsProviderInterface
{

    private ?string $id = null;

    /**
     * AnalyticsProvider constructor.
     *
     * @param ?string $id
     */
    public function __construct(?string $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAnalyticsID()
    {
        if (is_null($this->id) || empty($this->id)) {
            if (!Director::isDev()) {
                return trigger_error("Fatal error: You are calling google analytics snippet without any ID. Please add Google Analytics ID in mysite/_config/config.yml", E_USER_ERROR);
            }
        }

        return $this->id;
    }

    public function getAnalyticsCode(): string
    {
        return '';
    }

}

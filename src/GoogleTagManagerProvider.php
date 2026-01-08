<?php

declare(strict_types=1);

namespace Heyday\Analytics;

use SilverStripe\Control\Controller;
use SilverStripe\Core\Environment;

/**
 * Class GoogleTagManagerProvider
 * @package Heyday\Analytics
 *
 * @license MIT License https://github.com/heyday/silverstripe-analytics/LICENSE
 **/
class GoogleTagManagerProvider extends AnalyticsProvider
{
    /**
     * @var array $data Contains any manually set data layer key value pairs
     */
    private static $dataLayers = [];

    /**
     * @return string
     */
    public function getAnalyticsCode(): string
    {
        if (self::isTagManagerDisabled())  {
            return '';
        }

        $id = $this->getAnalyticsID();

        if (!$id) {
            return '';
        }

        $scriptTag = 'script';

        // support nonce on scripts
        $controller = Controller::curr();

        if ($controller && $controller->hasMethod('getNonce')) {
            $nonce = $controller->getNonce();
            $scriptTag = "script nonce=\"$nonce\"";
        }

        $dataLayer = self::getDataLayer();

        $analyticsCode = <<< EOS
            $dataLayer
            <!-- Google Tag Manager -->
            <$scriptTag>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer', '$id');</script>
            <!-- End Google Tag Manager -->
EOS;

        return $analyticsCode;
    }

    /**
     * @return string
     */
    public function getTagManagerNoScript(): string
    {
        if (self::isTagManagerDisabled())  {
            return '';
        }

        $id = $this->getAnalyticsID();

        if (!$id) {
            return '';
        }

        $noScriptCode = <<< EOS
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=$id"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
EOS;

        return $noScriptCode;
    }

    /**
     * Assign a data layer key value pair. This is be the same as pushing to
     * the data layer.
     *
     * @param string $key The data layer key / name
     * @param string $value The data layer value
     *
     * @return void
     */
    public static function insertDataLayer($key, $value)
    {
        self::$dataLayers[$key] = $value;
    }

    /**
     * Create the data layer code. All things like manually set data layer values
     * are processed and built as one data layer. Creating this way
     * stops the need of having to set a JavaScript data layer variable initially
     * within the page code and stops issues with undefined data layer when pushing.
     *
     * @return string
     */
    public static function getDataLayer()
    {
        if (self::isTagManagerDisabled())  {
            return '';
        }

        // support nonce on scripts
        $controller = Controller::curr();
        $scriptTag  = 'script';

        if ($controller && $controller->hasMethod('getNonce')) {
            $nonce = $controller->getNonce();
            $scriptTag = "script nonce=\"$nonce\"";
        }


        $javascript = "<$scriptTag>dataLayer = [{";

        // combine all the data layer values into a single data layer
        $javascript .= implode(
            ',',
            array_filter([
                self::buildDataLayer()
            ])
        );

        $javascript .= '}];</script>';

        return $javascript;
    }

    /**
     * Creates a JSON array formatted string containing our created data layer
     * key value pairs.
     *
     * @return string
     */
    private static function buildDataLayer()
    {
        $data = [];
        foreach (self::$dataLayers as $key => $value) {
            $str = "'" . $key . "' : '" . $value . "'";
            $data[] = $str;
        }

        return implode(',', $data);
    }



    public static function isTagManagerDisabled()
    {
        if (Environment::getEnv('GOOGLE_TAG_MANAGER_DISABLED')) {
            return true;
        }

        if (Controller::curr() && Controller::curr()->getRequest()->getVar('disable_gtm')) {
            return true;
        }

        return;
    }
}

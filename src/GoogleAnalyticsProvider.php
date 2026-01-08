<?php

namespace Heyday\Analytics;

use SilverStripe\Control\Controller;

/**
 * Class GoogleAnalyticsProvider
 * @package Heyday\Analytics
 *
 * @license MIT License https://github.com/heyday/silverstripe-analytics/LICENSE
 **/
class GoogleAnalyticsProvider extends AnalyticsProvider
{

    /**
     * @return string
     */
    public function getAnalyticsCode(): string
    {
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


        $analyticsCode = <<<EOS
            <$scriptTag>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
                ga('create', '$id', 'auto');
                ga('send', 'pageview');
            </script>'
EOS;

        return $analyticsCode;
    }

}

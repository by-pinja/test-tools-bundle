<?php

namespace Protacon\Bundle\TestToolsBundle\Util;

use Composer\Script\PackageEvent;

class Package
{
    public static function postPackageInstall(PackageEvent $event)
    {
        die('jeee');
    }

    public static function postUpdateInstall(PackageEvent $event)
    {
        die('joo');
    }
}

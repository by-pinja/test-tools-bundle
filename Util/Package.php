<?php

namespace Protacon\Bundle\TestToolsBundle\Util;

use Composer\Installer\PackageEvent;

class Package
{
    public static function postUpdate(PackageEvent $event)
    {
        die('jeee');
    }
}

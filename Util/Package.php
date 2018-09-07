<?php

namespace Protacon\Bundle\TestToolsBundle\Util;

use Composer\Script\PackageEvent;
use Composer\Script\Event;

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

    public static function postInstall(Event $event)
    {
        die('foo');
    }

    public static function postUpdate(Event $event)
    {
        die('bar');
    }
}

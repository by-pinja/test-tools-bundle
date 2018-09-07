<?php

namespace Protacon\Bundle\TestToolsBundle\Util;

use Composer\Script\PackageEvent;
use Composer\Script\Event;

class Package
{
    public static function postPackageInstall(PackageEvent $event)
    {
        file_put_contents('/tmp/temp.txt', 'jee1', FILE_APPEND);
        die('jeee');
    }

    public static function postUpdateInstall(PackageEvent $event)
    {
        file_put_contents('/tmp/temp.txt', 'jee2', FILE_APPEND);
        die('joo');
    }

    public static function postInstall(Event $event)
    {
        file_put_contents('/tmp/temp.txt', 'jee3', FILE_APPEND);
        die('foo');
    }

    public static function postUpdate(Event $event)
    {
        file_put_contents('/tmp/temp.txt', 'jee4', FILE_APPEND);
        die('bar');
    }
}

<?php

namespace Enhavo\Bundle\DownloadBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class enhavoDownloadBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
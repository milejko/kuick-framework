<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Composer\Script\Event;

/**
 *
 */
class ComposerInstaller
{
    private const KUICK_PATH = BASE_PATH . '/vendor/kuick/framework';
    private const INDEX_FILE = '/public/index.php';
    private const CONSOLE_FILE = '/bin/console.php';
    private const CONFIG_FILE = '/etc/config.php';
    private const ROUTES_FILE = '/etc/routes.php';

    protected static array $sysDirs = ['etc', 'public', 'bin', 'var'];

    protected static function initAutoload(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        define('BASE_PATH', realpath($vendorDir . '/../'));
        require $vendorDir . '/autoload.php';
    }

    public static function postUpdate(Event $event)
    {
        self::postInstall($event);
    }

    public static function postInstall(Event $event)
    {
        self::initAutoload($event);
        self::createSysDirs();
        self::copyDistributionFiles();
    }

    protected static function createSysDirs()
    {
        foreach (self::$sysDirs as $dir) {
            !file_exists(BASE_PATH . '/' . $dir) ? mkdir(BASE_PATH . '/' . $dir, 0777, true) : null;
            chmod($dir, 0777);
        }
    }

    protected static function copyDistributionFiles()
    {
        if (!file_exists(!self::KUICK_PATH)) {
            return;
        }
        copy(self::KUICK_PATH . self::INDEX_FILE, BASE_PATH . self::INDEX_FILE);
        copy(self::KUICK_PATH . self::CONSOLE_FILE, BASE_PATH . self::CONSOLE_FILE);
        chmod(BASE_PATH . self::CONSOLE_FILE, 0755);
        if (!file_exists(BASE_PATH . self::CONFIG_FILE)) {
            copy(self::KUICK_PATH . self::CONFIG_FILE, BASE_PATH . self::CONFIG_FILE);
        }
        if (!file_exists(BASE_PATH . self::ROUTES_FILE)) {
            copy(self::KUICK_PATH . self::ROUTES_FILE, BASE_PATH . self::ROUTES_FILE);
        }
    }
}

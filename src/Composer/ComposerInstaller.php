<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

/**
 *
 */
class ComposerInstaller
{
    private const KUICK_PATH =  '/vendor/kuick/framework';
    private const INDEX_FILE = '/public/index.php';
    private const CONSOLE_FILE = '/bin/console';
    private const SOURCE_ETC_DIR = '/etc/example';
    private const TARGET_ETC_DIR = '/etc';
    private const TMP_DIR = '/var/tmp';
    private const SYS_DIRS = ['bin', 'etc', 'etc/di', 'etc/routes', 'public'];

    private static bool $freshInstallation = true;

    /** @disregard P1009 Undefined type */
    protected static function initAutoload(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        define('BASE_PATH', realpath(dirname($vendorDir)));
        if (file_exists(BASE_PATH . self::INDEX_FILE) && file_exists(BASE_PATH . self::CONSOLE_FILE)) {
            self::$freshInstallation = false;
        }
        require $vendorDir . '/autoload.php';
    }

    /** @disregard P1009 Undefined type */
    public static function postUpdate(Event $event)
    {
        self::postInstall($event);
    }

    /** @disregard P1009 Undefined type */
    public static function postInstall(Event $event)
    {
        self::initAutoload($event);
        if (!self::$freshInstallation) {
            return;
        }
        self::createSysDirs();
        self::copyDistributionFiles();
    }

    protected static function createSysDirs()
    {
        !file_exists(BASE_PATH . self::TMP_DIR) ?
            mkdir(BASE_PATH . self::TMP_DIR, 0777, true) :
            chmod(BASE_PATH . self::TMP_DIR, 0777);
        foreach (self::SYS_DIRS as $dir) {
            !file_exists(BASE_PATH . DIRECTORY_SEPARATOR . $dir) ?
                mkdir(BASE_PATH . DIRECTORY_SEPARATOR . $dir, 0755, true) :
                chmod($dir, 0755);
        }
    }

    protected static function copyDistributionFiles()
    {
        if (!file_exists(BASE_PATH . self::KUICK_PATH)) {
            return;
        }
        copy(BASE_PATH . self::KUICK_PATH . self::INDEX_FILE, BASE_PATH . self::INDEX_FILE);
        copy(BASE_PATH . self::KUICK_PATH . self::CONSOLE_FILE, BASE_PATH . self::CONSOLE_FILE);
        chmod(BASE_PATH . self::CONSOLE_FILE, 0755);
        $fs = new Filesystem();
        $fs->mirror(BASE_PATH . self::KUICK_PATH . self::SOURCE_ETC_DIR, BASE_PATH . self::TARGET_ETC_DIR);
    }
}

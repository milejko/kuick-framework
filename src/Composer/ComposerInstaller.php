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

/**
 *
 */
class ComposerInstaller
{
    private const KUICK_PATH = BASE_PATH . '/vendor/kuick/framework';
    private const INDEX_FILE = '/public/index.php';
    private const CONSOLE_FILE = '/bin/console';
    private const ETC_FILE_LOCATIONS = [
        '/etc/*.config.php',
        '/etc/ui/*.routes.php',
        '/etc/ui/*.commands.php',
        '/etc/di/*.di.php',
    ];
    private const TMP_DIR = BASE_PATH . '/var/tmp';
    private const SYS_DIRS = ['etc', 'etc/di', 'etc/routes', 'public', 'bin'];

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
        !file_exists(self::TMP_DIR) ?
            mkdir(self::TMP_DIR, 0777, true) :
            chmod(self::TMP_DIR, 0777);
        foreach (self::SYS_DIRS as $dir) {
            !file_exists(BASE_PATH . DIRECTORY_SEPARATOR . $dir) ?
                mkdir(BASE_PATH . DIRECTORY_SEPARATOR . $dir, 0755, true) :
                chmod($dir, 0755);
        }
    }

    protected static function copyDistributionFiles()
    {
        if (!file_exists(self::KUICK_PATH)) {
            return;
        }
        copy(self::KUICK_PATH . self::INDEX_FILE, BASE_PATH . self::INDEX_FILE);
        copy(self::KUICK_PATH . self::CONSOLE_FILE, BASE_PATH . self::CONSOLE_FILE);
        chmod(BASE_PATH . self::CONSOLE_FILE, 0755);
        foreach (self::ETC_FILE_LOCATIONS as $etcFileLocation) {
            foreach (glob(BASE_PATH . $etcFileLocation) as $etcFilePath) {
                echo $etcFilePath;
            }
        }
    }
}

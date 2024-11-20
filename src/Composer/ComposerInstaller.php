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
    private const KUICK_PATH =  '/vendor/kuick/framework';
    private const INDEX_FILE = '/public/index.php';
    private const CONSOLE_FILE = '/bin/console';
    private const ETC_FILE_LOCATIONS = [
        '/etc/*.config.php',
        '/etc/routes/*.actions.php',
        '/etc/routes/*.commands.php',
        '/etc/di/sample.di.php',
    ];
    private const TMP_DIR = '/var/tmp';
    private const SYS_DIRS = ['etc', 'etc/di', 'etc/routes', 'public', 'bin'];

    protected static function initAutoload(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        define('BASE_PATH', realpath(dirname($vendorDir)));
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
        foreach (self::ETC_FILE_LOCATIONS as $etcFileLocation) {
            foreach (glob(BASE_PATH . self::KUICK_PATH . $etcFileLocation) as $etcFilePath) {
                $localEtcFileName = str_replace(BASE_PATH . self::KUICK_PATH, BASE_PATH, $etcFilePath);
                //if something is in a specific dir - do not do anything
                if (file_exists(dirname($localEtcFileName))) {
                    continue;
                }
                copy($etcFilePath, $localEtcFileName);
            }
        }
    }
}

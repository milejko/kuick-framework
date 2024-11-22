<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use DI\Attribute\Inject;

/**
 * Locale configurator
 */
final class ApplicationLocale
{
    private const DEFAULT_LOCALE = 'en_US.utf-8';
    private const DEFAULT_TIMEZONE = 'Europe/Warsaw';
    private const DEFAULT_CHARSET = 'UTF-8';

    private function __construct(
        #[Inject('kuick.app.locale')] private string $locale = self::DEFAULT_LOCALE,
        #[Inject('kuick.app.timezone')] private string $timezone = self::DEFAULT_TIMEZONE,
        #[Inject('kuick.app.charset')] private string $charset,
    )
    {
        mb_internal_encoding($charset);
        ini_set('default_charset', $charset);
        date_default_timezone_set($timezone);
        ini_set('date.timezone', $timezone);
        setlocale(LC_ALL, $locale);
        //numbers are always localized to en_US.utf-8'
        setlocale(LC_NUMERIC, self::DEFAULT_LOCALE);
    }
}
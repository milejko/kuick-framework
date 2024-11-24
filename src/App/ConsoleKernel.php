<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Kuick\Router\CommandLauncher;
use Kuick\Router\CommandMatcher;
use Throwable;

/**
 * Console Application Kernel
 */
final class ConsoleKernel extends KernelAbstract
{
    public function __invoke(array $argv): void
    {
        ini_set('max_execution_time', 0);
        try {
            //localization setup
            ($this->container->get(AppSetLocalization::class))();
            //@TODO: Command input/output instead of array of strings
            echo $this->container->get(CommandLauncher::class)(
                $this->container->get(CommandMatcher::class)->findRoute($argv),
                $argv
            ) . PHP_EOL;
            $this->logger->info('Command executed: ' . implode(' ', $argv));
        } catch (Throwable $error) {
            $this->logger->error($error->getMessage());
            echo $error->getMessage() . PHP_EOL;
            exit(1);
        }
    }
}

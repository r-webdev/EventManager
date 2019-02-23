<?php
namespace Mossengine\Core\v1\Commands;

use Symfony\Component\Console\Command\Command;

/**
 * Class CoreCommand
 * @package Mossengine\Core\v1\Commands
 */
class CoreCommand extends Command
{
    use \Mossengine\Core\v1\Traits\SlimContainerTrait;
}
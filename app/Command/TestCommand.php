<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\LoginService;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;

#[Command]
class TestCommand extends HyperfCommand
{
    public function __construct(protected ContainerInterface $container)
    {
        parent::__construct('test');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('this is a test script');
    }

    public function handle()
    {
//        var_dump(111);
        $this->container->get(LoginService::class)->dump();
    }
}

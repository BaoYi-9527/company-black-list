<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\EmailService;
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
        $this->setDescription('this is a test script.');
    }

    public function handle()
    {
        $toEmail = "1648186705@qq.com";
        $code    = rand(10000, 99999);
        (new EmailService())->send($toEmail, $code);
//        dd(111);
//        $this->line('Hello Hyperf!', 'info');
    }
}

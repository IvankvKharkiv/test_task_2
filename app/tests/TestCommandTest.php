<?php

namespace App\Tests;

use App\Exception\BinResultException;
use App\Service\BinListService\BinListService;
use App\Service\RateService\RateService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use App\Command\TestCommand;
use Symfony\Component\Serializer\SerializerInterface;

class TestCommandTest extends KernelTestCase
{
    private $commandTester;
    private $command;

    protected function setUp(): void
    {
        $application = new Application();

        $serializer = self::getContainer()->get(SerializerInterface::class);
        $binListService = self::getContainer()->get(BinListService::class);
        $rateService = self::getContainer()->get(RateService::class);

        $application->add(new TestCommand(
            $serializer,
            $binListService,
            $rateService
        ));

        $this->command = $application->find('app:test');
        $this->commandTester = new CommandTester($this->command);
    }

    public function testExecutePositive()
    {
        $options = [];
        $params  = ['-u' => true, '-i' => true];

        $exitCode = $this->commandTester->execute($params, $options);

        $this->assertEquals(0, $exitCode);

        $output = $this->commandTester->getDisplay();

        $this->assertNotEmpty($output);
        $this->assertMatchesRegularExpression('/1.*/', $output);
        $this->assertMatchesRegularExpression('/.*0\.46712513441526.*/', $output);
        $this->assertMatchesRegularExpression('/.*1\.1694572841109.*/', $output);
        $this->assertMatchesRegularExpression('/.*23\.643065370711.*/', $output);
    }

    public function testExecuteNegative()
    {
        $options = [];
        $params  = ['-u' => true];

        $this->expectException(BinResultException::class);

        $exitCode = $this->commandTester->execute($params, $options);
        $this->assertEquals(0, $exitCode);

        $options = [];
        $params  = [];

        $this->expectException(BinResultException::class);

        $exitCode = $this->commandTester->execute($params, $options);
        $this->assertEquals(0, $exitCode);
    }

}

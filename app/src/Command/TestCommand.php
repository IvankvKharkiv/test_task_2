<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:test', description: 'Hello PhpStorm')]
class TestCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (explode("\n", file_get_contents(__DIR__ . '/input.txt')) as $row) {

            if (empty($row)) break;
            dd(json_decode($row));
            $p = explode(",",$row);
            $p2 = explode(':', $p[0]);
            $value[0] = trim($p2[1], '"');
            $p2 = explode(':', $p[1]);
            $value[1] = trim($p2[1], '"');
            $p2 = explode(':', $p[2]);
            $value[2] = trim($p2[1], '"}');

            $binResults = file_get_contents(__DIR__ . '/binResults.json');
            if (!$binResults)
                die('error!');
            $r = json_decode($binResults);
            $isEu = $this->isEu($r->country->alpha2);

            $rate = @json_decode(file_get_contents(__DIR__ . '/rates.json'), true)['rates'][$value[2]];

            if ($value[2] == 'EUR' or $rate == 0) {
                $amntFixed = $value[1];
            }
            if ($value[2] != 'EUR' or $rate > 0) {
                $amntFixed = $value[1] / $rate;
            }

            echo $amntFixed * ($isEu == 'yes' ? 0.01 : 0.02);
            print "\n";
        }


        dump(123);
        return Command::SUCCESS;
    }

    protected function isEu($c) {
        $result = false;
        switch($c) {
            case 'AT':
            case 'BE':
            case 'BG':
            case 'CY':
            case 'CZ':
            case 'DE':
            case 'DK':
            case 'EE':
            case 'ES':
            case 'FI':
            case 'FR':
            case 'GR':
            case 'HR':
            case 'HU':
            case 'IE':
            case 'IT':
            case 'LT':
            case 'LU':
            case 'LV':
            case 'MT':
            case 'NL':
            case 'PO':
            case 'PT':
            case 'RO':
            case 'SE':
            case 'SI':
            case 'SK':
                $result = 'yes';
                return $result;
            default:
                $result = 'no';
        }
        return $result;
    }
}

<?php

namespace App\Tests\Unit\Service;

use App\Service\ChartService;
use PHPUnit\Framework\TestCase;
use Symfony\UX\Chartjs\Builder\ChartBuilder;
use Symfony\UX\Chartjs\Model\Chart;

class ChartServiceTest extends TestCase
{
    /**
     * @group unit
     * @dataProvider dataProvider
     */
    public function testGenerateChartDataForRegisteredUsers(array $usersCount): void
    {
        $chartBuilder = new ChartBuilder();
        $chartServics = new ChartService($chartBuilder);

        $chart = $chartServics->registredUsersChart($usersCount);

        $this->assertInstanceOf(Chart::class, $chart);
        $this->assertEquals(33, $chart->getData()['datasets'][0]['data'][1]);
    }

    public function dataProvider(): array
    {
        return [
            [
                [1 => 13, 33, 19, 21, 24, 11, 43, 22, 14, 10, 18, 14],
            ],
        ];
    }
}

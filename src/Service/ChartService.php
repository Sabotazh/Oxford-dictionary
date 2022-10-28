<?php

namespace App\Service;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartService
{
    private ChartBuilderInterface $chartBuilder;

    public function __construct(ChartBuilderInterface $chartBuilder)
    {
        $this->chartBuilder = $chartBuilder;
    }

    /**
     * @param array $usersCount
     * @return \Symfony\UX\Chartjs\Model\Chart
     */
    public function registredUsersChart(array $usersCount): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $this->stringMonthsLable(),
            'datasets' => [
                [
                    'label'             => 'User Registration',
                    'backgroundColor'   => 'rgb(255, 99, 132)',
                    'borderColor'       => 'rgb(255, 99, 132)',
                    'data'              => array_values($usersCount),
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => max($usersCount),
                ],
            ],
        ]);

        return $chart;
    }

    private function stringMonthsLable(): array
    {
        return [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ];
    }
}

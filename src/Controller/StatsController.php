<?php

namespace App\Controller;

use App\Repository\ResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class StatsController extends AbstractController
{
    #[Route('/stats', name: 'app_stats')]
    public function index(ResultRepository $resultRepository, ChartBuilderInterface $chartBuilder): Response
    {
        $stats = $resultRepository->numberOfOccurence();
        //$stats = $resultRepository->numberOfOccurenceNumeroChance();
        $statsNumeroChance = $resultRepository->numberOfOccurenceNumeroChance();

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => array_column($stats, 'numero'),
            'datasets' => [
                [
                    'label' => 'Moyenne des apparitions des numéros par tirage en pourcentage',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)', 'fill' => true,
                    'borderColor' => 'rgba(75, 192, 192)',
                    'data' => array_column($stats, 'moy'),
                ],
                [
                    'label' => 'Moyenne des apparitions des numéros lors du premier tirage en pourcentage',
                    'backgroundColor' => 'rgba(255, 205, 86, 0.2)', 'fill' => true,
                    'borderColor' => 'rgba(255, 205, 86)',
                    'data' => array_column($stats, 'total'),
                ],
                [
                    'label' => 'Moyenne des apparitions des numéros lors du second tirage en pourcentage',
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)', 'fill' => true,
                    'borderColor' => 'rgba(153, 102, 255)',
                    'data' => array_column($stats, 'total2'),
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 6,
                    'suggestedMax' => 18,
                ],
            ],
        ]);

        $chart2 = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart2->setData([
            'labels' => array_column($statsNumeroChance, 'numero'),
            'datasets' => [

                [
                    'label' => 'Moyenne des apparitions des numéros chance par soir en pourcentage',

                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)',
                        'rgb(255, 205, 86, 0.2)',
                        'rgb(235, 215, 8, 0.2)',
                        'rgb(235, 15, 138, 0.2)'
                    ],
                    'fill' => true,
                    'hoverOffset' => 4,
                    'data' => array_column($statsNumeroChance, 'total'),
                ],
            ],
        ]);

        $chart2->setOptions([
            'scales' => [

            ],
        ]);
        return $this->render('stats/index.html.twig', [
            'chart' => $chart,
            'chart2' => $chart2,
        ]);
    }
}

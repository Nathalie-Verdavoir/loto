<?php

namespace App\Controller;

use App\Repository\ResultRepository;
use App\Service\CheckResult;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/result')]
class ResultController extends AbstractController
{
    // exemple : /result/?grille=1-2-3-4-5|1
    #[Route('/', name: 'app_result_index', options: ["expose" => true], methods: ['GET'])]
    public function index(CheckResult $checkResult, ResultRepository $resultRepository): Response
    {
        $checkResult->setStrGrille($_GET["grille"]);
        $checkResult->getDernierTirage();
        $checkResult->parseGrille();

        $response = new Response(json_encode(['grille_gagnante' => $checkResult->checkResult()]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
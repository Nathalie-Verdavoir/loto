<?php

namespace App\Controller;

use App\Repository\ResultRepository;
use App\Service\CheckResult;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/result')]
class ResultController extends AbstractController
{
    // exemple : /result/update/?grille=1-2-3-4-5|1
    #[Route('/update', name: 'app_result_update', options: ["expose" => true], methods: ['GET'])]
    public function up(KernelInterface $kernel)
    {

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'loto:manage-zip',
        ]);

        // You can use NullOutput() if you don't need the output

        $application->run($input);

        // return the output, don't use if you used NullOutput()
        //  $content = $output->fetch();

        // return new Response(""), if you used NullOutput()
        return $this->redirectToRoute('app_result_index', [
            'grille' => $_GET["grille"],
        ]);

    }

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

    #[Route('/stats', name: 'app_result_stats', options: ["expose" => true], methods: ['GET'])]
    public function stats(ResultRepository $resultRepository): Response
    {
        $stats = $resultRepository->numberOfOccurence();

        $response = new Response(json_encode(['stats' => $stats]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
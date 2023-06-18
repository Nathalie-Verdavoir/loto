<?php

namespace App\Service;

use App\Repository\ResultRepository;

class CheckResult
{
    private string $strGrille;
    private array $arrBoulesDeLaGrilleJouee;
    private int $numeroChanceDeLaGrilleJouee;
    private array $dernierTirage;
    private ResultRepository $resultRepository;


    public function __construct(ResultRepository $resultRepository)
    {
        $this->resultRepository = $resultRepository;
    }

    public function parseGrille()
    {
        $this->arrBoulesDeLaGrilleJouee = explode('-', substr($this->strGrille, 0, strpos($this->strGrille, '|')));
        $this->numeroChanceDeLaGrilleJouee = substr($this->strGrille, strpos($this->strGrille, '|') + 1);
    }

    public function getDernierTirage()
    {
        $this->dernierTirage = $this->resultRepository->findLastTirage();
    }

    public function checkResult(): bool
    {

        if (
            count(array_intersect($this->arrBoulesDeLaGrilleJouee, explode(',', $this->dernierTirage['tirage1']))) > 1 ||
            count(array_intersect($this->arrBoulesDeLaGrilleJouee, explode(',', $this->dernierTirage['tirage2']))) > 1 ||
            $this->numeroChanceDeLaGrilleJouee === $this->dernierTirage['numero_chance']
        ) {
            return true;
        }
        return false;

    }

    /**
     * @param string $strGrille
     */
    public function setStrGrille(string $strGrille): void
    {
        $this->strGrille = $strGrille;
    }
}
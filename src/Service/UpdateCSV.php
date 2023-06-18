<?php

namespace App\Service;

use App\Entity\Result;
use App\Repository\ResultRepository;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Filesystem\Filesystem;

class UpdateCSV
{
    private string $zip_url;
    private string $destination_path;
    private string $projectDir;

    public function __construct(
        $projectDir,
        $zip_url = "https://media.fdj.fr/static/csv/loto/loto_201911.zip",
        $destination_path = '/public/loto_201911.zip'
    )
    {
        $this->projectDir = $projectDir;
        $this->zip_url = $zip_url;
        $this->destination_path = $destination_path;
    }

    public function update(): bool
    {
        try {

            $filesystem = new Filesystem();
            $filesystem->exists($this->projectDir . '/public/loto_201911.zip');
            file_put_contents($this->projectDir . '/' . $this->destination_path, fopen($this->zip_url, 'r'));
            $zip = new \ZipArchive();
            $zip->open($this->projectDir . '/' . $this->destination_path);
            $zip->extractTo($this->projectDir . '/' . "upload");
            $zip->close();
            unlink($this->projectDir . '/' . $this->destination_path);

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function populate(ResultRepository $resultRepository): bool
    {
        $tirageCollection = $resultRepository->findAll();
        $tirageNumero = array_map(function ($tirage) {
            return $tirage->getAnneeNumeroDeTirage();
        }, $tirageCollection);
        $reader = ReaderEntityFactory::createReaderFromFile($this->projectDir . '/' . 'public/upload/loto_201911.csv');

        $reader->setFieldDelimiter(';');
        $reader->open($this->projectDir . '/' . 'upload/loto_201911.csv');
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $indexOfRow => $row) {
                if ($indexOfRow === 1) {
                    foreach ($row->getCells() as $indexOfCell => $cell) {
                        if ($cell->getValue() === 'annee_numero_de_tirage') {
                            $indexOfAnneeNumeroDeTirage = $indexOfCell;
                        }
                        if ($cell->getValue() === 'date_de_tirage') {
                            $indexOfDateDeTirage = $indexOfCell;
                        }
                        if ($cell->getValue() === 'boule_1') {
                            $indexOfBoule1 = $indexOfCell;
                        }
                        if ($cell->getValue() === 'boule_2') {
                            $indexOfBoule2 = $indexOfCell;
                        }
                        if ($cell->getValue() === 'boule_3') {
                            $indexOfBoule3 = $indexOfCell;
                        }
                        if ($cell->getValue() === 'boule_4') {
                            $indexOfBoule4 = $indexOfCell;
                        }
                        if ($cell->getValue() === 'boule_5') {
                            $indexOfBoule5 = $indexOfCell;
                        }
                        if ($cell->getValue() === 'boule_1_second_tirage') {
                            $indexOfBoule1SecondTirage = $indexOfCell;
                        }
                        if ($cell->getValue() === 'boule_2_second_tirage') {
                            $indexOfBoule2SecondTirage = $indexOfCell;
                        }
                        if ($cell->getValue() === 'boule_3_second_tirage') {
                            $indexOfBoule3SecondTirage = $indexOfCell;
                        }
                        if ($cell->getValue() === 'boule_4_second_tirage') {
                            $indexOfBoule4SecondTirage = $indexOfCell;
                        }
                        if ($cell->getValue() === 'boule_5_second_tirage') {
                            $indexOfBoule5SecondTirage = $indexOfCell;
                        }
                        if ($cell->getValue() === 'numero_chance') {
                            $indexOfNumeroChance = $indexOfCell;
                        }
                    }
                } else {
                    /** @var $tirageCollection ArrayCollection */
                    if (count($tirageCollection) === 0 || !in_array($row->getCells()[$indexOfAnneeNumeroDeTirage]->getValue(), $tirageNumero)) {
                        $result = new Result();

                        $result->setDateDeTirage($row->getCells()[$indexOfDateDeTirage]);
                        $result->setAnneeNumeroDeTirage($row->getCells()[$indexOfAnneeNumeroDeTirage]->getValue());
                        $result->setBoule1($row->getCells()[$indexOfBoule1]->getValue());
                        $result->setBoule2($row->getCells()[$indexOfBoule2]->getValue());
                        $result->setBoule3($row->getCells()[$indexOfBoule3]->getValue());
                        $result->setBoule4($row->getCells()[$indexOfBoule4]->getValue());
                        $result->setBoule5($row->getCells()[$indexOfBoule5]->getValue());
                        $result->setBoule1SecondTirage($row->getCells()[$indexOfBoule1SecondTirage]->getValue());
                        $result->setBoule2SecondTirage($row->getCells()[$indexOfBoule2SecondTirage]->getValue());
                        $result->setBoule3SecondTirage($row->getCells()[$indexOfBoule3SecondTirage]->getValue());
                        $result->setBoule4SecondTirage($row->getCells()[$indexOfBoule4SecondTirage]->getValue());
                        $result->setBoule5SecondTirage($row->getCells()[$indexOfBoule5SecondTirage]->getValue());
                        $result->setNumeroChance($row->getCells()[$indexOfNumeroChance]->getValue());
                        $resultRepository->save($result, true);
                    }
                }
            }
        }
        return true;
    }
}
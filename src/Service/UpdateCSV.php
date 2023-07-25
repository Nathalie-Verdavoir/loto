<?php

namespace App\Service;

use App\Entity\Result;
use App\Repository\ResultRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $encoders = [new CsvEncoder()];
        $normalizers = [new ObjectNormalizer(), new DateTimeNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $context1 = [
            CsvEncoder::DELIMITER_KEY => ';'
        ];
        $tirageCollection = $resultRepository->findAll();
        $tirageNumero = array_map(function ($tirage) {
            return $tirage->getAnneeNumeroDeTirage();
        }, $tirageCollection);

        $datas = $serializer->decode(file_get_contents($this->projectDir . '/' . 'public/upload/loto_201911.csv'), 'csv', $context1);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('show_result')
            ->toArray();

        foreach ($datas as $i => $data) {
            $result = $serializer->denormalize($data, Result::class, 'csv', $context);
            if (count($tirageCollection) === 0 || !in_array($result->getAnneeNumeroDeTirage(), $tirageNumero)) {
                $resultRepository->save($result, true);
            }
        }
        return true;
    }
}
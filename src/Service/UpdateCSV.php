<?php

namespace App\Service;

use App\Entity\Result;
use App\Repository\ResultRepository;
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

    public function __construct($projectDir)
    {
        $this->projectDir = $projectDir;
        $this->zip_url = $_ENV['URL_LOTO'];
        $this->destination_path = 'public/upload';
    }

    public function downloadAndUnzip(): bool
    {
        try {
            $new_zip_dir = $this->projectDir . '/public/loto_201911.zip';
            file_put_contents($new_zip_dir, fopen($this->zip_url, 'r'));
            $zip = new \ZipArchive();
            $zip->open($new_zip_dir);
            $zip->extractTo($this->projectDir . '/' . $this->destination_path);
            $zip->close();
            unlink($new_zip_dir);

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
<?php

namespace App\Command;

use App\Repository\ResultRepository;
use App\Service\UpdateCSV;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'loto:manage-zip',
    description: 'Add a short description for your command',
)]
class LotoManageZipCommand extends Command
{
    private $projectDir;
    private $resultRepository;

    public function __construct($projectDir, ResultRepository $resultRepository)
    {
        $this->projectDir = $projectDir;
        $this->resultRepository = $resultRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {


        $updateCsv = new UpdateCSV($this->projectDir);
        $uploadedStatusOk = $updateCsv->update();
        if ($uploadedStatusOk) {
            $updateCsv->populate($this->resultRepository);
        }

        return Command::SUCCESS;
    }


}

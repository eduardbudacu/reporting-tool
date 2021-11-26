<?php

namespace Domain\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Domain\DataSources\S3;

class UploadCommand extends Command
{

    public function configure()
    {
        $this->setName('upload')
            ->setDescription('Uploads a file to S3.')
            ->setHelp('This command allows you to write report data to S3.')
            ->addArgument('file', InputArgument::REQUIRED, 'The name of the file you want to upload.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $data = new S3();
        $file = $input->getArgument('file');
        $result = $data->write($file, file_get_contents("data/{$file}"));
        $output->writeLn($result['ObjectURL']);
        return Command::SUCCESS;
    }
}

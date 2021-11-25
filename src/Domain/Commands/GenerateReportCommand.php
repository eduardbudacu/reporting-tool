<?php

namespace Domain\Commands;

use Domain\DataSources\DataSource;
use Domain\Reports\Report;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use DateTime;
use Domain\Publishing\Destination;
use Exception;

class GenerateReportCommand extends Command
{

    public function configure()
    {
        $this->setName('generate')
            ->setDescription('Generates a specific report')
            ->setHelp('This command allows you generate the report you specified.')
            ->addArgument('report', InputArgument::REQUIRED, 'The name of the report.')
            ->addOption('datasource', 'd', InputArgument::OPTIONAL, 'Data source for the report', 'local')
            ->addOption('publish', 'p', InputArgument::OPTIONAL, 'Destination for the report', 'local')
            ->addOption('startdate', 's', InputArgument::OPTIONAL, 'Start date')
            ->addOption('enddate', 'e', InputArgument::OPTIONAL, 'End date');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $reportOption = $input->getArgument('report');
        $datasourceOption = $input->getOption('datasource');
        $publishOption = $input->getOption('publish');
        $startDate = $input->getOption('startdate');
        $endDate = $input->getOption('enddate');
        

        if ($startDate) {
            $startDate = DateTime::createFromFormat('d-m-Y H:i:s', $startDate . ' 00:00:00');
            if($startDate) {
                $startDate = $startDate->getTimestamp();
            } else {
                throw new Exception('Invalid start date');
            }
        }

        if($endDate) {
            $endDate = DateTime::createFromFormat('d-m-Y H:i:s', $endDate . ' 00:00:00');
            if($endDate) {
                $endDate = $endDate->getTimestamp();
            } else {
                throw new Exception('Invalid start date');
            }
        }

        $datasource = DataSource::create($datasourceOption);
        $report = Report::create($reportOption, $datasource);
        $csv = $report->getCsv(['startdate' => $startDate, 'enddate' => $endDate]);

        $destination = Destination::create($publishOption, $reportOption . '.csv');
        $destination->publish($csv);

        if($publishOption == 'local') {
            $output->writeln([
                'File succesfuly exported:',
                $reportOption . '.csv'
            ]);
        } else {
            $reportLinks = [
                'turnover-per-brand' => 'https://datastudio.google.com/reporting/14905ce3-a16e-44ad-86db-f41c17b87600',
                'turnover-per-day' => 'https://datastudio.google.com/reporting/ed46327d-862a-459b-b4a1-d950235604e4'
            ];

            $output->writeln([
                'Report succesfuly generated. Follow the link below to view the report:',
                $reportLinks[$reportOption]
            ]);
        }
        return Command::SUCCESS;
    }
}

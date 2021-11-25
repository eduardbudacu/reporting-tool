<?php 
namespace Domain\Commands;

use Domain\DataSources\DataSource;
use Domain\Reports\Report;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateReportCommand extends Command
{
    
    public function configure()
    {
        $this->setName('generate')
            ->setDescription('Generates a specific report')
            ->setHelp('This command allows you generate the report you specified.')
            ->addArgument('report', InputArgument::REQUIRED, 'The name of the report.')
            ->addOption('datasource', 'd', InputArgument::OPTIONAL, 'Data source for the report', 'local')
            ->addOption('startdate', 's', InputArgument::OPTIONAL, 'Start date')
            ->addOption('enddate', 'e', InputArgument::OPTIONAL, 'End date');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
       $report = $input->getArgument('report');
       $datasourceOption = $input->getOption('datasource');
       $startDate = $input->getOption('startdate');
       $endDate = $input->getOption('enddate');
       
       $datasource = DataSource::create($datasourceOption);
       $report = Report::create($report, $datasource);
       var_dump($report->generateReport());
       return Command::SUCCESS;
    }
}
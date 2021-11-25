<?php

namespace Domain\Reports;

use Domain\DataSources\DataSource;
use Domain\Publishing\Destination;
use Exception;

abstract class Report {

    const VAT_PERCENT = 21;

    protected $datasource;

    abstract public function generateReport(array $filters = []);

    public function __construct(DataSource $datasource)
    {
        $this->datasource = $datasource;
    }

    public function getCsv(array $filters = []) {
        $data = $this->generateReport($filters);

        if(!empty($data)) {
            $csv = implode(",", array_keys(array_values($data)[0])). PHP_EOL;

            foreach ($data as $item) {
                $csv .= implode(",", array_values($item)) . PHP_EOL;
            }
            return $csv;
        }
        return false;
    }

    public static function create($type, DataSource $datasource): Report {
        switch($type) {
            case 'turnover-per-brand': {
                return new TurnoverPerBrand($datasource);
            }break;
            case 'turnover-per-day': {
                return new TurnoverPerDay($datasource);
            }break;
            default: 
                throw new Exception('Invalid report specified');
        }
    }

    protected function filterByDate(array $data, array $filters)
    {
        $startDate = isset($filters['startdate']) ? $filters['startdate'] : null;
        $endDate = isset($filters['enddate']) ? $filters['enddate'] : null;
        
        return array_filter($data, function ($item) use ($startDate, $endDate) {
            if($startDate && $endDate) {
                return ($item['date'] >= $startDate && $item['date'] <= $endDate);
            }

            if($startDate) {
                return $item['date'] >= $startDate;
            }

            if($endDate) {
                return $item['date'] <= $endDate;
            }

            return true;
        });
    }

    /**
     * Returns the amount without VAT
     */
    protected function substractVat($amount) {
        // amountWithVat = amountWithoutVat + amountWithoutVat * 21 / 100
        // amountWithVat = amountWithoutVat * (1 + 21 / 100)
        // amountWithVat = amountWithoutVat * 1.21;
        return round($amount / (1 + self::VAT_PERCENT / 100), 2); 
    }

}
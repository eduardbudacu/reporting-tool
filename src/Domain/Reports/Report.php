<?php

namespace Domain\Reports;

use Domain\DataSources\DataSource;
use Exception;

abstract class Report {

    const VAT_PERCENT = 21;

    protected $datasource;

    abstract public function generateReport();

    public function __construct(DataSource $datasource)
    {
        $this->datasource = $datasource;
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

    public function publish() {
        
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
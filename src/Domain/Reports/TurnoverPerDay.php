<?php

namespace Domain\Reports;

class TurnoverPerDay extends Report {
    protected $gmv;

    public function generateReport(array $filters = []) {
        $this->loadData();
        $this->gmv = $this->filterByDate($this->gmv, $filters);

        $daily_report = [];
        foreach($this->gmv as $sale) {
            if(isset($daily_report[$sale['date']])) {
                $daily_report[$sale['date']]['turnover'] += $sale['turnover'];
                $daily_report[$sale['date']]['turnoverWithoutVat'] += $this->substractVat($sale['turnover']);
            } else {
                $daily_report[$sale['date']] = [
                    'date' => date('Y-m-d H:i:s', $sale['date']),
                    'turnover' => $sale['turnover'],
                    'turnoverWithoutVat' => $this->substractVat($sale['turnover'])
                ];
            }
        }

        return $daily_report;
    }

    protected function loadData() {
        $this->gmv = $this->datasource->read('gmv.json');

        $this->gmv = array_map(function ($item) {
            $item['date'] = strtotime($item['date']);
            return $item;
        }, $this->gmv);
    }
    
}
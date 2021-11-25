<?php

namespace Domain\Reports;

class TurnoverPerBrand extends Report {
    protected $brands;
    protected $gmv;
    public function generateReport() {
        $this->loadData();
        $this->mapBrands();

        $brands_report = [];
        foreach($this->gmv as $sale) {
            if(isset($brands_report[$sale['brand_id']])) {
                $brands_report[$sale['brand_id']]['turnover'] += $this->substractVat($sale['turnover']);
            } else {
                $brands_report[$sale['brand_id']] = [
                    'name' => $sale['brand_name'],
                    'turnover' => $this->substractVat($sale['turnover'])
                ];
            }
        }

        return $brands_report;
    }

    protected function loadData() {
        $this->brands = $this->datasource->read('brands.json');
        $this->gmv = $this->datasource->read('gmv.json');
    }

    protected function mapBrands() {
        $brands_map = array_combine(array_column($this->brands, 'id'), array_values($this->brands));
        $this->gmv = array_map(function ($item) use ($brands_map) {
            $item['date'] = strtotime($item['date']);
            $item['brand_name'] = $brands_map[$item['brand_id']]['name'];
            return $item;
        }, $this->gmv);
    }
}
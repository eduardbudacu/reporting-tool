<?php

namespace Domain\Reports;

/**
 * Generates turnover by brand report
 */
class TurnoverPerBrand extends Report
{
    /**
     * Brands data
     * 
     * @var array 
     */
    protected $brands;

    /**
     * Sales data
     * 
     * @var array
     */
    protected $gmv;

    public function generateReport(array $filters = [])
    {
        $this->loadData();
        $this->mapBrands();
        $this->gmv = $this->filterByDate($this->gmv, $filters);

        $brands_report = [];
        foreach ($this->gmv as $sale) {
            if (isset($brands_report[$sale['brand_id']])) {
                $brands_report[$sale['brand_id']]['turnover'] += $sale['turnover'];
                $brands_report[$sale['brand_id']]['turnoverWithoutVat'] += $this->substractVat($sale['turnover']);
            } else {
                $brands_report[$sale['brand_id']] = [
                    'name' => $sale['brand_name'],
                    'turnover' => $sale['turnover'],
                    'turnoverWithoutVat' => $this->substractVat($sale['turnover'])
                ];
            }
        }

        return $brands_report;
    }

    /**
     * Loads data from external source
     */
    protected function loadData()
    {
        $this->brands = $this->datasource->read('brands.json');
        $this->gmv = $this->datasource->read('gmv.json');
    }

    /**
     * Performs brands mapping and conversion of date
     */
    protected function mapBrands()
    {
        $brands_map = array_combine(array_column($this->brands, 'id'), array_values($this->brands));
        $this->gmv = array_map(function ($item) use ($brands_map) {
            $item['date'] = strtotime($item['date']);
            $item['brand_name'] = $brands_map[$item['brand_id']]['name'];
            return $item;
        }, $this->gmv);
    }

    
}

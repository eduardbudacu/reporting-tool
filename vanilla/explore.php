<?php

define('VAT_PERCENT', 21);

function substractVat($amount) {
    // amountWithVat = amountWithoutVat + amountWithoutVat * 21 / 100
    // amountWithVat = amountWithoutVat * (1 + 21 / 100)
    // amountWithVat = amountWithoutVat * 1.21;
    return round($amount / (1 + VAT_PERCENT / 100), 2); 
}

function showData($data)
{
    echo implode(",", array_keys(array_values($data)[0])). PHP_EOL;

    foreach ($data as $item) {
        echo implode(",", array_values($item)) . PHP_EOL;
    }
}

$brands = json_decode(file_get_contents(__DIR__ .'/../data/brands.json'), true);

showData($brands);

$brands_map = array_combine(array_column($brands, 'id'), array_values($brands));

$gmv = json_decode(file_get_contents(__DIR__ .'/../data/gmv.json'), true);

$gmv = array_map(function ($item) use ($brands_map) {
    $item['date'] = strtotime($item['date']);
    $item['brand_name'] = $brands_map[$item['brand_id']]['name'];
    return $item;
}, $gmv);

$start_date = (DateTime::createFromFormat('d-m-Y H:i:s', '01-05-2018 00:00:00'))->getTimestamp();
$end_date = (DateTime::createFromFormat('d-m-Y H:i:s', '07-05-2018 00:00:00'))->getTimestamp();

$gmv = array_filter($gmv, function($item) use ($start_date, $end_date) {
    return ($item['date'] >= $start_date && $item['date'] <= $end_date);
});

$brands_report = [];
foreach($gmv as $sale) {
    if(isset($brands_report[$sale['brand_id']])) {
        $brands_report[$sale['brand_id']]['turnover'] += $sale['turnover'];
    } else {
        $brands_report[$sale['brand_id']] = [
            'name' => $sale['brand_name'],
            'turnover' => $sale['turnover']
        ];
    }
}

showData($brands_report);

$daily_report = [];
foreach($gmv as $sale) {
    if(isset($daily_report[$sale['date']])) {
        $daily_report[$sale['date']]['turnover'] += substractVat($sale['turnover']);
    } else {
        $daily_report[$sale['date']] = [
            'date' => date('Y-m-d H:i:s', $sale['date']),
            'turnover' => substractVat($sale['turnover'])
        ];
    }
}

showData($daily_report);


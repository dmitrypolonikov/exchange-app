<?php

// Подключаем файлы с классами
require 'ExchangeRatesClient.php';
require 'ExchangeRateDto.php';

// Используем класс ExchangeRatesClient
$apiKey = 'YOUR_API_KEY';
$apiClient = new ExchangeRatesClient($apiKey);

// Получение курсов обмена валют
$exchangeRates = $apiClient->getExchangeRates();
echo '<pre>';
var_dump($exchangeRates);
echo '</pre>';

// Получение данных с другого эндпоинта API
$otherParams = array(
    'show_alternative' => false,
    'show_inactive' => true,
    'prettyprint' => true,
);
$otherData = $apiClient->getOtherData('currencies.json', $otherParams);

echo '<pre>';
var_dump($otherData);
echo '</pre>';





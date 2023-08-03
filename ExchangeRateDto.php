<?php

/**
 * Класс ExchangeRateDto представляет данные о курсах обмена валют.
 */
class ExchangeRateDto
{
    /**
     * @var string Код базовой валюты.
     */
    public string $base;

    /**
     * @var int Временная метка последнего обновления курсов.
     */
    public int $timestamp;

    /**
     * @var array Ассоциативный массив с курсами валют.
     */
    public array $rates;
}

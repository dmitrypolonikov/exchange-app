<?php

/**
 * Класс ExchangeRatesClient предоставляет доступ к API для получения курсов обмена валют.
 */
class ExchangeRatesClient
{
    /**
     * @var string Ключ API для доступа к сервису.
     */
    private string $apiKey;

    /**
     * Создает новый экземпляр класса ExchangeRatesClient с указанным API ключом.
     *
     * @param string $apiKey Ключ API для доступа к сервису.
     *
     * @throws InvalidArgumentException Если API ключ не передан или пустой.
     */
    public function __construct(string $apiKey)
    {
        if (empty($apiKey)) {
            throw new InvalidArgumentException('API key cannot be empty.');
        }
        $this->apiKey = $apiKey;
    }

    /**
     * Отправляет запрос к API и получает данные в формате JSON.
     *
     * @param string $endpoint Конечная точка API, к которой нужно отправить запрос.
     * @param array $params Параметры запроса (необязательно).
     *
     * @return array|null Массив данных, полученных от API, либо null в случае ошибки.
     * 
     * @throws Exception Если произошла ошибка при выполнении запроса или некорректный ответ от API.
     */
    private function makeApiRequest(string $endpoint, array $params = []): ?array
    {
        $apiUrl = 'https://openexchangerates.org/api/';
        $params['app_id'] = $this->apiKey;
        $url = $apiUrl . $endpoint . '?' . http_build_query($params);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new Exception('API request error: ' . curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (!is_array($data)) {
            throw new Exception('Invalid API response: ' . $response);
        }

        return $data;
    }

    /**
     * Получает курсы обмена валют.
     *
     * @return ExchangeRateDto|null Объект DTO с данными курсов валют, либо null в случае ошибки.
     */
    public function getExchangeRates(): ?ExchangeRateDto
    {
        try {
            $data = $this->makeApiRequest('latest.json');

            if ($data === null) {
                return null;
            }

            $exchangeRateDto = new ExchangeRateDto();
            $exchangeRateDto->base = $data['base'];
            $exchangeRateDto->timestamp = $data['timestamp'];
            $exchangeRateDto->rates = $data['rates'];

            return $exchangeRateDto;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }

    /**
     * Получает данные с другой конечной точки API.
     *
     * @param string $endpoint Конечная точка API, к которой нужно отправить запрос.
     * @param array $params Параметры запроса (необязательно).
     *
     * @return array|null Массив данных, полученных от API, либо null в случае ошибки.
     */
    public function getOtherData(string $endpoint, array $params = []): ?array
    {
        try {
            return $this->makeApiRequest($endpoint, $params);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }
}

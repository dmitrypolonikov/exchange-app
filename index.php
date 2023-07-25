<?php 

class ExchangeRatesClient
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        if (empty($apiKey)) {
            throw new InvalidArgumentException('API key cannot be empty.');
        }
        $this->apiKey = $apiKey;
    }

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

class ExchangeRateDto
{
    public string $base;
    public int $timestamp;
    public array $rates;
}

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





<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BkashService
{
    protected $client;
    protected $baseUrl;
    protected $tokenUrl;
    protected $createUrl;
    protected $executeUrl;
    protected $appKey;
    protected $appSecret;
    protected $username;
    protected $password;


    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false // Only for sandbox!
        ]);
        
        $this->baseUrl = env('BKASH_BASE_URL');
        $this->tokenUrl = $this->baseUrl.'/checkout/token/grant';
        $this->createUrl = $this->baseUrl.'/checkout/create';
        $this->executeUrl = $this->baseUrl.'/checkout/execute';
        
        $this->appKey = env('BKASH_APP_KEY');
        $this->appSecret = env('BKASH_APP_SECRET');
        $this->username = env('BKASH_USERNAME');
        $this->password = env('BKASH_PASSWORD');
    }
    public function getSignatureHeaders($token, $paymentId)
    {
        $service = 'execute-api';
        $region = 'ap-southeast-1';
        $date = now()->format('Ymd');
        $datetime = now()->format('Ymd\THis\Z');
        
        // 1. Create canonical request
        $canonicalUri = "/v1.2.0-beta/tokenized/checkout/execute/$paymentId";
        $canonicalHeaders = "content-type:application/json\n"
            . "host:tokenized.pay.bka.sh\n"
            . "x-app-key:{$this->appKey}\n"
            . "x-amz-date:{$datetime}\n";
        
        $signedHeaders = "content-type;host;x-app-key;x-amz-date";
        $payloadHash = hash('sha256', json_encode([])); // Empty JSON payload for POST

        $canonicalRequest = "POST\n"
            . "$canonicalUri\n"
            . "\n" // No query string
            . "$canonicalHeaders\n"
            . "$signedHeaders\n"
            . "$payloadHash";

        // 2. Create string to sign
        $algorithm = "AWS4-HMAC-SHA256";
        $credentialScope = "$date/$region/$service/aws4_request";
        $stringToSign = "$algorithm\n"
            . "$datetime\n"
            . "$credentialScope\n"
            . hash('sha256', $canonicalRequest);

        // 3. Calculate signature
        $signingKey = $this->getSigningKey($date, $region, $service);
        $signature = hash_hmac('sha256', $stringToSign, $signingKey);

        // 4. Return headers
        return [
            'Content-Type' => 'application/json',
            'X-APP-Key' => $this->appKey,
            'X-Amz-Date' => $datetime,
            'Authorization' => "$algorithm "
                . "Credential={$this->appKey}/{$credentialScope}, "
                . "SignedHeaders={$signedHeaders}, "
                . "Signature={$signature}",
            'x-bkash-token' => $token
        ];
    }

    protected function getSigningKey($date, $region, $service)
    {
        $kSecret = 'AWS4' . $this->appSecret;
        $kDate = hash_hmac('sha256', $date, $kSecret, true);
        $kRegion = hash_hmac('sha256', $region, $kDate, true);
        $kService = hash_hmac('sha256', $service, $kRegion, true);
        return hash_hmac('sha256', 'aws4_request', $kService, true);
    }

    // public function getToken()
    // {
    //     return Cache::remember('bkash_token', 50, function () {
    //         try {
    //             $response = $this->client->post($this->tokenUrl, [
    //                 'headers' => [
    //                     'Content-Type' => 'application/json',
    //                     'username' => $this->username,
    //                     'password' => $this->password,
    //                 ],
    //                 'json' => [
    //                     'app_key' => $this->appKey,
    //                     'app_secret' => $this->appSecret,
    //                     'grant_type' => 'client_credentials'
    //                 ],
    //                 'verify' => env('BKASH_SANDBOX') ? false : true
    //             ]);

    //             $data = json_decode($response->getBody(), true);
                
    //             \Log::debug('bKash Token Response', ['response' => $data]);
                
    //             if (!isset($data['id_token'])) {
    //                 throw new \Exception('Token failed: ' . ($data['error'] ?? json_encode($data)));
    //             }

    //             return $data['id_token'];
    //         } catch (\Exception $e) {
    //             \Log::error('bKash Token Error', [
    //                 'error' => $e->getMessage(),
    //                 'trace' => $e->getTraceAsString(),
    //                 'credentials' => [
    //                     'username' => $this->username,
    //                     'app_key' => substr($this->appKey, 0, 5).'...',
    //                     'base_url' => $this->baseUrl
    //                 ]
    //             ]);
    //             throw new \Exception('Token generation failed: ' . $e->getMessage());
    //         }
    //     });
    // }

    public function getToken()
    {
        return Cache::remember('bkash_token', 50, function () { // 50 minutes
            try {
                $response = $this->client->post(
                    'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant',
                    [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'username' => $this->username,
                            'password' => $this->password,
                        ],
                        'json' => [
                            'app_key' => $this->appKey,
                            'app_secret' => $this->appSecret,
                            'grant_type' => 'client_credentials'
                        ],
                        'timeout' => 30
                    ]
                );

                $data = json_decode($response->getBody(), true);
                
                if (!isset($data['id_token'])) {
                    throw new \Exception('Token missing: '.json_encode($data));
                }

                return $data['id_token'];
            } catch (\Exception $e) {
                \Log::error('bKash Token Error', [
                    'error' => $e->getMessage(),
                    'credentials' => [
                        'username' => $this->username,
                        'app_key' => substr($this->appKey, 0, 5).'...',
                        'base_url' => $this->baseUrl
                    ]
                ]);
                throw $e;
            }
        });
    }

    public function createPayment($amount, $invoiceId, $callbackUrl)
    {
        try {
            $token = $this->getToken();

            $response = $this->client->post($this->createUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $token,
                    'X-APP-Key' => $this->appKey,
                ],
                'json' => [
                    'mode' => '0011', // Tokenized payment mode
                    'amount' => $amount,
                    'currency' => 'BDT',
                    'intent' => 'sale',
                    'merchantInvoiceNumber' => $invoiceId,
                    'callbackURL' => $callbackUrl,
                    'payerReference' => 'customer_'.$invoiceId,
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            if (!isset($data['paymentID'])) {
                Log::error('bKash Create Payment Error', ['response' => $data]);
                throw new \Exception('Payment creation failed: '.($data['statusMessage'] ?? 'Unknown error'));
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('bKash Create Payment Exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    
    public function executePayment($paymentId)
{
    try {
        $token = $this->getToken();
        $url = $this->executeUrl.'/'.$paymentId;
        
        $headers = $this->getSignatureHeaders($token, $paymentId);
        
        // Debug output
        \Log::debug('Execution Headers', [
            'headers' => $headers,
            'url' => $url,
            'time' => now()->toDateTimeString()
        ]);

        $response = $this->client->post($url, [
            'headers' => $headers,
            'http_errors' => false,
            'json' => [], // Empty JSON payload
            'timeout' => 30
        ]);

        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody(), true);

        if ($statusCode !== 200) {
            \Log::error('bKash Execution Failed', [
                'status' => $statusCode,
                'response' => $body,
                'request' => [
                    'url' => $url,
                    'headers' => $headers
                ]
            ]);
            throw new \Exception($body['message'] ?? "API returned $statusCode");
        }

        return $body;

    } catch (\Exception $e) {
        \Log::error('bKash Execution Error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        throw new \Exception('Payment execution failed: '.$e->getMessage());
    }
}

    public function debugCanonicalRequest($paymentId)
{
    $date = now()->format('Ymd');
    $datetime = now()->format('Ymd\THis\Z');
    
    return "POST\n"
        . "/v1.2.0-beta/tokenized/checkout/execute/$paymentId\n"
        . "\n"
        . "content-type:application/json\n"
        . "host:tokenized.pay.bka.sh\n"
        . "x-app-key:{$this->appKey}\n"
        . "x-amz-date:{$datetime}\n"
        . "\n"
        . "content-type;host;x-app-key;x-amz-date\n"
        . hash('sha256', '');
}

public function debugStringToSign($paymentId)
{
    $date = now()->format('Ymd');
    $datetime = now()->format('Ymd\THis\Z');
    $region = config('services.bkash.aws_region');
    $service = 'execute_payment';
    
    return "AWS4-HMAC-SHA256\n"
        . "$datetime\n"
        . "$date/$region/$service/aws4_request\n"
        . hash('sha256', $this->debugCanonicalRequest($paymentId));
}

public function debugSigningKey()
{
    $date = now()->format('Ymd');
    $region = config('services.bkash.aws_region');
    $service = 'execute_payment';
    
    $kDate = hash_hmac('sha256', $date, 'AWS4' . $this->appSecret, true);
    $kRegion = hash_hmac('sha256', $region, $kDate, true);
    $kService = hash_hmac('sha256', $service, $kRegion, true);
    return hash_hmac('sha256', 'aws4_request', $kService, true);
}
}
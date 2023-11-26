<?php

namespace App\Services;

use Aws\Credentials\Credentials;
use Aws\Signature\SignatureV4;
use Aws\Sts\StsClient;
use GuzzleHttp\Psr7\Request;

class AwsService
{
    public static function getHeader($url)
    {
        $credentials = new Credentials(env('YOUR_ACCESS_KEY_ID'), env('YOUR_SECRET_ACCESS_KEY'));
        $client = new StsClient([
            'region' => env('YOUR_REGION'),
            'version' => '2011-06-15',
            'credentials' => $credentials,
        ]);
        $signer = new SignatureV4(env('SERVICE_NAME'), env('YOUR_REGION'));

        // Construct the request parameters
        $parameters = [
            'method' => 'GET',
            'uri' => $url . env('MY_FUNCTION'), // Replace 'MY_FUNCTION' with the actual resource path
        ];

        $request = new Request($parameters['method'], $parameters['uri']);

        // Sign the request using the signer object
        $signedRequest = $signer->signRequest($request, $credentials);

        // Extract the authorization header from the signed request
        $authorizationHeader = $signedRequest->getHeaderLine('Authorization');
        return $authorizationHeader;
    }

    public static function getData($url)
    {
        $curl = curl_init();
        $authorizationHeader = self::getHeader($url);
        $date = new \DateTime();
        $formattedDate = $date->format('Ymd\THis\Z');
        $headers = array(
            'Content-Type: application/json',
            'X-Amz-Date: ' . $formattedDate,
            'Authorization: ' . $authorizationHeader
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER =>$headers,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
}

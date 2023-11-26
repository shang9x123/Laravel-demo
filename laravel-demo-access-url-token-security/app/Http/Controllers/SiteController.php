<?php

namespace App\Http\Controllers;

use App\Services\AwsService;
use Aws\Credentials\Credentials;
use Aws\Sdk;
use Aws\Signature\SignatureV4;
use Aws\Sts\StsClient;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $curl = curl_init();
        $url='https://ern2nvr3ri5audb5idfqgzvaea0qsqdg.lambda-url.ap-southeast-1.on.aws/api/post';
        $headers =  AwsService::getdata($url);
        return $headers;
    }
}

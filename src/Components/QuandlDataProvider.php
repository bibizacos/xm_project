<?php
/**
 * Created by PhpStorm.
 * User: bibiz
 * Date: 01-May-20
 * Time: 11:48 PM
 */

namespace App\Components;

use App\Models\Quandl;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class QuandlDataCollector
{


    public function search($symbol=null, $start_date=null, $end_date=null, $api_key=null)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.quandl.com/api/v3/datasets/WIKI/'.$symbol.'.json?order=asc&start_date='.$start_date.'&end_date='.$end_date.'&api_key='.$api_key.'');
        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            $normalizer = new ObjectNormalizer(null, null, null, new ReflectionExtractor());
            $serializer = new Serializer([new DateTimeNormalizer(), $normalizer]);
            return $serializer->denormalize(
                $response->toArray()['dataset'],
                'App\Models\Quandl'
            );
        }
        return new Quandl();
    }
}
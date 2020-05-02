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


class QuandlDataProvider
{

    /**
     * retrieve data from quandl API
     * @param null $symbol
     * @param null $start_date
     * @param null $end_date
     * @param $api_key
     * @return Quandl|array|object
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function search($symbol, $start_date, $end_date)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $_ENV['API_URL'].$symbol.'.json?order=asc&start_date='.$start_date.'&end_date='.$end_date.'&api_key='.$_ENV['API_KEY'].'');
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
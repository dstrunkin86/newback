<?php

namespace App\Services\Logging;

use Elastic\Elasticsearch\ClientBuilder;

use Illuminate\Database\Eloquent\Model;

/**
 * Class used for ElasticSearch logging
 *
 * @method array send(array $body)
 */
class ElasticSearchService extends Model
{
    public $client;

    function __construct()
    {
        $hosts = [
            config('elastic.host')
        ];

        $this->client = ClientBuilder::create()
            ->setHosts($hosts)
            ->setBasicAuthentication(config('elastic.user'), config('elastic.password'))
            ->build();
    }


    /**
     * Sends document to ElasticSearch.
     *
     * Usage:
     * $response = (new ElasticSearch)->send([
     *   'field1' => 'value1',
     *   'field2' => 'value2'
     * ]);
     *
     * Default strategy:
     * message = what happened
     * category = which process caused event
     * extra = all extra fields
     *
     * @param  Array $body
     * @return Boolean
     */
    public function send($body = [], $type = 'log')
    {
        $body = array_merge(["@timestamp" => gmdate('Y/m/d H:i:s')], $body);
        switch ($type) {
            case 'log':
                $index = config('elastic.index') . "-" . date('Y-m-d');
                break;
            case 'error':
                $index = config('elastic.errorIndex') . "-" . date('Y-m-d');
                break;
            default:
                $index = config('elastic.index') . "-" . date('Y-m-d');
        }
        $params = [
            'index' => $index,
            'body'  => $body
        ];
        $response = $this->client->index($params);
        $response = json_decode($response);
        return ($response->result == 'created') ? true : false;
    }
}

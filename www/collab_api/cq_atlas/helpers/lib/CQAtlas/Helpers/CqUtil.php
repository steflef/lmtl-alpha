<?php
namespace CQAtlas\Helpers;

/**
 * Class CqUtil
 * @package CQAtlas\Helpers
 */
abstract class CqUtil
{

    public function __construct (){}

    /**
     * @param $text
     * @return mixed|string
     */
    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }

    /**
     * @param $keys
     * @param $lookup
     * @return bool
     */
    static function matchKeys($keys, $lookup)
    {
        foreach($lookup as $item){
            foreach ($keys as $key) {
                if(mb_strtolower($key) === $item){
                    return $key;
                }
            }
        }
        return false;
    }

    /**
     * @param string $url
     * @param string $rawBody
     * @return array
     */
    static function curlPost($url='', $rawBody=''){

        $headers = array(
            "Content-Type: application/json",
            "Encoding: UTF-8"
        );

        $curl = curl_init();
        //echo $url;
        //echo $rawBody;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $rawBody);
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return array(
            'response' => $response,
            'status' => $status
        );
    }

    /**
     * @param array $di
     * @return array
     */
    static function getDatasets($di=array()){

        $sql = rawurlencode( "SELECT * FROM datasets;" );

        $url = 'http://'.$di['cartodb_subdomain'].'.'.$di['cartodb_endpoint'];
        $url .= '?q='.$sql.'&api_key='.$di['cartodb_api_key'];

        $headers = array(
            "Content-Type: application/json",
            "Encoding: UTF-8"
        );

        $curl = curl_init();
        //echo $url;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_POST, false);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return array(
            'response' => $response,
            'status' => $status
        );
    }

    /**
     * @param array $di
     * @param int $datasetId
     * @return array
     */
    static function getDataset($di=array(), $datasetId=0){

        $sql = rawurlencode( "SELECT * FROM datasets WHERE id=$datasetId;" );

        $url = 'http://'.$di['cartodb_subdomain'].'.'.$di['cartodb_endpoint'];
        $url .= '?q='.$sql.'&api_key='.$di['cartodb_api_key'];

        $headers = array(
            "Content-Type: application/json",
            "Encoding: UTF-8"
        );

        $curl = curl_init();
        //echo $url;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_POST, false);
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return array(
            'response' => $response,
            'status' => $status
        );
    }

    /**
     * @param array $di
     * @param int $datasetId
     * @return array
     */
    static function getDatasetPlaces($di=array(), $datasetId=0){

        $sql = rawurlencode( "SELECT * FROM places WHERE dataset_id=$datasetId;" );

        $url = 'http://'.$di['cartodb_subdomain'].'.'.$di['cartodb_endpoint'];
        $url .= '?q='.$sql.'&api_key='.$di['cartodb_api_key'];

        $headers = array(
            "Content-Type: application/json",
            "Encoding: UTF-8"
        );

        $curl = curl_init();
        //echo $url;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_POST, false);
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return array(
            'response' => $response,
            'status' => $status
        );
    }

    /**
     * @param array $di
     * @param int $placeId
     * @return array
     */
    static function getPlace($di=array(), $placeId=0){

        $sql = rawurlencode( "SELECT * FROM places WHERE place_id=$placeId;" );

        $url = 'http://'.$di['cartodb_subdomain'].'.'.$di['cartodb_endpoint'];
        $url .= '?q='.$sql.'&api_key='.$di['cartodb_api_key'];

        $headers = array(
            "Content-Type: application/json",
            "Encoding: UTF-8"
        );

        $curl = curl_init();
        //echo $url;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_POST, false);
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return array(
            'response' => $response,
            'status' => $status
        );
    }

    /**
     * @param array $di
     * @param int $datasetId
     * @param int $placeId
     * @return array
     */
    static function getDatasetPlaceId($di=array(), $datasetId=0, $placeId=0){

        $sql = rawurlencode( "SELECT * FROM places WHERE dataset_id = $datasetId AND place_id = $placeId;" );

        $url = 'http://'.$di['cartodb_subdomain'].'.'.$di['cartodb_endpoint'];
        $url .= '?q='.$sql.'&api_key='.$di['cartodb_api_key'];

        $headers = array(
            "Content-Type: application/json",
            "Encoding: UTF-8"
        );

        $curl = curl_init();
        //echo $url;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_POST, false);
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return array(
            'response' => $response,
            'status' => $status
        );
    }

    /**
     * @param array $di
     * @param int $datasetId
     * @return array
     */
    static function addPlace($di=array(), $datasetId=0){

        $sql = rawurlencode( "SELECT * FROM places WHERE dataset_id = $datasetId AND place_id = $placeId;" );

        $url = 'http://'.$di['cartodb_subdomain'].'.'.$di['cartodb_endpoint'];
        $url .= '?q='.$sql.'&api_key='.$di['cartodb_api_key'];

        $headers = array(
            "Content-Type: application/json",
            "Encoding: UTF-8"
        );

        $curl = curl_init();
        //echo $url;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_POST, false);
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return array(
            'response' => $response,
            'status' => $status
        );
    }
}
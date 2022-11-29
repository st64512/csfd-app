<?php

namespace App\Utils;

use Nette\Utils\Json;

class Utilities
{
    public static function solveArrayToString(array $data,string $key) : string
    {
        $dataString = "";
        $decodedJsonData = Json::decode($data[$key]);
        foreach ($decodedJsonData as $decodedData) {
            $dataString .= ' ' . $decodedData . ',';
        }
        return trim($dataString, ' ,');
    }

    public static function solveStringToJsonString(string $dataString) : string {
        $data = explode(',' ,$dataString);
        foreach ($data as $key => $d) {
            $data[$key] = trim($d, ' ');
        }

        return Json::encode($data);
    }
}
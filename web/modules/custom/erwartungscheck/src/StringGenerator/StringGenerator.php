<?php
/**
 * Created by PhpStorm.
 * User: Malte
 * Date: 01.07.2019
 * Time: 19:45
 */

namespace Drupal\stringgenerator\StringGenerator;

class StringGenerator {

    function randomString($length = 8) {
        $str = "";
        $characters = array_merge(range('A','Z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
}
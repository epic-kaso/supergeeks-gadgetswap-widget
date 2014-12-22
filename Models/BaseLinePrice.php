<?php

use ActiveRecord\Model;

class BaseLinePrice extends Model {

    //gadget_id

    static $belongs_to = array(
        array('gadget'),
    );

    public static function extractBaseLinePrices($textRepresentation) {
        $pricePerGroup = self::extractPricePerGroup($textRepresentation);
        return self::extractFinalSizeToPriceMapping($pricePerGroup);
    }

    private static function extractPricePerGroup($textRepresentation) {
        return explode(',', $textRepresentation);
    }

    private static function extractFinalSizeToPriceMapping($pricePerGroup) {
        $result = array();
        foreach ($pricePerGroup as $group) {
            list($key, $value) = self::breakAndMapPriceToSize($group);
            $result[$key] = $value;
        }

        return $result;
    }

    private static function breakAndMapPriceToSize($group) {
        $t = explode(':', $group);
        return array($t[0], $t[1]);
    }

}

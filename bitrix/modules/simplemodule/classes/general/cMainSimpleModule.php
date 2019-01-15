<?php

use SimpleModule\Orm13\OrmTable;

class cMainSimpleModule {
    static $MODULE_ID="simplemodule";

    /**
     * Хэндлер, отслеживающий изменения в инфоблоках
     * @param $arFields
     * @return bool
     */
    static function onBeforeElementUpdateHandler($arFields){
        // чтение параметров модуля
        // $iblock_id = COption::GetOptionString(self::$MODULE_ID, "iblock_id");
        // Активная деятельность
        // Результат
        return true;
    }

    public static function setLog() {
        echo 'Агент setLog';
        //if(\Bitrix\Main\Loader::includeModule("simplemodule")) {
            $res = CAgent::GetList(Array("ID" => "DESC"), array("NAME" => "cMainSimpleModule::setLog();"))->fetch();
            OrmTable::add(array(
                'DATE' => $res['LAST_EXEC'],
                'SOURCE' => $res['NAME']
            ));
        //}
    }
}
<?php

use SimpleModule\Orm13\OrmTable;

global $DB;

class cMainSimpleModule {
    static $MODULE_ID="simplemodule";

    public static function setLog() {
        if(\Bitrix\Main\Loader::includeModule("simplemodule")) {
            $res = CAgent::GetList(Array("ID" => "DESC"), array("NAME" => "cMainSimpleModule::setLog();"))->fetch();
            OrmTable::add(array(
                'DATE' => (!empty($res['LAST_EXEC'])) ? $res['LAST_EXEC'] : new \Bitrix\Main\Type\DateTime(),
                'SOURCE' => $res['NAME']
            ));
        }
        return "cMainSimpleModule::setLog();";
    }
}
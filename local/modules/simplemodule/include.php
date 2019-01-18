<?php
CModule::IncludeModule("simplemodule");
global $DBType;

$arClasses=array(
    "simplemodule" => "install/index.php",
    'SimpleModule\Orm13\OrmTable' =>'lib/orm.php',
    'cMainSimpleModule' =>'classes/general/cMainSimpleModule.php'
);

CModule::AddAutoloadClasses("simplemodule", $arClasses);
//\Bitrix\Main\Loader::registerAutoLoadClasses("simplemodule", $arClasses);
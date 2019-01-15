<?php
CModule::IncludeModule("simplemodule");
global $DBType;

$arClasses=array(
    'cMainSimpleModule' =>'classes/general/cMainSimpleModule.php'
);

CModule::AddAutoloadClasses("simplemodule", $arClasses);

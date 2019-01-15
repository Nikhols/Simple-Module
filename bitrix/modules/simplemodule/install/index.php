<?
include_once(dirname(__DIR__).'/lib/main.php');
// Не подключается?
include_once(dirname(__DIR__).'/lib/orm.php');

use \Bitrix\Main\Entity\Base;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\EventManager;
use \SimpleModule\Orm13\Main;

Loc::loadMessages(__FILE__);

Class simpleModule extends CModule
{
    var $MODULE_ID = "simplemodule";
    var $HIGHLOADBLOCK_NAME = "MyTbl";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;

    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__."/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("simplemodule_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("simplemodule_MODULE_DESC");

        $this->PARTNER_NAME = Loc::getMessage("simplemodule_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("simplemodule_PARTNER_URI");
    }

    function InstallDB()
    {
        //if(\Bitrix\Main\Loader::includeModule("simplemodule")){
            $connection = \Bitrix\Main\Application::getConnection();

            if(!$connection->isTableExists(\SimpleModule\Orm13\OrmTable::getTableName())) {
                Base::getInstance('\SimpleModule\Orm13\OrmTable')->createDbTable();
            }
        //}
    }

    function UnInstallDB()
    {
        \Bitrix\Main\Application::getConnection()->queryExecute("DROP TABLE IF EXISTS simplemodule_orm");
    }

    function InstallHLBlock() {
        if(Bitrix\Main\Loader::includeModule("highloadblock")){
            $result = \Bitrix\Highloadblock\HighloadBlockTable::add(array(
                'NAME' => $this->HIGHLOADBLOCK_NAME,
                'TABLE_NAME' => 'myname',
            ));
            $aUserField = array(
                'ENTITY_ID' => 'HLBLOCK_' . $result->getId(),
                'FIELD_NAME' => 'UF_LOG',
                'USER_TYPE_ID' => 'string',
                'SORT' => 500,
                'MULTIPLE' => 'N',
                'MANDATORY' => 'Y',
                'SHOW_FILTER' => 'N',
                'SHOW_IN_LIST' => 'Y',
                'EDIT_IN_LIST' => 'Y',
                'IS_SEARCHABLE' => 'N',
                'SETTINGS' => array(),
            );
            $oUserTypeEntity = new CUserTypeEntity();
            $oUserTypeEntity->Add($aUserField);

            if (!$result->isSuccess()) {
                $errors = $result->getErrorMessages();
            } else {
                $id = $result->getId();
            }
        }
    }

    function UnInstallHLBlock() {
        if(Bitrix\Main\Loader::includeModule("highloadblock")) {
            $hlBlock = \Bitrix\Highloadblock\HighloadBlockTable::getList([
                'select' => ['ID'],
                'filter' => ['=NAME' => $this->HIGHLOADBLOCK_NAME]
            ])->fetch();
            Bitrix\Highloadblock\HighloadBlockTable::delete($hlBlock['ID']);
        }
    }

    /**
     *
     */
    function InstallAgent() {
        CAgent::AddAgent(
            "cMainSimpleModule::setLog();",           // имя функции
            $this->MODULE_ID,                           // идентификатор модуля
            "N",                                 // агент не критичен к кол-ву запусков
            60,                               // интервал запуска - 1 сутки
            "",                                 // дата первой проверки на запуск
            "Y",                                  // агент активен
            "",                                 // дата первого запуска
            30);
    }

    function UnInstallAgent() {
        CAgent::RemoveAgent("cMainSimpleModule::setLog();", $this->MODULE_ID);
    }

    function InstallEvents()
    {
        EventManager::getInstance()->registerEventHandler(Main::MODULE_ID, '\SimpleModule\Orm13\Orm::OnAfterAdd', Main::MODULE_ID, '\SimpleModule\Orm13\Event', 'event');

        return true;
    }

    function UnInstallEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler(Main::MODULE_ID, '\SimpleModule\Orm13\Orm::OnAfterAdd', Main::MODULE_ID, '\SimpleModule\Orm13\Event', 'event');

        return true;
    }

    function InstallFiles($arParams = array())
    {
        CopyDirFiles(Main::GetPatch()."/install/components/simplemodule", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/simplemodule", true, true);

        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFilesEx("/bitrix/components/simplemodule/orm.list");

        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;
        if(Main::isVersionD7())
        {
            $this->InstallFiles();
            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallHLBlock();
            $this->InstallAgent();
            RegisterModule(Main::MODULE_ID);
        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage("simplemodule_INSTALL_ERROR_VERSION"));
        }

        $APPLICATION->IncludeAdminFile(Loc::getMessage("simplemodule_INSTALL_TITLE"), Main::GetPatch()."/install/step.php");
    }

    function DoUninstall()
    {
        UnRegisterModule(Main::MODULE_ID);
        $this->UnInstallEvents();
        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->UnInstallHLBlock();
        $this->UnInstallAgent();
    }

}
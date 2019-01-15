<?php

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \SimpleModule\Orm13\OrmTable;


class OrmList extends CBitrixComponent
{
    /**
     * проверяет подключение необходиимых модулей
     * @throws LoaderException
     */
    protected function checkModules()
    {
        if (!Main\Loader::includeModule('simplemodule'))
            throw new Main\LoaderException(Loc::getMessage('simplemodule_MODULE_NOT_INSTALLED'));
    }

    public function executeComponent()
    {
        $this -> checkModules();

        OrmTable::add(array(
            'DATE' => new Type\Date('2015-04-16', 'Y-m-d'),
            'SOURCE' => 'Один элемент сущности',
        ));

        $this->arResult = OrmTable::GetList()->fetchAll();

        $this->includeComponentTemplate();
    }
};
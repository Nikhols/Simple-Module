<?php
namespace SimpleModule\Orm13;

use Bitrix\Main\Entity;

class OrmTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'simplemodule_orm';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\DateField('DATE'),
            new Entity\StringField('SOURCE', array(
                'required' => true,
            )),
        );
    }
}
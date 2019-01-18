<?php
namespace SimpleModule\Orm13;

class event
{
    function Event(\Bitrix\Main\Entity\Event $event)
    {
        $fields = $event->getParameter("fields");
        echo'Отработало событие OnBeforeAdd<br>
            <pre>';
        var_dump($fields);
        echo'</pre><br>';
    }
}
<?php


use Bitrix\Main\Loader;

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderSaved',
    'OnSaleOrderSaved'
);
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderBeforeSaved',
    'OnSaleOrderBeforeSaved'
);
function OnSaleOrderBeforeSaved(\Bitrix\Main\Event $event)
{


    /** @var Bitrix\Crm\Order\Order $order */
    $order = $event->getParameter("ENTITY");


    if ($order) {
        $propertyCollection = $order->getPropertyCollection();

        $propsData = array();
        foreach ($propertyCollection as $propertyItem) {
            if (!empty($propertyItem->getField("CODE"))) {
                $propsData[$propertyItem->getField("CODE")] = trim($propertyItem->getValue());
            }
        }
        /** Пример $propsData **/
        //        [FIO] => TEST!
        //    [EMAIL] => tes111t@test.ru
        //    [PHONE] => +79111111113
        //    [ZIP] => 101000
        //    [LOCATION] => 0000073738
        //    [CITY] =>
        //    [ADDRESS] =>


        $strLoc = Bitrix\Sale\Location\Admin\LocationHelper::getLocationPathDisplay($propsData['LOCATION']);


        // Москва и область
        if ($propsData['LOCATION'] === '0000073738' || strripos($strLoc, 'Московская область')) {
            $order->setField('RESPONSIBLE_ID', 4202);
        }

        // Петербург
//        if ($propsData['LOCATION'] === '0000103664') {
//            $order->setField('RESPONSIBLE_ID', getRandomUserFromDepartment(4));
//        }

        else {
            $order->setField('RESPONSIBLE_ID', 4201);
        }


    }


    $event->addResult(
        new \Bitrix\Main\EventResult(
            \Bitrix\Main\EventResult::SUCCESS, $order
        )
    );

}

function OnSaleOrderSaved(\Bitrix\Main\Event $event)

{
    /** @var Bitrix\Crm\Order\Order $or */
    $or = $event->getParameter("ENTITY");
    if ($or && $or->isNew()) {
        $ordID = $or->getId();

        if (Loader::includeModule('crm') && Loader::includeModule('sale') && $ordID) {
            $orderList = \Bitrix\Sale\Order::getList([
                'select' => [
                    "ID",
                    "USER_ID",
                    "PERSON_TYPE_ID",
                    "RESPONSIBLE_ID",
                    "LOCATION.VALUE",
                    "CITY.VALUE",
                    "PHONE.VALUE",
                    "EMAIL.VALUE",
                    "FIO.VALUE",
                    "INDEX.VALUE",
                    "ADDRESS.VALUE",
                ],
                'filter' => [
                    '=LOCATION.CODE' => 'LOCATION',
                    '=CITY.CODE' => 'CITY',
                    '=PHONE.CODE' => 'PHONE',
                    '=EMAIL.CODE' => 'EMAIL',
                    '=FIO.CODE' => $or->getPersonTypeId() == '5' ? 'FIO' : 'CONTACT_PERSON',
                    '=INDEX.CODE' => 'ZIP',
                    '=ADDRESS.CODE' => 'ADDRESS',
                    'ID' => $ordID,

                ],
                'runtime' => [
                    new \Bitrix\Main\Entity\ReferenceField(
                        'FIO',
                        '\Bitrix\sale\Internals\OrderPropsValueTable',
                        ["=this.ID" => "ref.ORDER_ID"],
                        ["join_type" => "left"]
                    ),
                    new \Bitrix\Main\Entity\ReferenceField(
                        'CITY',
                        '\Bitrix\sale\Internals\OrderPropsValueTable',
                        ["=this.ID" => "ref.ORDER_ID"],
                        ["join_type" => "left"]
                    ),
                    new \Bitrix\Main\Entity\ReferenceField(
                        'LOCATION',
                        '\Bitrix\sale\Internals\OrderPropsValueTable',
                        ["=this.ID" => "ref.ORDER_ID"],
                        ["join_type" => "left"]
                    ),

                    new \Bitrix\Main\Entity\ReferenceField(
                        'PHONE',
                        '\Bitrix\sale\Internals\OrderPropsValueTable',
                        ["=this.ID" => "ref.ORDER_ID"],
                        ["join_type" => "left"]
                    ),
                    new \Bitrix\Main\Entity\ReferenceField(
                        'EMAIL',
                        '\Bitrix\sale\Internals\OrderPropsValueTable',
                        ["=this.ID" => "ref.ORDER_ID"],
                        ["join_type" => "left"]
                    ),
                    new \Bitrix\Main\Entity\ReferenceField(
                        'INDEX',
                        '\Bitrix\sale\Internals\OrderPropsValueTable',
                        ["=this.ID" => "ref.ORDER_ID"],
                        ["join_type" => "left"]
                    ),
                    new \Bitrix\Main\Entity\ReferenceField(
                        'ADDRESS',
                        '\Bitrix\sale\Internals\OrderPropsValueTable',
                        ["=this.ID" => "ref.ORDER_ID"],
                        ["join_type" => "left"]
                    ),
                ],

            ]);

            while ($order = $orderList->Fetch()) {

                $contact = CCrmContact::GetList([], ['CHECK_PERMISSIONS' => 'N', 'UF_CRM_1683954248' => $order['USER_ID']], ['ID'])->Fetch();
                $cont = new CCrmContact(false);
                $arParams['FM']['PHONE'] = array(
                    'n0' => array(
                        'VALUE_TYPE' => 'WORK',
                        'VALUE' => $order['SALE_INTERNALS_ORDER_PHONE_VALUE'],
                    )
                );
                $arParams['FM']['EMAIL'] = array(
                    'n0' => array(
                        'VALUE_TYPE' => 'WORK',
                        'VALUE' => $order['SALE_INTERNALS_ORDER_EMAIL_VALUE'],
                    )
                );
                $nm = explode(' ', $order['SALE_INTERNALS_ORDER_FIO_VALUE']);
                $arParams['LAST_NAME'] = $nm[0] ?: ' - ';
                $arParams['NAME'] = $nm[1] ?: ' - ';
                $arParams['SECOND_NAME'] = $nm[2];
                $arParams['TYPE_ID'] = $order ['PERSON_TYPE_ID'] == 5 ? 'CLIENT' : 'SUPPLIER';
                $arParams['ASSIGNED_BY_ID'] = $order['RESPONSIBLE_ID'];
                $arParams['UF_CRM_1681886724'] = $order['SALE_INTERNALS_ORDER_CITY_VALUE'];
                $arParams['UF_CRM_1681886730'] = $order['SALE_INTERNALS_ORDER_INDEX_VALUE'];
                $arParams['UF_CRM_1681886780'] = $order['SALE_INTERNALS_ORDER_ADDRESS_VALUE'];
                $arParams['UF_CRM_1683962680'] = Bitrix\Sale\Location\Admin\LocationHelper::getLocationPathDisplay($order['SALE_INTERNALS_ORDER_LOCATION_VALUE']);
                $arParams['UF_CRM_1683954248'] = $order['USER_ID'];
                if (!($contact)) {
                    $contactId = $cont->Add($arParams, true, array('DISABLE_USER_FIELD_CHECK' => true));
                } else {
                    try {
                        $cont->Update($contact['ID'], $arParams);

                    } catch (Bitrix\Main\DB\SqlQueryException $err) {

                    }
                    $contactId = $contact['ID'];
                }

                $communication = $or->getContactCompanyCollection();
                $prCo = $communication->getPrimaryContact();

                if (!$prCo) {
                    $contact = $communication->createContact();
                    $contact->setField('ENTITY_ID', $contactId);
                    $contact->setField('IS_PRIMARY', 'Y');
                    $contact->save();

                }

            }


        }
    }
}

function getRandomUserFromDepartment($depID)
{
    if (Loader::IncludeModule('timeman') && $depID) {
        $users = [];
        $ob = CUser::GetList([], [], ['UF_DEPARTMENT' => $depID]);
        while ($rr = $ob->Fetch()) {
            $obUser = new CTimeManUser($rr['ID']);
            $state = $obUser->State();
            if ($state === 'OPENED' || $state === 'PAUSED') {
                $users[] = $rr;
            }
        }
        if (count($users)) {
            $randUser = $users[random_int(0, count($users) - 1)];
            return $randUser['ID'];
        }
    }
    return 1;

}
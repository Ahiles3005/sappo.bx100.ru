<?php


use Bitrix\Main\Loader;

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderSaved',
    ['Unisender', 'preprocessingSubscribeForOrder']
);


\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'subscribe',
    'OnStartSubscriptionUpdate',
    ['Unisender', 'preprocessingSubscribeForSubscribe']
);


\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'subscribe',
    'OnStartSubscriptionAdd',
    ['Unisender', 'preprocessingSubscribeForSubscribe']
);


class Unisender
{

    private static string $apiKey = '6imu1ps5k5ebf78166nykw7mdnbbkegh5jin89fy';
    private static string $apiHost = 'https://api.unisender.com/ru/api';


    public static function preprocessingSubscribeForSubscribe($data)
    {
        self::subscribe([899], '', $data['EMAIL']);
    }


    public static function preprocessingSubscribeForOrder(Bitrix\Main\Event $event)
    {

        /** @var Bitrix\Crm\Order\Order $order */

        $order = $event->getParameter("ENTITY");

        if ($order->isNew()) {
            $propertyCollection = $order->getPropertyCollection();

            $userName = '';
            $userEmail = '';

            foreach ($propertyCollection as $property) {
                $propertyCode = $property->getField('CODE');
                if ($propertyCode === 'FIO') {
                    $userName = $property->getValue();
                }

                if ($propertyCode === 'EMAIL') {
                    $userEmail = $property->getValue();
                }
            }

            self::subscribe([900], $userName, $userEmail);
        }

    }


    private static function subscribe(array $list_ids, string $name, string $email)
    {

        $data['fields'] = [
            'Name' => $name,
            'email' => $email
        ];
        $data['list_ids'] = $list_ids;
        $data['double_optin'] = 3;
        self::Send('subscribe', $data);
    }


    private static function Send(string $method, array $data)
    {
        $data['api_key'] = self::$apiKey;
        $data['format'] = 'json';

        $url = self::$apiHost . '/' . $method . '?' . http_build_query($data);
        $result = file_get_contents($url);
    }

}
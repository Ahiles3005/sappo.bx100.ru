<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/unisender/vendor/autoload.php';


use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementPropertyTable;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Basket;
use Bitrix\Main\UserTable;
use Bitrix\Main\Application;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;


//пользователь совершил покупку
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderSaved',
    ['UnisenderWrap', 'preprocessingSubscribeForOrder']
);

//пользователь подписался на рассылку
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'subscribe',
    'OnStartSubscriptionUpdate',
    ['UnisenderWrap', 'preprocessingSubscribeForSubscribe']
);

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'subscribe',
    'OnStartSubscriptionAdd',
    ['UnisenderWrap', 'preprocessingSubscribeForSubscribe']
);


//товар попал в раздел акции
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'iblock',
    'OnBeforeIBlockElementUpdate',
    ['UnisenderWrap', 'productSetSellout']
);

//товар заканчивается
//OnStoreProductUpdate
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'catalog',
    'Bitrix\Catalog\Model\Product::' . Bitrix\Main\ORM\Data\DataManager::EVENT_ON_UPDATE,
    ['UnisenderWrap', 'productLowQuantity']
);

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'сatalog',
    'OnProductUpdate',
    ['UnisenderWrap', 'productLowQuantity']
);

//товар появился в наличии
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'catalog',
    'Bitrix\Catalog\Model\Product::' . Bitrix\Main\ORM\Data\DataManager::EVENT_ON_UPDATE,
    ['UnisenderWrap', 'hasProductArrived']
);

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'сatalog',
    'OnProductUpdate',
    ['UnisenderWrap', 'hasProductArrived']
);

class UnisenderWrap
{

    private static string $apiKey = '6imu1ps5k5ebf78166nykw7mdnbbkegh5jin89fy';
    private static string $apiHost = 'https://api.unisender.com/ru/api';
    private static string $apiKeyGo = '6ztndnqp7yndhspfgc1fpxpbpimjhnbspudg17ja';
    private static string $templateTovarSkoroKonchitsa = '07d0109a-0ee1-11f0-a6c1-2e1ebb078ba7';
    private static string $templateSkidka = '44674df8-0ee0-11f0-834c-067a735718f5';
    private static string $templatePodZakaz = 'd870e87e-1782-11f0-89e2-f2d6584a5364';
    private static string $templateBroshenaKorzina = '1b776394-227a-11f0-9f3c-660a6fb0e4f7';
    private static string $templateBroshenaKorzina2Day = '8c188596-46de-11f0-a993-def7068c4b3d';
    private static string $templateBroshenieTovary = '13f4be90-46de-11f0-a426-f2f02b55ec83';


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


    /**
     *
     *
     */
    public static function productSetSellout(&$arFields)
    {

        $active = \Bitrix\Main\Config\Option::get("askaron.settings", "UF_ACTIVE_PSS");
        if ($active != 1) {
            return true;
        }

        Loader::includeModule('iblock');
        $elementId = $arFields['ID'];
        $propertyId = 424;
        $valueEnum = 7405;
        $iblockId = $arFields['IBLOCK_ID'] ?? 0;

        if ($arFields['IBLOCK_ID'] != 42) {
            return true;
        }

        $sellOut = false;

        //смотрим что установили товар в позицию акция
        $property = $arFields['PROPERTY_VALUES'][$propertyId] ?? [];

        foreach ($property as $value) {
            if ($value['VALUE'] == $valueEnum) {
                $sellOut = true;
                break;
            }
        }

        if (!$sellOut) {
            return true;
        }

        //смотрим есть ли свойство до изменения
        $propertyValue = ElementPropertyTable::getList([
            'select' => ['VALUE_ENUM'],
            'filter' => [
                '=IBLOCK_ELEMENT_ID' => $elementId,
                '=IBLOCK_PROPERTY_ID' => $propertyId,
            ],
        ]);

        $propertiesValue = [];
        while ($prop = $propertyValue->fetch()) {
            $propertiesValue[] = $prop['VALUE_ENUM'];
        }


        if (in_array($valueEnum, $propertiesValue)) {
            return true;
        }

        // Получаем пользователей одним запросом
        $users = UserTable::getList([
            'select' => ['ID', 'LOGIN', 'NAME', 'LAST_NAME', 'EMAIL'],
            'filter' => [
                '@ID' => new \Bitrix\Main\DB\SqlExpression(
                    'SELECT USER_ID FROM b_sale_fuser WHERE ID IN (
                SELECT FUSER_ID FROM b_sale_basket 
                WHERE DELAY = "Y" 
                AND PRODUCT_ID = ' . $elementId . '
                AND ORDER_ID IS NULL
                GROUP BY FUSER_ID
            )'
                )
            ]
        ]);

        $productData = self::getProductData($elementId, $iblockId);
        $recipients = [];
        while ($user = $users->fetch()) {
            $name = $user['LAST_NAME'] . ' ' . $user['NAME'];
            $email = $user['EMAIL'];
            if (!$email) {
                continue;
            }
            $recipients[] = [
                "email" => $email,
                "substitutions" => [
                    "Name" => $name,
                    "Tovar1" => $productData['name'],
                    "TovarLink" => $productData['url'],
                ]
            ];

        }

        $testerEmail = \Bitrix\Main\Config\Option::get("askaron.settings", "UF_UNSIDER_TEST_EMAIL");
        if (!empty($testerEmail)) {
            $recipients = [];
            $recipients[] = [
                "email" => $testerEmail,
                "substitutions" => [
                    "Name" => $name ?? 'test',
                    "Tovar1" => $productData['name'] ?? 'test',
                    "TovarLink" => $productData['url'] ?? 'test',
                ]
            ];
        }


        if (!empty($recipients)) {
            $params = [
                "message" => [
                    "recipients" => $recipients,
                    "template_id" => self::$templateSkidka
                ],
            ];

            try {
                $client = new Unisender\UniGoClient(self::$apiKeyGo, 'go2.unisender.ru');
                $response = $client->emails()->send($params);
                self::saveLog(date('c'));
                self::saveLog('productSetSellout');
                self::saveLog(json_encode($response));
                self::saveLog(json_encode($params));
                self::saveLog('#######');
            } catch (\Throwable $e) {
                self::saveLog(date('c'));
                self::saveLog('productSetSellout');
                self::saveLog($e->getMessage());
                self::saveLog('#######');
            }
        }

        return true;


    }


    public static function productLowQuantity(Bitrix\Catalog\Model\Event $event)
    {
        $active = \Bitrix\Main\Config\Option::get("askaron.settings", "UF_ACTIVE_PLQ");
        if ($active != 1) {
            return true;
        }

        $productId = $event->getParameter('id');
        $ex_fields = $event->getParameter('external_fields');
        $fields = $event->getParameter('fields');
        $iblockId = intval($ex_fields['IBLOCK_ID']) ?? 0;

        if ($iblockId !== 42) {
            return true;
        }


        if (array_key_exists('QUANTITY', $fields)) {
            $result = Bitrix\Catalog\ProductTable::getList([
                'select' => [
                    'ID',
                    'QUANTITY',
                ],
                'filter' => [
                    '=ID' => $productId
                ]
            ])->fetch();

            $originalQuantity = $result['QUANTITY'] ?? 0;
            if ($originalQuantity > 3 && ($fields['QUANTITY'] <= 3 && $fields['QUANTITY'] > 0)) {

                // Получаем пользователей одним запросом
                $users = UserTable::getList([
                    'select' => ['ID', 'LOGIN', 'NAME', 'LAST_NAME', 'EMAIL'],
                    'filter' => [
                        '@ID' => new \Bitrix\Main\DB\SqlExpression(
                            'SELECT USER_ID FROM b_sale_fuser WHERE ID IN (
                SELECT FUSER_ID FROM b_sale_basket 
                WHERE DELAY = "Y" 
                AND PRODUCT_ID = ' . $productId . '
                AND ORDER_ID IS NULL
                GROUP BY FUSER_ID
            )'
                        )
                    ]
                ]);

                $productData = self::getProductData($productId, $iblockId);
                $recipients = [];
                while ($user = $users->fetch()) {
                    $name = $user['LAST_NAME'] . ' ' . $user['NAME'];
                    $email = $user['EMAIL'];
                    if (!$email) {
                        continue;
                    }
                    $recipients[] = [
                        "email" => $email,
                        "substitutions" => [
                            "Name" => $name,
                            "Tovar1" => $productData['name'],
                            "TovarLink" => $productData['url'],
                        ]
                    ];

                }

                $testerEmail = \Bitrix\Main\Config\Option::get("askaron.settings", "UF_UNSIDER_TEST_EMAIL");
                if (!empty($testerEmail)) {
                    $recipients = [];
                    $recipients[] = [
                        "email" => $testerEmail,
                        "substitutions" => [
                            "Name" => $name ?? 'test',
                            "Tovar1" => $productData['name'] ?? 'test',
                            "TovarLink" => $productData['url'] ?? 'test',
                        ]
                    ];
                }

                if (!empty($recipients)) {
                    $params = [
                        "message" => [
                            "recipients" => $recipients,
                            "template_id" => self::$templateTovarSkoroKonchitsa
                        ],
                    ];

                    try {
                        $client = new Unisender\UniGoClient(self::$apiKeyGo, 'go2.unisender.ru');
                        $response = $client->emails()->send($params);
                        self::saveLog(date('c'));
                        self::saveLog('productLowQuantity');
                        self::saveLog(json_encode($response));
                        self::saveLog(json_encode($params));
                        self::saveLog('#######');
                    } catch (\Throwable $e) {
                        self::saveLog(date('c'));
                        self::saveLog('productLowQuantity');
                        self::saveLog($e->getMessage());
                        self::saveLog('#######');
                    }
                }


                return true;
            }
        }

        return true;

    }

    public static function hasProductArrived(Bitrix\Catalog\Model\Event $event)
    {

        $active = \Bitrix\Main\Config\Option::get("askaron.settings", "UF_ACTIVE_HPA");
        if ($active != 1) {
            return true;
        }

        $productId = $event->getParameter('id');
        $ex_fields = $event->getParameter('external_fields');
        $fields = $event->getParameter('fields');
        $iblockId = intval($ex_fields['IBLOCK_ID']) ?? 0;

        if ($iblockId !== 42) {
            return true;
        }


        if (array_key_exists('QUANTITY', $fields)) {
            $result = Bitrix\Catalog\ProductTable::getList([
                'select' => [
                    'ID',
                    'QUANTITY',
                ],
                'filter' => [
                    '=ID' => $productId
                ]
            ])->fetch();

            $originalQuantity = $result['QUANTITY'] ?? 0;
            if ($originalQuantity == 0 && $fields['QUANTITY'] > 1) {
                $connection = Application::getConnection();

                $sqlHelper = $connection->getSqlHelper();
                $productIdEscaped = $sqlHelper->forSql($productId);

                $sql = "SELECT RESULT_ID, FIELD_ID, USER_TEXT  FROM b_form_result_answer 
                        WHERE FORM_ID = 9 
                          AND FIELD_ID = 44
                          AND USER_TEXT = '{$productIdEscaped}'";

                $results = $connection->query($sql);

                $resultIds = [];
                while ($row = $results->fetch()) {
                    $resultIds[] = $row['RESULT_ID'];
                }

                if (empty($resultIds)) {
                    return true;
                }

                $resultIdsString = implode(',', $resultIds);

                $sql = "SELECT RESULT_ID, FIELD_ID, USER_TEXT
            FROM b_form_result_answer
            WHERE FORM_ID = 9
              AND FIELD_ID IN (40,42)
              AND RESULT_ID IN ({$resultIdsString})";

                $resultValues = $connection->query($sql);

                $userData = [];

                $productData = self::getProductData($productId, $iblockId);
                $recipients = [];

                while ($row = $resultValues->fetch()) {
                    //FIELD_ID
                    //42 email
                    //40 name
                    $userData[$row['RESULT_ID']][$row['FIELD_ID']] = $row['USER_TEXT'];
                }

                foreach ($userData as $k => $v) {
                    $name = $v['40'];
                    $email = $user['42'];
                    if (!$email) {
                        continue;
                    }
                    $recipients[] = [
                        "email" => $email,
                        "substitutions" => [
                            "Name" => $name,
                            "Tovar1" => $productData['name'],
                            "TovarLink" => $productData['url'],
                        ]
                    ];

                }

                $testerEmail = \Bitrix\Main\Config\Option::get("askaron.settings", "UF_UNSIDER_TEST_EMAIL");
                if (!empty($testerEmail)) {
                    $recipients = [];
                    $recipients[] = [
                        "email" => $testerEmail,
                        "substitutions" => [
                            "Name" => $name ?? 'test',
                            "Tovar1" => $productData['name'] ?? 'test',
                            "TovarLink" => $productData['url'] ?? 'test',
                        ]
                    ];
                }

                if (!empty($recipients)) {
                    $params = [
                        "message" => [
                            "recipients" => $recipients,
                            "template_id" => self::$templatePodZakaz
                        ],
                    ];

                    try {
                        $client = new Unisender\UniGoClient(self::$apiKeyGo, 'go2.unisender.ru');
                        $response = $client->emails()->send($params);
                        self::saveLog(date('c'));
                        self::saveLog('hasProductArrived');
                        self::saveLog(json_encode($response));
                        self::saveLog(json_encode($params));
                        self::saveLog('#######');
                    } catch (\Throwable $e) {
                        self::saveLog(date('c'));
                        self::saveLog('hasProductArrived');
                        self::saveLog($e->getMessage());
                        self::saveLog('#######');
                    }
                }


                return true;
            }
        }

        return true;

    }


    private static function saveLog($text)
    {
        file_put_contents(__DIR__ . '/' . date('Y-m-d') . '.log', $text . PHP_EOL, FILE_APPEND);
    }


    private static function getProductData($productId, $iblockId): array
    {
        $element = Bitrix\Iblock\ElementTable::getList([
            'filter' => ['=ID' => $productId, '=IBLOCK_ID' => $iblockId],
            'select' => ['ID', 'CODE', 'NAME']
        ])->fetch();
        $url = \CIBlock::GetArrayByID($iblockId, 'DETAIL_PAGE_URL');
        $url = str_replace('#SITE_DIR#', '/', $url);
        $url = str_replace('#ELEMENT_CODE#', $element['CODE'], $url);

        return [
            'url' => 'https://sappo.ru' . $url,
            'name' => $element['NAME']
        ];
    }

    private static function getProductsData($productIds, $iblockId): array
    {
        $elements = Bitrix\Iblock\ElementTable::getList([
            'filter' => ['ID' => $productIds, '=IBLOCK_ID' => $iblockId],
            'select' => ['ID', 'CODE', 'NAME']
        ])->fetchAll();

        $data = [];
        $url = \CIBlock::GetArrayByID($iblockId, 'DETAIL_PAGE_URL');
        foreach ($elements as $element) {
            $urlElement = $url;
            $urlElement = str_replace('#SITE_DIR#', '/', $urlElement);
            $urlElement = str_replace('#ELEMENT_CODE#', $element['CODE'], $urlElement);

            $data[] = [
                'url' => 'https://sappo.ru' . $urlElement,
                'name' => $element['NAME']
            ];
        }
        return $data;

    }

    public static function sendEmailByDroppedBasket()
    {

        $active = \Bitrix\Main\Config\Option::get("askaron.settings", "UF_ACTIVE_SEBDB");
        if ($active != 1) {
            return "UnisenderWrap::sendEmailByDroppedBasket();";
        }

        Loader::includeModule('catalog');
        Loader::includeModule('sale');

        self::clearDataEmailSendDroppedBasket();

        $fUserIdsHasEmail = self::getFUserIdsHasEmail();

        $users = \Bitrix\Sale\Internals\BasketTable::getList([
            "select" => [
                "FUSER_ID",
                "USER_ID" => "FUSER.USER.ID",
                "USER_LOGIN" => "FUSER.USER.LOGIN",
                "USER_NAME" => "FUSER.USER.NAME",
                "USER_LAST_NAME" => "FUSER.USER.LAST_NAME",
                "USER_EMAIL" => "FUSER.USER.EMAIL",
            ],
            "filter" => [
                "ORDER_ID" => null,
                "DELAY" => 'N',
                "!FUSER_ID" => $fUserIdsHasEmail,
                "!FUSER.USER.ID" => false // Исключаем записи, где нет связи с пользователем
            ],
            "group" => [
                "FUSER_ID",
                "FUSER.USER.ID",
                "FUSER.USER.LOGIN",
                "FUSER.USER.NAME",
                "FUSER.USER.LAST_NAME",
                "FUSER.USER.EMAIL"
            ]
        ])->fetchAll();

        $entity_data_class = self::getHighload();

        foreach ($users as $user) {
            $entity_data_class::add(['UF_FUSER_ID' => $user['FUSER_ID'], 'UF_TIMESTAMP_FIRST_SEND' => time()]);

            $name = $user['USER_LAST_NAME'] . ' ' . $user['USER_NAME'];
            $email = $user['USER_EMAIL'];
            if (!$email) {
                continue;
            }

            $productsData = \Bitrix\Sale\Internals\BasketTable::getList([
                "select" => [
                    "PRODUCT_ID",
                ],
                "filter" => [
                    "FUSER_ID" => $user['FUSER_ID'],
                    "ORDER_ID" => null,
                    "DELAY" => 'N',
                ],

            ])->fetchAll();

            $productIds = [];
            foreach ($productsData as $productData) {
                $productIds[] = $productData['PRODUCT_ID'];
            }

            if (count($productIds) == 0) {
                continue;
            }


            $productsData = self::getProductsData($productIds, 42);

            $recipients[] = [
                "email" => $email,
                "substitutions" => [
                    "Name" => $name,
                    "basket" => self::getBasketHtml($productsData),
                ]
            ];
        }

        $testerEmail = \Bitrix\Main\Config\Option::get("askaron.settings", "UF_UNSIDER_TEST_EMAIL");
        if (!empty($testerEmail)) {
            $recipients = [];
            $recipients[] = [
                "email" => $testerEmail,
                "substitutions" => [
                    "Name" => $name ?? 'test',
                    "basket" => self::getBasketHtml($productsData),
                ]
            ];
        }


        if (!empty($recipients)) {
            $params = [
                "message" => [
                    "recipients" => $recipients,
                    "template_id" => self::$templateBroshenaKorzina
                ],
            ];

            try {
                $client = new Unisender\UniGoClient(self::$apiKeyGo, 'go2.unisender.ru');
                $response = $client->emails()->send($params);
                self::saveLog(date('c'));
                self::saveLog('sendEmailByDroppedBasket');
                self::saveLog(json_encode($response));
                self::saveLog(json_encode($params));
                self::saveLog('#######');
            } catch (\Throwable $e) {
                self::saveLog(date('c'));
                self::saveLog('sendEmailByDroppedBasket');
                self::saveLog($e->getMessage());
                self::saveLog('#######');
            }
        }

        return "UnisenderWrap::sendEmailByDroppedBasket();";

    }

    public static function sendEmailByDroppedBasketAfter2Day()
    {

        $active = \Bitrix\Main\Config\Option::get("askaron.settings", "UF_ACTIVE_SEBDB");
        if ($active != 1) {
            return "UnisenderWrap::sendEmailByDroppedBasket();";
        }

        Loader::includeModule('catalog');
        Loader::includeModule('sale');

        self::clearDataEmailSendDroppedBasket();

        $highlightData = self::getFUserIdsHasEmailAndNotActiveAfter2Day();

        $fUserIdsHasEmail = $highlightData['fuserId'];
        $hilightIdByFuserIds = $highlightData['hl'];


        $users = \Bitrix\Sale\Internals\BasketTable::getList([
            "select" => [
                "FUSER_ID",
                "USER_ID" => "FUSER.USER.ID",
                "USER_LOGIN" => "FUSER.USER.LOGIN",
                "USER_NAME" => "FUSER.USER.NAME",
                "USER_LAST_NAME" => "FUSER.USER.LAST_NAME",
                "USER_EMAIL" => "FUSER.USER.EMAIL",
            ],
            "filter" => [
                "ORDER_ID" => null,
                "DELAY" => 'N',
                "FUSER_ID" => $fUserIdsHasEmail,
                "!FUSER.USER.ID" => false // Исключаем записи, где нет связи с пользователем
            ],
            "group" => [
                "FUSER_ID",
                "FUSER.USER.ID",
                "FUSER.USER.LOGIN",
                "FUSER.USER.NAME",
                "FUSER.USER.LAST_NAME",
                "FUSER.USER.EMAIL"
            ]
        ])->fetchAll();

        $entity_data_class = self::getHighload();


        foreach ($users as $user) {
            $primeryId = $hilightIdByFuserIds[$user['FUSER_ID']];
            $entity_data_class::update($primeryId, ['UF_SENT_SECOND_TIME' => 1]);

            $name = $user['USER_LAST_NAME'] . ' ' . $user['USER_NAME'];
            $email = $user['USER_EMAIL'];
            if (!$email) {
                continue;
            }

            $productsData = \Bitrix\Sale\Internals\BasketTable::getList([
                "select" => [
                    "PRODUCT_ID",
                ],
                "filter" => [
                    "FUSER_ID" => $user['FUSER_ID'],
                    "ORDER_ID" => null,
                    "DELAY" => 'N',
                ],

            ])->fetchAll();

            $productIds = [];
            foreach ($productsData as $productData) {
                $productIds[] = $productData['PRODUCT_ID'];
            }

            if (count($productIds) == 0) {
                continue;
            }


            $productsData = self::getProductsData($productIds, 42);

            $recipients[] = [
                "email" => $email,
                "substitutions" => [
                    "Name" => $name,
                    "basket" => self::getBasketHtml($productsData),
                ]
            ];
        }

        $testerEmail = \Bitrix\Main\Config\Option::get("askaron.settings", "UF_UNSIDER_TEST_EMAIL");
        if (!empty($testerEmail)) {
            $recipients = [];
            $recipients[] = [
                "email" => $testerEmail,
                "substitutions" => [
                    "Name" => $name ?? 'test',
                    "basket" => self::getBasketHtml($productsData),
                ]
            ];
        }


        if (!empty($recipients)) {
            $params = [
                "message" => [
                    "recipients" => $recipients,
                    "template_id" => self::$templateBroshenaKorzina2Day
                ],
            ];

            try {
                $client = new Unisender\UniGoClient(self::$apiKeyGo, 'go2.unisender.ru');
                $response = $client->emails()->send($params);
                self::saveLog(date('c'));
                self::saveLog('sendEmailByDroppedBasketAfter2Day');
                self::saveLog(json_encode($response));
                self::saveLog(json_encode($params));
                self::saveLog('#######');
            } catch (\Throwable $e) {
                self::saveLog(date('c'));
                self::saveLog('sendEmailByDroppedBasketAfter2Day');
                self::saveLog($e->getMessage());
                self::saveLog('#######');
            }
        }

        return "UnisenderWrap::sendEmailByDroppedBasket();";

    }

    public static function sendEmailByViewedProducts()
    {

        $active = \Bitrix\Main\Config\Option::get("askaron.settings", "UF_ACTIVE_VP");
        if ($active != 1) {
            return "UnisenderWrap::sendEmailByViewedProducts();";
        }

        Loader::includeModule('catalog');
        Loader::includeModule('sale');
        global $DB;

        $viewedProductsData = \Bitrix\Catalog\CatalogViewedProductTable::getList([
            "select" => [
                "FUSER_ID",
                "PRODUCT_ID",
                "USER_NAME" => "FUSER.USER.NAME",
                "USER_LAST_NAME" => "FUSER.USER.LAST_NAME",
                "USER_EMAIL" => "FUSER.USER.EMAIL",
            ],
            "filter" => [
                "SITE_ID" => 's1',
                "!FUSER.USER.ID" => false
            ]
        ])->fetchAll();


        $fuserIds = [];
        foreach ($viewedProductsData as $viewedProductData) {
            $fuserIds[$viewedProductData['FUSER_ID']] = $viewedProductData['FUSER_ID'];
        }

        $productsInBasket = \Bitrix\Sale\Internals\BasketTable::getList([
            "select" => [
                "FUSER_ID",
                "PRODUCT_ID",
                "USER_NAME" => "FUSER.USER.NAME",
                "USER_LAST_NAME" => "FUSER.USER.LAST_NAME",
                "USER_EMAIL" => "FUSER.USER.EMAIL",
            ],
            "filter" => [
                "!ORDER_ID" => null,
                "FUSER_ID" => $fuserIds
            ]
        ])->fetchAll();


        $firstFlat = array_map('json_encode', $viewedProductsData);
        $secondFlat = array_map('json_encode', $productsInBasket);

        $diff = array_diff($firstFlat, $secondFlat);

        $resultDiff = array_map('json_decode', $diff, array_fill(0, count($diff), true));

        $users = [];
        foreach ($resultDiff as $element) {
            $users[$element['FUSER_ID']]['productsId'][] = $element['PRODUCT_ID'];
            $users[$element['FUSER_ID']]['name'] = $element['USER_NAME'];
            $users[$element['FUSER_ID']]['last_name'] = $element['USER_LAST_NAME'];
            $users[$element['FUSER_ID']]['email'] = $element['USER_EMAIL'];
        }

        foreach ($users as $fuserId => $user) {
            $name = $user['last_name'] . ' ' . $user['name'];
            $email = $user['email'];
            if (!$email) {
                continue;
            }

            $productsData = self::getProductsData($user['productsId'], 42);

            $recipients[] = [
                "email" => $email,
                "substitutions" => [
                    "Name" => $name,
                    "basket" => self::getBasketHtml($productsData),
                ]
            ];
        }

        $testerEmail = \Bitrix\Main\Config\Option::get("askaron.settings", "UF_UNSIDER_TEST_EMAIL");
        if (!empty($testerEmail)) {
            $recipients = [];
            $recipients[] = [
                "email" => $testerEmail,
                "substitutions" => [
                    "Name" => $name ?? 'test',
                    "basket" => self::getBasketHtml($productsData),
                ]
            ];
        }


        if (!empty($recipients)) {
            $params = [
                "message" => [
                    "recipients" => $recipients,
                    "template_id" => self::$templateBroshenieTovary
                ],
            ];

            try {
                $client = new Unisender\UniGoClient(self::$apiKeyGo, 'go2.unisender.ru');
                $response = $client->emails()->send($params);
                self::saveLog(date('c'));
                self::saveLog('sendEmailByViewedProducts');
                self::saveLog(json_encode($response));
                self::saveLog(json_encode($params));
                self::saveLog('#######');

                $DB->Query('DELETE FROM b_catalog_viewed_product WHERE FUSER_ID=' . $fuserId, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
            } catch (\Throwable $e) {
                self::saveLog(date('c'));
                self::saveLog('sendEmailByViewedProducts');
                self::saveLog($e->getMessage());
                self::saveLog('#######');
            }
        }

        return "UnisenderWrap::sendEmailByViewedProducts();";

    }

    private static function clearDataEmailSendDroppedBasket()
    {
        $entity_data_class = self::getHighload();

        $fUserIdsData = $entity_data_class::getList([
            "select" => ["UF_FUSER_ID", 'ID'],
        ])->fetchAll();

        $fUserIdsSend = [];
        $rowIdByfUserId = [];

        foreach ($fUserIdsData as $fUserIdData) {
            $fUserIdsSend[] = $fUserIdData['UF_FUSER_ID'];
            $rowIdByfUserId[$fUserIdData['UF_FUSER_ID']] = $fUserIdData['ID'];
        }


        $fUserIdsDataFromBasket = \Bitrix\Sale\Internals\BasketTable::getList([
            "select" => [
                "FUSER_ID",
            ],
            "filter" => [
                "ORDER_ID" => null,
                "DELAY" => 'N',
                "FUSER_ID" => $fUserIdsSend,
            ],
            "group" => [
                "FUSER_ID",
            ]
        ])->fetchAll();


        $fUserIdsInBasket = [];
        foreach ($fUserIdsDataFromBasket as $fUserIdDataFromBasket) {
            $fUserIdsInBasket[] = $fUserIdDataFromBasket['FUSER_ID'];
        }


        $dropData = [];
        foreach ($fUserIdsSend as $fUserIdSend) {
            if (!in_array($fUserIdSend, $fUserIdsInBasket)) {
                $dropData[] = $fUserIdSend;
            }
        }

        foreach ($dropData as $fUserId) {
            $entity_data_class::Delete($rowIdByfUserId[$fUserId]);
        }

    }

    private static function getFUserIdsHasEmail()
    {

        $entity_data_class = self::getHighload();

        $fUserIdsData = $entity_data_class::getList([
            "select" => ["UF_FUSER_ID", 'ID', 'UF_TIMESTAMP_FIRST_SEND'],
        ])->fetchAll();

        $fUserIdsSend = [];

        foreach ($fUserIdsData as $fUserIdData) {
            $fUserIdsSend[] = $fUserIdData['UF_FUSER_ID'];
        }
        return $fUserIdsSend;

    }

    private static function getFUserIdsHasEmailAndNotActiveAfter2Day()
    {

        $entity_data_class = self::getHighload();

        $fUserIdsData = $entity_data_class::getList([
            "select" => ["UF_FUSER_ID", 'ID', 'UF_TIMESTAMP_FIRST_SEND', 'UF_SENT_SECOND_TIME'],
            "filter" => ["!UF_SENT_SECOND_TIME" => 1],

        ])->fetchAll();

        $fUserIdsSend = [];
        $highlightIdByFuserIds = [];

        $currentDate = new DateTimeImmutable();
        foreach ($fUserIdsData as $fUserIdData) {
            $dateFirstsend = (new DateTimeImmutable())->setTimestamp((int)$fUserIdData['UF_TIMESTAMP_FIRST_SEND']);
            $interval = $currentDate->diff($dateFirstsend);
            if ($interval->days >= 2) {
                $fUserIdsSend[] = $fUserIdData['UF_FUSER_ID'];
                $hilightIdByFuserIds[$fUserIdData['UF_FUSER_ID']] = $fUserIdData['ID'];
            }

        }

        return ['fuserId' => $fUserIdsSend, 'hl' => $hilightIdByFuserIds];

    }


    private static function getHighload()
    {
        Loader::IncludeModule("highloadblock");

        $hlbl = 6;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        return $entity_data_class;
    }

    private static function getBasketHtml($productsData)
    {
        $result = '';

        foreach ($productsData as $productData) {

            $result .= '   
        <table class="paragraph_block block-2" width="100%" border="0" cellpadding="0"
        cellspacing="0" role="presentation"
        style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
            <tr>
                <td class="pad"
                style="padding-bottom:10px;padding-left:30px;padding-right:30px;padding-top:10px">
                <div style="color:#fff;direction:ltr;font-family:\'Open Sans\',\'Helvetica Neue\',Helvetica,Arial,sans-serif;font-size:18px;font-weight:700;letter-spacing:0;line-height:200%;text-align:left;mso-line-height-alt:36px">
                <p style="margin:0">' . $productData['name'] . '</p></div>
                </td>
            </tr>
        </table>
        <table class="paragraph_block block-6" width="100%" border="0" cellpadding="0"
        cellspacing="0" role="presentation"
        style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
            <tr>
                <td class="pad"
                style="padding-bottom:10px;padding-left:30px;padding-right:30px;padding-top:10px">
                <div style="color:#fff;direction:ltr;font-family:\'Open Sans\',\'Helvetica Neue\',Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;letter-spacing:0;line-height:200%;text-align:left;mso-line-height-alt:32px">
                <p style="margin:0"><a href="' . $productData['url'] . '" target="_blank" style="text-decoration: underline; color: #0068a5;">Ссылка
                на страницу товара</a></p></div>
                </td>
            </tr>
        </table>';
        }

        return $result;
    }


}
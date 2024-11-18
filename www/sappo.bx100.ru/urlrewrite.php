<?php
$arUrlRewrite=array (
  137 => 
  array (
    'CONDITION' => '#^/ajax/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/starlabs.tools/ajax.php',
    'SORT' => 13,
  ),
  31 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/([\\w\\d\\-]+)?(/)?(([\\w\\d\\-]+)(/)?)?#',
    'RULE' => 'REQUEST_OBJECT=$1&METHOD=$4',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/docs/pub/(?<hash>[0-9a-f]{32})/(?<action>[0-9a-zA-Z]+)/\\?#',
    'RULE' => 'hash=$1&action=$2&',
    'ID' => 'bitrix:disk.external.link',
    'PATH' => '/docs/pub/index.php',
    'SORT' => 100,
  ),
  103 => 
  array (
    'CONDITION' => '#^/site_dz/pub/calendar-sharing/([0-9a-zA-Z]+)/?([^/]*)#',
    'RULE' => 'hash=$1',
    'ID' => 'bitrix:calendar.pub.sharing',
    'PATH' => '/site_dz/pub/calendar_sharing.php',
    'SORT' => 100,
  ),
  87 => 
  array (
    'CONDITION' => '#^/pub/calendar-event/([0-9]+)/([0-9a-zA-Z]+)/?([^/]*)#',
    'RULE' => 'event_id=$1&hash=$2',
    'ID' => 'bitrix:calendar.pub.event',
    'PATH' => '/pub/calendar_event.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^/disk/(?<action>[0-9a-zA-Z]+)/(?<fileId>[0-9]+)/\\?#',
    'RULE' => 'action=$1&fileId=$2&',
    'ID' => 'bitrix:disk.services',
    'PATH' => '/bitrix/services/disk/index.php',
    'SORT' => 100,
  ),
  102 => 
  array (
    'CONDITION' => '#^/pub/calendar-sharing/([0-9a-zA-Z]+)/?([^/]*)#',
    'RULE' => 'hash=$1',
    'ID' => 'bitrix:calendar.pub.sharing',
    'PATH' => '/pub/calendar_sharing.php',
    'SORT' => 100,
  ),
  19 => 
  array (
    'CONDITION' => '#^\\/?\\/mobile/web_mobile_component\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobile/webcomponent.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/pub/pay/([\\w\\W]+)/([0-9a-zA-Z]+)/([^/]*)#',
    'RULE' => 'account_number=$1&hash=$2',
    'ID' => NULL,
    'PATH' => '/pub/payment.php',
    'SORT' => 100,
  ),
  16 => 
  array (
    'CONDITION' => '#^\\/?\\/mobile/mobile_component\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobile/jscomponent.php',
    'SORT' => 100,
  ),
  12 => 
  array (
    'CONDITION' => '#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  20 => 
  array (
    'CONDITION' => '#^/mobile/disk/(?<hash>[0-9]+)/download#',
    'RULE' => 'download=1&objectId=$1',
    'ID' => 'bitrix:mobile.disk.file.detail',
    'PATH' => '/mobile/disk/index.php',
    'SORT' => 100,
  ),
  109 => 
  array (
    'CONDITION' => '#^/site_dz/pub/payment-slip/([\\w\\W]+)/#',
    'RULE' => 'signed_payment_id=$1',
    'ID' => 'bitrix:salescenter.pub.payment.slip',
    'PATH' => '/site_dz/pub/payment_slip.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/video([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1&videoconf',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  110 => 
  array (
    'CONDITION' => '#^/timeman/login-history/([0-9]+)/.*#',
    'RULE' => 'user=$1',
    'ID' => 'bitrix:intranet.user.login.history',
    'PATH' => '/timeman/login-history/index.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/stssync/contacts_extranet_emp/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/contacts_extranet_emp/index.php',
    'SORT' => 100,
  ),
  95 => 
  array (
    'CONDITION' => '#^/sale-new/filter/(.+?)/apply/#',
    'RULE' => 'SMART_FILTER_PATH=$1',
    'ID' => '',
    'PATH' => '/sale-new/index.php',
    'SORT' => 100,
  ),
  18 => 
  array (
    'CONDITION' => '#^\\/?\\/mobile/jn/(.*)\\/(.*)\\/.*#',
    'RULE' => 'componentName=$2&namespace=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobile/jscomponent.php',
    'SORT' => 100,
  ),
  32 => 
  array (
    'CONDITION' => '#^/personal/history-of-orders/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.order',
    'PATH' => '/personal/history-of-orders/index.php',
    'SORT' => 100,
  ),
  108 => 
  array (
    'CONDITION' => '#^/pub/payment-slip/([\\w\\W]+)/#',
    'RULE' => 'signed_payment_id=$1',
    'ID' => 'bitrix:salescenter.pub.payment.slip',
    'PATH' => '/pub/payment_slip.php',
    'SORT' => 100,
  ),
  27 => 
  array (
    'CONDITION' => '#^/marketplace/configuration/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.configuration',
    'PATH' => '/marketplace/configuration/index.php',
    'SORT' => 100,
  ),
  21 => 
  array (
    'CONDITION' => '#^\\/?\\/mobileapp/jn\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobileapp/jn.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/stssync/contacts_extranet/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/contacts_extranet/index.php',
    'SORT' => 100,
  ),
  10 => 
  array (
    'CONDITION' => '#^/stssync/calendar_extranet/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/calendar_extranet/index.php',
    'SORT' => 100,
  ),
  28 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  101 => 
  array (
    'CONDITION' => '#^/marketing/master-yandex/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/marketing/master-yandex.php',
    'SORT' => 100,
  ),
  9 => 
  array (
    'CONDITION' => '#^/stssync/tasks_extranet/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/tasks_extranet/index.php',
    'SORT' => 100,
  ),
  17 => 
  array (
    'CONDITION' => '#^\\/?\\/mobile/jn\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobile/jscomponent.php',
    'SORT' => 100,
  ),
  88 => 
  array (
    'CONDITION' => '#^/shop/import/instagram/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.import.instagram',
    'PATH' => '/shop/import/instagram/index.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/stssync/contacts_crm/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/contacts_crm/index.php',
    'SORT' => 100,
  ),
  13 => 
  array (
    'CONDITION' => '#^/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  24 => 
  array (
    'CONDITION' => '#^/marketplace/local/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace.localapp',
    'PATH' => '/marketplace/local/index.php',
    'SORT' => 100,
  ),
  48 => 
  array (
    'CONDITION' => '#^/company/partners/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/partners/index.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^/stssync/calendar/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/calendar/index.php',
    'SORT' => 100,
  ),
  14 => 
  array (
    'CONDITION' => '#^/stssync/contacts/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/contacts/index.php',
    'SORT' => 100,
  ),
  26 => 
  array (
    'CONDITION' => '#^/marketplace/hook/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.hook',
    'PATH' => '/marketplace/hook/index.php',
    'SORT' => 100,
  ),
  49 => 
  array (
    'CONDITION' => '#^/company/licenses/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/licenses/index.php',
    'SORT' => 100,
  ),
  82 => 
  array (
    'CONDITION' => '#^/test/teststores/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store',
    'PATH' => '/test/teststores/index.php',
    'SORT' => 100,
  ),
  25 => 
  array (
    'CONDITION' => '#^/marketplace/app/#',
    'RULE' => '',
    'ID' => 'bitrix:app.layout',
    'PATH' => '/marketplace/app/index.php',
    'SORT' => 100,
  ),
  135 => 
  array (
    'CONDITION' => '#^/company/vacancy/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/vacancy/index.php',
    'SORT' => 100,
  ),
  139 => 
  array (
    'CONDITION' => '#^/contacts/stores/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store',
    'PATH' => '/contacts/stores/index.php',
    'SORT' => 100,
  ),
  89 => 
  array (
    'CONDITION' => '#^/calendar/rooms/#',
    'RULE' => '',
    'ID' => 'bitrix:calender',
    'PATH' => '/calendar/rooms.php',
    'SORT' => 100,
  ),
  34 => 
  array (
    'CONDITION' => '#^/personal/order/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.order',
    'PATH' => '/personal/order/index.php',
    'SORT' => 100,
  ),
  111 => 
  array (
    'CONDITION' => '#^/agent_contract/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.agent.contract.controller',
    'PATH' => '/agent_contract/index.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/crm/invoicing/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/crm/invoicing/index.php',
    'SORT' => 100,
  ),
  40 => 
  array (
    'CONDITION' => '#^/company/staff/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/staff/index.php',
    'SORT' => 100,
  ),
  29 => 
  array (
    'CONDITION' => '#^/stssync/tasks/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/tasks/index.php',
    'SORT' => 100,
  ),
  106 => 
  array (
    'CONDITION' => '#^/shop/terminal/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.terminal.payment.controller',
    'PATH' => '/terminal/index.php',
    'SORT' => 100,
  ),
  51 => 
  array (
    'CONDITION' => '#^/company/docs/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/docs/index.php',
    'SORT' => 100,
  ),
  15 => 
  array (
    'CONDITION' => '#^/mobile/webdav#',
    'RULE' => '',
    'ID' => 'bitrix:mobile.webdav.file.list',
    'PATH' => '/mobile/webdav/index.php',
    'SORT' => 100,
  ),
  100 => 
  array (
    'CONDITION' => '#^/shop/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.productcard.controller',
    'PATH' => '/shop/catalog/index.php',
    'SORT' => 100,
  ),
  107 => 
  array (
    'CONDITION' => '#^/crm/terminal/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.terminal.payment.controller',
    'PATH' => '/terminal/index.php',
    'SORT' => 100,
  ),
  115 => 
  array (
    'CONDITION' => '#^/bi/dashboard/#',
    'RULE' => '',
    'ID' => 'bitrix:biconnector.apachesuperset.dashboard.controller',
    'PATH' => '/bi/dashboard/index.php',
    'SORT' => 100,
  ),
  91 => 
  array (
    'CONDITION' => '#^/sharebasket/#',
    'RULE' => '',
    'ID' => 'aspro:basket.share.max',
    'PATH' => '/sharebasket/index.php',
    'SORT' => 100,
  ),
  23 => 
  array (
    'CONDITION' => '#^/marketplace/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace',
    'PATH' => '/marketplace/index.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/\\.well-known#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/groupdav.php',
    'SORT' => 100,
  ),
  30 => 
  array (
    'CONDITION' => '#^/conference/#',
    'RULE' => '',
    'ID' => 'bitrix:im.conference.center',
    'PATH' => '/conference/index.php',
    'SORT' => 100,
  ),
  47 => 
  array (
    'CONDITION' => '#^/lookbooks/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/lookbooks/index.php',
    'SORT' => 100,
  ),
  73 => 
  array (
    'CONDITION' => '#^/personal/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.section',
    'PATH' => '/personal/index.php',
    'SORT' => 100,
  ),
  38 => 
  array (
    'CONDITION' => '#^/projects/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/projects/index.php',
    'SORT' => 100,
  ),
  42 => 
  array (
    'CONDITION' => '#^/services/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/services/index.php',
    'SORT' => 100,
  ),
  85 => 
  array (
    'CONDITION' => '#^/crm/type/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.router',
    'PATH' => '/crm/type/index.php',
    'SORT' => 100,
  ),
  44 => 
  array (
    'CONDITION' => '#^/landings/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/landings/index.php',
    'SORT' => 100,
  ),
  105 => 
  array (
    'CONDITION' => '#^/terminal/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.terminal.payment.controller',
    'PATH' => '/terminal/index.php',
    'SORT' => 100,
  ),
  133 => 
  array (
    'CONDITION' => '#^/info/faq/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/info/faq/index.php',
    'SORT' => 100,
  ),
  136 => 
  array (
    'CONDITION' => '#^/contacts/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/contacts/page_contacts_2.php',
    'SORT' => 100,
  ),
  127 => 
  array (
    'CONDITION' => '#^/sale-new/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/sale-new/index.php',
    'SORT' => 100,
  ),
  132 => 
  array (
    'CONDITION' => '#^/product/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  131 => 
  array (
    'CONDITION' => '#^/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  104 => 
  array (
    'CONDITION' => '#^/market/#',
    'RULE' => '',
    'ID' => 'bitrix:market',
    'PATH' => '/market/index.php',
    'SORT' => 100,
  ),
  114 => 
  array (
    'CONDITION' => '#^/spaces/#',
    'RULE' => '',
    'ID' => 'bitrix:socialnetwork.spaces',
    'PATH' => '/spaces/index.php',
    'SORT' => 100,
  ),
  116 => 
  array (
    'CONDITION' => '#^/brands/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/brands/index.php',
    'SORT' => 100,
  ),
  22 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
  36 => 
  array (
    'CONDITION' => '#^/auth/#',
    'RULE' => '',
    'ID' => 'aspro:auth.max',
    'PATH' => '/auth/index.php',
    'SORT' => 100,
  ),
  45 => 
  array (
    'CONDITION' => '#^/sale/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/sale/index.php',
    'SORT' => 100,
  ),
  86 => 
  array (
    'CONDITION' => '#^/page/#',
    'RULE' => '',
    'ID' => 'bitrix:intranet.customsection',
    'PATH' => '/page/index.php',
    'SORT' => 100,
  ),
  112 => 
  array (
    'CONDITION' => '#^/sign/#',
    'RULE' => '',
    'ID' => 'bitrix:sign.start',
    'PATH' => '/sign/index.php',
    'SORT' => 100,
  ),
  129 => 
  array (
    'CONDITION' => '#^/blog/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/blog/index.php',
    'SORT' => 100,
  ),
  138 => 
  array (
    'CONDITION' => '#^/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/news/index.php',
    'SORT' => 100,
  ),
);

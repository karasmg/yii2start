<?php

session_start();
$avalible_langs = array(
    'ru' =>'Русский',
    'ua'=>'Українська',
    //'en'=>'English',
    //'lv' => 'Latviski',
);

$lang_rules			= implode("|", array_keys($avalible_langs));
$article_rules		= '[a-zA-Z0-9-]{1,}\.html';
$categ_rules		= '(?!^admin.+|^sitemap.html|^sitemap.xml|^sections|.+\.html).+';
$page_rules			= '[2-9]{1,}|[0-9]{2,}';
$categ_zveno_rules	= '(?!^admin.+|^sitemap.html|^sitemap.xml|^sections).+';
$mobile_sub_categ	= '';
$firts_site_page	= '/';
$parent_host		= 'http://local.kred/';
$layout_site_path	= 'layouts';

/*
require_once dirname(__FILE__).'/Mobile_Detect.php';
$detector = new Mobile_Detect;
if ( $detector->isMobile() ) {

	$firts_site_page	= '/m/';
	$mobile_sub_categ	= 'm/';
	$layout_site_path	= 'layouts_mobile';
}
*/


$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'bootstrap' => ['log'],

    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'umKB1XUGrEpkcP6qNQtnVrFYpZR8NoVq',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'yii\components\WebUser',
            'enableAutoLogin' => false,
            'loginUrl'		=>'site/login',
            'returnUrl'		=>'site/index',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager'=>array(
            'enablePrettyUrl' => true,
            'showScriptName'	=> false,
            'rules'=>array(
                //Раскомментировать для использования gii

                'gii'=>'gii',
                'gii/<controller:\w+>'=>'gii/<controller>',
                'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',

                '<_lang:('.$lang_rules.')>/admin'													=> 'adminMain/index',
                'admin'																				=> 'adminMain/index',

                '<_lang:('.$lang_rules.')>/admin/Main'												=> 'adminMain/index',
                'admin/Main'																		=> 'adminMain/index',				//Админ панель

                '<_lang:('.$lang_rules.')>/admin/<controller:>/<action:>/<page:'.$page_rules.'>'	=> 'admin<controller>/<action>',	//Админ панель
                'admin/<controller:>/<action:>/<page:'.$page_rules.'>'								=> 'admin<controller>/<action>',

                '<_lang:('.$lang_rules.')>/admin/<controller:>/<action:>/id/<id:\d+>/page/<page:\d+>'	=> 'admin<controller>/<action>',	//Админ панель
                'admin/<controller:>/<action:>/id/<id:\d+>/page/<page:\d+>'								=> 'admin<controller>/<action>',

                '<_lang:('.$lang_rules.')>/admin/<controller:>/<action:>'							=> 'admin<controller>/<action>',	//Админ панель
                'admin/<controller:>/<action:>'														=> 'admin<controller>/<action>',

                '<_lang:('.$lang_rules.')>/admin/Main/hystory/<table:>/<row_id:>'					=> 'adminMain/hystory',	//Админ панель
                'admin/Main/hystory/<table:>/<row_id:>'												=> 'adminMain/hystory',


                'get_requests.php'						=> 'request/showrequests', //Получение списка запросов для 1С
                'pull_requests.php'						=> 'request/pullrequest', //Запросы от 1С
                'pull_requests_test.php'				=> 'request/pullrequesttest', //тестирование
                'ubki_anketa'							=> 'ubki/outputdata', //Вывод анкеты

                'mathcalcs'								=> 'payment/testpayclass', //Вывод анкеты
                'cardpay'								=> 'payment/testcardpay', //Перевод на карту

                '<_lang:('.$lang_rules.')>/payments/<_action:>/<_id:\d+>'	=> 'payment/<_action>',
                'payments/<_action:>/<_id:\d+>'								=> 'site/redirect',
                '<_lang:('.$lang_rules.')>/payments/<_action:>'				=> 'payment/<_action>',
                'payments/<_action:>'										=> 'site/redirect',


                '<_lang:('.$lang_rules.')>'				=> 'site/index',

                '<_lang:('.$lang_rules.')>/register'	=> 'site/registration',
                'register'								=> 'site/redirect',

                '<_lang:('.$lang_rules.')>/login'		=> 'site/login',
                'login'									=> 'site/redirect',

                '<_lang:('.$lang_rules.')>/iframe'		=> 'iframe/iframe',
                'iframe'								=> 'site/redirect',

                '<_lang:('.$lang_rules.')>/passrecovery'		=> 'site/passrecovery',
                'login'									=> 'site/redirect',

                '<_lang:('.$lang_rules.')>/personalpage/cards/<_action:>/<_id:\d+>'	=> 'cards/<_action>',
                'personalpage/cards/<_action:>/<_id:\d+>'							=> 'site/redirect',

                '<_lang:('.$lang_rules.')>/personalpage/loan/<_action:>/<_number:>'	=> 'loan/<_action>',
                'personalpage/loan/<_action:>/<_number:>'							=> 'site/redirect',


                '<_lang:('.$lang_rules.')>/personalpage/cards/<_action:>'			=> 'cards/<_action>',
                'personalpage/cards/<_action:>'										=> 'site/redirect',

                '<_lang:('.$lang_rules.')>/personalpage/cards'						=> 'cards',
                'personalpage/cards'												=> 'site/redirect',

                '<_lang:('.$lang_rules.')>/personalpage/loan/<_action:>'	=> 'loan/<_action>',
                'personalpage/loan/<_action:>'							=> 'site/redirect',


                '<_lang:('.$lang_rules.')>/personalpage/loan/creditstatus/<_action:>'	=> 'loan/<_action>',
                'personalpage/loan/creditstatus/<_action:>'							=> 'site/redirect',

                '<_lang:('.$lang_rules.')>/test'		=> 'site/test',
                'test'								=> 'site/redirect',


                '<_lang:('.$lang_rules.')>/logout'		=> 'Isregistered/logout',
                'logout'								=> 'site/redirect',

                '<_lang:('.$lang_rules.')>/personalpage'=> 'Isregistered/personalpage',
                'personalpage'							=> 'site/redirect',

                '<_lang:('.$lang_rules.')>/isregistered/changepassword/pass/needtochange'=> 'Isregistered/changepassword',
                '<_lang:('.$lang_rules.')>/isregistered/changepassword'=> 'Isregistered/changepassword',
                'changepassword'							=> 'site/redirect',

                '<_lang:('.$lang_rules.')>/anketa/<page:\d+>'	=> 'Isregistered/anketa',
                '<_lang:('.$lang_rules.')>/anketa'		=> 'Isregistered/anketa',
                'anketa'								=> 'site/redirect',



                $mobile_sub_categ.'<_lang:('.$lang_rules.')>/locations/<_alias:>'	=> 'lombard/item',	//Вывод отделения
                $mobile_sub_categ.'locations/<_alias:>'							=> 'site/redirect',
                $mobile_sub_categ.'<_lang:('.$lang_rules.')>/locations'	=> 'lombard/locations',
                $mobile_sub_categ.'locations'								=> 'site/redirect',

                $mobile_sub_categ.'<_lang:('.$lang_rules.')>/news/<page:\d+>'	=>	'news/index', //Вывод новости
                $mobile_sub_categ.'news/<page:\d+>'								=> 'site/redirect',
                $mobile_sub_categ.'<_lang:('.$lang_rules.')>/news/<_alias:>'	=>	'news/item', //Вывод новости
                $mobile_sub_categ.'news/<_alias:>'								=> 'site/redirect',
                $mobile_sub_categ.'<_lang:('.$lang_rules.')>/news'				=>	'news/index', //Вывод новостей
                $mobile_sub_categ.'news'										=> 'site/redirect',

                $mobile_sub_categ.'<_lang:('.$lang_rules.')>/actions/<page:\d+>'	=>	'news/actions', //Вывод акций
                $mobile_sub_categ.'actions/<page:\d+>'								=> 'site/redirect',
                $mobile_sub_categ.'<_lang:('.$lang_rules.')>/actions/<_alias:>'	=>	'news/actionsitem', //Вывод акций
                $mobile_sub_categ.'actions/<_alias:>'								=> 'site/redirect',
                $mobile_sub_categ.'<_lang:('.$lang_rules.')>/actions'				=>	'news/actions', //Вывод акций
                $mobile_sub_categ.'actions'										=> 'site/redirect',

                $mobile_sub_categ.'<_lang:('.$lang_rules.')>/guestbook/<_page:'.$page_rules.'>'				=> 'guestbook/guestbook',		//Голосование
                $mobile_sub_categ.'guestbook/<_page:'.$page_rules.'>'										=> 'site/redirect',
                $mobile_sub_categ.'<_lang:('.$lang_rules.')>/guestbook/'									=> 'guestbook/guestbook',		//Голосование
                $mobile_sub_categ.'guestbook/'																=> 'site/redirect',

                $mobile_sub_categ.'<_lang:('.$lang_rules.')>/<_alias:>'	=>	'site/article', //Материал
                $mobile_sub_categ.'<_alias:>'							=> 'site/redirect',

            ),
        ),




        'db' => require(__DIR__ . '/db.php'),
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;

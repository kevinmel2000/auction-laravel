<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rus = $this->countriesRU();
        $ens = $this->countriesEN();
        $countries = [];

        foreach ($ens as $code => $item) {
            $countries[] = '("' . $code . '", "' . $item . '", "' . $rus[$code] . '")';
        }

        DB::statement('REPLACE INTO countries (code, en, ru) VALUES ' . implode(', ', $countries));
    }

    private function countriesEN()
    {
        return [
            'AF' => 'Afghanistan',
            'AX' => 'Åland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AG' => 'Antigua & Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia & Herzegovina',
            'BW' => 'Botswana',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'VG' => 'British Virgin Islands',
            'BN' => 'Brunei',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'IC' => 'Canary Islands',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo - Brazzaville',
            'CD' => 'Congo - Kinshasa',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Côte d’Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DG' => 'Diego Garcia',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong SAR China',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Laos',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macau SAR China',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar (Burma)',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'KP' => 'North Korea',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territories',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn Islands',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Réunion',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'RW' => 'Rwanda',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'São Tomé & Príncipe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia & South Sandwich Islands',
            'KR' => 'South Korea',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SH' => 'St. Helena',
            'KN' => 'St. Kitts & Nevis',
            'LC' => 'St. Lucia',
            'PM' => 'St. Pierre & Miquelon',
            'VC' => 'St. Vincent & Grenadines',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard & Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syria',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad & Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks & Caicos Islands',
            'TV' => 'Tuvalu',
            'UM' => 'U.S. Outlying Islands',
            'VI' => 'U.S. Virgin Islands',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VA' => 'Vatican City',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'WF' => 'Wallis & Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        ];
    }

    private function countriesRU()
    {
        return [
            'AU' => 'Австралия',
            'AT' => 'Австрия',
            'AZ' => 'Азербайджан',
            'AX' => 'Аландские о-ва',
            'AL' => 'Албания',
            'DZ' => 'Алжир',
            'AS' => 'Американское Самоа',
            'AI' => 'Ангилья',
            'AO' => 'Ангола',
            'AD' => 'Андорра',
            'AG' => 'Антигуа и Барбуда',
            'AR' => 'Аргентина',
            'AM' => 'Армения',
            'AW' => 'Аруба',
            'AF' => 'Афганистан',
            'BS' => 'Багамские о-ва',
            'BD' => 'Бангладеш',
            'BB' => 'Барбадос',
            'BH' => 'Бахрейн',
            'BY' => 'Беларусь',
            'BZ' => 'Белиз',
            'BE' => 'Бельгия',
            'BJ' => 'Бенин',
            'BM' => 'Бермудские о-ва',
            'BG' => 'Болгария',
            'BO' => 'Боливия',
            'BA' => 'Босния и Герцеговина',
            'BW' => 'Ботсвана',
            'BR' => 'Бразилия',
            'IO' => 'Британская территория в Индийском океане',
            'BN' => 'Бруней-Даруссалам',
            'BF' => 'Буркина-Фасо',
            'BI' => 'Бурунди',
            'BT' => 'Бутан',
            'VU' => 'Вануату',
            'VA' => 'Ватикан',
            'GB' => 'Великобритания',
            'HU' => 'Венгрия',
            'VE' => 'Венесуэла',
            'VG' => 'Виргинские о-ва (Британские)',
            'VI' => 'Виргинские о-ва (США)',
            'UM' => 'Внешние малые о-ва (США)',
            'TL' => 'Восточный Тимор',
            'VN' => 'Вьетнам',
            'GA' => 'Габон',
            'HT' => 'Гаити',
            'GY' => 'Гайана',
            'GM' => 'Гамбия',
            'GH' => 'Гана',
            'GP' => 'Гваделупа',
            'GT' => 'Гватемала',
            'GN' => 'Гвинея',
            'GW' => 'Гвинея-Бисау',
            'DE' => 'Германия',
            'GI' => 'Гибралтар',
            'HN' => 'Гондурас',
            'HK' => 'Гонконг (особый район)',
            'GD' => 'Гренада',
            'GL' => 'Гренландия',
            'GR' => 'Греция',
            'GE' => 'Грузия',
            'GU' => 'Гуам',
            'DK' => 'Дания',
            'DJ' => 'Джибути',
            'DG' => 'Диего-Гарсия',
            'DM' => 'Доминика',
            'DO' => 'Доминиканская Республика',
            'EG' => 'Египет',
            'ZM' => 'Замбия',
            'EH' => 'Западная Сахара',
            'ZW' => 'Зимбабве',
            'IL' => 'Израиль',
            'IN' => 'Индия',
            'ID' => 'Индонезия',
            'JO' => 'Иордания',
            'IQ' => 'Ирак',
            'IR' => 'Иран',
            'IE' => 'Ирландия',
            'IS' => 'Исландия',
            'ES' => 'Испания',
            'IT' => 'Италия',
            'YE' => 'Йемен',
            'CV' => 'Кабо-Верде',
            'KZ' => 'Казахстан',
            'KY' => 'Каймановы о-ва',
            'KH' => 'Камбоджа',
            'CM' => 'Камерун',
            'CA' => 'Канада',
            'IC' => 'Канарские о-ва',
            'QA' => 'Катар',
            'KE' => 'Кения',
            'CY' => 'Кипр',
            'KG' => 'Киргизия',
            'KI' => 'Кирибати',
            'CN' => 'Китай',
            'KP' => 'КНДР',
            'CC' => 'Кокосовые о-ва',
            'CO' => 'Колумбия',
            'KM' => 'Коморские о-ва',
            'CG' => 'Конго - Браззавиль',
            'CD' => 'Конго - Киншаса',
            'CR' => 'Коста-Рика',
            'CI' => 'Кот-д’Ивуар',
            'CU' => 'Куба',
            'KW' => 'Кувейт',
            'LA' => 'Лаос',
            'LV' => 'Латвия',
            'LS' => 'Лесото',
            'LR' => 'Либерия',
            'LB' => 'Ливан',
            'LY' => 'Ливия',
            'LT' => 'Литва',
            'LI' => 'Лихтенштейн',
            'LU' => 'Люксембург',
            'MU' => 'Маврикий',
            'MR' => 'Мавритания',
            'MG' => 'Мадагаскар',
            'YT' => 'Майотта',
            'MO' => 'Макао (особый район)',
            'MK' => 'Македония',
            'MW' => 'Малави',
            'MY' => 'Малайзия',
            'ML' => 'Мали',
            'MV' => 'Мальдивские о-ва',
            'MT' => 'Мальта',
            'MA' => 'Марокко',
            'MQ' => 'Мартиника',
            'MH' => 'Маршалловы о-ва',
            'MX' => 'Мексика',
            'MZ' => 'Мозамбик',
            'MD' => 'Молдова',
            'MC' => 'Монако',
            'MN' => 'Монголия',
            'MS' => 'Монтсеррат',
            'MM' => 'Мьянма (Бирма)',
            'NA' => 'Намибия',
            'NR' => 'Науру',
            'NP' => 'Непал',
            'NE' => 'Нигер',
            'NG' => 'Нигерия',
            'NL' => 'Нидерланды',
            'NI' => 'Никарагуа',
            'NU' => 'Ниуэ',
            'NZ' => 'Новая Зеландия',
            'NC' => 'Новая Каледония',
            'NO' => 'Норвегия',
            'NF' => 'о-в Норфолк',
            'CX' => 'о-в Рождества',
            'SH' => 'О-в Св. Елены',
            'CK' => 'о-ва Кука',
            'TC' => 'О-ва Тёркс и Кайкос',
            'AE' => 'ОАЭ',
            'OM' => 'Оман',
            'PK' => 'Пакистан',
            'PW' => 'Палау',
            'PS' => 'Палестинские территории',
            'PA' => 'Панама',
            'PG' => 'Папуа – Новая Гвинея',
            'PY' => 'Парагвай',
            'PE' => 'Перу',
            'PN' => 'Питкэрн',
            'PL' => 'Польша',
            'PT' => 'Португалия',
            'PR' => 'Пуэрто-Рико',
            'KR' => 'Республика Корея',
            'RE' => 'Реюньон',
            'RU' => 'Россия',
            'RW' => 'Руанда',
            'RO' => 'Румыния',
            'SV' => 'Сальвадор',
            'WS' => 'Самоа',
            'SM' => 'Сан-Марино',
            'ST' => 'Сан-Томе и Принсипи',
            'SA' => 'Саудовская Аравия',
            'SZ' => 'Свазиленд',
            'MP' => 'Северные Марианские о-ва',
            'SC' => 'Сейшельские о-ва',
            'PM' => 'Сен-Пьер и Микелон',
            'SN' => 'Сенегал',
            'VC' => 'Сент-Винсент и Гренадины',
            'KN' => 'Сент-Китс и Невис',
            'LC' => 'Сент-Люсия',
            'RS' => 'Сербия',
            'SG' => 'Сингапур',
            'SY' => 'Сирия',
            'SK' => 'Словакия',
            'SI' => 'Словения',
            'US' => 'Соединенные Штаты',
            'SB' => 'Соломоновы о-ва',
            'SO' => 'Сомали',
            'SD' => 'Судан',
            'SR' => 'Суринам',
            'SL' => 'Сьерра-Леоне',
            'TJ' => 'Таджикистан',
            'TH' => 'Таиланд',
            'TW' => 'Тайвань',
            'TZ' => 'Танзания',
            'TG' => 'Того',
            'TK' => 'Токелау',
            'TO' => 'Тонга',
            'TT' => 'Тринидад и Тобаго',
            'TV' => 'Тувалу',
            'TN' => 'Тунис',
            'TM' => 'Туркменистан',
            'TR' => 'Турция',
            'UG' => 'Уганда',
            'UZ' => 'Узбекистан',
            'UA' => 'Украина',
            'WF' => 'Уоллис и Футуна',
            'UY' => 'Уругвай',
            'FO' => 'Фарерские о-ва',
            'FM' => 'Федеративные Штаты Микронезии',
            'FJ' => 'Фиджи',
            'PH' => 'Филиппины',
            'FI' => 'Финляндия',
            'FK' => 'Фолклендские о-ва',
            'FR' => 'Франция',
            'GF' => 'Французская Гвиана',
            'PF' => 'Французская Полинезия',
            'TF' => 'Французские Южные Территории',
            'HR' => 'Хорватия',
            'CF' => 'ЦАР',
            'TD' => 'Чад',
            'ME' => 'Черногория',
            'CZ' => 'Чехия',
            'CL' => 'Чили',
            'CH' => 'Швейцария',
            'SE' => 'Швеция',
            'SJ' => 'Шпицберген и Ян-Майен',
            'LK' => 'Шри-Ланка',
            'EC' => 'Эквадор',
            'GQ' => 'Экваториальная Гвинея',
            'ER' => 'Эритрея',
            'EE' => 'Эстония',
            'ET' => 'Эфиопия',
            'ZA' => 'ЮАР',
            'GS' => 'Южная Георгия и Южные Сандвичевы о-ва',
            'JM' => 'Ямайка',
            'JP' => 'Япония'
        ];
    }
}

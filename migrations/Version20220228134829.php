<?php

namespace Sprint\Migration;

use Exchange1C\Helper;
use Exchange1C\Definitions;
use Exchange1C\Catalog\CatalogSections;
use Exchange1C\Catalog\CatalogProperties;

class Version20220228134829 extends Version
{
    protected $description = 'Создание ИБ Каталога товаров, добавление настроек магазина, добавление разделов и свойств из xml файла';
    protected $moduleVersion = '4.0.2';

    private const XML_URI = Definitions::XML_URI;                           // путь к xml-файлу с содержимым каталога (задаётся в Definitions)
    private const IBLOCK_CATALOG_CODE = Definitions::IBLOCK_CATALOG_CODE;   // символьный код нового каталога (задаётся в Definitions)
    private const IBLOCK_CATALOG_NAME = 'Торговый каталог';                 // название нового каталога
    private const OLD_IBLOCK_CATALOG_CODE = 'products';                     // символьный код старого каталога

    /**
     * @return void
     * @throws Exceptions\HelperException
     */
    public function up(): void
    {
        $sUri = $_SERVER['DOCUMENT_ROOT'] . self::XML_URI;

        $helper = $this->getHelperManager();

        // Деактивация старого каталога
        $helper->Iblock()->updateIblockIfExists(self::OLD_IBLOCK_CATALOG_CODE, ['ACTIVE' => 'N']);

        // Добавление нового каталога
        $iBlockCatalogId = $helper->Iblock()->saveIblock(
            [
                'IBLOCK_TYPE_ID' => 'catalog',
                'LID' =>
                    [
                        0 => 's1'
                    ],
                'CODE' => self::IBLOCK_CATALOG_CODE,
                'NAME' => self::IBLOCK_CATALOG_NAME,
                'ACTIVE' => 'Y',
                'SORT' => '500',
                'LIST_PAGE_URL' => '#SITE_DIR#/catalog/',
                'DETAIL_PAGE_URL' => '#SITE_DIR#/catalog/#SECTION_CODE#/#ELEMENT_CODE#/',
                'SECTION_PAGE_URL' => '#SITE_DIR#/catalog/#SECTION_CODE#/',
                'CANONICAL_PAGE_URL' => '',
                'PICTURE' => null,
                'DESCRIPTION' => '',
                'DESCRIPTION_TYPE' => 'text',
                'RSS_TTL' => '24',
                'RSS_ACTIVE' => 'Y',
                'RSS_FILE_ACTIVE' => 'N',
                'RSS_FILE_LIMIT' => null,
                'RSS_FILE_DAYS' => null,
                'RSS_YANDEX_ACTIVE' => 'N',
                'API_CODE' => self::IBLOCK_CATALOG_CODE,
                'INDEX_ELEMENT' => 'Y',
                'INDEX_SECTION' => 'Y',
                'WORKFLOW' => 'N',
                'BIZPROC' => 'N',
                'SECTION_CHOOSER' => 'L',
                'LIST_MODE' => '',
                'RIGHTS_MODE' => 'S',
                'SECTION_PROPERTY' => 'Y',
                'PROPERTY_INDEX' => 'N',
                'VERSION' => '2',
                'LAST_CONV_ELEMENT' => '0',
                'SOCNET_GROUP_ID' => null,
                'EDIT_FILE_BEFORE' => '',
                'EDIT_FILE_AFTER' => '',
                'SECTIONS_NAME' => 'Разделы',
                'SECTION_NAME' => 'Раздел',
                'ELEMENTS_NAME' => 'Товары',
                'ELEMENT_NAME' => 'Товар',
                'EXTERNAL_ID' => '',
                'LANG_DIR' => '/',
                'SERVER_NAME' => '',
                'ELEMENT_ADD' => 'Добавить товар',
                'ELEMENT_EDIT' => 'Изменить товар',
                'ELEMENT_DELETE' => 'Удалить товар',
                'SECTION_ADD' => 'Добавить раздел',
                'SECTION_EDIT' => 'Изменить раздел',
                'SECTION_DELETE' => 'Удалить раздел',
            ]
        );

        /* Поля Каталога */
        $helper->Iblock()->saveIblockFields(
            $iBlockCatalogId,
            [
                'IBLOCK_SECTION' =>
                    [
                        'NAME' => 'Привязка к разделам',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' =>
                            [
                                'KEEP_IBLOCK_SECTION_ID' => 'N',
                            ],
                    ],
                'ACTIVE' =>
                    [
                        'NAME' => 'Активность',
                        'IS_REQUIRED' => 'Y',
                        'DEFAULT_VALUE' => 'Y',
                    ],
                'ACTIVE_FROM' =>
                    [
                        'NAME' => 'Начало активности',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' => '',
                    ],
                'ACTIVE_TO' =>
                    [
                        'NAME' => 'Окончание активности',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' => '',
                    ],
                'SORT' =>
                    [
                        'NAME' => 'Сортировка',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' => '0',
                    ],
                'NAME' =>
                    [
                        'NAME' => 'Название',
                        'IS_REQUIRED' => 'Y',
                        'DEFAULT_VALUE' => '',
                    ],
                'PREVIEW_PICTURE' =>
                    [
                        'NAME' => 'Картинка для анонса',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' =>
                            [
                                'FROM_DETAIL' => 'N',
                                'SCALE' => 'N',
                                'WIDTH' => '',
                                'HEIGHT' => '',
                                'IGNORE_ERRORS' => 'N',
                                'METHOD' => 'resample',
                                'COMPRESSION' => 95,
                                'DELETE_WITH_DETAIL' => 'N',
                                'UPDATE_WITH_DETAIL' => 'N',
                                'USE_WATERMARK_TEXT' => 'N',
                                'WATERMARK_TEXT' => '',
                                'WATERMARK_TEXT_FONT' => '',
                                'WATERMARK_TEXT_COLOR' => '',
                                'WATERMARK_TEXT_SIZE' => '',
                                'WATERMARK_TEXT_POSITION' => 'tl',
                                'USE_WATERMARK_FILE' => 'N',
                                'WATERMARK_FILE' => '',
                                'WATERMARK_FILE_ALPHA' => '',
                                'WATERMARK_FILE_POSITION' => 'tl',
                                'WATERMARK_FILE_ORDER' => null,
                            ],
                    ],
                'PREVIEW_TEXT_TYPE' =>
                    [
                        'NAME' => 'Тип описания для анонса',
                        'IS_REQUIRED' => 'Y',
                        'DEFAULT_VALUE' => 'text',
                    ],
                'PREVIEW_TEXT' =>
                    [
                        'NAME' => 'Описание для анонса',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' => '',
                    ],
                'DETAIL_PICTURE' =>
                    [
                        'NAME' => 'Детальная картинка',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' =>
                            [
                                'SCALE' => 'N',
                                'WIDTH' => '',
                                'HEIGHT' => '',
                                'IGNORE_ERRORS' => 'N',
                                'METHOD' => 'resample',
                                'COMPRESSION' => 95,
                                'USE_WATERMARK_TEXT' => 'N',
                                'WATERMARK_TEXT' => '',
                                'WATERMARK_TEXT_FONT' => '',
                                'WATERMARK_TEXT_COLOR' => '',
                                'WATERMARK_TEXT_SIZE' => '',
                                'WATERMARK_TEXT_POSITION' => 'tl',
                                'USE_WATERMARK_FILE' => 'N',
                                'WATERMARK_FILE' => '',
                                'WATERMARK_FILE_ALPHA' => '',
                                'WATERMARK_FILE_POSITION' => 'tl',
                                'WATERMARK_FILE_ORDER' => null,
                            ],
                    ],
                'DETAIL_TEXT_TYPE' =>
                    [
                        'NAME' => 'Тип детального описания',
                        'IS_REQUIRED' => 'Y',
                        'DEFAULT_VALUE' => 'text',
                    ],
                'DETAIL_TEXT' =>
                    [
                        'NAME' => 'Детальное описание',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' => '',
                    ],
                'XML_ID' =>
                    [
                        'NAME' => 'Внешний код',
                        'IS_REQUIRED' => 'Y',
                        'DEFAULT_VALUE' => '',
                    ],
                'CODE' =>
                    [
                        'NAME' => 'Символьный код',
                        'IS_REQUIRED' => 'Y',
                        'DEFAULT_VALUE' =>
                            [
                                'UNIQUE' => 'Y',
                                'TRANSLITERATION' => 'Y',
                                'TRANS_LEN' => 100,
                                'TRANS_CASE' => 'L',
                                'TRANS_SPACE' => '-',
                                'TRANS_OTHER' => '-',
                                'TRANS_EAT' => 'Y',
                                'USE_GOOGLE' => 'N',
                            ],
                    ],
                'TAGS' =>
                    [
                        'NAME' => 'Теги',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' => '',
                    ],
                'SECTION_NAME' =>
                    [
                        'NAME' => 'Название',
                        'IS_REQUIRED' => 'Y',
                        'DEFAULT_VALUE' => '',
                    ],
                'SECTION_PICTURE' =>
                    [
                        'NAME' => 'Картинка для анонса',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' =>
                            [
                                'FROM_DETAIL' => 'N',
                                'SCALE' => 'N',
                                'WIDTH' => '',
                                'HEIGHT' => '',
                                'IGNORE_ERRORS' => 'N',
                                'METHOD' => 'resample',
                                'COMPRESSION' => 95,
                                'DELETE_WITH_DETAIL' => 'N',
                                'UPDATE_WITH_DETAIL' => 'N',
                                'USE_WATERMARK_TEXT' => 'N',
                                'WATERMARK_TEXT' => '',
                                'WATERMARK_TEXT_FONT' => '',
                                'WATERMARK_TEXT_COLOR' => '',
                                'WATERMARK_TEXT_SIZE' => '',
                                'WATERMARK_TEXT_POSITION' => 'tl',
                                'USE_WATERMARK_FILE' => 'N',
                                'WATERMARK_FILE' => '',
                                'WATERMARK_FILE_ALPHA' => '',
                                'WATERMARK_FILE_POSITION' => 'tl',
                                'WATERMARK_FILE_ORDER' => null,
                            ],
                    ],
                'SECTION_DESCRIPTION_TYPE' =>
                    [
                        'NAME' => 'Тип описания',
                        'IS_REQUIRED' => 'Y',
                        'DEFAULT_VALUE' => 'text',
                    ],
                'SECTION_DESCRIPTION' =>
                    [
                        'NAME' => 'Описание',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' => '',
                    ],
                'SECTION_DETAIL_PICTURE' =>
                    [
                        'NAME' => 'Детальная картинка',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' =>
                            [
                                'SCALE' => 'N',
                                'WIDTH' => '',
                                'HEIGHT' => '',
                                'IGNORE_ERRORS' => 'N',
                                'METHOD' => 'resample',
                                'COMPRESSION' => 95,
                                'USE_WATERMARK_TEXT' => 'N',
                                'WATERMARK_TEXT' => '',
                                'WATERMARK_TEXT_FONT' => '',
                                'WATERMARK_TEXT_COLOR' => '',
                                'WATERMARK_TEXT_SIZE' => '',
                                'WATERMARK_TEXT_POSITION' => 'tl',
                                'USE_WATERMARK_FILE' => 'N',
                                'WATERMARK_FILE' => '',
                                'WATERMARK_FILE_ALPHA' => '',
                                'WATERMARK_FILE_POSITION' => 'tl',
                                'WATERMARK_FILE_ORDER' => null,
                            ],
                    ],
                'SECTION_XML_ID' =>
                    [
                        'NAME' => 'Внешний код',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' => '',
                    ],
                'SECTION_CODE' =>
                    [
                        'NAME' => 'Символьный код',
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' =>
                            [
                                'UNIQUE' => 'N',
                                'TRANSLITERATION' => 'N',
                                'TRANS_LEN' => 100,
                                'TRANS_CASE' => 'L',
                                'TRANS_SPACE' => '-',
                                'TRANS_OTHER' => '-',
                                'TRANS_EAT' => 'Y',
                                'USE_GOOGLE' => 'N',
                            ],
                    ],
            ]
        );


        /* Добавление разделов в каталог*/
        $this->addCatalogSections($iBlockCatalogId, $helper);

        /* Получение объекта свойств каталога из xml и преобразование в массив*/
        $obCatalogProperties = new CatalogProperties();
        $arCatalogProperties = Helper::turnObjectIntoArray($obCatalogProperties->getXmlValues($sUri));
        if (is_string($arCatalogProperties)) {
            throw new Exceptions\HelperException($arCatalogProperties);
        }

        /*Добавление свойств товаров*/
        //т.к. свойства 'Артикул' нет в xml, а у товаров это свойство есть, добавим его 'вручную'
        $this->addArtnumberProperty($helper, $iBlockCatalogId);

        foreach ($arCatalogProperties['Свойство'] as $property) {
            switch ($property['ТипЗначений']) {
                case 'Справочник' :
                    $this->addReferenceProperty($property, $helper, $iBlockCatalogId);
                    break;
                case 'Число' :
                    $this->addOtherProperty($property, $helper, $iBlockCatalogId, 'N');
                    break;
                case 'Строка' :
                    $this->addOtherProperty($property, $helper, $iBlockCatalogId, 'S');
                    break;
            }
        }

        /*Преобразование ИБ в каталог товаров, добавление типа цены, ставки налогов и склада*/
        // Добавление ставки налога:
//        $arFieldsVat = [
//            'ACTIVE' => 'Y',
//            'NAME' => 'НДС',
//            'SORT' => 100,
//            'RATE' => 20,
//        ];
//        $vatId = \CCatalogVat::Add($arFieldsVat);
//        if (!$vatId) {
//            throw new Exceptions\HelperException('Ставка налога не создана!');
//        }

        // Подключение ИБ к модулю торгового каталога:
        $boolResult = \CCatalog::Add(['IBLOCK_ID' => $iBlockCatalogId]);
        if (!$boolResult) {
            throw new Exceptions\HelperException('Ошибка подключения ИБ ' . $iBlockCatalogId . ' к модулю торгового каталога!');
        }

        // добавление типа цен:
        $arUsersGroup = [];
        $obGroups = \Bitrix\Main\GroupTable::getList([
            'select' => ['ID'],
            'filter' => []
        ]);
        while ($arGroup = $obGroups->fetch()) {
            $arUsersGroup[]= (int)$arGroup['ID'];
        }

        $arFieldsPrice = [
            'BASE' => 'Y',
            'NAME' => 'standard',
            'SORT' => 150,
            'XML_ID' => '75e39d96-509f-11e5-a5d8-74d4351ab4dc',
            'USER_GROUP' => $arUsersGroup,
            'USER_GROUP_BUY' => $arUsersGroup,
            'USER_LANG' => [
                'ru' => 'Стандартная 1 без НДС',
                'en' => 'Standard 1 without VAT'
            ]
        ];
        $priceId = \CCatalogGroup::Add($arFieldsPrice);
        if ($priceId <= 0) {
            throw new Exceptions\HelperException('Ошибка добавления типа цены!');
        }

        // добавление склада
        $arFieldsStore = [
            "TITLE" => 'Подольск 3',
            "ACTIVE" => 'Y',
            "XML_ID" => 'cc3ba7c9-f947-11e8-82f1-000c29257886',
        ];
        $boolStore = \CCatalogStore::Add($arFieldsStore);
        if (!$boolStore) {
            throw new Exceptions\HelperException('Ошибка создания склада!');
        }
    }

    private function addArtnumberProperty(object $helper, int $iBlockCatalogId)
    {
        $helper->Iblock()->saveProperty(
            $iBlockCatalogId,
            [
                'NAME' => 'Артикул',
                'ACTIVE' => 'Y',
                'SORT' => '400',
                'CODE' => 'ARTNUMBER',
                'DEFAULT_VALUE' => '',
                'PROPERTY_TYPE' => 'S',
                'ROW_COUNT' => '1',
                'COL_COUNT' => '60',
                'LIST_TYPE' => 'L',
                'MULTIPLE' => 'N',
                'XML_ID' => Definitions::ARTNUMBER_XML_ID,
                'FILE_TYPE' => '',
                'MULTIPLE_CNT' => '5',
                'LINK_IBLOCK_ID' => '0',
                'WITH_DESCRIPTION' => 'N',
                'SEARCHABLE' => 'N',
                'FILTRABLE' => 'N',
                'IS_REQUIRED' => 'N',
                'VERSION' => '2',
                'USER_TYPE' => null,
                'USER_TYPE_SETTINGS' => null,
                'HINT' => '',
            ]
        );
    }

    private function addReferenceProperty(array $arProperties, object $helper, int $iBlockCatalogId)
    {
        $arValues = [];
        foreach ($arProperties['ВариантыЗначений']['Справочник'] as $key => $property) {
            $arValues[$key]['VALUE'] = $property['Значение'];
            $arValues[$key]['XML_ID'] = $property['ИдЗначения'];
            $arValues[$key]['SORT'] = 500;
        }
        $helper->Iblock()->saveProperty(
            $iBlockCatalogId,
            [
                'NAME' => $arProperties['Наименование'],
                'ACTIVE' => 'Y',
                'SORT' => '500',
                'CODE' => Helper::transliterateString($arProperties['Наименование'], true),
                'DEFAULT_VALUE' => '',
                'PROPERTY_TYPE' => 'L',
                'ROW_COUNT' => '1',
                'COL_COUNT' => '30',
                'LIST_TYPE' => 'L',
                'MULTIPLE' => 'N',
                'XML_ID' => $arProperties['Ид'],
                'FILE_TYPE' => '',
                'MULTIPLE_CNT' => '5',
                'LINK_IBLOCK_ID' => '0',
                'WITH_DESCRIPTION' => 'N',
                'SEARCHABLE' => 'N',
                'FILTRABLE' => 'N',
                'IS_REQUIRED' => 'N',
                'VERSION' => '2',
                'USER_TYPE' => null,
                'USER_TYPE_SETTINGS' => null,
                'HINT' => '',
                'VALUES' => $arValues
            ]
        );
    }

    private function addOtherProperty(array $arProperties, object $helper, int $iBlockCatalogId, $PropertyType)
    {
        $helper->Iblock()->saveProperty(
            $iBlockCatalogId,
            [
                'NAME' => $arProperties['Наименование'],
                'ACTIVE' => 'Y',
                'SORT' => '500',
                'CODE' => Helper::transliterateString($arProperties['Наименование'], true),
                'DEFAULT_VALUE' => '',
                'PROPERTY_TYPE' => $PropertyType,
                'ROW_COUNT' => '1',
                'COL_COUNT' => '60',
                'LIST_TYPE' => 'L',
                'MULTIPLE' => 'N',
                'XML_ID' => $arProperties['Ид'],
                'FILE_TYPE' => '',
                'MULTIPLE_CNT' => '5',
                'LINK_IBLOCK_ID' => '0',
                'WITH_DESCRIPTION' => 'N',
                'SEARCHABLE' => 'N',
                'FILTRABLE' => 'N',
                'IS_REQUIRED' => 'N',
                'VERSION' => '2',
                'USER_TYPE' => null,
                'USER_TYPE_SETTINGS' => null,
                'HINT' => '',
            ]
        );
    }

    private function addCatalogSections($iBlockCatalogId, $helper)
    {
        $sUri = $_SERVER['DOCUMENT_ROOT'] . self::XML_URI;
        $obCatalogSections = new CatalogSections();
        $arCatalogSections = Helper::turnObjectIntoArray($obCatalogSections->getXmlValues($sUri));
        foreach ($arCatalogSections['Группа'] as $section) {
            $sectionId = $helper->Iblock()->addSection(
                $iBlockCatalogId,
                [
                    'NAME' => $section['Наименование'],
                    'CODE' => Helper::transliterateString($section['Наименование']),
                    'SORT' => '500',
                    'ACTIVE' => 'Y',
                    'XML_ID' => $section['Ид'],
                    'DESCRIPTION' => '',
                    'DESCRIPTION_TYPE' => 'text',
                ]
            );
            if (is_array($section['Группы']['Группа'])) {
                $arSubsectionsValues = [];
                foreach ($section['Группы']['Группа'] as $subsection) {
                    $arSubsectionsValues[] = [
                        'NAME' => $subsection['Наименование'],
                        'CODE' => Helper::transliterateString($subsection['Наименование']),
                        'SORT' => '500',
                        'ACTIVE' => 'Y',
                        'XML_ID' => $subsection['Ид'],
                        'DESCRIPTION' => '',
                        'DESCRIPTION_TYPE' => 'text',
                    ];
                }
                $helper->Iblock()->addSectionsFromTree($iBlockCatalogId, $arSubsectionsValues, $sectionId);
            }
        }
    }

    public function down()
    {
        $helper = $this->getHelperManager();

        // активация старого каталога товаров
        $helper->Iblock()->updateIblockIfExists(self::OLD_IBLOCK_CATALOG_CODE, ['ACTIVE' => 'Y']);

        // удаление нового каталога товаров
        $helper->Iblock()->deleteIblockIfExists(self::IBLOCK_CATALOG_CODE);


        // удаление новой ставки налога
//        $vatId = 0;
//        $dbVat = \CCatalog::GetList([], ['=NAME' => 'НДС'], ['ID']);
//        while ($arVat = $dbVat->Fetch()) {
//            $vatId = (int)$arVat['ID'];
//        }
//        $boolVat = \CCatalog::Delete($vatId);
//        if (!$boolVat) {
//            throw new Exceptions\HelperException('Ставка налога не удалена!');
//        }

        // удаление нового типа цен
        $boolPrice = \CCatalogGroup::Update(1, ['BASE'=>'Y']);
        $priceId = 0;
        $dbPrice = \CCatalogGroup::GetList([], ['=NAME' => 'standard'], false, false, ['ID']);
        while ($arPrice = $dbPrice->Fetch()) {
            $priceId = (int)$arPrice['ID'];
        }
        $boolPrice = \CCatalogGroup::Delete($priceId);
        if (!$boolPrice) {
            throw new Exceptions\HelperException('Тип цен не удален!');
        }

        // удаление склада
        $storeId = 0;
        $dbStore = \CCatalogStore::GetList([], ['=NAME' => 'standard'], false, false, ['ID']);
        while ($arStore = $dbStore->Fetch()) {
            $storeId = (int)$arStore['ID'];
        }
        $boolStore = \CCatalogStore::Delete($storeId);
        if (!$boolStore) {
            throw new Exceptions\HelperException('Склад не удален!');
        }
    }
}
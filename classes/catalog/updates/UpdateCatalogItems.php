<?php

namespace Exchange1C\Catalog\Updates;

use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\PropertyTable;
use \Bitrix\Iblock\SectionTable;
use Exchange1C\Definitions;
use Exchange1C\Helper;
use Exchange1C\Catalog\CatalogItems;

class UpdateCatalogItems
{
    protected int $catalogId;           // id ИБ Каталог
    protected array $arXmlSectionsId; // массив XML_ID => ID раздела ИБ Каталог
    protected array $arXmlElements; // массив XML_ID => ID свойства и тип свойства ИБ Каталог

    public function __construct()
    {
        Loader::includeModule('iblock');

        // Получаем id ИБ Каталог:
        $this->catalogId = IblockTable::getList(
            [
                'select' => ['ID', 'NAME'],
                'filter' => ['=CODE' => Definitions::IBLOCK_CATALOG_CODE],
            ]
        )->fetchObject()->getId();

        // Получаем массив свойств ИБ Каталог:
        $arCatalogProperties = PropertyTable::getList([
            'select' => ['ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_TYPE', 'LIST_TYPE', 'XML_ID'],
            'filter' => ['=IBLOCK_ID' => $this->catalogId]
        ])->fetchAll();

        // Получаем массив-связку XML_ID => ID свойства и тип свойства ИБ Каталог:
        foreach ($arCatalogProperties as $item) {
            $this->arXmlElements[$item['XML_ID']]['ID'] = (int)$item['ID'];
            $this->arXmlElements[$item['XML_ID']]['PROPERTY_TYPE'] = $item['PROPERTY_TYPE'];
        }

        // Получаем массив разделов ИБ Каталог:
        $arCatalogSections = SectionTable::getList([
            'select' => ['ID', 'XML_ID'],
            'filter' => ['=IBLOCK_ID' => $this->catalogId],
        ])->fetchAll();

        // Получаем массив-связку XML_ID => ID раздела ИБ Каталог:
        foreach ($arCatalogSections as $section) {
            $this->arXmlSectionsId[$section['XML_ID']] = (int)$section['ID'];
        }
    }

    public function refreshCatalogItems($sUri = false)
    {
        // Если путь к файлу не передан, получаем предопределенный в Definitions путь к xml файлу:
        if (!$sUri) {
            $sUri = $_SERVER["DOCUMENT_ROOT"] . Definitions::XML_URI;
        }

        // Получаем объект с элементами каталога из xml:
        $ob = new CatalogItems();
        $obItems = $ob->getXmlValues($sUri);

        $arItems = Helper::turnObjectIntoArray($obItems);
        foreach ($arItems['Товар'] as $arItem) {
            $arCatalogElement = ElementTable::getList(
                [
                    'select' => ['ID', 'NAME', 'XML_ID'],
                    'filter' => ['=XML_ID' => $arItem['Ид']],
                ]
            )->fetchAll();

            if (empty($arCatalogElement)) {
                $this->addElement($arItem);
            } else {
                $this->updateElement($arItem, $arCatalogElement[0]['ID']);
            }
        }
    }

    private function addElement($arItem)
    {
        $el = new \CIBlockElement;

        $arProps = [];
        $arProps[$this->arXmlElements[Definitions::ARTNUMBER_XML_ID]['ID']] = $arItem['Артикул']; // артикул товара заполняем отдельно
        foreach ($arItem['ЗначенияСвойств']['ЗначенияСвойства'] as $arProperties) {
            if ($this->arXmlElements[$arProperties['Ид']]['PROPERTY_TYPE'] === 'L') {
                $enumId = $this->getEnumId($arProperties['Ид'], $arProperties['Значение']);
                $arProps[$this->arXmlElements[$arProperties['Ид']]['ID']] = ['VALUE' => $enumId];
            } else {
                $arProps[$this->arXmlElements[$arProperties['Ид']]['ID']] = $arProperties['Значение'];
            }
        }

        $arLoadProductPropertiesArray = [
            'IBLOCK_SECTION_ID' => $this->arXmlSectionsId[$arItem['Группы']['Ид']],
            'IBLOCK_ID' => $this->catalogId,
            'CODE' => Helper::transliterateString($arItem['Наименование']),
            'PROPERTY_VALUES' => $arProps,
            'NAME' => $arItem['Наименование'],
            'ACTIVE' => 'Y',
            'ACTIVE_FROM' => new \Bitrix\Main\Type\DateTime(),
            'XML_ID' => $arItem['Ид']
        ];

        if ($nProductId = $el->Add($arLoadProductPropertiesArray)) {
            \Bitrix\Main\Diag\Debug::dumpToFile($nProductId, date("Y-m-d H:i:s") . ' ADDING an element SUCCESS:', '/log.html');
        } else {
            \Bitrix\Main\Diag\Debug::dumpToFile($el->LAST_ERROR, date("Y-m-d H:i:s") . ' ADDING an element ERROR:', '/log.html');
        }
    }


    private function updateElement($arItem, $elId)
    {
        $el = new \CIBlockElement;

        $arProps = [];
        $arProps[$this->arXmlElements[Definitions::ARTNUMBER_XML_ID]['ID']] = $arItem['Артикул']; // артикул товара заполняем отдельно
        foreach ($arItem['ЗначенияСвойств']['ЗначенияСвойства'] as $arProperties) {
            if ($this->arXmlElements[$arProperties['Ид']]['PROPERTY_TYPE'] === 'L') {
                $enumId = $this->getEnumId($arProperties['Ид'], $arProperties['Значение']);
                $arProps[$this->arXmlElements[$arProperties['Ид']]['ID']] = ['VALUE' => $enumId[$arProperties['Значение']]];
            } else {
                $arProps[$this->arXmlElements[$arProperties['Ид']]['ID']] = $arProperties['Значение'];
            }
        }

        $arLoadProductPropertiesArray = [
            'IBLOCK_SECTION_ID' => $this->arXmlSectionsId[$arItem['Группы']['Ид']],
            'IBLOCK_ID' => $this->catalogId,
            'CODE' => Helper::transliterateString($arItem['Наименование']),
            'PROPERTY_VALUES' => $arProps,
            'NAME' => $arItem['Наименование'],
            'ACTIVE' => 'Y',
            'ACTIVE_FROM' => new \Bitrix\Main\Type\DateTime(),
            'XML_ID' => $arItem['Ид']
        ];

        if ($nProductId = $el->Update($elId, $arLoadProductPropertiesArray)) {
            \Bitrix\Main\Diag\Debug::dumpToFile($nProductId, date("Y-m-d H:i:s") . ' UPDATING an element SUCCESS:', '/log.html');
        } else {
            \Bitrix\Main\Diag\Debug::dumpToFile($el->LAST_ERROR, date("Y-m-d H:i:s") . ' UPDATING an element ERROR:', '/log.html');
        }
    }

    private function getEnumId($propertyXml, $value)
    {
        $enumId = [];
        $obPropEnums = \CIBlockPropertyEnum::GetList(
            ["SORT" => "ASC"],
            [
                "IBLOCK_ID" => $this->catalogId,
                'PROPERTY_ID' => $this->arXmlElements[$propertyXml]['ID'],
                "XML_ID " => $value
            ]
        );
        while ($arEnumFields = $obPropEnums->GetNext()) {
            $enumId[$arEnumFields['EXTERNAL_ID']] = $arEnumFields['ID'];
        }

        return $enumId;
    }
}
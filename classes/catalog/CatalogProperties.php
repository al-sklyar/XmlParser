<?php

namespace Exchange1C\Catalog;

use Exchange1C\XmlCatalogParser;
use Bitrix\Main\DB\Exception;

class CatalogProperties extends XmlCatalogParser
{
    /**
     * Получает объект свойств элементов ИБ Каталог из xml файла
     *
     * @param string $sUri Путь к xml файлу
     *
     * @return object|string
     */
    public function getXmlValues(string $sUri)
    {
        try {
            $obProperties = $this->getXmlFromFile($sUri)->Классификатор->Свойства;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }

        return $obProperties;
    }
}
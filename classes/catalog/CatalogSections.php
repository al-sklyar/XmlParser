<?php

namespace Exchange1C\Catalog;

use Exchange1C\XmlCatalogParser;
use Bitrix\Main\DB\Exception;

class CatalogSections extends XmlCatalogParser
{
    /**
     * Получает объект свойств ИБ Каталог из xml файла
     *
     * @param string $sUri Путь к xml файлу
     *
     * @return object|string
     */
    public function getXmlValues(string $sUri)
    {
        try {
            $obSections = $this->getXmlFromFile($sUri)->Классификатор->Группы;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }

        return $obSections;
    }
}
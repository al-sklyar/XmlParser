<?php

namespace Exchange1C\Catalog;

use Exchange1C\XmlCatalogParser;
use Bitrix\Main\DB\Exception;

class CatalogItems extends XmlCatalogParser
{
    /**
     * Получает объект элементов ИБ Каталог из xml файла
     *
     * @param string $sUri Путь к xml файлу
     *
     * @return object|string
     */
    public function getXmlValues(string $sUri)
    {
        try {
            $obItems = $this->getXmlFromFile($sUri)->Каталог->Товары;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }

        return $obItems;
    }
}
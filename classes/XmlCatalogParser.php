<?php

namespace Exchange1C;

use Bitrix\Main\DB\Exception;

IncludeModuleLangFile(__FILE__);

class XmlCatalogParser implements ParserInterface
{
    /**
     * Получает объект содержимого ИБ Каталог из xml файла
     *
     * @param string $sUri путь к xml файлу
     *
     * @return object|string
     */
    public function getXmlValues(string $sUri)
    {
        try {
            $obValues = $this->getXmlFromFile($sUri);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }

        return $obValues;
    }

    /**
     * Если файл по заданному пути существует, получает объект из xml файла, иначе выбрасывает исключение
     * @throws Exception
     */
    protected function getXmlFromFile(string $sUri)
    {
        if (!file_exists($sUri)) {
            throw new Exception(GetMessage("MISSING_FILE"));
        }

        return simplexml_load_file($sUri);
    }
}
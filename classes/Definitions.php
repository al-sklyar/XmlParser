<?php

namespace Exchange1C;

class Definitions {
    const XML_URI = '/upload/import.xml';   // Путь к файлу импорта

    const IBLOCK_CATALOG_CODE = 'catalog';  // Символьный код ИБ "Каталога товаров"
    const MAX_UPPER_CASE = 50;              // Максимальная длина строки в верхнем регистре
    const MAX_LOWER_CASE = 100;             // Максимальная длина строки в нижнем регистре

    const ARTNUMBER_XML_ID = 'artnumber';   // В xml файле нет XML_ID для артикула товара, зададим его вручную
}
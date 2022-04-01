<?php

namespace Exchange1C;

class Helper
{
    /**
     * Транслитерация текста для символьных кодов и свойств товара
     *
     * @param string $sCode
     * @param bool   $bCase null or false - lower case, true - upper case
     *
     * @return string
     */
    public static function transliterateString(string $sCode, bool $bCase = false): string
    {
        if ($bCase) {
            $sReplaceSpace = '_';
            $sCase = 'U';
            $sReplaceOther = '';
            $nMaxLen = Definitions::MAX_UPPER_CASE;
        } else {
            $sReplaceSpace = '-';
            $sCase = 'L';
            $sReplaceOther = '-';
            $nMaxLen = Definitions::MAX_LOWER_CASE;
        }

        $arParams = ['max_len' => $nMaxLen, 'change_case' => $sCase, 'replace_space' => $sReplaceSpace, 'replace_other' => $sReplaceOther];
        return \Cutil::translit($sCode, 'ru', $arParams);
    }

    /**
     * Превращает объект в ассоциативный массив
     *
     * @param object $ob
     *
     * @return array
     */
    public static function turnObjectIntoArray(object $ob): array
    {
        return json_decode(json_encode($ob), true);
    }
}
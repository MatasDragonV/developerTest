<?php

/*
 * @name iBlock Cache Test
 * @autor Oleg Matasov
 */

/* Проверка подключения пролога */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

class iblockCacheCL {
    
    function ibGetElements(
            $IBLOCK_ID, 
            $arOrder = array("SORT"=>"ASC"), 
            $arFilter = array(), 
            $arGroupBy = array(), 
            $arNavStartParams = false, 
            $arSelectFields = "*",
            $cacheID = 'arIblockListCache',
            $cachePath='/arIblockListCache/',
            $cacheTime = 3600
            ) {
        $cache = new CPHPCache(); 
        $arIBlockListCache = false;
        if ($cache_time > 0 && $cache->InitCache($cacheTime, $cacheID, $cachePath)) {
            $res = $cache->GetVars();
            if (is_array($res["arIBlockListCache"]) && (count($res["arIBlockListCache"]) > 0))
                $arIBlockListCache = $res["arIBlockListCache"];
        }
        if (!is_array($arIBlockListCache)) {
            $res = CIBlockElement::GetList(
                    $arOrder,
                    $arFilter,
                    $arGroupBy,
                    $arNavStartParams,
                    $arSelectFields                    
            );
            while ($ar_res = $res->Fetch()) {
                if ($ar_res['ELEMENT_CNT'] > 0)
                    $arIBlockListCache[] = $ar_res;
            }            
            if ($cacheTime > 0) {
                $cache->StartDataCache($cacheTime, $cacheID, $cachePath);
                $cache->EndDataCache(array("arIBlockListCache" => $arIBlockListCache));
            }
        }
        return $arIBlockListCache;
    }

}

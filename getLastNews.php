<?php

/*
 * @name PHP Program module
 * @autor Oleg Matasov
 */

class rssParser {

    var $rss = "https://lenta.ru/rss";

    function isCommandLineInterface() {
        return (php_sapi_name() === 'cli');
    }

    function throwExeption($message, $stop) {
        $message = date('d.m.Y h:i:s') . ' - Неожиданное исключение:' . $message . "<br/>";
        $stopMsg = date('d.m.Y h:i:s') . ' - Выполнение прервано из за исключения в обработке!';
        if ($this->isCommandLineInterface()) {
            $message = iconv("UTF-8", "CP866", $message);
            $message = str_replace("<br/>", PHP_EOL, $message);
            $stopMsg = iconv("UTF-8", "CP866", $stopMsg);
        }
        echo $message;
        if ($stop) {
            die($stopMsg);
        }
    }

    function parseRss($count) {
        $xml = @simplexml_load_file($this->rss);
        if ($xml === false)
            $this->throwExeption('Error parse RSS: ' . $rss, true);
        $cnt = 0;
        foreach ($xml->channel->item as $item) {
            $cnt++;
            $message = ' - ' . $item->title . '<br/>' . ' - ' . $item->link . '<br/>' . ' - ' . $item->description;
            if ($this->isCommandLineInterface()) {
                $message = iconv("UTF-8", "CP866", $message);
                $message = str_replace("<br/>", PHP_EOL, $message);
            }
            echo $message;
            if ($cnt >= $count)
                break;
        }
    }

}

$parseObj = new rssParser();
$parseObj->rss = "https://lenta.ru/rss";
$parseObj->parseRss(5);



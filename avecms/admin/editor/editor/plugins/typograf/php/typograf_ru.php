<?php
/**
* Функция возвращает результат post запроса
* $host - хост сайта куда предполагается делать post запросы. Напр., имеется сайт
*    http://www.typograf.ru/webservice/, хостом в данном случае является www.typograf.ru
* $script - имя каталога или скрипта, который обрабатывает ваш post запрос.
*    Для сайта http://www.typograf.ru/webservice/ обработчиком будет каталог /webservice/.
* $data - это данные формата имя=значение, которые передаются для обработки. Для веб-сервиса
*    http://www.typograf.ru/webservice/ необходимо передать значение переменной text, поэтому
*    значение переменной $data будет text=текст для типографирования.
*/

function post($host, $script, $data)
{ 

	$fp = fsockopen($host,80,$errno, $errstr, 30 );  
         
	if ($fp) { 
		fputs($fp, "POST $script HTTP/1.1\n");  
		fputs($fp, "Host: $host\n");  
		fputs($fp, "Content-type: application/x-www-form-urlencoded\n");  
		fputs($fp, "Content-length: " . strlen($data) . "\n");
		fputs($fp, "User-Agent: PHP Script\n");  
		fputs($fp, "Connection: close\n\n");  
		fputs($fp, $data);  
		while(fgets($fp,2048) != "\r\n" && !feof($fp));
		unset($buf);
		$buf = "";
		while(!feof($fp)) $buf .= fread($fp,2048);
		fclose($fp); 
	}
	else{ 
		return "Сервер не отвечает"; 
	}
	return $buf; 
}

$word = urldecode($_POST['text']);

$xml = '';
$out_txt = post('www.typograf.ru','/webservice/','text='.urlencode($word).'&xml='.urlencode($xml).'&chr=UTF-8');

function Utf8ToWin($fcontents) {
    $out = $c1 = '';
    $byte2 = false;
    for ($c = 0;$c < strlen($fcontents);$c++) {
        $i = ord($fcontents[$c]);
        if ($i <= 127) {
            $out .= $fcontents[$c];
        }
        if ($byte2) {
            $new_c2 = ($c1 & 3) * 64 + ($i & 63);
            $new_c1 = ($c1 >> 2) & 5;
            $new_i = $new_c1 * 256 + $new_c2;
            if ($new_i == 1025) {
                $out_i = 168;
            } else {
                if ($new_i == 1105) {
                    $out_i = 184;
                } else {
                    $out_i = $new_i - 848;
                }
            }
            $out .= chr($out_i);
            $byte2 = false;
        }
        if (($i >> 5) == 6) {
            $c1 = $i;
            $byte2 = true;
        }
    }
    return $out;
}

echo Utf8ToWin($out_txt);
//echo $out_txt;
?>
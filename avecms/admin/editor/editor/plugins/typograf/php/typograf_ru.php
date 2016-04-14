<?php
/**
* ������� ���������� ��������� post �������
* $host - ���� ����� ���� �������������� ������ post �������. ����., ������� ����
*    http://www.typograf.ru/webservice/, ������ � ������ ������ �������� www.typograf.ru
* $script - ��� �������� ��� �������, ������� ������������ ��� post ������.
*    ��� ����� http://www.typograf.ru/webservice/ ������������ ����� ������� /webservice/.
* $data - ��� ������ ������� ���=��������, ������� ���������� ��� ���������. ��� ���-�������
*    http://www.typograf.ru/webservice/ ���������� �������� �������� ���������� text, �������
*    �������� ���������� $data ����� text=����� ��� ����������������.
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
		return "������ �� ��������"; 
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
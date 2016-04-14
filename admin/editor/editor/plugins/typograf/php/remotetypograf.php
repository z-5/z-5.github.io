<?php
/*
	remotetypograf.php
	PHP-implementation of ArtLebedevStudio.RemoteTypograf class (web-service client)

	Copyright (c) Art. Lebedev Studio | http://www.artlebedev.ru/

	Typograf homepage: http://typograf.artlebedev.ru/
	Web-service address: http://typograf.artlebedev.ru/webservices/typograf.asmx
	WSDL-description: http://typograf.artlebedev.ru/webservices/typograf.asmx?WSDL

	Default charset: UTF-8

	Version: 1.0 (August 30, 2005)
	Author: Andrew Shitov (ash@design.ru)


	Example:
		include "remotetypograf.php";
		$remoteTypograf = new RemoteTypograf();
		// $remoteTypograf = new RemoteTypograf ('Windows-1251');
		print $remoteTypograf->processText ('"Вы все еще кое-как верстаете в "Ворде"? - Тогда мы идем к вам!"');
*/

class RemoteTypograf
{
	var $_entityType = 4;
	var $_useBr = 1;
	var $_useP = 1;
	var $_maxNobr = 3;
	var $_encoding = 'UTF-8';



	function RemoteTypograf ($encoding)
	{
		if ($encoding) $this->_encoding = $encoding;
	}

	function htmlEntities()
	{
		$this->_entityType = 1;
	}

	function xmlEntities()
	{
		$this->_entityType = 2;
	}

	function mixedEntities()
	{
		$this->_entityType = 4;
	}

	function noEntities()
	{
		$this->_entityType = 3;
	}

	function br ($value)
	{
		$this->_useBr = $value ? 1 : 0;
	}

	function p ($value)
	{
		$this->_useP = $value ? 1 : 0;
	}

	function nobr ($value)
	{
		$this->_maxNobr = $value ? $value : 0;
	}

	function processText ($text)
	{
		$text = str_replace ('&', '&amp;', $text);
		$text = str_replace ('<', '&lt;', $text);
		$text = str_replace ('>', '&gt;', $text);

		$SOAPBody = '<?xml version="1.0" encoding="' . $this->_encoding . '"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
	<ProcessText xmlns="http://typograf.artlebedev.ru/webservices/">
	  <text>' . $text . '</text>
      <entityType>' . $this->_entityType . '</entityType>
      <useBr>' . $this->_useBr . '</useBr>
      <useP>' . $this->_useP . '</useP>
      <maxNobr>' . $this->_maxNobr . '</maxNobr>
	</ProcessText>
  </soap:Body>
</soap:Envelope>';

		$host = 'typograf.artlebedev.ru';
		$SOAPRequest = 'POST /webservices/typograf.asmx HTTP/1.1
Host: typograf.artlebedev.ru
Content-Type: text/xml
Content-Length: ' . strlen ($SOAPBody). '
SOAPAction: "http://typograf.artlebedev.ru/webservices/ProcessText"

'.
	$SOAPBody;

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

		$remoteTypograf = fsockopen ($host, 80);
		fwrite ($remoteTypograf, $SOAPRequest);
		$typografResponse = '';
		while (!feof ($remoteTypograf))
		{
			$typografResponse .= fread ($remoteTypograf, 8192);
		}
		fclose ($remoteTypograf);

		$startsAt = strpos ($typografResponse, '<ProcessTextResult>') + 19;
		$endsAt = strpos ($typografResponse, '</ProcessTextResult>');
		$typografResponse = substr ($typografResponse, $startsAt, $endsAt - $startsAt - 1);

		$typografResponse = Utf8ToWin($typografResponse);

		$typografResponse = str_replace ('&amp;', '&', $typografResponse);
		$typografResponse = str_replace ('&lt;', '<', $typografResponse);
		$typografResponse = str_replace ('&gt;', '>', $typografResponse);
		return  $typografResponse;
	}
}

?>

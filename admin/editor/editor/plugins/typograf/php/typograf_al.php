<?php

if ($_POST['text'])
{
	$text = urldecode($_POST['text']);
	include "remotetypograf.php";
	$remoteTypograf = new RemoteTypograf ('UTF-8');
	$remoteTypograf->htmlEntities();
	$remoteTypograf->br (false);
	$remoteTypograf->p (true);
	$remoteTypograf->nobr (3);
	print $remoteTypograf->processText ($text);
}

?>
<?php
/**
 * AVE.cms
 *
 * Класс работы с системными блоками
 *
 * @package AVE.cms
 * @filesource
 */
class AVE_SysBlock
{
	/**
	 * Вывод списка системных блоков
	 *
	 */
	function sys_blockList()
	{
		global $AVE_DB, $AVE_Template;

		$sys_blocks = array();
		$sql = $AVE_DB->Query("SELECT * FROM " . PREFIX . "_sysblocks");

        // Формируем массив из полученных данных
        while ($result = $sql->FetchRow())
		{
			$result->sysblock_author_id = get_username_by_id($result->sysblock_author_id);
			array_push($sys_blocks, $result);
		}

		$AVE_Template->assign('sys_blocks', $sys_blocks);
		$AVE_Template->assign('content', $AVE_Template->fetch('sysblocks/list.tpl'));
	}

	/**
	 * Сохранение системного блока
	 *
	 * @param int $sysblock_id идентификатор системного блока
	 */
	function sys_blockSave($sysblock_id = null)
	{
		global $AVE_DB, $AVE_Template;

		if (is_numeric($sysblock_id))
		{
			$save = $AVE_DB->Query("
				UPDATE " . PREFIX . "_sysblocks
				SET
					sysblock_name = '" . $_POST['sysblock_name'] . "',
					sysblock_text = '" . $_POST['sysblock_text'] . "'
				WHERE
					id = '" . $sysblock_id . "'
			");

			// Сохраняем системное сообщение в журнал
			reportLog($_SESSION['user_name'] . " - " . $AVE_Template->get_config_vars('SYSBLOCK_SQLUPDATE') . " (" . stripslashes($_POST['sysblock_name']) . ") (id: $sysblock_id)", 2, 2);
		}
		else
		{
			$AVE_DB->Query("
				INSERT
				INTO " . PREFIX . "_sysblocks
				SET
					id = '',
					sysblock_name = '" . $_POST['sysblock_name'] . "',
					sysblock_text = '" . $_POST['sysblock_text'] . "',
					sysblock_author_id = '" . (int)$_SESSION['user_id'] . "',
					sysblock_created = '" . time() . "'
			");
			$sysblock_id = $AVE_DB->Query("SELECT LAST_INSERT_ID(id) FROM " . PREFIX . "_sysblocks ORDER BY id DESC LIMIT 1")->GetCell();

			// Сохраняем системное сообщение в журнал
			reportLog($_SESSION['user_name'] . " - " . $AVE_Template->get_config_vars('SYSBLOCK_SQLNEW') . " (" . stripslashes($_POST['sysblock_name']) . ") (id: $sysblock_id)", 2, 2);
		}
		if (!isset($_REQUEST['next_edit'])) {
			header('Location:index.php?do=sysblocks&cp=' . SESSION);
		} else {
			header('Location:index.php?do=sysblocks&action=edit&&id='.$sysblock_id.'&cp='. SESSION);
		}

	}

	/**
	 * Редактирование системного блока
	 *
	 * @param int $sysblock_id идентификатор системного блока
	 *
	 */
	function sys_blockEdit($sysblock_id)
	{
		global $AVE_DB, $AVE_Template;

			$sql = $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_sysblocks
				WHERE id = '" . $sysblock_id . "'
			");

			$row = $sql->FetchAssocArray();

		$AVE_Template->assign($row);
		$AVE_Template->assign('content', $AVE_Template->fetch('sysblocks/form.tpl'));
	}

	/**
	 * Создание системного блока
	 *
	 */
	function sys_blockNew()
	{
		global $AVE_DB, $AVE_Template;

		$row['sysblock_name'] = '';
		$row['sysblock_text'] = '';

		$AVE_Template->assign($row);
		$AVE_Template->assign('content', $AVE_Template->fetch('sysblocks/form.tpl'));
	}

	/**
	 * Удаление системного блока
	 *
	 * @param int $sysblock_id идентификатор системного блока
	 */
	function sys_blockDelete($sysblock_id)
	{
		global $AVE_DB, $AVE_Template;

		if (is_numeric($sysblock_id))
		{
			 $sql= $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_sysblocks
				WHERE id = '" . $sysblock_id . "'
			")->FetchRow();

			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_sysblocks
				WHERE id = '" . $sysblock_id . "'
			");

			// Сохраняем системное сообщение в журнал
			reportLog($_SESSION['user_name'] . " - " . $AVE_Template->get_config_vars('SYSBLOCK_SQLDEL') . " (" . stripslashes($sql->sysblock_name) . ") (id: $sysblock_id)", 2, 2);
		}
		header('Location:index.php?do=sysblocks&cp=' . SESSION);
	}
}

?>
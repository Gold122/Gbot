<?php


	class get_static
	{

		function start($db,$ts,$config,$ft,$cache,$function,$i)
		{
			$serverInfo = $ts->getElement('data',$ts->serverInfo());
			$db->prepare("INSERT INTO static(id,nick,mem,online) VALUES (:id,:nick,:mem,:online) ON DUPLICATE KEY UPDATE nick = :nick,mem=:mem,online=:online")->execute(array(':id' => $i,':nick' => $config['connection']['bot_name'],':mem' => $ft->RamUsage(),':online' => $serverInfo['virtualserver_clientsonline']-$serverInfo['virtualserver_queryclientsonline']));
			unset($serverInfo,$db);
		}

	}
<?php


	class get_static
	{

		function start($db,$ts,$config,$ft,$cache,$function,$i)
		{
			$db->prepare("INSERT INTO static(id,nick,mem,online) VALUES (:id,:nick,:mem,:online) ON DUPLICATE KEY UPDATE nick = :nick,mem=:mem,online=:online")->execute(array(':id' => $i,':nick' => $config['connection']['bot_name'],':mem' => $ft->RamUsage(),':online' => $cache['serverInfo']['virtualserver_clientsonline']-$cache['serverInfo']['virtualserver_queryclientsonline']));
		}

	}
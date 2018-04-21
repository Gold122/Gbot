<?php


	class ServerName
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			$edit = NULL;
			foreach($config['functions']['ServerName']['functions'] as $name => $list)
			{
				if($list['enabled'] == true)
				{
					$edit[$name] = self::replace($list['name'],$cache['serverInfo']);
				}
			}
			if($edit != NULL)
			{
				$ts->serverEdit($edit);
			}
			unset($edit,$name,$list);
		}

		private function replace($msg,$serverInfo)
		{
			$replace = array(
				1 => array(1 => '[online]', 2 => $serverInfo['virtualserver_clientsonline'] - $serverInfo['virtualserver_queryclientsonline']),
				2 => array(1 => '[maxslots]', 2 => $serverInfo['virtualserver_maxclients']),
				3 => array(1 => '[channels]', 2 => $serverInfo['virtualserver_channelsonline']),
				4 => array(1 => '[name]', 2 => $serverInfo['virtualserver_name']),
				5 => array(1 => '[percent]', 2 => round((($serverInfo['virtualserver_clientsonline'] - $serverInfo['virtualserver_queryclientsonline']) / $serverInfo['virtualserver_maxclients'])*100),1),
			);	
			foreach($replace as $stats)
			{
				$msg = str_replace($stats[1], $stats[2], $msg);
			}
			return $msg;	
		}

	}
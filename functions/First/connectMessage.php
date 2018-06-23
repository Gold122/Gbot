<?php


	class connectMessage
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			$clients = array();

			foreach($ts->getElement('data',$ts->clientList('-country')) as $clientList) 
			{
				if($clientList['client_type'] == 0)
				{
					$clients[] = $clientList['clid'];
				}
			}

			foreach(array_diff($clients,$cache['firstClient']) as $clients)
			{
				$info = $ts->getElement('data',$ts->clientInfo($clients));
				foreach($config['functions']['connectMessage']['message'] as $message)
				{
					$ts->sendMessage(1,$clients,self::replace($message,$info,$ts->getElement('data',$ts->serverInfo())));
				}
			}

		}

		private function replace($msg,$clientList,$serverInfo)
		{
			$replace = array(
				1 => array(1 => '[online]', 2 => $serverInfo['virtualserver_clientsonline'] - $serverInfo['virtualserver_queryclientsonline']),
				2 => array(1 => '[nickname]', 2 => $clientList['client_nickname']),
				3 => array(1 => '[country]', 2 => $clientList['client_country']),
				4 => array(1 => '[maxslots]', 2 => $serverInfo['virtualserver_maxclients']),
				5 => array(1 => '[channels]', 2 => $serverInfo['virtualserver_channelsonline']),
				6 => array(1 => '[name]', 2 => $serverInfo['virtualserver_name']),
			);	
			foreach($replace as $stats)
			{
				$msg = str_replace($stats[1], $stats[2], $msg);
			}
			return $msg;	
		}

	}
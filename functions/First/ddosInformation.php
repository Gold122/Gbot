<?php


	class ddosInformation
	{

		function start($db,$ts,$config,$ft,$cache,$function)
		{
			foreach($config['functions'][$function]['functions'] as $name => $functions)
			{
				if($cache['serverInfo'][$name] > $functions['threshold'])
				{
					foreach($cache['clientList'] as $admins)
					{
						foreach($functions['groups'] as $groups)
						{
							if(in_array($groups,explode(',',$admins['client_servergroups'])))
							{
								foreach($functions['message'] as $message)
								{
									$ts->sendMessage(1,$admins['clid'],self::replace($message,$cache['serverInfo']));
								}
							}
						}
					}
				}
			}
		}

		private function replace($msg,$serverInfo)
		{
			$replace = array(
				1 => array(1 => '[query]', 2 => $serverInfo['virtualserver_queryclientsonline']),
				2 => array(1 => '[online]', 2 => $serverInfo['virtualserver_clientsonline'] - $serverInfo['virtualserver_queryclientsonline']),
				3 => array(1 => '[packet_lost]', 2 => round($serverInfo['virtualserver_total_packetloss_total']*100,1)),
				4 => array(1 => '[packet_lost_speach]', 2 => round($serverInfo['virtualserver_total_packetloss_speech']*100,1)),
				5 => array(1 => '[ping]', 2 => round($serverInfo['virtualserver_total_ping'],1)),
				6 => array(1 => '[channel]', 2 => $serverInfo['virtualserver_channelsonline']),
				7 => array(1 => '[maxslots]', 2 => $serverInfo['virtualserver_maxclients']),
			);	
			foreach($replace as $stats)
			{
				$msg = str_replace($stats[1], $stats[2], $msg);
			}
			return $msg;	
		}

	}
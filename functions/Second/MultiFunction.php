<?php


	class MultiFunction
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			foreach($config['functions']['MultiFunction']['functions'] as $id => $functions)
			{
				if($functions['enabled'] == true)
				{
					$ts->channelEdit($id,array('channel_name' => self::replace($functions['channel_name'],$cache['serverInfo'])));
				}
			}
			unset($id,$functions);
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
<?php


	class RecordOnline
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			$record = $db->prepare("SELECT * FROM `record` LIMIT 1");
			$record->execute();
			$record = $record->fetch(PDO::FETCH_ASSOC);
			if(!isset($record) || $cache['serverInfo']['virtualserver_clientsonline'] - $cache['serverInfo']['virtualserver_queryclientsonline'] > $record['online'])
			{
					$ts->channelEdit($config['functions']['RecordOnline']['channel_id'],array('channel_name' => self::replace($config['functions']['RecordOnline']['channel_name'],$cache['serverInfo']),'channel_description' => self::replace($config['functions']['RecordOnline']['channel_description'].$cache['footer'],$cache['serverInfo'])));
					$db->prepare("UPDATE `record` SET online=:online,time = :time")->execute(array('online' => $cache['serverInfo']['virtualserver_clientsonline'] - $cache['serverInfo']['virtualserver_queryclientsonline'],'time' => time()));
			}
			unset($record);
		}

		private function replace($msg,$serverInfo)
		{
			$replace = array(
				1 => array(1 => '[record]', 2 => $serverInfo['virtualserver_clientsonline'] - $serverInfo['virtualserver_queryclientsonline']),
				2 => array(1 => '[date]', 2 => date('d-m-Y H:i',time())),
			);	
			foreach($replace as $stats)
			{
				$msg = str_replace($stats[1], $stats[2], $msg);
			}
			return $msg;	
		}

	}
<?php


	class RecordOnline
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			$record = $db->prepare("SELECT * FROM `record` WHERE `uid` = :uid");
			$record->execute(array(':uid' => $cache['serverInfo']['virtualserver_unique_identifier']));
			$record = $record->fetch(PDO::FETCH_ASSOC);
			if($cache['serverInfo']['virtualserver_clientsonline'] - $cache['serverInfo']['virtualserver_queryclientsonline'] > $record['online'])
			{
				$ts->channelEdit($config['functions']['RecordOnline']['channel_id'],array('channel_name' => self::replace($config['functions']['RecordOnline']['channel_name'],$cache['serverInfo']),'channel_description' => self::replace($config['functions']['RecordOnline']['channel_description'].$cache['footer'],$cache['serverInfo'])));
				$db->prepare("INSERT INTO record(uid,online,time) VALUES (:uid,:online,:time) ON DUPLICATE KEY UPDATE online=:online,time = :time")->execute(array(':uid' => $cache['serverInfo']['virtualserver_unique_identifier'], ':online' => $cache['serverInfo']['virtualserver_clientsonline'] - $cache['serverInfo']['virtualserver_queryclientsonline'],':time' => time()));
			}
			unset($record,$db);
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
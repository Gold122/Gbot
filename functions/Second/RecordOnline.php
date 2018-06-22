<?php


	class RecordOnline
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			$serverInfo = $ts->getElement('data',$ts->serverInfo());
			$record = $db->prepare("SELECT * FROM `record` WHERE `uid` = :uid");
			$record->execute(array(':uid' => $serverInfo['virtualserver_unique_identifier']));
			$record = $record->fetch(PDO::FETCH_ASSOC);
			if($serverInfo['virtualserver_clientsonline'] - $serverInfo['virtualserver_queryclientsonline'] > $record['online'])
			{
				$ts->channelEdit($config['functions']['RecordOnline']['channel_id'],array('channel_name' => self::replace($config['functions']['RecordOnline']['channel_name'],$serverInfo),'channel_description' => self::replace($config['functions']['RecordOnline']['channel_description'].$cache['footer'],$serverInfo)));
				$db->prepare("INSERT INTO record(uid,online,time) VALUES (:uid,:online,:time) ON DUPLICATE KEY UPDATE online=:online,time = :time")->execute(array(':uid' => $serverInfo['virtualserver_unique_identifier'], ':online' => $serverInfo['virtualserver_clientsonline'] - $serverInfo['virtualserver_queryclientsonline'],':time' => time()));
			}
			unset($record,$db,$serverInfo);
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
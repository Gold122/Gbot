<?php


	class NewUserToday
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			$clients = array();
			$all = array();
			foreach($ts->getElement('data',$ts->clientList('-uid -times')) as $clientList)
			{
				if($clientList['client_type'] == 0)
				{
					if(date('d-m-Y') == date('d-m-Y',$clientList['client_created']))
					{
						$all[$clientList['client_database_id']] = $clientList;
						$clients[] = $clientList['client_database_id'];
					}
				}
			}

			$edit = '[center][size=15] [B]Nowi użytkownicy dzisiaj[/B][/size][/center] \n[list]';
			$last = array();
			$find = $db->prepare("SELECT * FROM `newuserToday`");
			$find->execute();
			foreach($find->fetchAll(PDO::FETCH_ASSOC) as $find)
			{
				if(date('d-m-Y') == date('d-m-Y',$find['time']))
				{
					$edit .= '[*][url=client://0/'.$find['client_unique_identifier'].']'.str_replace(array('[',']'),'',$find['client_nickname']).'[/url]\n';
					$last[] = $find['dbid'];
				}
			}

			$add = array_diff($clients,$last);
			if($add != NULL)
			{
				foreach($add as $add)
				{
					$db->prepare("INSERT INTO newuserToday(dbid,client_nickname,client_unique_identifier,time) VALUES (:dbid,:client_nickname,:client_unique_identifier,:time)")->execute(array(':dbid' => $all[$add]['client_database_id'],':client_unique_identifier' => $all[$add]['client_unique_identifier'],':client_nickname' => $all[$add]['client_nickname'],':time' => time()));
				}
			}
			$edit .= '[/list]'.$cache['footer'];
			$ts->channelEdit($config['functions']['NewUserToday']['channel_id'],array('channel_name' => self::replace($config['functions']['NewUserToday']['channel_name'],$last),'channel_description' => $edit));
			unset($edit,$add,$clients,$last,$find,$clientList);
		}

		private function replace($msg,$clientList)
		{
			$replace = array(
				1 => array(1 => '[count]', 2 => count($clientList)),
			);	
			foreach($replace as $stats)
			{
				$msg = str_replace($stats[1], $stats[2], $msg);
			}
			return $msg;	
		}

	}
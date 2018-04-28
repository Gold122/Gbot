<?php


	class adminsOnline
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			$edit = $config['functions']['adminsOnline']['channel_description'];
			$i = 0;
			foreach($config['functions']['adminsOnline']['admin_groups'] as $groups)
			{
				foreach($cache['clientList'] as $admins)
				{
					if(in_array($groups,explode(',',$admins['client_servergroups'])))
					{
						$clientInfo = $ts->getElement('data',$ts->clientInfo($admins['clid']));
						$edit .= '[*][url=client://0/'.$admins['client_unique_identifier'].']'.str_replace(array('[',']'),'',$admins['client_nickname']).'[/url] jest na kanale [url=channelID://'.$admins['cid'].']'.$ts->getElement('data',$ts->channelInfo($admins['cid']))['channel_name'].'[/url] od '.$ft->secToHR($clientInfo['connection_connected_time']/1000).' \n';
						$i++;
					}
				}
			}
			$edit .= '[/list]';
			$edit .= $cache['footer'];

			$ts->channelEdit($config['functions']['adminsOnline']['channel_id'],array('channel_description' => $edit));
			$ts->channelEdit($config['functions']['adminsOnline']['channel_id'],array('channel_name' => self::replace($config['functions']['adminsOnline']['channel_name'],$i)));
			unset($edit,$i,$clientInfo,$admins,$groups);
		}

		private function replace($msg,$i)
		{
			$replace = array(
				1 => array(1 => '[online]', 2 => $i),
			);	
			foreach($replace as $stats)
			{
				$msg = str_replace($stats[1], $stats[2], $msg);
			}
			return $msg;	
		}

	}
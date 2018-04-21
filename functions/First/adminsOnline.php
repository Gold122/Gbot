<?php


	class adminsOnline
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			$edit = $config['functions']['adminsOnline']['channel_description'];
			$i = 0;
			$admin = array();
			foreach($config['functions']['adminsOnline']['admin_groups'] as $groups)
			{
				foreach($cache['clientList'] as $admins)
				{
					if(in_array($groups,explode(',',$admins['client_servergroups'])))
					{
						$edit .= '[*][url=client://0/'.$admins['client_unique_identifier'].']'.$admins['client_nickname'].'[/url]\n';
						$i++;
						$admin[] = $admins;
					}
				}
			}
			$edit .= '[/list]';
			$edit .= $cache['footer'];

			$ts->channelEdit($config['functions']['adminsOnline']['channel_id'],array('channel_description' => $edit));
			$ts->channelEdit($config['functions']['adminsOnline']['channel_id'],array('channel_name' => self::replace($config['functions']['adminsOnline']['channel_name'],$i)));
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
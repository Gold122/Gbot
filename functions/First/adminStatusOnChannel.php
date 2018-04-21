<?php


	class adminStatusOnChannel
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			$info = array();
			foreach($cache['clientList'] as $clientList)
			{
				$info[] = $clientList;
			}

			foreach($config['functions']['adminStatusOnChannel']['channels'] as $dbid => $channel)
			{
				foreach($config['functions']['adminStatusOnChannel']['admin_groups'] as $admins)
				{
					foreach($ts->getElement('data',$ts->serverGroupsByClientID($dbid)) as $groups)
					{
						if($admins == $groups['sgid'])
						{
							$status = 'Offline';
							foreach($cache['clientList'] as $clientList)
							{
								if($clientList['client_database_id'] == $dbid)
								{
									$status = 'Online';
									$ts->channelEdit($channel['channel_id'],array('channel_name' => self::replace($channel['show'],$clientList,$groups,$status)));
									break;
								}
							}

							foreach($ts->getElement('data',$ts->serverGroupClientList($groups['sgid'],true)) as $clientList)
							{
								if($clientList['cldbid'] == $dbid)
								{
									$ts->channelEdit($channel['channel_id'],array('channel_name' => self::replace($channel['show'],$clientList,$groups,$status)));
									break;
								}
							}
						}
					}
				}
			}
		}

		private function replace($msg,$clientList,$groups,$status)
		{
			$replace = array(
				1 => array(1 => '[group]', 2 => $groups['name']),
				2 => array(1 => '[name]', 2 => $clientList['client_nickname']),
				3 => array(1 => '[status]', 2 => $status),
			);	
			foreach($replace as $stats)
			{
				$msg = str_replace($stats[1], $stats[2], $msg);
			}
			return $msg;	
		}

	}
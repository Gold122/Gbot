<?php


	class ChannelGuard
	{
		function start($db,$ts,$config,$ft,$cache)
		{
			$i = 0;
			$free = array();
			$all = array();
			foreach($ts->getElement('data',$ts->channelList('-topic')) as $channelList)
			{
				if($channelList['pid'] == $config['functions']['ChannelGuard']['channel_creator']['channel_Section'])
				{
					$i++;
					$channelGroup = $ts->getElement('data',$ts->channelGroupClientList($channelList['cid']));
					if(explode('.',$channelList['channel_name'])[0] != $i)
					{
						if(isset(explode('.',$channelList['channel_name'])[1]) && $channelGroup != false)
						{
							$name = explode('.',$channelList['channel_name'])[1];
						}
						elseif($channelGroup != false)
						{
							$name = 'Zmień nazwe';
						}
						else
						{
							$name = $config['functions']['ChannelGuard']['channel_creator']['free_channel_name'];
						}
						$ts->channelEdit($channelList['cid'],array('channel_name' => $i.'.'.$name));
					}

					if(explode('.',$channelList['channel_name'])[0] == $i && isset(explode('.',$channelList['channel_name'])[1]) && $channelGroup == false)
					{
						$free[] = $channelList;
					}

					if($channelGroup != false)
					{
						foreach($channelGroup as $channelGroup)
						{
							if($config['functions']['ChannelGuard']['channel_creator']['head_channel_admin_group'] == $channelGroup['cgid'])
							{
								foreach($ts->getElement('data',$ts->clientList()) as $clientList)
								{
									if($clientList['client_database_id'] == $channelGroup['cldbid'])
									{
										if($channelList['channel_topic'] != date('d-m-Y'))
										{
											$ts->channelEdit($channelList['cid'],array('channel_topic' => date('d-m-Y')));
										}

										$db->prepare("INSERT INTO channelPrivate(dbid,channels,time) VALUES (:dbid,:channels,:time) ON DUPLICATE KEY UPDATE time = :time")->execute(array(':dbid' => $channelGroup['cldbid'],':channels' => $channelGroup['cid'],':time' => time()));
										break;
									}
								}
								break;
							}
						}
					}

					$all[] = $channelList;
				}
			}

			$check = $db->prepare("SELECT * FROM channelPrivate");
			$check->execute();
			foreach($check->fetchAll(PDO::FETCH_ASSOC) as $check)
			{
				if($check['time'] < time()-((($config['functions']['ChannelGuard']['channel_scanner']['delete_channel']*60)*60)*24))
				{
					$ts->channelDelete($check['channels']);
					$db->prepare("DELETE FROM `channelPrivate` WHERE `dbid` = :dbid")->execute(array(':dbid' => $check['dbid']));
				}
			}
			
			if(count($free) < $config['functions']['ChannelGuard']['channel_creator']['minimum_channel'])
			{
				$last = explode('.',end($all)['channel_name'])[0];
				for($i=count($free); $i < $config['functions']['ChannelGuard']['channel_creator']['minimum_channel']; $i++)
				{
					$last++;
					$ts->channelCreate(array(
						'cpid' => $config['functions']['ChannelGuard']['channel_creator']['channel_Section'],
						'channel_name' => $last.'.'.$config['functions']['ChannelGuard']['channel_creator']['free_channel_name'],
						'channel_description' => $config['functions']['ChannelGuard']['channel_creator']['free_channel_description'],
						'CHANNEL_FLAG_PERMANENT' => 1,
						'channel_maxclients' => 0,
						'channel_maxfamilyclients' => 0,
						'channel_flag_maxclients_unlimited' => 0,
						'channel_flag_maxfamilyclients_unlimited' => 0,)
					);

				}
			}
			unset($channelList,$name,$free,$all,$i,$last,$check,$clientList);
		}

		private function replace($msg,$limit)
		{
			$replace = array(
				1 => array(1 => '[number]', 2 => $limit+1),
			);	
			foreach($replace as $stats)
			{
				$msg = str_replace($stats[1], $stats[2], $msg);
			}
			return $msg;	
		}
	}
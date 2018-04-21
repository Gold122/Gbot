<?php


	class GetPrivateChannel
	{
		function start($db,$ts,$config,$ft,$cache)
		{
			$i = 0;
			$free = array();
			foreach($cache['clientList'] as $clientList)
			{
				if($clientList['cid'] == $config['functions']['GetPrivateChannel']['getChannel_id'] && !array_intersect($config['functions']['GetPrivateChannel']['need_ranks'],explode(',',$clientList['client_servergroups'])))
				{
					foreach($cache['channelList'] as $channelList)
					{
						if($channelList['pid'] == $config['functions']['ChannelGuard']['channel_creator']['channel_Section'])
						{
							$group = $ts->getElement('data',$ts->channelGroupClientList($channelList['cid']));
							if($group != false)
							{
								foreach($group as $groups)
								{
									if($groups['cldbid'] == $clientList['client_database_id'])
									{
										$actual = array('id' => $channelList['cid'],'group' => $group,'channel_name' => $channelList['channel_name']);
										break;
									}
								}
							}
							if(isset(explode('.',$channelList['channel_name'])[1]) && $group == false)
							{
								$free[] = $channelList;
							}
						}
					}
					
					if($free != NULL)
					{
						if(!isset($actual))
						{
							$ts->channelGroupAddClient($config['functions']['ChannelGuard']['channel_creator']['head_channel_admin_group'],$free[0]['cid'],$clientList['client_database_id']);
							$ts->clientMove($clientList['clid'],$free[0]['cid']);
							$ts->channelEdit($free[0]['cid'],array('channel_name' => explode('.',$free[0]['channel_name'])[0].'.Kanał '.$clientList['client_nickname']));
							for($i=1; $i < $config['functions']['ChannelGuard']['channel_creator']['sub_channels']+1; $i++)
							{
								$ts->channelCreate(array(
									'cpid' => $free[0]['cid'],
									'channel_name' => $i.'. Podkanał',
									'channel_description' => self::replace($config['functions']['GetPrivateChannel']['channel_description'],$clientList['client_nickname']),
									'CHANNEL_FLAG_PERMANENT' => 1,
								));

							}
						}
						else
						{
							$ts->sendMessage(1,$clientList['clid'],'Posiadasz już kanał!');
							$ts->clientMove($clientList['clid'],$actual['id']);
						}
					}
					unset($actual);
					break;
				}
			}
			unset($clientList,$channelList,$group,$groups,$free,$i);
		}

		private function replace($msg,$limit)
		{
			$replace = array(
				1 => array(1 => '[nick]', 2 => $limit),
			);	
			foreach($replace as $stats)
			{
				$msg = str_replace($stats[1], $stats[2], $msg);
			}
			return $msg;	
		}
	}
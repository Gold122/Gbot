<?php


	class ChannelGuard
	{
		function start($db,$ts,$config,$ft,$cache)
		{
			$i = 0;
			$free = array();
			$all = array();
			foreach($cache['channelList'] as $channelList)
			{
				if($channelList['pid'] == $config['functions']['ChannelGuard']['channel_creator']['channel_Section'])
				{
					$i++;
					if(explode('.',$channelList['channel_name'])[0] != $i)
					{
						if(isset(explode('.',$channelList['channel_name'])[1]) && $ts->getElement('data',$ts->channelGroupClientList($channelList['cid'])) != false)
						{
							$name = explode('.',$channelList['channel_name'])[1];
						}
						else
						{
							$name = $config['functions']['ChannelGuard']['channel_creator']['free_channel_name'];
						}
						$ts->channelEdit($channelList['cid'],array('channel_name' => $i.'.'.$name));
					}
					elseif(explode('.',$channelList['channel_name'])[0] == $i && isset(explode('.',$channelList['channel_name'])[1]) && $ts->getElement('data',$ts->channelGroupClientList($channelList['cid'])) == false)
					{
						$free[] = $channelList;
					}
					$all[] = $channelList;
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
			unset($channelList,$name,$free,$all,$i,$last);
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
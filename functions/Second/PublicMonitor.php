<?php


	class PublicMonitor
	{
		function start($db,$ts,$config,$ft,$cache,$functions,$instance)
		{
			foreach($config['functions']['PublicMonitor']['channels'] as $id => $channels)
			{
				foreach($ts->getElement('data',$ts->channelList()) as $channelList)
				{
					if($channelList['pid'] == $id)
					{
						$all[$id][] = &$channelList;
						if($channelList["total_clients"] == 0)
						{
							$free[$id][] = &$channelList;
						}
					}
				}
				if(isset($free[$id]) && count($free[$id]) > $channels['free_channels'])
				{
					for($i = count($free[$id])-1; $i>=$channels['free_channels']; $i--)
					{
						$ts->channelDelete($free[$id][$i]['cid'],0);
					}
				}

				if(!isset($free[$id]) || count($free[$id]) < $channels['free_channels'])
				{
					$numer=0;
					$numer = @count($all[$id])-1;
					for($i = @count($free[$id]); $i<$channels['free_channels']; $i++)
					{
						$numer++;
						if($channels['channel_maxclients'] == -1)
						{
							$ts->channelCreate(array('cpid' => $id,'channel_name' => self::replace($channels['channel_name'],$numer),'CHANNEL_FLAG_PERMANENT' => 1));
						}
						else
						{
							$ts->channelCreate(array('cpid' => $id,'channel_name' => self::replace($channels['channel_name'],$numer),'CHANNEL_FLAG_PERMANENT' => 1,'channel_maxclients' => $channels['channel_maxclients'],'channel_maxfamilyclients' => $channels['channel_maxclients'],'channel_flag_maxclients_unlimited' => 0,'channel_flag_maxfamilyclients_unlimited' => 0));
						}
					}
				}

				$i=0;
				foreach($ts->getElement('data',$ts->channelList()) as $channelList)
				{
					if($channelList['pid'] == $id)
					{
						$ts->channelEdit($channelList['cid'],array('channel_name' => self::replace($channels['channel_name'],$i)));
						$i++;
					}
				}
			}
			$channels = NULL;
			$channelList = NULL;
			$all = NULL;
			$free = NULL;
			$numer = NULL;
			$i= NULL;
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
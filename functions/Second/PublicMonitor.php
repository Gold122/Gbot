<?php


	class PublicMonitor
	{
		function start($db,$ts,$config,$ft,$cache)
		{
			foreach($config['functions']['PublicMonitor']['channels'] as $id => $channels)
			{
				foreach($cache['channelList'] as $channelList)
				{
					if($channelList['pid'] == $id)
					{
						@$all[$id][] = $channelList;
						if($channelList["total_clients"] == 0)
						{
							$free[$id][] = $channelList;
						}
					}
				}
				if(isset($free[$id]) && count($free[$id]) > 1)
				{
					for($i = count($free[$id])-1; $i>=1; $i--)
					{
						$ts->channelDelete($free[$id][$i]['cid'],0);
					}
				}

				if(!isset($free[$id]))
				{
					if(!isset($all[$id]))
					{
						$all = 0;
					}
					$channels['channel_name'] = self::replace($channels['channel_name'],count($all[$id]));
					$channels['cpid'] = $id;
					$ts->channelCreate($channels);
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
			unset($id,$channels,$channelList,$all,$free,$i);
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
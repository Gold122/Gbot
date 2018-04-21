<?php


	class static_channel
	{

		function start($db,$ts,$config,$ft,$cache,$function)
		{
				$all = 0;
				$mem = $db->prepare("SELECT * FROM static");
				$mem->execute();
				$edit = $config['functions']['static_channel']['channel_description'];
				foreach($mem->fetchAll(PDO::FETCH_ASSOC) as $mem)
				{
					$edit .= '[*]'.$mem['nick'].' ('.$mem['mem'].' MB) \n';
					$all = $all+$mem['mem'];
				}
				$edit .= '[/list]';
				$edit .= $cache['footer'];
				$ts->channelEdit($config['functions']['static_channel']['channel_id'],array('channel_name' => self::replace($config['functions']['static_channel']['channel_name'],$all),'channel_description' => self::replace($edit,$all)));
				unset($mem,$all);
		}

		private function replace($msg,$all)
		{
			$replace = array(
				1 => array(1 => '[mem_usage]', 2 => $all),
			);	
			foreach($replace as $stats)
			{
				$msg = str_replace($stats[1], $stats[2], $msg);
			}
			return $msg;	
		}

	}
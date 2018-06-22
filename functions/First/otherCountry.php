<?php


	class otherCountry
	{

		function start($db,$ts,$config,$ft,$cache,$function)
		{
			$edit = $config['functions']['otherCountry']['channel_description'];
			$i = 0;
			foreach($ts->getElement('data',$ts->clientList('-uid -country -groups')) as $clientList)
			{
				if($clientList['client_type'] == 0)
				{
					if($clientList['client_country'] != 'PL')
					{
						if(!array_intersect($config['functions']['otherCountry']['ignored_ranks'],explode(',',$clientList['client_servergroups'])))
						{
							$i++;
							$edit .= '[*] [img]http://www.countryflags.io/'.$clientList['client_country'].'/flat/16.png[/img] [URL=client://0/'.$clientList['client_unique_identifier'].']'.$clientList['client_nickname'].'[/URL] [COLOR=#ff5500]'.$clientList['client_country'].'[/COLOR]';
						}
					}
				}
			}
			$ts->channelEdit($config['functions']['otherCountry']['channel_id'],array('channel_name' => self::replace($config['functions']['otherCountry']['channel_name'],$i),'channel_description' => $edit));
			unset($clientList,$edit,$i);
		}

		private function replace($msg,$i)
		{
			$replace = array(
				1 => array(1 => '[number]', 2 => $i),
			);	
			foreach($replace as $stats)
			{
				$msg = str_replace($stats[1], $stats[2], $msg);
			}
			return $msg;	
		}

	}
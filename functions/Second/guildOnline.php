<?php


	class guildOnline
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			foreach($config['functions']['guildOnline']['guilds'] as $guilds)
			{
				$GroupClient = $ts->getElement('data',$ts->serverGroupClientList($guilds['group_id']));
				foreach($ts->getElement('data',$ts->serverGroupList()) as $groups)
				{
					if($groups['sgid'] == $guilds['group_id'])
					{
						$group = $groups;
					}
				}

				if(isset($group))
				{
					if($GroupClient != false)
					{
						$i = 0;
						$max = 0;
						$edit = '';
						foreach($GroupClient as $GroupClient)
						{
							if(isset($GroupClient["cldbid"]))
							{
								$max++;
								$client = NULL;
								foreach($cache['clientList'] as $clientList)
								{
									if($GroupClient["cldbid"] == $clientList['client_database_id'])
									{
										$clientInfo = $ts->getElement('data',$ts->clientInfo($clientList['clid']));
										$i++;
										$client = '[*][img]https://i.imgur.com/DshoYEe.png[/img]    [b][URL=client://0/'.$clientList['client_unique_identifier'].']'.$clientList['client_nickname'].'[/URL] jest aktualnie [color=green][b]ONLINE[/b][/color] od '.$ft->secToHR($clientInfo['connection_connected_time']/1000);
										unset($clientInfo);
										break;
									}
								}
								if($client == NULL)
								{
									$clientDbInfo = $ts->getElement('data',$ts->clientDbInfo($GroupClient["cldbid"]));
									$edit .= '[*][img]https://i.imgur.com/DshoYEe.png[/img]    [b][URL=client://0/'.$clientDbInfo['client_unique_identifier'].']'.$clientDbInfo['client_nickname'].'[/URL] jest aktualnie [color=red][b]OFFLINE[/b][/color]  od '.$ft->secToHR(time()-$clientDbInfo['client_lastconnected']);
									unset($clientDbInfot);
								}
								else
								{
									$edit .= $client;
								}
							}
						}
						$edits = self::replace($config['functions']['guildOnline']['channel_description'],$group,$i,$max);
						$edits .= $edit;
						$ts->channelEdit($guilds['channel_id'],array('channel_name' => self::replace($guilds['channel_name'],$group,$i,$max),'channel_description' => self::replace($edits,$group,$i,$max)));
					}
					else
					{
						echo PHP_EOL.' Brak gildi o id: '.$guilds['group_id'];
					}
				}
				else
				{
					echo PHP_EOL.' Brak gildi o id: '.$guilds['group_id'];
				}
			}
			unset($guilds,$groups,$group,$GroupClient,$cache,$clientList,$edit,$edits,$i,$max);

		}

		private function replace($msg,$sg,$online,$max)
		{
			$replace = array(
				1 => array(1 => '[name]', 2 => $sg['name']),
				2 => array(1 => '[online]', 2 => $online),
				3 => array(1 => '[max]', 2 => $max),
			);	
			foreach($replace as $stats)
			{
				$msg = str_replace($stats[1], $stats[2], $msg);
			}
			return $msg;	
		}

	}
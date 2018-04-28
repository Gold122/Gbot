<?php


	class guildsRank
	{

		function start($db,$ts,$config,$ft,$cache)
		{

			foreach($config['functions']['guildsRank']['guilds'] as $guilds)
			{
				$channel = array();
				foreach($ts->getElement('data',$ts->channelList()) as $channelList)
				{
					if($channelList['cid'] == $guilds['channel_id'])
					{
						$channel = $channelList;
						break;
					}
				}
				if($channel != NULL)
				{
					foreach($ts->getElement('data',$ts->channelClientList($guilds['channel_id'],'-groups')) as $clientList)
					{
						if(in_array($guilds['group_id'],explode(',',$clientList['client_servergroups'])))
						{
							$ts->serverGroupDeleteClient($guilds['group_id'],$clientList['client_database_id']);
							$ts->channelGroupAddClient($config['functions']['guildsRank']['default_channel_group'],$channel['pid'],$clientList['client_database_id']);
							$ts->clientKick($clientList['clid'],'channel','Ranga została ci pomyślnie zabrana!');
							$ts->sendMessage(1,$clientList['clid'],'Ranga została ci pomyślnie zabrana!');
						}
						else
						{
							$ts->serverGroupAddClient($guilds['group_id'],$clientList['client_database_id']);
							$ts->sendMessage(1,$clientList['clid'],'Ranga została ci pomyślnie nadana!');
							$ts->clientMove($clientList['clid'],$channel['pid']);
						}
					}
				}
				else
				{
					echo PHP_EOL.' Nie znaleziono kanału o id:'.$guilds['channel_id'];
				}
			}
			unset($guilds,$channel,$channelList,$clientList);

		}

	}
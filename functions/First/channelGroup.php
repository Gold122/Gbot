<?php


	class channelGroup
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			$groups = array();
			foreach($config['functions']['channelGroup']['channels'] as $rank)
			{
				$groups[] = $rank['group'];
			}
			foreach($config['functions']['channelGroup']['channels'] as $id => $rank)
			{
				foreach($cache['clientList'] as $clientList)
				{
					if($id == $clientList['cid'])
					{
						if(!array_intersect($groups,explode(',',$clientList['client_servergroups'])))
						{
							if(!in_array($rank['group'],explode(',',$clientList['client_servergroups'])))
							{
								$clientInfo = $ts->getElement('data',$ts->clientInfo($clientList['clid']));
								if(time() - $clientInfo['client_created'] > $rank['timeSpent'] && $clientInfo['client_totalconnections'] > $rank['connections'])
								{
									$ts->serverGroupAddClient($rank['group'],$clientList['client_database_id']);
									$ts->clientPoke($clientList['clid'],'Ranga została pomyślnie nadana!');
									$ts->clientKick($clientList['clid'],'channel','Ranga została pomyślnie nadana!');
								}
								else
								{
									$ts->clientPoke($clientList['clid'],'Nie spełniasz wymagań!');
									$ts->clientKick($clientList['clid'],'channel','Nie spełniasz wymagań!');
								}
							}
							else
							{
								$ts->clientPoke($clientList['clid'],'Posiadasz już rangę');
								$ts->clientKick($clientList['clid'],'channel','Posiadasz już rangę');
							}
						}
						else
						{
							$ts->clientPoke($clientList['clid'],'Posiadasz już rangę');
							$ts->clientKick($clientList['clid'],'channel','Posiadasz już rangę');
						}
						break;
					}
				}
			}
		}

	}
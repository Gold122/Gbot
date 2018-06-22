<?php


	class autoRegister
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			foreach($config['functions']['autoRegister']['ranks'] as $id => $rank)
			{
				foreach($ts->getElement('data',$ts->clientList('-groups -times')) as $clientList)
				{
					if($clientList['client_type'] == 0)
					{
						if(!in_array($id,explode(',',$clientList['client_servergroups'])) && !array_intersect($rank['ignored_ranks'],explode(',',$clientList['client_servergroups'])))
						{
							$clientInfo = $ts->getElement('data',$ts->clientInfo($clientList['clid']));
							if(time() - $clientInfo['client_created'] > $rank['timeSpent'] && $clientInfo['client_totalconnections'] > $rank['connections'])
							{
								foreach($ts->getElement('data',$ts->serverGroupList()) as $serverGroupList)
								{
									if($serverGroupList['sgid'] == $id)
									{
										$ts->serverGroupAddClient($serverGroupList['sgid'],$clientInfo['client_database_id']);
										$ts->sendMessage(1,$clientList['clid'],'Gratulacje otrzymałeś rangę '.$serverGroupList['name']);
										break;
									}
								}
							}
						}
					}
				}
			}
		}

	}
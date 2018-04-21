<?php


	class TopStatistic
	{
		function start($db,$ts,$config,$ft,$cache)
		{
			foreach($cache['clientList'] as $clientList)
			{
				if($clientList['client_type'] != 1)
				{
					if(!array_intersect($config['functions']['TopStatistic']['ignored_ranks'], explode(',',$clientList['client_servergroups'])))
					{
						$last = $db->prepare("SELECT * FROM `tops` WHERE client_database_id = :client_database_id");
						$last->execute(array(':client_database_id' => $clientList['client_database_id']));
						$last = $last->fetch(PDO::FETCH_ASSOC);
						$clientInfo = $ts->getElement('data',$ts->clientInfo($clientList['clid']));

						if(round($clientInfo['client_idle_time']/1000) >= $config['functions']['TopStatistic']['timeAFK'])
						{
							$afk = 60;
						}
						else
						{
							$afk = 0;
						}

						if(round($clientInfo['connection_connected_time']/1000) >= $last['clientTime'])
						{
							$time = round(($clientInfo['connection_connected_time']/1000),0);
						}
						else
						{
							if(!isset($last['clientTime']))
							{
								$time = 0;
							}
							else
							{
								$time = $last['clientTime'];

							}
						}

						if($last != NULL)
						{
							$db->prepare("INSERT INTO tops(client_database_id,client_unique_identifier,client_nickname,clientConnections,clientTime,clientTimeSpent,clientAFK) VALUES (:client_database_id,:client_unique_identifier,:client_nickname,:clientConnections,:clientTime,:clientTimeSpent,:clientAFK) ON DUPLICATE KEY UPDATE client_nickname = :client_nickname,clientConnections = :clientConnections,clientTime = :clientTime,clientTimeSpent = :clientTimeSpent,clientAFK = :clientAFK")->execute(array(
										':client_database_id' => $clientList['client_database_id'],
										':client_nickname' => $clientList['client_nickname'],
										':client_unique_identifier' => $clientList['client_unique_identifier'],
										':clientConnections' => $clientInfo['client_totalconnections'],
										':clientTime' => $time,
										':clientTimeSpent' => $last['clientTimeSpent']+60,
										':clientAFK' => $last['clientAFK']+$afk,
									));
						}
						else
						{
							$db->prepare("INSERT INTO tops(client_database_id,client_unique_identifier,client_nickname,clientConnections,clientTime,clientTimeSpent,clientAFK) VALUES (:client_database_id,:client_unique_identifier,:client_nickname,:clientConnections,:clientTime,:clientTimeSpent,:clientAFK) ON DUPLICATE KEY UPDATE client_nickname = :client_nickname,clientConnections = :clientConnections,clientTime = :clientTime,clientTimeSpent = :clientTimeSpent,clientAFK = :clientAFK")->execute(array(
										':client_database_id' => $clientList['client_database_id'],
										':client_nickname' => $clientList['client_nickname'],
										':client_unique_identifier' => $clientList['client_unique_identifier'],
										':clientConnections' => $clientInfo['client_totalconnections'],
										':clientTime' => $time,
										':clientTimeSpent' => 60,
										':clientAFK' => 0+$afk,
									));
						}
					}
				}
			}
			unset($last,$time,$afk,$clientInfo,$clientList);
		}
	}
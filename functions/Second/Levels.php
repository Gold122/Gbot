<?php


	class Levels
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			$last = array();
			$actual = array();
			foreach($config['functions']['Levels']['ranks'] as $id => $rank)
			{
				foreach($ts->getElement('data',$ts->clientList('-groups')) as $clientList)
				{
					if($clientList['client_type'] == 0)
					{
						if(in_array($id,explode(',',$clientList['client_servergroups'])))
						{
							$last[$clientList['client_database_id']][] = $id;
						}
						if(!array_intersect($config['functions']['Levels']['ignored_ranks'], explode(',',$clientList['client_servergroups'])))
						{
							$top = $db->prepare("SELECT * FROM `tops` WHERE client_database_id = :dbid");
							$top->execute(array(':dbid' => $clientList['client_database_id']));
							$top = $top->fetch(PDO::FETCH_ASSOC);
							if($top != NULL)
							{
								if($top['clientTimeSpent']-$top['clientAFK'] > $rank['timeSpent'] && $top['clientConnections'] > $rank['connections'])
								{
									$actual[$clientList['client_database_id']] = array('timeSpent' => $rank['timeSpent'],'clientConnections' => $top['clientConnections'],'rank' => $id);
								}
							}
						}
					}
				}
			}
			foreach($actual as $id => $rank)
			{
				$ts->serverGroupAddClient($rank['rank'],$id);
			}
			foreach($last as $id => $rank)
			{
				foreach($rank as $rank)
				{
					if($rank != $actual[$id]['rank'])
					{
						$ts->serverGroupDeleteClient($rank,$id);
					}
				}
			}
			unset($last,$actual,$id,$rank,$top,$clientList,$cache);
		}

	}
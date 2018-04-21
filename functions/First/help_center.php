<?php


	class help_center
	{

		function start($db,$ts,$config,$ft,$cache)
		{
			foreach($config['functions']['help_center']['channels'] as $id => $groups)
			{
				$admin = array();
				foreach($cache['clientList'] as $admins)
				{
					if(array_intersect($groups,explode(',',$admins['client_servergroups'])))
					{
						$admin[] = $admins;
					}
				}

				foreach($cache['clientList'] as $clientList)
				{
					if(!array_intersect($groups,explode(',',$clientList['client_servergroups'])) && !array_intersect(array($id),explode(',',$admins['cid'])))
					{
						if($id == $clientList['cid'])
						{
							if($admin != NULL)
							{
								foreach($admin as $admins)
								{
									$ts->clientPoke($admins['clid'],'Użytkownik [b] '.$clientList['client_nickname'].'[/b] czeka na Centrum Pomocy!');
								}
								$ts->sendMessage(1,$clientList['clid'],'Administracja została powiadomiona!');
							}
							else
							{
								$ts->sendMessage(1,$clientList['clid'],'Brak dostępnych administratorów!');
							}
							break;
						}
					}
				}
			}
		}

	}
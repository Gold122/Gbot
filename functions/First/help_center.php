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
					if($id == $clientList['cid'])
					{
						if(!array_intersect($groups,explode(',',$clientList['client_servergroups'])))
						{
							if($admin != NULL)
							{
								foreach($admin as $admins)
								{
									$ts->clientPoke($admins['clid'],'Użytkownik [b] [URL=client://0/'.$clientList['client_unique_identifier'].']'.$clientList['client_nickname'].'[/URL][/b] czeka na Centrum Pomocy!');
								}
								$ts->sendMessage(1,$clientList['clid'],'Administracja została powiadomiona!');
								$ts->sendMessage(1,$clientList['clid'],'Dostępni administratorzy:');
								foreach($admin as $admins)
								{
									$ts->sendMessage(1,$clientList['clid'],'» [b] [URL=client://0/'.$admins['client_unique_identifier'].']'.$admins['client_nickname'].'[/URL][/b]');
								}
							}
							else
							{
								$ts->sendMessage(1,$clientList['clid'],'Brak dostępnych administratorów!');
							}
							break;
						}
						else
						{
							break;
						}
					}
				}
			}
			unset($id,$groups,$admin);
		}

	}
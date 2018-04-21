<?php


	class TopGenerator
	{
		function start($db,$ts,$config,$ft,$cache)
		{
			foreach($config['functions']['TopGenerator']['functions'] as $name => $function)
			{
				if($config['functions']['TopGenerator']['functions'][$name]['enabled'] == true)
				{
					$edit = self::replace($config['functions']['TopGenerator']['functions'][$name]['channel_description'],$config['functions']['TopGenerator']['functions'][$name]['limit']);
					$i=1;
					$top = $db->prepare("SELECT * FROM tops ORDER BY :name DESC");
					$top->execute(array(':name' => $name));
					foreach($top->fetchAll(PDO::FETCH_ASSOC) as $top)
					{
						if($name == 'clientConnections')
						{
							$edit .= $i.'. [url=client://0/'.$top['client_unique_identifier'].']'.$top['client_nickname'].'[/url] ( '.$top[$name].' połączeń ) \n';
						}
						else
						{
							$edit .= $i.'. [url=client://0/'.$top['client_unique_identifier'].']'.$top['client_nickname'].'[/url] ( '.$ft->secToHR($top[$name]).' ) \n';
						}
						$i++;
					}
					$edit .= '[/size]'.$cache['footer'];
					$ts->channelEdit($function['channel_id'],array('channel_description' => $edit));
				}
			}
			unset($name,$function,$edit,$top);
		}

		private function replace($msg,$limit)
		{
			$replace = array(
				1 => array(1 => '[limit]', 2 => $limit),
			);	
			foreach($replace as $stats)
			{
				$msg = str_replace($stats[1], $stats[2], $msg);
			}
			return $msg;	
		}
	}
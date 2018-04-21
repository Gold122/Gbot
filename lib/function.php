<?php	
	
	class gbot
	{

		function can_do($function,$interval,$cache)
		{
			if(isset($cache[$function]))
			{
				if(time() > $cache[$function]+$interval)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return true;
			}
		}
		
		function convertinterval($interval)
		{
			$interval['hours'] = $interval['hours'] + $interval['days'] * 24;
			$interval['minutes'] = $interval['minutes'] + $interval['hours'] * 60;
			$interval['seconds'] = $interval['seconds'] + $interval['minutes'] * 60;
			return $interval['seconds'];
		}

		function secToHR($sec) {
			$convert['days']=floor($sec / 86400);
			$convert['hours']=floor(($sec - ($convert['days'] * 86400)) / 3600);
			$convert['minutes']=floor(($sec - (($convert['days'] * 86400)+($convert['hours']*3600))) / 60);
			$convert['seconds']=floor(($sec - (($convert['days'] * 86400)+($convert['hours']*3600)+($convert['minutes'] * 60))));

			$time = '';
			if($convert['days']>0)
			{
				$time .= ''.$convert['days'].' dni ';
			}
			if($convert['hours']>0)
			{
				$time .= ''.$convert['hours'].' godzin ';
			}
			if($convert['minutes']>0)
			{
				$time .= ''.$convert['minutes'].' minut';
			}

			return $time;

		}

		function add_log($name,$add,$log)
		{
			if(!is_dir('logs'))
			{
				mkdir('logs');
			}

			if(is_dir('logs/'.$name))
			{
				file_put_contents('logs/'.$name.'/'.date('d.m.Y').'.txt',PHP_EOL.' ===> '.$add.date(' [ H:i d/m/Y ] ').$log, FILE_APPEND);
			}
			else
			{
				echo END.PREFIX.' Tworzenie Katalogu!'.END;
				mkdir('logs/'.$name);
				file_put_contents('logs/'.$name.'/'.date('d.m.Y').'.txt',PHP_EOL.' ===> '.$add.date(' [ H:i d/m/Y ] ').$log, FILE_APPEND);
			}
		}

		function RamUsage()
		{
			return round((memory_get_usage()/ 1024)/1024,2);
		}

		function curl($link)
		{
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$link);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,3);
			$query = json_decode(curl_exec($ch),true);
			curl_close($ch);
			
			return $query;
		}

	}
	
	
	
	
?>
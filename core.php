<?php

##		DON'T ALLOW EDIT		##
##								##			
##   Copyright by: Gold122		##
##								##
##		DON'T ALLOW EDIT		##


	require_once("config.php");
	require_once("src/ts3admin.class.php");
	require_once("lib/function.php");
	date_default_timezone_set('Europe/Warsaw');
	define('OWNER', 'Gold122');
	define('VERSION', '2.5');
	define('PREFIX', '	:> ');
	define('END', "\n");

	$instance = getopt("i:");
	if(@isset($config[$instance['i']]))
	{
		echo END.END;
		echo PREFIX.' GBOT '.VERSION.END;
		echo PREFIX.' Stworzony przez '.OWNER.END.END;
		$ft = new gbot;

		foreach ($config[$instance['i']]['functions'] as $filename => $file_name)
		{
			if($file_name['enabled'] == true)
			{
				include_once 'functions/'.$config[$instance['i']]['connection']['name'].'/'.$filename.'.php';
				if($config[$instance['i']]['connection']['debug_mode'])
				{
					echo PREFIX.'Poprawnie załadowano '.$filename.END;
				}
			}
		}

		echo END.END;

			$ts = new ts3admin($config[$instance['i']]['connection']['server_ip'], $config[$instance['i']]['connection']['query_port']);
			if($ts->getElement('success', $ts->connect())){
				$ft->show_errors($ts->login($config[$instance['i']]['connection']['query_login'], $config[$instance['i']]['connection']['query_password']));
				$ft->show_errors($ts->selectServer($config[$instance['i']]['connection']['voice_port']));
				echo PREFIX.'SUCCESS: Poprawnie połączono z serwerem'.END;
				$ft->show_errors($ts->setName($config[$instance['i']]['connection']['bot_name']));
				echo PREFIX.'SUCCESS: Poprawnie ustawiono nazwe na '.$config[$instance['i']]['connection']['bot_name'].END;
				$whoam = $ts->getElement('data',$ts->whoAmI());
				$ft->show_errors($ts->clientMove($whoam['client_id'],$config[$instance['i']]['connection']['join_to_channel']));
				echo PREFIX.'SUCCESS: Poprawnie wszedlem na kanal o id '.$config[$instance['i']]['connection']['join_to_channel'].END;
				$cache = array();
				$cache['footer'] = '[hr][right][img]https://goldproject.eu/img/logo/logocolor.png[/img]';
				if($config[$instance['i']]['save_log']['enabled'] == true)
				{
					$ft->add_log($config[$instance['i']]['connection']['bot_name'],'Start','Bot zostal pomyslnie uruchomiony');
				}

				if(isset($config[$instance['i']]['functions']['connectMessage']))
				{
					$cache['firstClient'] = array();
					foreach($ts->getElement('data',$ts->clientList('-uid -times -groups -info -voice')) as $clientList)
					{
						if($clientList['client_type'] == 0)
						{
							$cache['firstClient'][] = $clientList['clid'];
						}
					}
				}

				while(1){
					foreach($config[$instance['i']]['functions'] as $functions => $config_value)
					{
						if($config_value['enabled'] == true)
						{
							if($ft->can_do($functions,$ft->convertinterval($config_value['interval']),$cache))
							{
								if(in_array('mysql',$config_value['require']))
								{
									try{
										$odb = new PDO('mysql:host=' . $config['mysql']['host'] . ';dbname=' . $config['mysql']['database'], $config['mysql']['login'], $config['mysql']['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
										$odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
										$funkcja = new $functions;
										$funkcja->start($odb,$ts,$config[$instance['i']],$ft,$cache,$functions,$instance['i']);
										$cache[$functions] = time();
										unset($funkcja);

									}
									catch( PDOException $Exception )
									{
										echo PHP_EOL.$Exception->getMessage();
									}
									$odb = NULL;
								}
								else
								{
									$funkcja = new $functions;
									$funkcja->start($db=null,$ts,$config[$instance['i']],$ft,$cache,$functions,$instance['i']);
									$cache[$functions] = time();
									unset($funkcja);
								}
								if($functions == 'connectMessage')
								{
									$cache['firstClient'] = array();
									foreach($ts->getElement('data',$ts->clientList('-uid -groups -info -voice')) as $clientList)
									{
										if($clientList['client_type'] == 0)
										{
											$cache['firstClient'][] = $clientList['clid'];
										}
									}
								}
							}
						}
					}
				sleep(1);
				}
			}
			else
			{
				echo PREFIX.'ERROR: Nie można się połączyć z ts3'.END;
				if($config[$instance['i']]['save_log']['enabled'] == true)
				{
					$ft->add_log($config[$instance['i']]['connection']['bot_name'],'ERROR','Brak połączenia z ts3');
				}
				die;
			}
		}
		else
		{
			echo PREFIX.'ERROR: Nie ma takiej instancji'.END;
			die;
		}
?>

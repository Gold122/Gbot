<?php

	// !-----> Config Instancji 1 <-----! //
	
	$config[1]['connection'] =  array(
		
		'server_ip' => 'localhost',  // IP serwera ts3
		
		'voice_port' => 9987,
		
		'query_port' => 10011,
		
		'query_login' => 'serveradmin',
		
		'query_password' => 'pass_query',
		
		'bot_name' => '[ GBot ] Pracownik', // Nazwa z która ma wejść bot
		
		'join_to_channel' => 110, // Kanał na którym ma siedzieć bot

		'name' => 'First',
		
	);
	
	
	$config[1]['save_log'] = array(
	
		'enabled' => true,
		
	);

	$config['mysql'] = array(
		'host' => 'localhost',
		'login' => 'root',
		'password' => 'pass',
		'database' => 'gbot',
	);

	$config[1]['functions'] = array(


		/* Wysyłanie wiadomości dla użytkownika przy wejściu  */
		'connectMessage' => array(
			'enabled' => true, // włączone - true / wyłączone - false
			'message' => array(
				'Witaj [nickname]',
				'Na serwerze jest online [online] osób!',
				'Połączyłeś się z kraju [country]',
				'Za działanie na szkodę serwera zostaniesz zablokowany!'
			),
			'require' => array('clientList','serverInfo'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 0,'seconds' => 1),
		),

		'ddosInformation' => array(
			'enabled' => false, // włączone - true / wyłączone - false
			'functions' => array(
				'virtualserver_total_ping' => array(
					'threshold' => 200, // granica przy jakiej ma informować!
					'groups' => array(11,12,13), // grupy do których ma wysyłać wiadmość
					'message' => array(
						'Drogi administratorze wykryłem wysoki Ping.',
						'Ping: [ping]',
						'Ilość utraconych pakietów: [packet_lost]%',
					),
				),
				'virtualserver_total_packetloss_speech' => array(
					'threshold' => 30*100, //granica przy jakie ma informować
					'groups' => array(11,12,13), // grupy do których ma wysyłać wiadmość
					'message' => array(
						'Drogi administratorze wykryłem wysoke Pakiety.',
						'Ping: [ping]',
						'Ilość utraconych pakietów: [packet_lost]%',
					),
				),
			),
			'require' => array('clientList','serverInfo'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 1,'seconds' => 0), // Optymalny czas: 1 min
		),

		/* Funkcja służąca do generowania na kanale ilości administratorów online  */
		'adminsOnline' => array(
			'enabled' => true, // włączone - true / wyłączone - false 
			'admin_groups' => array(11,12,13), // akceptowane rangi
			'channel_name' => '» Dostępne osoby z zepsołu [online] osoba/y', // nazwa kanału
			'channel_description' => '[center][size=15] [B]Administratorów Online[/B][/size][/center][list]',
			'channel_id' => 88, // id kanały
			'require' => array('clientList'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 5,'seconds' => 0), // Optymalny czas: 5min
		), 

		/* Obsługa centrum pomocy, pokowanie administracji  */
		'help_center' => array(
			'enabled' => true, // włączone - true / wyłączone - false 
			'channels' => array(
				87 => array(12,11), // id kanału / id grupy
			),
			'require' => array('clientList'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 0,'seconds' => 5), // Optymalny czas: 5sek
		),


		/* Funkcja służaca do generowania listy administracji  */
		'adminList' => array(
			'enabled' => true, // włączone - true / wyłączone - false
			'admin_groups' => array(11,12,13), // akceptowane rangi
			'channel_id' => 75, // id kanału
			'channel_description' => '[center][size=15] [B]Administratorów Online[/B][/size][/center]\n',
			'client_afk' => 120, // po jakim czasie ma pokazywać że użytkownik jest afk ( sek )
			'require' => array('clientList','serverGroupList'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 1,'seconds' => 0) // Optymalny czas: 1-5 min
		),
		
		/* Funkcja służaca do wypisywania statusy administratora na kanale  */
		'adminStatusOnChannel' => array(
			'enabled' => true,  
			'admin_groups' => array(11,12,13), // akceptowane rangi
			'channels' => array(
				/* dbid użytkownika */ 3 => array( 
					'channel_id' => 70, // id kanału
					'show' => '» Status: [status]',
				),
			),
			'require' => array('clientList'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 1,'seconds' => 0) // Optymalny czas: 1min
		),

		/* Funkcja służąca do nadawania rangi na danym kanale */
		'channelGroup' => array(
			'enabled' => true, // włączone - true / wyłączone - false
			'channels' => array(
				93 => array('group' => 19,'timeSpent' => 3600,'connections' => 5),	/* id kanału / id rangi  */
				94 => array('group' => 43,'timeSpent' => 3600,'connections' => 5),	/* id kanału / id rangi  */
			),
			'require' => array('clientList'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 0,'seconds' => 2) // Optymalny czas: 2sek
		),

		/* Nadawanie rangi po określonym czasie */
		'autoRegister' => array(
			'enabled' => true, // włączone - true / wyłączone - false
			'ranks' => array(
				44 => array('timeSpent' => 3600,'connections' => 5,'ignored_ranks' => array(20,21)),	/* id rangi / id rangi  */
			),
			'require' => array('clientList','serverGroupList'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 0,'seconds' => 10) // Optymalny czas: 10sek
		),

		/* Zbieranie statystyk */
		'get_static' => array(
			'enabled' => true, // włączone - true / wyłączone - false
			'require' => array('serverInfo','mysql'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 1,'seconds' => 0) // Optymalny czas: 10sek
		),

		/* wypisywanie statystyk na kanale */
		'static_channel' => array(
			'enabled' => true, // włączone - true / wyłączone - false
				'channel_id' => 183, // id kanały
				'channel_name' => '◦ Boty query zużywają: [mem_usage] MB RAM', // nazwa kanału
				'channel_description' => '[center][size=15] [B]Zużycie ramu przez boty query[/B][/size][/center][list]',
			'require' => array('mysql'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 5,'seconds' => 0) // Optymalny czas: 10sek
		),
		
	);


	// !-----> Config Instancji 2 <-----! //
	
	$config[2]['connection'] =  array(
		
		'server_ip' => 'localhost',  // IP serwera ts3
		
		'voice_port' => 9987,
		
		'query_port' => 10011,
		
		'query_login' => 'serveradmin',
		
		'query_password' => 'pass_query',
		
		'bot_name' => '[ GBot ] Manager', // Nazwa z która ma wejść bot
		
		'join_to_channel' => 110, // Kanał na którym ma siedzieć bot

		'name' => 'Second',
		
	);
	
	$config[2]['save_log'] = array(
	
		'enabled' => true,
		
	);
	
	$config[2]['functions'] = array(

		/* Funkcja służąca do edytowania nazwy serwera */
		'ServerName' => array(
			'enabled' => true, // włączone - true / wyłączone - false
			'functions' => array(
				'virtualserver_name' => array(
					'enabled' => true,
					'name' => 'GoldProject.EU |  [online]/[maxslots] | [percent]%', // nazwa serwera
				),
				'virtualserver_welcomemessage' => array(
					'enabled' => false,
					'name' => '',
				),
				'virtualserver_hostmessage' => array(
					'enabled' => false,
					'name' => '',
				),
				'virtualserver_hostbanner_gfx_url' => array(
					'enabled' => false,
					'name' => '',
				),
			),
			'require' => array('serverInfo'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 1,'seconds' => 0) // Optymalny czas: 1min
		),

		/* MultiFuncion */
		'MultiFunction' => array(
			'enabled' => true,
			'functions' => array(
				110 => array( 
					'enabled' => true,
					'channel_name' => '◦ Botów Query: [query]', // nazwa kanału
				),
				111 => array(
					'enabled' => true,
					'channel_name' => '◦ Użytkowników Online [online]', // nazwa kanału
				),
				113 => array(
					'enabled' => true,
					'channel_name' => '◦ PacketLost [packet_lost_speach]%', // nazwa kanału
				),
				112 => array(
					'enabled' => true,
					'channel_name' => '◦ Ping [ping] ms', // nazwa kanału
				),
			),
			'require' => array('serverInfo'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 5,'seconds' => 0) // Optymalny czas: 5min
		),

		/* Funkcja służaca do wypisywania największej ilośći użytkowników na kanale */
		'RecordOnline' => array(
			'enabled' => true, // włączone - true / wyłączone - false
			'channel_name' => '◦ Rekord: [record] osób', // nazwa kanału
			'channel_description' => '[center][size=15] [B]Rekord[/B][/size][/center] \n[size=10] [B]Osób:[/B] [color=green][record][/color] \n [B]Data:[/B] [color=#a26c00][date][/color][/size] \n',
			'channel_id' => 114, // id kanału
			'require' => array('serverInfo','mysql'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 0,'seconds' => 10) // Optymalny czas: 10sek
		),

		/* Funkcja służaca do wypisywania największej ilośći użytkowników na kanale */
		'NewUserToday' => array(
			'enabled' => true, // włączone - true / wyłączone - false
			'channel_name' => '◦ Nowych Użytkowników: [count] osób', // nazwa kanłu
			'channel_id' => 115, // id kanału
			'require' => array('clientList','mysql'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 0,'seconds' => 10) // Optymalny czas: 10sek
		),

		/* Poziomy */
		'Levels' => array(
			'enabled' => true,
			'ranks' => array(
				27 => array('timeSpent' => 3600,'connections' => 20),	/* id rangi / id rangi  */
				28 => array('timeSpent' => 7200,'connections' => 30),
				30 => array('timeSpent' => 14400,'connections' => 40),
				31 => array('timeSpent' => 28800,'connections' => 50),
				32 => array('timeSpent' => 57600,'connections' => 60),
				38 => array('timeSpent' => 115204,'connections' => 70),
				35 => array('timeSpent' => 230408,'connections' => 80),
				36 => array('timeSpent' => 460816,'connections' => 90),
				37 => array('timeSpent' => 921632,'connections' => 100),
				39 => array('timeSpent' => 1000000,'connections' => 110),
			),
			'ignored_ranks' => array(20,21),
			'require' => array('clientList','serverGroupList','mysql'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 0,'seconds' => 10) // Optymalny czas: 10sek
		),

		'TopGenerator' => array(
			'enabled' => true,
			'functions' => array(
				'clientConnections' => array(
					'enabled' => true, // włączone - true / wyłączone - false
					'channel_id' => 57, // id kanału
					'channel_description' => '[center][size=15] [B]Top [limit] Połączeń[/B][/size][/center] \n[size=10]',
					'limit' => 15, // limit wyświetlania 
				),
				'clientTime' => array(
					'enabled' => true, // włączone - true / wyłączone - false
					'channel_id' => 58, // id kanału
					'channel_description' => '[center][size=15] [B]Top [limit] Najdłuższych Połączenia[/B][/size][/center] \n[size=10]',
					'limit' => 15, // limit wyświetlania 
				),
				'clientTimeSpent' => array(
					'enabled' => true, // włączone - true / wyłączone - false
					'channel_id' => 55, // id kanału
					'channel_description' => '[center][size=15] [B]Top [limit] Spędzonego Czasu[/B][/size][/center] \n[size=10]',
					'limit' => 15, // limit wyświetlania
				),
				'clientAFK' => array(
					'enabled' => true, // włączone - true / wyłączone - false
					'channel_id' => 116,  // id kanału
					'channel_description' => '[center][size=15] [B]Top [limit] AFK[/B][/size][/center] \n[size=10]',
					'limit' => 15, // limit wyświetlania
				),
			),
			'require' => array('mysql'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 5,'seconds' => 0) // Optymalny czas: 1-5 min
		),

		'TopStatistic' => array(
			'enabled' => true, // włączone - true / wyłączone - false 
			'timeAFK' => 120, // czas AFK
			'ignored_ranks' => array(20,21), // ignorowane gurpy
			'require' => array('clientList','mysql'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 1,'seconds' => 0) // Musi być 1 min!
		),

		'PublicMonitor' => array(
			'enabled' => true,
			'channels' => array(
				104 => array(
					'channel_name' => '◦ Kanał - Bez Limitu #[number]',
					'channel_description' => 'Tutaj opis kanału',
					'CHANNEL_FLAG_PERMANENT' => 1,
				),
				105 => array(
					'channel_name' => '◦ Kanał - MAX2 #[number]',
					'channel_description' => 'Tutaj opis kanału',
					'CHANNEL_FLAG_PERMANENT' => 1,
					'channel_maxclients' => 2,
					'channel_maxfamilyclients' => 2,
					'channel_flag_maxclients_unlimited' => 0,
					'channel_flag_maxfamilyclients_unlimited' => 0,
				),
				106 => array(
					'channel_name' => '◦ Kanał - MAX3 #[number]',
					'channel_description' => 'Tutaj opis kanału',
					'CHANNEL_FLAG_PERMANENT' => 1,
					'channel_maxclients' => 3,
					'channel_maxfamilyclients' => 3,
					'channel_flag_maxclients_unlimited' => 0,
					'channel_flag_maxfamilyclients_unlimited' => 0,
				),
				107 => array(
					'channel_name' => '◦ Kanał - MAX4 #[number]',
					'channel_description' => 'Tutaj opis kanału',
					'CHANNEL_FLAG_PERMANENT' => 1,
					'channel_maxclients' => 4,
					'channel_maxfamilyclients' => 4,
					'channel_flag_maxclients_unlimited' => 0,
					'channel_flag_maxfamilyclients_unlimited' => 0,
				),
				108 => array(
					'channel_name' => '◦ Kanał - MAX5 #[number]',
					'channel_description' => 'Tutaj opis kanału',
					'CHANNEL_FLAG_PERMANENT' => 1,
					'channel_maxclients' => 5,
					'channel_maxfamilyclients' => 5,
					'channel_flag_maxclients_unlimited' => 0,
					'channel_flag_maxfamilyclients_unlimited' => 0,
				),
			),
			'require' => array('channelList'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 0,'seconds' => 10) // Optymalny czas: 10sek
		),
		/* Zbieranie statystyk */
		'get_static' => array(
			'enabled' => true,
			'require' => array('serverInfo','mysql'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 1,'seconds' => 0) // Optymalny czas: 10sek
		),

	);

	// !-----> Config Instancji 3 <-----! //
	
	$config[3]['connection'] =  array(
		
		'server_ip' => 'localhost',  // IP serwera ts3
		
		'voice_port' => 9987,
		
		'query_port' => 10011,
		
		'query_login' => 'serveradmin',
		
		'query_password' => 'pass_query',
		
		'bot_name' => '[ GBot ] ChannelManager', // Nazwa z która ma wejść bot
		
		'join_to_channel' => 110, // Kanał na którym ma siedzieć bot

		'name' => 'Third',
		
	);
	
	
	$config[3]['save_log'] = array(
	
		'enabled' => true,
		
	);
	
	$config[3]['functions'] = array(

		/* Tworzenie wolnych kanałów prywatnych */
		'ChannelGuard' => array(
			'enabled' => true,
			
			'channel_creator' => array(
				'channel_Section' => 150,

				'empty_channel_topic' => '#free',
				'free_channel_name' => 'Kanał Wolny',
				'free_channel_description' => 'Opis kanału',

				'sub_channels' => 3, // ilośc sub-kanałów
				'name_sub_channel' => 'Podkanał', // nazwa sub-kanału
				'description_sub_channel' =>  'Opis sub-kanału', // opis sub-kanału

				'head_channel_admin_group' => 5,
				'minimum_channel' => 3, //ilośc kanałów która ma byc wolna
			),

			'require' => array('channelList'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 0,'seconds' => 10) // Optymalny czas: 30min
		),
		/* tworzenie kanału prywatnego po wejściu na odpowiedni kanał */
		'GetPrivateChannel' => array(
			'enabled' => true,
			'need_ranks' => array(), // wymagana ranga aby móc stworzyć kanał prywatny
			'channel_description' => '[center][size=15] [B]Kanał [nick] [/B][/size][/center] \n',
			'getChannel_id' => 95, // id kanału od nadawania kanału prywatnego
			'require' => array('channelList','clientList'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 0,'seconds' => 3) // Optymalny czas: 30min
		),

		/* Zbieranie statystyk */
		'get_static' => array(
			'enabled' => true,
			'require' => array('serverInfo','mysql'),
			'interval' => array('days' => 0,'hours' => 0,'minutes' => 1,'seconds' => 0) // Optymalny czas: 10sek
		),

	);
	
?>

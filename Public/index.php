<?php

/* 
	Welcome to WebFeels!
		... Feeling the web in a hole new way!

	Authors:
		Tadeu Bento	<tcb13@iklive.org>
		Enoque Duarte <enoquefcd@gmail.com>
		Emanuel Alves <emannxx_artik@outlook.com>
		
	Project Pages:
		https://bitbucket.org/TCB13/webfeels/ (Invite Only)
		
	Copyright Notice:
		Copyright © 2013 Tadeu Bento, Enoque Duarte & Emanuel Alves.
		This work in protected under every single copyright law you might found in the universe. 
		Copying, editing, remixing and/or using this software without prior written consent is 
		considered a direct violation of this terms and WE WILL HUNT YOU DOWN!
*/

/*
	Setup None: In order for this software to work, you MUST HAVE an .htaccess file in your server containing the following:
	
		DirectoryIndex index.php
		RewriteEngine On
		RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
		RewriteRule ^(.*)$ http://%1/$1 [R=301,L]
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-l
		RewriteCond %{HTTP_HOST} ^(.*)$ [NC]
		RewriteRule ^(.+)$ index.php?q=$1&s=%1 [QSA,L]
	
*/	

require_once "../AppCore/Controller.php";
date_default_timezone_set("Europe/Lisbon");

$controller = Controller::getInstance();
$controller->run();

?>
<?php
	# Mantis - a php based bugtracking system
	# Copyright (C) 2002 - 2004  Mantis Team   - mantisbt-dev@lists.sourceforge.net
	# Copyright (C) 2004  Gerrit Beine - gerrit.beine@pitcom.de
	# This program is distributed under the terms and conditions of the GPL
	# See the README and LICENSE files for details

	# This page receives an E-Mail via POP3 and IMAP and generates a new issue

  # 20130117 - fman - improvement in architecture and configuration
  #                   in order to have several mail managers
  #
	$GLOBALS[ 'g_bypass_headers' ] = 1;

	# Make sure this script doesn't run via the webserver
	$t_mail_secured_script = plugin_config_get( 'mail_secured_script' );
	if( php_sapi_name() !== 'cli' && $t_mail_secured_script )
	{
		echo "bug_report_mail.php is not allowed to run through the webserver.\n";
		exit( 1 );
	}

	require_once( plugin_config_get( 'path_erp', NULL, TRUE ) . 'core/mail_api.php' );
	require_once( plugin_config_get( 'path_erp', NULL, TRUE ) . 'core/config_api.php' );

	$GLOBALS[ 't_mailboxes' ] = plugin_config_get( 'mailboxes' );
  $mailCfg = getProjectMailCfg();
	$t_mail_mantisbt_url_fix = plugin_config_get( 'mail_mantisbt_url_fix', '' );
	if ( php_sapi_name() === 'cli' && !is_blank( $t_mail_mantisbt_url_fix ) )
	{
		config_set_global( 'path', $t_mail_mantisbt_url_fix );
	}

	$t_mailbox_api_index = ERP_get_mailbox_api_name();

	$GLOBALS[$t_mailbox_api_index] = new ERP_mailbox_api;
  
  // TESI
  $GLOBALS[$t_mailbox_api_index]->set_mail_tags($mailCfg->mail_tags);
  $GLOBALS[$t_mailbox_api_index]->set_mail_substr($mailCfg->mail_substr);
  $GLOBALS[$t_mailbox_api_index]->set_mail_tags_exclude($mailCfg->mail_tags_exclude);

	ini_set( 'memory_limit', -1 );
	set_time_limit( 0 );

	foreach( $GLOBALS['t_mailboxes'] as $t_mailbox )
	{
		echo "\n (tesi)Mail Box Description:" . $t_mailbox['description'] . "\n";
		$GLOBALS[$t_mailbox_api_index]->process_mailbox($t_mailbox);
	}

	echo "\n\n" . 'Done checking all mailboxes' . "\n";

	exit( 0 );


  // TESI
  function getProjectMailCfg()
  {
    // now get config from projects table (from project_api.php)
    $dummy_set = project_cache_all();
   
    // change access key to project_id
    $cfg = new stdClass();
    $cfg->mail_tags = array();
    $cfg->mail_substr = array();
    $cfg->mail_tags_exclude = array();

    foreach($dummy_set as $idx => $projectObj)
    {
	  	$mmpid = intval($projectObj['mail_manager_project_id']);  
	  	if( $mmpid <= 0 )
	  	{
	  	  continue;  
	  	}

	  	$key2use = 'mail_tag';     
      if( isset($projectObj[$key2use]) &&  
          !is_null($projectObj[$key2use]) && ($t_mail_tag = trim($projectObj[$key2use])) != '')
      {
      	$cfg->mail_tags[$mmpid][$t_mail_tag] = array('id' => $projectObj['id'], 'name' => $projectObj['name'] );  
      }
      
      $key2use = 'mail_substr';
      if(isset($projectObj[$key2use]) && !is_null($projectObj[$key2use]) && (trim($projectObj[$key2use])) != '')
      {
      	$yumyum = explode(':',$projectObj[$key2use]);
      	foreach($yumyum as $composite)
      	{
      		$xd = explode('=',$composite);
      		$cfg->mail_substr[$mmpid][$projectObj['id']][] = array('project_name' => $projectObj['name'], 
      												                                   'needle' => $xd[0], 'len' => $xd[1]);  
      	}	
      }

	  	$key2use = 'mail_tag_exclude';
      if(isset($projectObj[$key2use]) && !is_null($projectObj[$key2use]) && trim($projectObj[$key2use]) != '')
      {
      	$yumyum = explode(',',$projectObj[$key2use]);
      	foreach($yumyum as $ordos)
      	{
      		$cfg->mail_tags_exclude[$mmpid][$projectObj['id']][] = array('project_name' => $projectObj['name'], 
      														                                'needle' => $ordos);  
      	}	
      }
    }
    unset($dummy);
    return $cfg;    
  }  
?>
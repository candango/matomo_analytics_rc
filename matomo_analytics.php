<?php

/**
 * piwik_tracking
 *
 * Bind google analytics script
 *
 * @author Roland 'rosali' Liebl
 * @modified_by Ondra 'Kepi' Kudlík
 * @website https://github.com/igloonet/roundcube_google_analytics
 * @licence GNU GPL
 * 
 * Forked and modified to do PiWik
 * 
 * @modified_by Arjan Spaninks
 * @website http://github.com/aspaninks/roundcube_piwik_tracking
 * @licence GNU GPL
 **/

class piwik_tracking extends rcube_plugin
{
    function init()
    {
        $this->add_hook('render_page', array($this, 'add_script'));
    }

    function add_script($args)
    {
        $rcmail = rcmail::get_instance();

        // test if we have global_config plugin
        if ( !in_array('global_config', $plugins = $rcmail->config->get('plugins')) ) {
            $this->load_config('config/config.inc.php');
        }

        // do not allow logged users if privacy on
        if(!empty($_SESSION['user_id']) && $rcmail->config->get('piwik_tracking_privacy', FALSE))
            return $args;

        // excluding or including
        if ( $rcmail->config->get('piwik_tracking_excluding', TRUE) ) {
            if ( in_array($args['template'], $rcmail->config->get('piwik_tracking_exclude', array())) )
                return $args;
        }
        else {
            if ( !in_array($args['template'], $rcmail->config->get('piwik_tracking_include', array('login'))) )
                return $args;
        }
	
	// Uglified version of the PiWik JS (Credit:  http://marijnhaverbeke.nl/uglifyjs )
	$script = '<script type="text/javascript">var _paq=_paq||[];_paq.push(["setCookieDomain","*.'.$rcmail->config->get('piwik_tracking_subdomain').'"]),_paq.push(["trackPageView"]),_paq.push(["enableLinkTracking"]),function(){var a=("https:"==document.location.protocol?"https":"http")+"://'.$rcmail->config->get('piwik_tracking_server').'/";_paq.push(["setTrackerUrl",a+"piwik.php"]),_paq.push(["setSiteId","'.$rcmail->config->get('piwik_tracking_siteid').'"]);var b=document,c=b.createElement("script"),d=b.getElementsByTagName("script")[0];c.type="text/javascript",c.defer=!0,c.async=!0,c.src=a+"piwik.js",d.parentNode.insertBefore(c,d)}();</script>'; 

	$rcmail->output->add_footer($script);

        return $args;
    }
}

?>

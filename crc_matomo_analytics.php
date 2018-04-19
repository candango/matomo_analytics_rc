<?php
/**
 * Candango Roundcube Matomo plugin (http://roundcube.candango.org)
 *
 * Bind google analytics script was created by Roland 'rosali' Liebl and
 * modified by Ondra 'Kepi' Kudlík.
 *
 * See: https://github.com/igloonet/roundcube_google_analytics
 *
 * Roundcube piwik tracking was authored by Arjan Spaninks.
 *
 * See:http://github.com/aspaninks/roundcube_piwik_tracking
 *
 *
 * @link      http://github.com/candango/matomo_analytics
 * @copyright Copyright (c) 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

/**
 * Candango matome analytics plugin
 *
 * Insert the Matomo JavaScript tracking code to the roundcube.
 *
 * based on: http://github.com/igloonet/roundcube_google_analytics
 *
 * @category plugin
 * @package crc_matomo_analytics
 * @author     Flavio Garcia <piraz@candango.org>
 */
class crc_matomo_analytics extends rcube_plugin
{
    function init()
    {
        $this->add_hook('render_page', array($this, 'add_script'));
    }

    function add_script($args)
    {
        $rcmail = rcmail::get_instance();

        // test if we have global_config plugin
        if ( !in_array('global_config',
            $plugins = $rcmail->config->get('plugins')) ) {
            $this->load_config('config/config.inc.php');
        }

        // do not allow logged users if privacy on
        if (!empty($_SESSION['user_id']) && $rcmail->config->get('matomo_analytics_privacy', FALSE)) {
            return $args;
        }

        // excluding or including
        if ( $rcmail->config->get('matomo_analytics_excluding', TRUE) ) {
            if ( in_array($args['template'], $rcmail->config->get('matomo_analytics_exclude', array())) )
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

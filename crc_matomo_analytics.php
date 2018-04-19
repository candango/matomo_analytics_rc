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
 * @link      http://github.com/candango/crc_matomo_analytics
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
        if (!empty($_SESSION['user_id']) && $rcmail->config->get('crc_matomo_analytics_privacy', FALSE)) {
            return $args;
        }

        // excluding or including
        if ( $rcmail->config->get('crc_matomo_analytics_excluding', TRUE) ) {
            if (in_array($args['template'], $rcmail->config->get(
                'crc_matomo_analytics_exclude', array()))) {
                return $args;
            }
        }
        else {
            if (!in_array($args['template'], $rcmail->config->get(
                'crc_matomo_analytics_include', array('login')))) {
                return $args;
            }
        }

        $script = <<<SCRIPT
<!-- Matomo -->
<script type="text/javascript">
  var _paq = _paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//{$rcmail->config->get("crc_matomo_analytics_server")}/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '{$rcmail->config->get("crc_matomo_analytics_id")}']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->
SCRIPT;

	    $rcmail->output->add_footer($script);

        return $args;
    }
}

?>

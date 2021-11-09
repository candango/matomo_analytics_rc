<?php
/**
 * Candango Roundcube Matomo plugin
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
 * @link      https://github.com/candango/matomo_analytics_rc
 * @copyright Copyright (c) 2018 - 2021 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

/**
 * Candango Matomo Analytics plugin
 *
 * Insert the Matomo JavaScript tracking code to the roundcube.
 *
 * based on: http://github.com/igloonet/roundcube_google_analytics
 *
 * @category plugin
 * @package  matomo_analytics_rc
 * @author   Flavio Garcia <piraz@candango.org>
 */
class matomo_analytics_rc extends rcube_plugin
{
    private $processed = false;

    function init()
    {
        $this->add_hook("render_page", array($this, "add_script"));
    }

    function add_script($args)
    {
        $rcmail = rcmail::get_instance();
        # Preventing the plugin to process the script twice
        if ($this->processed) {
            return $args;
        }
        // test if we have global_config plugin
        if (!in_array("global_config",
            $plugins = $rcmail->config->get("plugins")))
        {
            $this->load_config("config/config.inc.php");
        }

        // do not allow logged users if privacy on
        if (!empty($_SESSION['user_id']) && $rcmail->config->get(
            "matomo_analytics_rc_privacy", FALSE))
        {
            return $args;
        }

        // excluding or including
        if ($rcmail->config->get("matomo_analytics_rc_excluding", TRUE) )
        {
            if (in_array($args['template'], $rcmail->config->get(
                "matomo_analytics_rc_exclude", array())))
            {
                return $args;
            }
        } else {
            if (!in_array($args['template'], $rcmail->config->get(
                "matomo_analytics_rc_include", array("login")))) {
                return $args;
            }
        }

        $script = <<<SCRIPT
<!-- Matomo -->
<script type="text/javascript">
  var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="{$rcmail->config->get('matomo_analytics_rc_server')}/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '{$rcmail->config->get("matomo_analytics_rc_id")}']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->
SCRIPT;

	    $rcmail->output->add_footer($script);
        $this->processed = true;
        return $args;
    }
}

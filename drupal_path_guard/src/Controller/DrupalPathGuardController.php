<?php

namespace Drupal\drupal_path_guard\Controller;

use Drupal\Core\Controller\ControllerBase;

class DrupalPathGuardController extends ControllerBase
{
    /**
     * admin page for Drupal Path Guard
     * @return array
     */
    public function index()
    {
        $drupalpathguardsettings = \Drupal::config('drupal_path_guard.settings');
        $pf = $drupalpathguardsettings->get('uri_regex');
        $rp = \Drupal\Core\Url::fromRoute("drupal_path_guard.redirect_page", [])->toString();

        return array(
            "#type" => "markup",
            "#markup" => $this->t("<p>Drupal Path Guard using uri_regex: <code>'%d'</code> <br/>... redirect to <code>'%s'</code> if GUEST (ANONYMOUS) user access node using matching path.</p>", ['%d' => $pf, '%s' => $rp]),
            "#actions" => "test"
        );
    }

    /**
     * Saves a new Path Filter setting to the settings file
     */
    private function savePathFilter($npf)
    {
        $config = \Drupal::service('config.factory')->getEditable('drupal_path_guard.settings');

        // Set and save new path_filter value.
        $config->set('uri_regex', $npf)->save();

        return true;
    }
}

<?php

namespace Drupal\drupal_path_guard\EventSubscriber;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class DrupalPathGuardEventSubscriber.
 *
 * @package Drupal\drupal_path_guard\DrupalPathGuardEventSubscriber
 */
class DrupalPathGuardEventSubscriber implements EventSubscriberInterface
{
    private $loggerFactory;
    private $uri_regex;

    /**
     * @param Drupal\Core\Logger\LoggerChannelFactoryInterface
     */
    public function __construct(LoggerChannelFactoryInterface $loggerFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $drupalpathguardsettings = \Drupal::config('drupal_path_guard.settings');
        $this->uri_regex = $drupalpathguardsettings->get('uri_regex');
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     *   The event names to listen for, and the methods that should be executed.
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    /**
     * React to a request being made, and filter for node_path_filter.
     * Block access to paths containing node_path_filter.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *   Kernel Request Event.
     */
    public function onKernelRequest($event)
    {
        // $event is Symfony\Component\HttpKernel\Event\GetResponseEvent
        try {
            $request = $event->getRequest()->getRequestUri();

            //$regex = "~/node/d*~i"; 
            $regex = $this->uri_regex;

            if (preg_match($regex, $request)) {
                // only if user is guest ...
                $current_user = \Drupal::currentUser();
                if ($current_user->isAnonymous() === TRUE) {
                    $this->loggerFactory->get('default')
                        ->info('Drupal Path Guard stopped GUEST request to : ' . $request);

                    // We want to redirect user to page specified in module.routing.yml.
                    $url = \Drupal\Core\Url::fromRoute("drupal_path_guard.redirect_page", [])->toString();
                    $response = new RedirectResponse($url);
                    $response->send();
                    die;  //I'm not sure if this is OK????
                }
            }
        } catch (Exception $e) {
            // log exception
            $this->loggerFactory->get('error')
                ->error('Drupal Path Guard : ' . $e->message());
        }
    }
}

<?php

namespace Jowy\RabbitInternet\Controllers;

use Jowy\RabbitInternet\Contracts\Geocode;
use Jowy\RabbitInternet\Contracts\TwitterService;
use Jowy\RabbitInternet\Services\Search;
use Jowy\RabbitInternet\Services\SqliteStorage;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MainController
 * @package Jowy\RabbitInternet\Controllers
 */
class MainController implements ControllerProviderInterface
{
    /**
     * @var Application
     */
    private $app;

    public function connect(Application $app)
    {
        $this->app = $app;
        $controllers = $app['controllers_factory'];
        $controllers->match('/', [$this, 'indexAction'])->bind('homepage');
        $controllers->match('/history', [$this, 'historyAction'])->bind('history');
        return $controllers;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        if ($request->getMethod() === 'GET') {
            $data = [
                'default_geo' => '{lat: -6.21885, lng: 106.89606}',
                'statuses' => '[]',
                'locations' => '[]',
                'label' => ''
            ];
            return $this->app['twig']->render('index.twig', $data);
        }

        /**
         * @var Geocode $geocode
         */
        $geocode = $this->app['geocode.service'];
        /**
         * @var Search $search
         */
        $search = $this->app['search.service'];

        $geometry = $geocode->searchLocationByCity($request->get('location'));

        if (count($geometry) === 0) {
            $data = [
                'default_geo' => '{lat: -6.21885, lng: 106.89606}',
                'statuses' => '[]',
                'locations' => '[]',
                'label' => "Location {$request->get('location')} Not Found"
            ];
            return $this->app['twig']->render('index.twig', $data);
        }

        $lat = $geometry[0]['geometry']['location']['lat'];
        $lng = $geometry[0]['geometry']['location']['lng'];

        $query = $search->search(
            $request->get('location'),
            $lat,
            $lng,
            $this->app['settings']['search_radius'],
            $request->cookies->get('Jowy-UserId')
        );


        $data = [
            'default_geo' => "{lat: {$lat}, lng: {$lng}}",
            'locations' => json_encode($query['locations']),
            'statuses' => json_encode($query['statuses']),
            'label' => "Search Result For {$request->get('location')}"
        ];
        return $this->app['twig']->render('index.twig', $data);
    }

    public function historyAction(Request $request)
    {
        /**
         * @var SqliteStorage $storage
         */
        $storage = $this->app['sqlite.storage'];

        $result = [];
        if ($request->cookies->has('Jowy-UserId')) {
            $result = $storage->getUserHistory($request->cookies->get('Jowy-UserId'));
        }

        return $this->app['twig']->render('history.twig', ['data' => $result]);
    }
}

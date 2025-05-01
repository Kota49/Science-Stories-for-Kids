<?php

/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\components\widgets;

use app\components\TBaseWidget;
use yii\web\View;
use yii\helpers\VarDumper;
use app\components\helpers\World;

/* How to use it ? */

/*
 * use app\components\WorldMap;
 * <?=
 * WorldClock::widget([
 * 'address' => '',
 * 'latitude' =>
 * 'longitude' =>
 * 'title'=>false // make it true to show country name also
 * ]);
 * ?>
 */
class WorldMap extends TBaseWidget
{

    public $address;

    public $longitude = 0;

    public $latitude = 0;

    public $zoom = 18;

    public function init()
    {
        parent::init();
        $this->registerAssets();
    }

    protected function registerAssets()
    {
        $this->view->registerCssFile("https://unpkg.com/leaflet@1.9.4/dist/leaflet.css", [
            'integrity' => "sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=",
            'crossorigin' => "",
            'position' => View::POS_HEAD
        ]);

        $this->view->registerJsFile("https://unpkg.com/leaflet@1.9.4/dist/leaflet.js", [
            'integrity' => "sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=",
            'crossorigin' => "",
            'position' => View::POS_HEAD
        ]);
    }

    public function renderHtml()
    {
        self::log(' address is given. Try getting location using OSM');
        if (! empty($this->address) && empty($this->longitude)) {
            // only address is given. Try getting location using OSM
            $data = World::getLocationByAddress($this->address);

            self::log(VarDumper::dumpAsString($data));
            if ($data) {
                $this->longitude = $data['longitude'];
                $this->latitude = $data['latitude'];
            }
        }

        $this->longitude = $this->longitude ?? '0';
        $this->latitude = $this->latitude ?? '0';

        $id = $this->getId() . '-map';

        echo '<div class = "map-widget" id="' . $id . '" ></div>';
        $js = "

              // Where you want to render the map.
        var element = document.getElementById('{$id}');
        
        // Height has to be set. You can do this in CSS too.
        element.style = 'height:300px;';
        
        // Create Leaflet map on map element.
        var map = L.map(element);
        
        // Add OSM tile layer to the Leaflet map.
        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href=\'http://osm.org/copyright\'>OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Target's GPS coordinates.
        var target = L.latLng({$this->latitude},{$this->longitude});
        
        // Set map's center to target with zoom 14.
        map.setView(target, {$this->zoom});
        
        // Place a marker on the same location.
        L.marker(target).addTo(map);
        ";
        $this->view->registerJs($js, View::POS_END, 'world-map');
    }
}

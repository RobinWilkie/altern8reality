<?php

    // include the initialisation file
    require_once 'core/init.php';


    if(Session::exists('home')){
        echo '<p>' . Session::flash('home') . '</p>';
    }

    $user = new User();

if($user->isLoggedIn()){
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Robin Wilkie -->
        <!-- HND Interactive Media 2016/17 -->
        <!-- Altern8 Reality AR Game -->
        <title>Altern8 Reality Game Page</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta NAME="ROBOTS" CONTENT="INDEX, NOFOLLOW">
        <link type="text/plain" rel="author" href="humans.txt" />
        <link rel="icon" href="images/a8icon.gif" type="image/gif" sizes="16x16">
        <!-- links to Google Fonts and JQuery Mobile CDN hosted files -->
        <link href="https://fonts.googleapis.com/css?family=Raleway%7CUbuntu" rel="stylesheet">
        <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
        <link rel="stylesheet" href="css/styles.css">


        <!-- internal styling to avoid conflicts with JQuery Mobile styles -->
        <style type="text/css">
            * {
                margin: 0;
                padding: 0;
            }
            
            .gameTop {
                position: absolute;
                left: 0;
                top: 2vh;
                overflow: hidden;
                width: 96%;
                margin: 0 2%;
                display: flex;
                justify-content: space-between;
            }
            
            #container {
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                right: 0;
                overflow: hidden;
            }
            
            #map {
                height: 100vh;
                width: 100vw;
                margin: 0;
                padding: 0;
            }

        </style>

    </head>

    <body>
        <div data-role="page" class="content" id="game">

            <div data-role="main" class="ui-content">

                <!-- game container -->
                <div id="container"></div>

                <div class="gameTop">
                    <a href="options.php" rel="external" class="ui-icon-delete ui-btn-icon-left" id="sendscore" data-transition="flip"></a>
                    <?php  echo escape($user->data()->username); } ?>
                    <div id="result"></div>
                    <div id="score">
                        <!-- users score will show here when objects are clicked -->
                    </div>
                </div>

                <!-- button for map navigation -->
                <a href="#userMap" class="ui-icon-carat-l ui-btn-icon-left" data-transition="slide" data-direction="reverse"></a>

            </div>

        </div>

        <!-- Google Map page accessed through swipe function or button -->
        <div data-role="page" id="userMap">
            <div id="map"></div>
            <a href="#game" class="ui-icon-carat-r ui-btn-icon-right" data-transition="slide"></a>
        </div>

        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
        <!-- links to awe.js augmented reality library files -->
        <script src="awe.js"></script>
        <script type="text/javascript" src="awe.js/js/awe-v8.js"></script>
        <script type="text/javascript" src="awe.js/js/awe-loader.js"></script>

        <!-- Load awe.js script to detect device and check if it has a camera, 
    initiate container with augmented reality interface, adds objects to the 
    scene and adds functions to add to users score and performs actions on objects
    when clicking on them -->
        <script type="text/javascript">
            window.addEventListener('load', function() {
                // initialize awe after page loads
                window.awe.init({
                    // automatically detect the device type
                    device_type: awe.AUTO_DETECT_DEVICE_TYPE,
                    // populate the default settings
                    settings: {
                        container_id: 'container',
                        fps: 30,
                        // set default camera position to users coordinates
                        default_camera_position: {
                            x: this.location.lat,
                            y: this.location.lng,
                            z: 0
                        },
                        default_lights: [{
                            id: 'hemisphere_light',
                            type: 'hemisphere',
                            color: 0xCCCCCC
                        }, ],
                    },
                    ready: function() {
                        var d = '?_=' + Date.now();

                        // load js files based on capability detection then setup the scene if successful
                        awe.util.require([{
                                capabilities: ['webgl', 'gum'],
                                files: [
                                    ['awe.js/js/awe-standard-dependencies.js' + d, 'awe.js/js/awe-standard.js' + d], // core dependencies for this app 
                                    ['awe.js/js/plugins/StereoEffect.js' + d, 'awe.js/js/plugins/VREffect.js' + d], // dependencies for render effects
                                    'awe.js/js/plugins/awe.rendering_effects.js' + d,
                                    'awe.js/js/plugins/awe-standard-object_clicked_or_focused.js' + d, // object click/tap handling plugin
                                    'awe.js/examples/basic/awe.gyro.js' + d, // basic gyro handling
                                    'awe.js/examples/basic/awe.mouse.js' + d, // basic mouse handling
                                ],
                                success: function() {
                                    // setup and paint the scene
                                    awe.setup_scene();

                                    var click_plugin = awe.plugins.view('object_clicked');
                                    if (click_plugin) {
                                        click_plugin.register();
                                        click_plugin.enable();
                                    }
                                    var gyro_plugin = awe.plugins.view('gyro');
                                    if (gyro_plugin) {
                                        gyro_plugin.enable();
                                    }
                                    var mouse_plugin = awe.plugins.view('gyro');
                                    if (gyro_plugin) {
                                        gyro_plugin.enable();
                                    }

                                    awe.settings.update({
                                        data: {
                                            value: 'ar'
                                        },
                                        where: {
                                            id: 'view_mode'
                                        }
                                    })
                                    var render_effects_plugin = awe.plugins.view('render_effects');
                                    if (render_effects_plugin) {
                                        render_effects_plugin.enable();
                                    }

                                    // setup some code to handle when an object is clicked/tapped

                                    //set user score at zero to begin with
                                    var userScore = 0;

                                    window.addEventListener('object_clicked', function(e) {
                                        // add one point on to user score every time an object is clicked
                                        userScore++;
                                        // insert new score into 'score' div on page
                                        document.getElementById("score").innerHTML = "Score: " + userScore;


                                        // when clicked rotate object by 360 degrees around x and y axes over 2 seconds
                                        var p = awe.projections.view(e.detail.projection_id);
                                        awe.projections.update({
                                            data: {
                                                animation: {
                                                    duration: 2,
                                                },
                                                rotation: {
                                                    y: p.rotation.y + 360,
                                                    x: p.rotation.x + 360
                                                },
                                            },
                                            where: {
                                                id: e.detail.projection_id
                                            }
                                        });
                                    }, false);


                                    // add some points of interest (poi) around the player to start


                                    awe.pois.add({
                                        id: 'welcome',
                                        position: {
                                            x: 0,
                                            y: 0,
                                            z: -200
                                        }
                                    });
                                    awe.pois.add({
                                        id: 'welcomecube',
                                        position: {
                                            x: 200,
                                            y: 0,
                                            z: 0
                                        }
                                    });
                                    awe.pois.add({
                                        id: 'sun',
                                        position: {
                                            x: 200,
                                            y: 100,
                                            z: -300
                                        }
                                    });
                                    awe.pois.add({
                                        id: 'earth',
                                        position: {
                                            x: -400,
                                            y: 0,
                                            z: -400
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'mars',
                                        position: {
                                            x: 200,
                                            y: -100,
                                            z: -600
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'robincube',
                                        position: {
                                            x: -200,
                                            y: 0,
                                            z: 200
                                        }
                                    });

                                    // add points of interest around Anniesland College
                                    awe.pois.add({
                                        id: 'anniesland1',
                                        position: {
                                            x: 55.890175,
                                            y: -4.325463,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'anniesland2',
                                        position: {
                                            x: 55.889681,
                                            y: -4.321626,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'anniesland3',
                                        position: {
                                            x: 55.888860,
                                            y: -4.321428,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'anniesland4',
                                        position: {
                                            x: 55.888437,
                                            y: -4.321438,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'anniesland5',
                                        position: {
                                            x: 55.887523,
                                            y: -4.321133,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'anniesland6',
                                        position: {
                                            x: 55.886771,
                                            y: -4.318477,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'anniesland7',
                                        position: {
                                            x: 55.886175,
                                            y: -4.319223,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'anniesland8',
                                        position: {
                                            x: 55.886758,
                                            y: -4.319513,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'anniesland9',
                                        position: {
                                            x: 55.885864,
                                            y: -4.318413,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'anniesland10',
                                        position: {
                                            x: 55.886073,
                                            y: -4.319338,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'anniesland11',
                                        position: {
                                            x: 55.887044,
                                            y: -4.320129,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'anniesland12',
                                        position: {
                                            x: 55.886468,
                                            y: -4.321267,
                                            z: 200
                                        }
                                    });

                                    // add points of interest around Cardonald College

                                    awe.pois.add({
                                        id: 'Cardonald1',
                                        position: {
                                            x: 55.840257,
                                            y: -4.335820,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'Cardonald2',
                                        position: {
                                            x: 55.841156,
                                            y: -4.336915,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'Cardonald3',
                                        position: {
                                            x: 55.841231,
                                            y: -4.337886,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'Cardonald4',
                                        position: {
                                            x: 55.840532,
                                            y: -4.337580,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'Cardonald5',
                                        position: {
                                            x: 55.841089,
                                            y: -4.335912,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'Cardonald6',
                                        position: {
                                            x: 55.840650,
                                            y: -4.335112,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'Cardonald7',
                                        position: {
                                            x: 55.840105,
                                            y: -4.336834,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'Cardonald8',
                                        position: {
                                            x: 55.839918,
                                            y: -4.336604,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'Cardonald9',
                                        position: {
                                            x: 55.840551,
                                            y: -4.336770,
                                            z: 200
                                        }
                                    });

                                    awe.pois.add({
                                        id: 'Cardonald10',
                                        position: {
                                            x: 55.844732,
                                            y: -4.332232,
                                            z: 200
                                        }
                                    });

                                    // add projections to each of the pois

                                    // welcome board
                                    awe.projections.add({
                                        id: 'wel',
                                        geometry: {
                                            shape: 'plane',
                                            height: 100,
                                            width: 100
                                        },
                                        rotation: {
                                            x: 0,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/welcome.png'
                                        },
                                    }, {
                                        poi_id: 'welcome'
                                    });

                                    // cube with 8 design
                                    awe.projections.add({
                                        id: 'welcube',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'welcomecube'
                                    });

                                    // sun 3d object
                                    awe.projections.add({
                                        id: 'sunid',
                                        geometry: {
                                            shape: 'sphere',
                                            radius: 60
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/sun.jpg'
                                        },
                                    }, {
                                        poi_id: 'sun'
                                    });

                                    // earth planet 3d object
                                    awe.projections.add({
                                        id: 'earthid',
                                        geometry: {
                                            shape: 'sphere',
                                            radius: 50
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/earth.jpg'
                                        },
                                    }, {
                                        poi_id: 'earth'
                                    });

                                    // mars planet 3d object
                                    awe.projections.add({
                                        id: 'marsid',
                                        geometry: {
                                            shape: 'sphere',
                                            radius: 40
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/mars.jpg'
                                        },
                                    }, {
                                        poi_id: 'mars'
                                    });

                                    // cube with my picture on it
                                    awe.projections.add({
                                        id: 'robin',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/robin.jpg'
                                        },
                                    }, {
                                        poi_id: 'robincube'
                                    });

                                    awe.projections.add({
                                        id: 'a1',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'anniesland1'
                                    });

                                    awe.projections.add({
                                        id: 'a2',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'anniesland2'
                                    });

                                    awe.projections.add({
                                        id: 'a3',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'anniesland3'
                                    });

                                    awe.projections.add({
                                        id: 'a4',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'anniesland4'
                                    });

                                    awe.projections.add({
                                        id: 'a5',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'anniesland5'
                                    });

                                    awe.projections.add({
                                        id: 'a6',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'anniesland6'
                                    });

                                    awe.projections.add({
                                        id: 'a7',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'anniesland7'
                                    });

                                    awe.projections.add({
                                        id: 'a8',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'anniesland8'
                                    });

                                    awe.projections.add({
                                        id: 'a9',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'anniesland9'
                                    });

                                    awe.projections.add({
                                        id: 'a10',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'anniesland10'
                                    });

                                    awe.projections.add({
                                        id: 'a11',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'anniesland11'
                                    });

                                    awe.projections.add({
                                        id: 'a12',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'anniesland12'
                                    });

                                    // projections for Cardonald College
                                    awe.projections.add({
                                        id: 'c1',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'Cardonald1'
                                    });

                                    awe.projections.add({
                                        id: 'c2',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'Cardonald2'
                                    });

                                    awe.projections.add({
                                        id: 'c3',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'Cardonald3'
                                    });

                                    awe.projections.add({
                                        id: 'c4',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'Cardonald4'
                                    });

                                    awe.projections.add({
                                        id: 'c5',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'Cardonald5'
                                    });

                                    awe.projections.add({
                                        id: 'c6',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'Cardonald6'
                                    });

                                    awe.projections.add({
                                        id: 'c7',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'Cardonald7'
                                    });

                                    awe.projections.add({
                                        id: 'c8',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'Cardonald8'
                                    });

                                    awe.projections.add({
                                        id: 'c9',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'Cardonald9'
                                    });

                                    awe.projections.add({
                                        id: 'c10',
                                        geometry: {
                                            shape: 'cube',
                                            x: 50,
                                            y: 50,
                                            z: 50
                                        },
                                        rotation: {
                                            x: 30,
                                            y: 30,
                                            z: 0
                                        },
                                        material: {
                                            type: 'phong',
                                            color: 0xFFFFFF,
                                        },
                                        texture: {
                                            path: 'images/cube8.png'
                                        },
                                    }, {
                                        poi_id: 'Cardonald10'
                                    });


                                },

                            },
                            { // else create a fallback
                                capabilities: [],
                                files: [],
                                success: function() {
                                    document.body.innerHTML = '<p>This demo currently requires a standards compliant mobile browser.';
                                    return;
                                },
                            },
                        ]);
                    }
                });
            });

        </script>

        <script>
            // function to send user score to database using ajax
            // sends the final score to server when the close button is clicked
            $("#sendscore").click(function() {
                $.ajax({
                    type: "POST",
                    url: "functions/savescore.php",
                    data: userScore,
                    success: function(data) {}
                })
            });

        </script>

        <!-- initiate Google map -->
        <script>
            $(document).on("pageinit", "#userMap", function() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 14,
                    styles: [{
                            elementType: 'geometry',
                            stylers: [{
                                color: '#242f3e'
                            }]
                        },
                        {
                            elementType: 'labels.text.stroke',
                            stylers: [{
                                color: '#242f3e'
                            }]
                        },
                        {
                            elementType: 'labels.text.fill',
                            stylers: [{
                                color: '#746855'
                            }]
                        },
                        {
                            featureType: 'administrative.locality',
                            elementType: 'labels.text.fill',
                            stylers: [{
                                color: '#d59563'
                            }]
                        },
                        {
                            featureType: 'poi',
                            elementType: 'labels.text.fill',
                            stylers: [{
                                color: '#d59563'
                            }]
                        },
                        {
                            featureType: 'poi.park',
                            elementType: 'geometry',
                            stylers: [{
                                color: '#263c3f'
                            }]
                        },
                        {
                            featureType: 'poi.park',
                            elementType: 'labels.text.fill',
                            stylers: [{
                                color: '#6b9a76'
                            }]
                        },
                        {
                            featureType: 'road',
                            elementType: 'geometry',
                            stylers: [{
                                color: '#38414e'
                            }]
                        },
                        {
                            featureType: 'road',
                            elementType: 'geometry.stroke',
                            stylers: [{
                                color: '#212a37'
                            }]
                        },
                        {
                            featureType: 'road',
                            elementType: 'labels.text.fill',
                            stylers: [{
                                color: '#9ca5b3'
                            }]
                        },
                        {
                            featureType: 'road.highway',
                            elementType: 'geometry',
                            stylers: [{
                                color: '#746855'
                            }]
                        },
                        {
                            featureType: 'road.highway',
                            elementType: 'geometry.stroke',
                            stylers: [{
                                color: '#1f2835'
                            }]
                        },
                        {
                            featureType: 'road.highway',
                            elementType: 'labels.text.fill',
                            stylers: [{
                                color: '#f3d19c'
                            }]
                        },
                        {
                            featureType: 'transit',
                            elementType: 'geometry',
                            stylers: [{
                                color: '#2f3948'
                            }]
                        },
                        {
                            featureType: 'transit.station',
                            elementType: 'labels.text.fill',
                            stylers: [{
                                color: '#d59563'
                            }]
                        },
                        {
                            featureType: 'water',
                            elementType: 'geometry',
                            stylers: [{
                                color: '#17263c'
                            }]
                        },
                        {
                            featureType: 'water',
                            elementType: 'labels.text.fill',
                            stylers: [{
                                color: '#515c6d'
                            }]
                        },
                        {
                            featureType: 'water',
                            elementType: 'labels.text.stroke',
                            stylers: [{
                                color: '#17263c'
                            }]
                        }
                    ]
                });
                var infoWindow = new google.maps.InfoWindow({
                    map: map
                });
                // Try HTML5 geolocation.
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        infoWindow.setPosition(pos);
                        infoWindow.setContent('Player Location');
                        map.setCenter(pos);
                    }, function() {
                        handleLocationError(true, infoWindow, map.getCenter());
                    });
                } else {
                    // Browser doesn't support Geolocation
                    handleLocationError(false, infoWindow, map.getCenter());
                }

                setMarkers(map);
            });

            // Data for the markers consisting of a name, a LatLng and a zIndex for the
            // order in which these markers should display on top of each other.
            var items = [
                ['anniesland1', 55.890175, -4.325463, 1],
                ['anniesland2', 55.889681, -4.321626, 2],
                ['anniesland3', 55.888860, -4.321428, 3],
                ['anniesland4', 55.888437, -4.321438, 4],
                ['anniesland5', 55.887523, -4.321133, 5],
                ['anniesland6', 55.886771, -4.318477, 6],
                ['anniesland7', 55.886175, -4.319223, 7],
                ['anniesland8', 55.886758, -4.319513, 8],
                ['anniesland9', 55.885864, -4.318413, 9],
                ['anniesland10', 55.886073, -4.319338, 10],
                ['anniesland11', 55.887044, -4.320129, 11],
                ['anniesland12', 55.886468, -4.321267, 12],
                ['Cardonald1', 555.840257, -4.335820, 13],
                ['Cardonald2', 55.841156, -4.336915, 14],
                ['Cardonald3', 55.841231, -4.337886, 15],
                ['Cardonald4', 55.840532, -4.337580, 16],
                ['Cardonald5', 55.841089, -4.335912, 17],
                ['Cardonald6', 55.840650, -4.335112, 18],
                ['Cardonald7', 55.840105, -4.336834, 19],
                ['Cardonald8', 55.839918, -4.336604, 20],
                ['Cardonald9', 55.840551, -4.336770, 21],
                ['Cardonald10', 55.844732, -4.332232, 22]
            ];

            function setMarkers(map) {
                // Adds markers to the map.

                // Marker sizes are expressed as a Size of X,Y where the origin of the image
                // (0,0) is located in the top left of the image.

                // Origins, anchor positions and coordinates of the marker increase in the X
                // direction to the right and in the Y direction down.
                var image = {
                    url: 'images/marker2.png',
                    // This marker is 51 pixels wide by 51 pixels high.
                    size: new google.maps.Size(50, 50),
                    // The origin for this image is (0, 0).
                    origin: new google.maps.Point(0, 0),
                    // The anchor for this image is the base of the flagpole at (0, 32).
                    anchor: new google.maps.Point(0, 32)
                };
                // Shapes define the clickable region of the icon. The type defines an HTML
                // <area> element 'poly' which traces out a polygon as a series of X,Y points.
                // The final coordinate closes the poly by connecting to the first coordinate.
                var shape = {
                    coords: [1, 1, 1, 20, 18, 20, 18, 1],
                    type: 'poly'
                };
                for (var i = 0; i < items.length; i++) {
                    var locat = items[i];
                    var marker = new google.maps.Marker({
                        position: {
                            lat: locat[1],
                            lng: locat[2]
                        },
                        map: map,
                        icon: image,
                        shape: shape,
                        title: locat[0],
                        zIndex: locat[3]
                    });
                }
            }

        </script>

        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAmn1T2d3dbTJKevv80lmwThoCC9BpvKRo&callback=initMap"></script>


        <!-- functions to access map when swiping right and back to game screen when swiping left using JQuery and JQuery Mobile -->
        <script>
            $(document).on('swiperight', '#game', function(event) {
                if (event.handled !== true) // This will prevent event triggering more then once
                {
                    var nextpage = $.mobile.activePage.next('[data-role="page"]');
                    // swipe using id of next page
                    if (nextpage.length > 0) {
                        $.mobile.changePage(nextpage, {
                            transition: "slide",
                            reverse: false
                        }, true, true);
                    }
                    event.handled = true;
                }
                return false;
            });


            $(document).on('swipeleft', '#userMap', function(event) {
                if (event.handled !== true) // This will prevent event triggering more then once
                {
                    var prevpage = $(this).prev('[data-role="page"]');
                    // swipe using id of previous page
                    if (prevpage.length > 0) {
                        $.mobile.changePage(prevpage, {
                            transition: "slide",
                            reverse: true
                        }, true, true);
                    }
                    event.handled = true;
                }
                return false;
            });

        </script>


    </body>

    </html>

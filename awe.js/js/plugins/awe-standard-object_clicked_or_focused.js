
;(function(awe) {

  var projector,
    raycaster,
    main_camera,
    canvas;
  
  var _ready = false;
  var _enabled = false;
  
  var _right_clicked = false;

  var stereo_plugin;
  var avg_dev;
  var precision = 4;
  var orientation_diff = 0.1,
      last_alpha = [], last_beta = [], last_gamma = [], max_values = 10, threshold = 0.2;
  
  
  // Programmer: Larry Battle
  // Date: Mar 06, 2011
  // Purpose: Calculate standard deviation, variance, and average among an array of numbers.
  var isArray = function (obj) {
    return Object.prototype.toString.call(obj) === "[object Array]";
  },
  getNumWithSetDec = function( num, numOfDec ){
    var pow10s = Math.pow( 10, numOfDec %7C%7C 0 );
    return ( numOfDec ) ? Math.round( pow10s * num ) / pow10s : num;
  },
  getAverageFromNumArr = function( numArr, numOfDec ){
    if( !isArray( numArr ) ){ return false;    }
    var i = numArr.length,
        sum = 0;
    while( i-- ){
      sum += numArr[ i ];
    }
    return getNumWithSetDec( (sum / numArr.length ), numOfDec );
  },
  getVariance = function( numArr, numOfDec ){
    if( !isArray(numArr) ){ return false; }
    var avg = getAverageFromNumArr( numArr, numOfDec ),
        i = numArr.length,
        v = 0;
 
    while( i-- ){
      v += Math.pow( (numArr[ i ] - avg), 2 );
    }
    v /= (numArr.length-1);
    return getNumWithSetDec( v, numOfDec );
  },
  getStandardDeviation = function( numArr, numOfDec ){
    if( !isArray(numArr) ){ return false; }
    var stdDev = Math.sqrt( getVariance( numArr, numOfDec ) );
    return getNumWithSetDec( stdDev, numOfDec );
  };
  
  var mouse_down_timer;
  var last_mouseup_was_click = false;
  var click_threshold = 200; // [ms] delta between mousedown and mouseup that constitutes a click
  function _mousedown(is_mouse_down, e){
    if (is_mouse_down) {
      mouse_down_timer = Date.now();
    }
    else {
      var delta = Date.now() - mouse_down_timer;
      if (delta < click_threshold) {
        last_mouseup_was_click = true;
      }
      else {
        last_mouseup_was_click = false;
      }
    }
  }
  
  function is_stereo(){
    try {
      var view_count = awe.settings.view('view_count') ? awe.settings.view('view_count').value() : 'mono';
      return (view_count == 'stereo' && stereo_plugin && stereo_plugin.enabled);
    }
    catch(e) { console.warn('object-focused plugin is_stereo()', e); }
    return false
  }
  
  function setup() {
    projector = new THREE.Projector();
    raycaster = new THREE.Raycaster();
    main_camera = awe.pov().get_mesh();
    stereo_plugin = awe.plugins.view('render_effects');
    _ready = true;
  }

  function _get_clicked_projection(mesh) {
    if (mesh.projection_id == undefined) {
      if (mesh.parent == undefined) {
        return undefined;
      } else {
        return _get_clicked_projection(mesh.parent);
      }
    } else {
      return mesh;
    }
  }
  
  function _click(x,y,camera, right_click){
    if (!last_mouseup_was_click) { return; }
    
    var mouse_x = (x/canvas.clientWidth) * 2 - 1;
    var mouse_y = -(y/canvas.clientHeight) * 2 + 1;
    var camera_position = camera.getWorldPosition();
    var mouse_vector = new THREE.Vector3(mouse_x, mouse_y, 1);
    mouse_vector.unproject(camera); 
    var direction = mouse_vector.sub(camera_position).normalize();
    raycaster.set(camera_position, direction);
    var intersects = raycaster.intersectObjects(awe.projections.list({ type:'clickable' }), true);
    console.log(intersects.length)
    if (intersects.length) {
      var mesh = _get_clicked_projection(intersects[0].object);
      if (mesh && mesh.projection_id) {
        intersects[0].projection_id = mesh.projection_id;
        var event_name = right_click ? 'object_right_clicked' : 'object_clicked';
        var event = new CustomEvent(event_name, { detail:intersects[0] });
        window.dispatchEvent(event);
        return;
      }
    }
    var event_name = right_click ? 'scene_right_clicked' : 'scene_clicked';
    var event = new CustomEvent(event_name, { detail:intersects[0] });
    window.dispatchEvent(event);
  }
  
	awe.plugins.add([{
		id: 'object_clicked',
    capabilities: ['webgl'],
    auto_register: false,
    enable: function(){
      _enabled = true;
    },
    disable: function(){
      _enabled = false;
    },
		register: function(){
  		canvas = awe.renderer().get_canvas();
			awe.events.add([
  			{
    			id: 'object_clicked_gyro',
  				capabilities: ['webgl', 'gyro'],
  				register: function(handler) {
  					window.addEventListener('deviceorientation', handler, false);
  				},
  				unregister: function(handler){
  					window.removeEventListener('deviceorientation', handler, false);
  				},
  				handler: function(event) {
        				
  					if (!_enabled) {
    					return;
  					}
    				if (!is_stereo()) {
      			  return;
      		  }
            last_alpha.unshift(event.alpha);
            last_beta.unshift(event.beta);
            last_gamma.unshift(event.gamma);
            if (last_alpha.length > max_values) {
              last_alpha.pop();
              last_beta.pop();
              last_gamma.pop();
            }
            var alpha_dev = getStandardDeviation(last_alpha, precision);
            var beta_dev = getStandardDeviation(last_beta, precision);
            var gamma_dev = getStandardDeviation(last_gamma, precision);
            avg_dev = (alpha_dev+beta_dev+gamma_dev) / 3;
            
  				}
  			},

			  {
  				id: 'object_clicked_tick',
  				capabilities: ['webgl', 'gyro'],
  				register: function(handler) {
  					window.addEventListener('tick', handler, false);
  				},
  				unregister: function(handler){
  					window.removeEventListener('tick', handler, false);
  				},
  				handler: function(e) {
  					if (!_enabled) {
    					return;
  					}
    				if (!_ready) {
    					setup();
    				}
    				if (!is_stereo()) {
      			  return;
      		  }
					
          	if (avg_dev && avg_dev < threshold ) {
              // still
              var x = e.clientX,
                y = e.clientY;
              if (is_stereo()) {
                var cameras = stereo_plugin.get_stereo_cameras();
                var camera;
                
                if (x > canvas.clientWidth/2) {
                  // right viewport half
                  camera = cameras.right;
                  x -= canvas.clientWidth/2;
                }
                else {
                  // left viewport half
                  camera = cameras.left;
                }
                x *= 2;
                // perhaps add a timeout here
                _click(canvas.clientWidth/2, canvas.clientHeight/2, camera);
              }
            }
            else {
              // shaking 
            }
          }
  			},
			  {
  				id: 'object_clicked_mouseup',
  				capabilities: ['webgl'],
  				register: function(handler) {
  					canvas.addEventListener('mouseup', handler, false);
  				},
  				unregister: function(handler){
  					canvas.removeEventListener('mouseup', handler, false);
  				},
  				handler: function(e) {
    				_right_clicked = (e.which == 3);
    				_mousedown(false);
  				}
  			},
			  {
  				id: 'object_clicked_mousedown',
  				capabilities: ['webgl'],
  				register: function(handler) {
  					canvas.addEventListener('mousedown', handler, false);
  				},
  				unregister: function(handler){
  					canvas.removeEventListener('mousedown', handler, false);
  				},
  				handler: function(e) {
    				_right_clicked = false;
    				_mousedown(true);
  				}
  			},
			  {
  				id: 'object_clicked',
  				capabilities: ['webgl'],
  				register: function(handler) {
  					canvas.addEventListener('contextmenu', handler, false);
  					canvas.addEventListener('click', handler, false);
  				},
  				unregister: function(handler){
  					canvas.removeEventListener('contextmenu', handler, false);
  					canvas.removeEventListener('click', handler, false);
  				},
  				handler: function(e) {
    				if (!_enabled) {
    					console.log('click plugin disabled');
    					return;
  					}
  					if (!_ready) {
    					setup();
  					}
            var settings = awe.util.get_settings('click_handling');
            if (settings.click_handling === undefined %7C%7C settings.click_handling == true) {
              var x = e.clientX,
                y = e.clientY,
                camera;
              if (is_stereo()) {
//               var view_count = awe.settings.view('view_count') ? awe.settings.view('view_count').value() : 'mono';
//               if (view_count == 'stereo' && stereo_plugin && stereo_plugin.enabled) {
                var cameras = stereo_plugin.get_stereo_cameras();
                
                if (x > canvas.clientWidth/2) {
                  // right viewport half
                  camera = cameras.right;
                  x -= canvas.clientWidth/2;
                }
                else {
                  // left viewport half
                  camera = cameras.left;
                }
                x *= 2;
              }
              else {
                camera = main_camera;
              }
              e.preventDefault();
              _click(x, y, camera, e.type == 'contextmenu');
              
            }
  				}
  			},

			]);
		},
		unregister: function(){
			awe.events.delete('object_clicked_gyro');
			awe.events.delete('object_clicked_tick');
			awe.events.delete('object_clicked_mono');
		}
	}]);
})(window.awe);

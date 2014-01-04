<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['multimedia_css'] = 'media/css/multimediagallery.css';

$config['path'] = 'multimedia';
$config['jmedia_element'] = 'multimedia/jMediaelement';

$config['jmedia_styles'] = $config['jmedia_element'] . '/css/styles.css';
$config['jmedia_player_controls'] = $config['jmedia_element'] . '/css/player-controls.css';

$config['jmedia_utils_slider'] = $config['jmedia_element'] . '/utils/a11y-slider.ext.js';
$config['jmedia_utils_activity'] = $config['jmedia_element'] . '/utils/useractivity.js';
$config['jmedia_utils_controls'] = $config['jmedia_element'] . '/utils/jmeEmbedControls.js';
$config['jmedia_packages_mmfull'] = $config['jmedia_element'] . '/packages/mm.full.min.js';
$config['jmedia_plugins_fullwindow'] = $config['jmedia_element'] . '/plugins/fullwindow.js';

$config['jquery_viewport'] = 'media/js/jquery.viewport.js';
$config['jquery_multimedia'] = 'media/js/jquery.multimediagallery.js';
$config['jquery_json'] = 'media/js/json/json2.js';

$config['media'] = $config['path'] . '/media';
$config['media_trash'] = $config['media'] . '/trash';
$config['media_photos'] = $config['media'] . '/photos';
$config['media_audio'] = $config['media'] . '/audio';
$config['media_videos'] = $config['media'] . '/videos';

$config['photos_thumbs'] = $config['media_photos'] . 'thumbs';
$config['audio_thumbs'] = $config['media_audio'] . 'thumbs';
$config['videos_thumbs'] = $config['media_videos'] . 'thumbs';


/* End of file multimedia.php */
/* Location: ./application/config/multimedia.php */

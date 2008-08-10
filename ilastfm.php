<?php
/*
Plugin Name: iLast.Fm
Plugin URI: http://leandrow.net/lastfm/
Description: If you like good music this plugin is for you: it offers a complete integration between your blog and your Last.fm account. You can show on your blog what musics you are listening to, your top albums or your loved tracks. Funny like that!
Version: 0.2
Author: Leandro Alonso
Author URI: http://leandrow.net/
*/

/* When the plugin is activated, this function will be executed */

register_activation_hook( __FILE__, 'ilastfm_activate' );

function ilastfm_activate() {
	$ilastfm_options = array(
		"username" => '',
		"method" => 5,
		"display_mode" => 1,
		"display_number" => 9,
		"period" => 1,
		"cover_width" => 2,
		"cache" => 3,
		"cache_images" => 0,
		"jump" => 0,
		"nocover" => get_option('siteurl') . '/wp-content/plugins/ilastfm/nocover.jpg',
		"nextcache" => 0,
		"widget_title" => 'iLast.fm',
		"style" => 0,
		"style_width" => 60
	);
	$path = dirname(__FILE__) . '/cache';
	if (!is_writable($path)) {
		$ilastfm_options['cache'] = 1;
	}
	add_option("ilastfm_options", $ilastfm_options, '', 'yes');
}

/* When the plugin is deactivated, this function will be executed 
   And yes, we keep your database clear. :) */

register_deactivation_hook( __FILE__, 'ilastfm_deactivate' );

function ilastfm_deactivate() {
	delete_option("ilastfm_options");
}

/* Add iLast.Fm on Plugins' Menu */

function ilastfm_add_menu() {
 if (function_exists('add_options_page')) {
    add_submenu_page('plugins.php', 'iLast.Fm - Your musics in your blog', 'iLast.Fm', 8, basename(__FILE__), 'ilastfm_options_page');
  }
}
add_action('admin_menu', 'ilastfm_add_menu');

function ilastfm_options_page() { ?>

<?php
	$ilastfm_options = get_option('ilastfm_options');
	
	if ($_POST['ilastfm_send']) {
		
		if (!empty($_POST['ilastfm_username']) && !empty($_POST['ilastfm_number']) && !empty($_POST['ilastfm_nocover'])) {
			$ilastfm_options['username'] = $_POST['ilastfm_username'];
			$ilastfm_options['method'] = $_POST['ilastfm_display'];
			$ilastfm_options['display_mode'] = $_POST['ilastfm_mode'];
			$ilastfm_options['display_number'] = $_POST['ilastfm_number'];
			$ilastfm_options['period'] = $_POST['ilastfm_period'];
			$ilastfm_options['cover_width'] = $_POST['ilastfm_cover'];
			$ilastfm_options['cache'] = $_POST['ilastfm_cache'];
			$ilastfm_options['cache_images'] = $_POST['ilastfm_cacheimages'];
			$ilastfm_options['jump'] = $_POST['ilastfm_jump'];
			$ilastfm_options['nocover'] = $_POST['ilastfm_nocover'];
			$ilastfm_options['style'] = $_POST['ilastfm_style'];
			$ilastfm_options['style_width'] = $_POST['ilastfm_style_width'];
			$ilastfm_options['nextcache'] = 0;
			update_option('ilastfm_options', $ilastfm_options);
			echo '<div class="updated fade" id="message" style="background-color: rgb(255, 251, 204);"><p><strong>Settings saved.</strong></p></div>';
		}
		
	}
	
	function check_sel($number,$option) {
		$ilastfm_options = get_option('ilastfm_options');
		if ($option == 1) { $check = $ilastfm_options['method']; }
		elseif ($option == 2) { $check = $ilastfm_options['period']; }
		elseif ($option == 3) { $check = $ilastfm_options['display_mode']; }
		elseif ($option == 4) { $check = $ilastfm_options['cover_width']; }
		elseif ($option == 5) { $check = $ilastfm_options['cache']; }
		if ($number == $check) {
			echo 'selected="selected"';
		}
	}
	
?>

<div class="wrap">
	<h2>iLast.Fm Configuration</h2>
	<form id="ilastfm" class="form-table" method="post" action="">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="ilastfm_username">Last.Fm username</label>
					</th>
					<td>
						<input type="text" name="ilastfm_username" id="ilastfm_username" value="<?php echo $ilastfm_options['username'] ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="ilastfm_display">Display</label>
					</th>
					<td>
						<select id="ilastfm_display" name="ilastfm_display">
							<option value="1" <?php check_sel(1,1); ?>>Recent Tracks</option>
							<option value="2" <?php check_sel(2,1); ?>>Top Albums</option>
							<option value="3" <?php check_sel(3,1); ?>>Top Artists</option>
							<option value="4" <?php check_sel(4,1); ?>>Top Tracks</option>
							<option value="5" <?php check_sel(5,1); ?>>Weekly Album Chart</option>
							<option value="6" <?php check_sel(6,1); ?>>Weekly Artist Chart</option>
							<option value="7" <?php check_sel(7,1); ?>>Weekly Track Chart</option>
							<option value="8" <?php check_sel(8,1); ?>>Loved Tracks</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="ilastfm_period">Period</label>
					</th>
					<td>
						<select id="ilastfm_period" name="ilastfm_period">
							<option value="1" <?php check_sel(1,2); ?>>Overall</option>
							<option value="2" <?php check_sel(2,2); ?>>3 month</option>
							<option value="3" <?php check_sel(3,2); ?>>6 month</option>
							<option value="4" <?php check_sel(4,2); ?>>12 month</option>
						</select>
						<br/>
						Only used for "Top Albums", "Top Artists" and "Top Tracks".
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="ilastfm_mode">Display mode</label>
					</th>
					<td>
						<select id="ilastfm_mode" name="ilastfm_mode">
							<option value="1" <?php check_sel(1,3); ?>>Only album art</option>
							<option value="2" <?php check_sel(2,3); ?>>Album art and infos</option>
							<option value="3" <?php check_sel(3,3); ?>>Only infos</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="ilastfm_cover">Cover width</label>
					</th>
					<td>
						<select id="ilastfm_cover" name="ilastfm_cover">
							<option value="1" <?php check_sel(1,4); ?>>Small</option>
							<option value="2" <?php check_sel(2,4); ?>>Medium</option>
							<option value="3" <?php check_sel(3,4); ?>>Large</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="ilastfm_number">Number of covers/infos</label>
					</th>
					<td>
						<input type="text" id="ilastfm_number" name="ilastfm_number" value="<?php echo $ilastfm_options['display_number']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="ilastfm_cache">Cache</label>
					</th>
					<td>
						<?php $path = dirname(__FILE__) . '/cache'; if (!is_writable($path)) { ?>
						The cache will not work. The folder /cache can not be written. <a href="http://leandrow.net/lastfm/#faq">Need help?</a>
						<?php } else { ?>
						<select id="ilastfm_cache" name="ilastfm_cache">
							<option value="1" <?php check_sel(1,5); ?>>No</option>
							<option value="2" <?php check_sel(2,5); ?>>Yes - Every 6 hours</option>
							<option value="3" <?php check_sel(3,5); ?>>Yes - Every day</option>
							<option value="4" <?php check_sel(4,5); ?>>Yes - Every week</option>
							<option value="5" <?php check_sel(5,5); ?>>Yes - Every two week</option>
							<option value="6" <?php check_sel(6,5); ?>>Yes - Every three week</option>
							<option value="7" <?php check_sel(7,5); ?>>Yes - Every month</option>
						</select>
						<br/>
						Is highly recommended leave the cache enabled. Leaving it off demand much processing.
						<?php } ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Cache images?
					</th>
					<td>
						<?php $path = dirname(__FILE__) . '/cache'; if (!is_writable($path)) { ?>
						The cache will not work. The folder /cache can not be written. <a href="http://leandrow.net/lastfm/#faq">Need help?</a>
						<?php } else { ?>
						<label for="ilastfm_cacheimages">
							<input type="checkbox" id="ilastfm_cacheimages" name="ilastfm_cacheimages" value="1" <?php if ($ilastfm_options['cache_images'] == 1) { echo 'checked="checked"'; } ?> />
							Enable the cache of all images of covers to your server
						</label>
						<?php } ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Jump albums without cover?
					</th>
					<td>
						<label for="ilastfm_jump">
							<input type="checkbox" id="ilastfm_jump" name="ilastfm_jump" value="1" <?php if ($ilastfm_options['jump'] == 1) { echo 'checked="checked"'; } ?> />
							Albums without cover will not be displayed
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="ilastfm_nocover">Image for albums without cover</label>
					</th>
					<td>
						<input type="text" name="ilastfm_nocover" id="ilastfm_nocover" style="width:50%;" value="<?php echo $ilastfm_options['nocover']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Style (CSS)
					</th>
					<td>
						<label for="ilastfm_style">
							<input type="checkbox" id="ilastfm_style" name="ilastfm_style" value="1" <?php if ($ilastfm_options['style'] == 1) { echo 'checked="checked"'; } ?> />
							Use default style
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="ilastfm_style_width">CSS Cover Width</label>
					</th>
					<td>
						<input type="text" id="ilastfm_style_width" name="ilastfm_style_width" value="<?php echo $ilastfm_options['style_width']; ?>" />
						<br/>
						Only works if the default style is enabled. <a href="http://leandrow.net/lastfm/#styles">Need more styles?</a>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="ilastfm_send" id="ilastfm_send" value="true" />
		<p class="submit"><input type="submit" value="Save Changes" /></p>
	</form>
</div>

<?php }

function ilastfm() {
	$ilastfm_options = get_option("ilastfm_options");
	if ($ilastfm_options['cache'] > 1) {
		if (time() > $ilastfm_options['nextcache']) {
			$ilastfm = new iLastfm($ilastfm_options['username'], $ilastfm_options['method'], $ilastfm_options['display_mode'],  $ilastfm_options['display_number'], $ilastfm_options['period'], $ilastfm_options['cover_width'], $ilastfm_options['cache'], $ilastfm_options['cache_images'], $ilastfm_options['jump'], $ilastfm_options['nocover']);
			if (!$ilastfm->error) {
				$ilastfm->cleanCache();
				$ilastfm->display();
				if (!$ilastfm->error) {
					switch ($ilastfm_options['cache']) {
						case 2:
						$moretime = 6*60*60;
						break;

						case 3:
						$moretime = 24*60*60;
						break;

						case 4:
						$moretime = 7*24*60*60;
						break;

						case 5:
						$moretime = 2*7*24*60*60;
						break;

						case 6:
						$moretime = 3*7*24*60*60;
						break;

						case 7:
						$moretime = 4*7*24*60*60;
						break;
					}
					$expires = time() + $moretime;
					$ilastfm_options['nextcache'] = $expires;
					update_option('ilastfm_options', $ilastfm_options);
				}
			}
		} else {
			include dirname(__FILE__) . '/cache/output.cache';
		}
	} else {
		$ilastfm = new iLastfm($ilastfm_options['username'], $ilastfm_options['method'], $ilastfm_options['display_mode'],  $ilastfm_options['display_number'], $ilastfm_options['period'], $ilastfm_options['cover_width'], $ilastfm_options['cache'], $ilastfm_options['cache_images'], $ilastfm_options['jump'], $ilastfm_options['nocover']);
		if (!$ilastfm->error) {
			$ilastfm->display();
		}
	}
}

/* Here is the place where the magic happens! :) */

class iLastfm {
	
	private $apikey;
	private $username;
	private $method;
	private $mode;
	private $number;
	private $period;
	private $coverwidth;
	private $cache;
	private $imagecache;
	private $jump;
	private $nocover;
	
	var $error;
	
	var $artist;
	var $music;
	var $album;
	var $image;
	var $playcount;
	var $url;
	
	function __construct($username, $method, $mode, $number, $period, $coverwidth, $cache, $imagecache, $jump, $nocover) {
		
		if (empty($username)) { $this->error = true; }
		else {
			$this->username = $username;
			// Check if attributes has value, otherwise gives them default values
			if (!empty($method)) { $this->method = $method; }
			else { $this->method = 5; }
			if (!empty($mode)) { $this->mode = $mode; }
			else { $this->mode = 1; }
			if (!empty($number)) { $this->number = $number; }
			else { $this->number = 9; }
			if (!empty($period)) { $this->period = $period; }
			else { $this->period = 1; }
			if (!empty($coverwidth)) { $this->coverwidth = $coverwidth - 1; }
			else { $this->coverwidth = 1; }
			if (!empty($cache)) { $this->cache = $cache; }
			else { $this->cache = 3; }
			if (!empty($imagecache)) { $this->imagecache = $imagecache; }
			else { $this->imagecache = 0; }
			if (!empty($jump)) { $this->jump = $jump; }
			else { $this->jump = 0; }
			if (!empty($nocover)) { $this->nocover = $nocover; }
			else { $this->nocover = 'http://img67.imageshack.us/img67/6964/itunestd5.jpg'; }
			$this->apikey = "3b3945ec4948e970f2277e0c9a05bd68";
			
			if ($this->method > 1 && $this->method < 5) {
				switch ($this->period) {
					case 1:
					$this->period = 'overall';
					break;

					case 2:
					$this->period = '3month';
					break;

					case 3:
					$this->period = '6month';
					break;

					case 4:
					$this->period = '12month';
					break;
				}
			}
			
			if ($this->method >= 5) {
				$chart = self::getXml('user.getweeklychartlist','');
				$chartopt = sizeof($chart->weeklychartlist->chart) - 1;
				$chart = $chart->weeklychartlist->chart[$chartopt];
			}
			
			switch($this->method) {
				case 1:
				self::getRecentTracks();
				break;

				case 2:
				self::getTopAlbums($this->period); // put period
				break;

				case 3:
				self::getTopArtists($this->period); // put period
				break;

				case 4:
				self::getTopTracks($this->period); // put period
				break;

				case 5:
				self::getWeeklyAlbumChart($chart['from'],$chart['to']); // put from and to
				break;

				case 6:
				self::getWeeklyArtistChart($chart['from'],$chart['to']); // put from and to
				break;

				case 7:
				self::getWeeklyTrackChart($chart['from'],$chart['to']); // put from and to;
				break;

				case 8:
				self::getLovedTracks();
				break;
			}
			
			$this->error = false;
		}
		
	}
	
	function getXml($method,$arguments) {
		$url = 'http://ws.audioscrobbler.com/2.0/?method=' . $method . '&user=' . $this->username .'&api_key=' . $this->apikey . "$arguments";
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$content = curl_exec($ch);
		curl_close($ch);
		if ($content) {
			if (function_exists('simplexml_load_file')) {
				$xml = new SimpleXMLElement($content);
				return $xml;
			} else {
				$this->error = true;
			}
		} else {
			$this->error = true;
		}
	}
	
	function getRecentTracks() {
		$recents = self::getXml('user.getrecenttracks','');
		foreach ($recents->recenttracks->track as $track) {
			$this->artist[] = $track->artist;
			$this->music[] = $track->name;
			$this->album[] = $track->album;
			$this->url[] = $track->url;
			$this->image[] = $track->image[$this->coverwidth];
		}
	}
	
	function getTopAlbums($period) {
		$period = '&period=' . $period;
		$albums = self::getXml('user.gettopalbums',$period);
		foreach ($albums->topalbums->album as $album) {
			$this->artist[] = $album->artist->name;
			$this->album[] = $album->name;
			$this->url[] = $album->url;
			$putz = $this->coverwidth;
			$this->image[] = $album->image[$this->coverwidth];
		}
	}
	
	function getTopArtists($period) {
		$period = '&period=' . $period;
		$artists = self::getXml('user.gettopartists',$period);
		foreach ($artists->topartists->artist as $artist) {
			$this->artist[] = $artist->name;
			$this->url[] = $artist->url;
			$this->image[] = $artist->image[$this->coverwidth];
			$this->playcount[] = $artist->playcount;
		}
	}
	
	function getTopTracks($period) {
		$period = '&period=' . $period;
		$tracks = self::getXml('user.gettoptracks',$period);
		foreach ($tracks->toptracks->track as $track) {
			$this->artist[] = $track->artist->name;
			$this->music[] = $track->name;
			$this->url[] = $track->url;
			$this->image[] = $track->image[$this->coverwidth];
			$this->playcount[] = $track->playcount;
		}
	}
	
	function getWeeklyAlbumChart($from,$to) {
		$fromto = '&from=' . $from . '&to=' . $to;
		$albumchart = self::getXml('user.getweeklyalbumchart',$fromto);
		$i = 0;
		foreach ($albumchart->weeklyalbumchart->album as $album) {
			$this->artist[] = $album->artist;
			$this->album[] = $album->name;
			$this->url[] = $album->url;
			$this->playcount[] = $album->playcount;
			if ($album->mbid != '') { $this->image[] = self::getArt($album->mbid,'',''); }
			else {
				$urlx = explode("/",$album->url);
				$this->image[] = self::getArt('',$urlx[4],$urlx[5]);
			}
			if ($i == $this->number - 1) { break; }
			$i++;
		}
	}
	
	function getWeeklyArtistChart($from,$to) {
		$fromto = '&from=' . $from . '&to=' . $to;
		$artistchart = self::getXml('user.getweeklyartistchart',$fromto);
		foreach ($artistchart->weeklyartistchart->artist as $artist) {
			$this->artist[] = $artist->name;
			$this->url[] = $artist->url;
			$this->playcount[] = $artist->playcount;
			$this->image[] = self::getArt('',$artist->name,'');
		}
	}
	
	function getWeeklyTrackChart($from,$to) {
		$fromto = '&from=' . $from . '&to=' . $to;
		$trackchart = self::getXml('user.getweeklytrackchart',$fromto);
		foreach ($trackchart->weeklytrackchart->track as $track) {
			$this->artist[] = $track->artist;
			$this->music[] = $track->name;
			$this->url[] = $track->url;
			$this->playcount[] = $track->playcount;
		}
	}
	
	function getLovedTracks() {
		$lovedtrack = self::getXml('user.getlovedtracks','');
		foreach ($lovedtrack->lovedtracks->track as $track) {
			$this->artist[] = $track->artist->name[0];
			$this->music[] = $track->name;
			$this->url[] = 'http://' . $track->url;
			$this->image[] = $track->image[$this->coverwidth];
		}
	}
	
	function putImage($url,$n,$alt,$link) {
		if ($url == '') { $url = $this->nocover; }
		elseif ($this->imagecache == 1) {
			$path = dirname(__FILE__) . '/cache';
			$file = explode('/',$url);
			$file = $file[sizeof($file)-1];
			if (is_file($path . '/' . $file)) {
				$url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename($path . '/' . $file);
			} else {
				if (is_writable($path)) {
					$ch = curl_init($url);
					$imagefile = fopen($path . '/' . $file, 'w');
					curl_setopt($ch, CURLOPT_FILE, $imagefile);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_exec($ch);
					curl_close($ch);
					fclose($imagefile);
					$url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename($path . '/' . $file);
				}
			}
		}
		return '<a href="' . $link . '" title="' . $alt .'"><img src="' . $url . '" class="lastfm_album art' . $n . '" alt="' . $alt . '" /></a>';
	}
	
	function putInfos($artist,$album,$music,$playcount,$url) {
		if ($url != '') { $output .= '<a href="' . $url . '">'; }
		if ($artist != '') { $output .= '<span class="lastfm_artist">' . $artist . '</span>'; }
		if ($album != '') { $output .= '<span class="lastfm_album"> - ' . $album . '</span>'; }
		if ($music != '') { $output .= '<span class="lastfm_music"> - ' . $music . '</span>'; }
		if ($playcount != '') { $output .= '<span class="lastfm_playcount"> - ' . $playcount . '</span>'; }
		if ($url != '') { $output .= '</a>'; }
		return $output;
	}
	
	function getArt($mbid,$artist,$album) {
		if ($mbid != '') {
			$art = self::getXml('album.getinfo','&mbid=' . $mbid);
			return $art->album->image[$this->coverwidth];
		}
		elseif ($album != '') {
			$art = self::getXml('album.getinfo','&artist=' . $artist . '&album=' . $album);
			return $art->album->image[$this->coverwidth];
		} else {
			$artist = urlencode($artist);
			$art = self::getXml('artist.getinfo','&artist=' . $artist);
			return $art->artist->image[$this->coverwidth];
		}
	}
	
	function display() {
		for ($i = 0; $i < sizeof($this->artist); $i++) {
			$noart = explode("/",$this->image[$i]);
			if ($noart[5] == 'noimage') { $this->image[$i] = ''; }
			if ($this->jump == 1 && $this->image[$i] == '') {
				$this->number += 1;
			} else {
				$output .= '<li>';
				if ($this->album[$i] != '') { $album = ' - ' . $this->album[$i]; }
				if ($this->mode < 3) { $output .= $this->putImage($this->image[$i],$i,$this->artist[$i] . $album,$this->url[$i]); }
				if ($this->mode > 1) { $output .= $this->putInfos($this->artist[$i],$this->album[$i],$this->music[$i],$this->playcount[$i],$this->url[$i]) . '<br/>'; }
				$output .= '</li>' . "\n";
			}
			if ($i == $this->number - 1) { break; }
		}
		if ($this->cache > 1) { self::cache($output); include dirname(__FILE__) . '/cache/output.cache'; }
		else { echo $output; }
	}
	
	function cache($output) {
		$path = dirname(__FILE__) . '/cache';
		if (is_writable($path)) {
			$handle = fopen($path . "/output.cache", "w+");
			fwrite($handle, $output);
			fclose($handle);
		} else {
			$this->error = true;
			echo $output;
		}
	}
	
	function cleanCache() {
		$path = dirname(__FILE__) . '/cache';
		if (is_writable($path)) {
			if ($dir_handle = opendir($path)) {
				while(($file = readdir($dir_handle)) != false) {
					if (filetype($path . '/' . $file) == 'file') {
						unlink($path . '/' . $file);
					}
				}
			}
		}
	}

}

/* Add iLast.Fm Widget */

function widget_ilastfm_init() {
	
	if (!function_exists('register_sidebar_widget')) {
		return;
	}
	
	function widget_ilastfm($args) {
	    extract($args);
		$ilastfm_options = get_option('ilastfm_options');
		$title = $ilastfm_options['widget_title'];
	?>
		<?php echo $before_widget; ?>
			<?php echo $before_title
                . $title
                . $after_title; ?>
				<ul id="ilastfm_display">
		            <?php ilastfm(); ?>
				</ul>
		<?php echo $after_widget; ?>
	<?php
	}
	register_sidebar_widget('iLast.Fm', 'widget_ilastfm');
	
	function widget_ilastfm_control() {
		$ilastfm_options = get_option('ilastfm_options');
		$title = $ilastfm_options['widget_title'];
		
		if (!empty($_POST['ilastfm_widget_title'])) {
			$title = strip_tags(stripslashes($_POST['ilastfm_widget_title']));
			$ilastfm_options['widget_title'] = $title;
			update_option('ilastfm_options', $ilastfm_options);
		}
		
		$title = htmlspecialchars($title, ENT_QUOTES);
		?>
			
			<p>
				<label for="ilastfm_widget_title">
					Title:
					<input type="text" id="ilastfm_widget_title" name="ilastfm_widget_title" value="<?php echo $title; ?>" />
				</label>
			</p>
			
		<?php
		
	}
	register_widget_control('iLast.Fm', 'widget_ilastfm_control', 200, 50);
}
add_action('widgets_init', 'widget_ilastfm_init');

/* Function that shows (or not) the default style */

add_action('wp_head', 'ilastfm_head');

function ilastfm_head() {
	$ilastfm_options = get_option('ilastfm_options');
	if ($ilastfm_options['style'] == 1) { ?>
		<style type="text/css" media="screen">
		ul#ilastfm li, ul#ilastfm_display li {
			list-style-type: none;
			list-style-image: none;
			display: inline;
		}

		ul#ilastfm, ul#ilastfm_display {
			margin: 5px 0 0 5px;
			padding: 0;
		}

		#ilastfm a img, #ilastfm_display a img {
			background: #E8E5DC;
			padding: 2px;
			border: 1px solid #E8E5DC;
			width: <?php echo $ilastfm_options['style_width'] . 'px'; ?>;
			height: <?php echo $ilastfm_options['style_width'] . 'px'; ?>;
		}

		#ilastfm a:hover img, #ilastfm_display a:hover img {
			border: 1px solid #C0C0A8;
		}
		</style>
	<?php }
}

?>
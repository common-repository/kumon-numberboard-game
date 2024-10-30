<?php
/*
Plugin Name: Kumon Numberboard Game
Plugin URI: http://www.kumon.co.uk/download-numberboard-game/index.htm
Description: A Kumon Numberboard Game for Wordpress.
Author: Kumonuk
Version: 1.0
Author URI: http://www.kumon.co.uk/
*/

$kumon_numberboard_data = array();
$kumon_numberboard_data['300x250'] = array('lable' => '300 x 250','code' => '<script type="text/javascript" src="http://www.kumon.co.uk/widgets/numberboard/widget.js?style=3"></script>
<div style="width:300px;text-align:right;font-size:8pt;"><a rel="kumon_link" href="http://www.kumon.co.uk">Powered by Kumon</a></div>');

$kumon_numberboard_data['300x180'] = array('lable' => '300 x 180','code' => '<script type="text/javascript" src="http://www.kumon.co.uk/widgets/numberboard/widget.js?style=4"></script>
<div style="width:300px;text-align:right;font-size:8pt;"><a rel="kumon_link" href="http://www.kumon.co.uk">Powered by Kumon</a></div>');

$kumon_numberboard_data['160x110'] = array('lable' => '160 x 110','code' => '<script type="text/javascript" src="http://www.kumon.co.uk/widgets/numberboard/widget.js?style=2"></script>
<div style="width:160px;text-align:right;font-size:8pt;"><a rel="kumon_link" href="http://www.kumon.co.uk">Powered by Kumon</a></div>');

$kumon_numberboard_data['120x120'] = array('lable' => '120 x 120','code' => '<script type="text/javascript" src="http://www.kumon.co.uk/widgets/numberboard/widget.js?style=1"></script>
<div style="width:120px;text-align:right;font-size:8pt;"><a rel="kumon_link" href="http://www.kumon.co.uk">Powered by Kumon</a></div>');


function wp_kumon_replace_contents($content){
	if(is_single() || is_page()){
		global $kumon_numberboard_data;
		$find = array();
		$replace = array();
		foreach($kumon_numberboard_data as $widget_size => $widget_data){
			$find[] = '<!--'.$widget_size.'-->';
			$replace[] = $widget_data['code'];
			
		}
		$content = str_replace($find,$replace,$content);
	}
	return $content;
}

add_action('the_content','wp_kumon_replace_contents');

if(preg_match('/wp-admin/',$_SERVER['PHP_SELF'])){

function wp_kumon_numberboard_menus(){
		add_menu_page('Kumon Game', 'Kumon Game', 8, __FILE__, 'wpkn_basic_config');
	}
	
function wpkn_basic_config(){
	global $kumon_numberboard_data;
	?>
 <div class="icon32" id="icon-options-general"><br/></div>

<h2>Kumon Numberboard Game</h2>

<BR />

<table class="form-table">
<caption><h3>Guide To Embed The Game in Posts & Pages</h3></caption>
<tbody>

<tr class="tr">

	<td width="200"><strong>Size</strong></td>

	<td class="first">
        <strong>Embed Code</strong>
    </td>
</tr>

<?php
foreach($kumon_numberboard_data as $widget_size => $widget_data){
?>
<tr class="tr">

	<td width="200"><?php echo $widget_data['lable'];?></td>

	<td class="first">
       <?php echo htmlentities('<!--'.$widget_size.'-->');?>
    </td>
</tr>
<?php	
}
?>


</tbody>
</table><BR />
Note: To enable the game in your post or page, just copy and paste the relevent embed code into your post (in the HTML mode, not in the WYSIWYG mode). That's all. 
    <?php
}
add_action('admin_menu', 'wp_kumon_numberboard_menus');	
}


// This gets called at the plugins_loaded action
function widget_kumon_numberboard_init() {

	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') )
	return;


	if(!function_exists('widget_kumon_numberboard_control')){
		function widget_kumon_numberboard_control() {
			if(isset($_POST['widget_kumon_title'])){
					update_option('widget_kumon_title',trim($_POST['widget_kumon_title']));
					update_option('widget_kumon_size',$_POST['widget_kumon_size']);
			}
			global $kumon_numberboard_data;
			?>
			<table cellpadding="20" cellspacing="20" border="1">
            <tr><td>Widget Title</td><td><input value="<?php echo get_option('widget_kumon_title');?>" type="text" name="widget_kumon_title" id="widget_kumon_title" size="25" /></td></tr>
            <tr><td>Widget Size</td><td><select name="widget_kumon_size">
            <?php
			$widget_kumon_size = get_option('widget_kumon_size');
			foreach($kumon_numberboard_data as $widget_size => $widget_data){
				$selected = $widget_kumon_size == $widget_size ? " selected " : "";
			?>
            <option value="<?php echo $widget_size;?>" <?php echo $selected;?>><?php echo $widget_data['lable'];?></option>
           <?php
			}
		   ?>
            </select></td></tr>
            </table>
			<?php
		}
	}
	
	function widget_kumon_numberboard(){
		global $kumon_numberboard_data;
		 echo $before_widget; ?>
			<center><?php echo $before_title . get_option('widget_kumon_title') . $after_title;
  				?></center>
  				<table align="center"><tr>
  				<td>
                <?php
				echo $kumon_numberboard_data[get_option('widget_kumon_size')]['code'];
				?>
                </td>
				</tr>
				</table>
		<?php echo $after_widget; 
		
	}
	register_widget_control('Kumon Numberboard Widget', 'widget_kumon_numberboard_control',400,500);
	register_sidebar_widget('Kumon Numberboard Widget', 'widget_kumon_numberboard');
}

add_action('widgets_init', 'widget_kumon_numberboard_init');
?>
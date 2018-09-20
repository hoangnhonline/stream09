<?php
/**
 * The Template for displaying all single video posts with standard layout.
 *
 * @package deTube
 * @subpackage Template
 * @since deTbue 1.1
 */

get_header(); 

$info_toggle = (int)get_option('dp_info_toggle');
?>
<?php 
$download = get_post_meta( $post->ID, 'download', true);
$arrDetailH = get_post_meta($post->ID);		

$isXvideo = $is_hihi = 0;
if(isset($arrDetailH) && !empty($arrDetailH)){
	$urlGet = $arrDetailH["dp_video_file"][0];	
	if(!$urlGet) $urlGet = $arrDetailH['dp_video_url']['0'];	
	$isXvideo = ( strpos($urlGet, 'xvideos') > 0 || strpos($urlGet, 'hihi.com') > 0 || strpos($urlGet, 'redtube.com') > 0 || strpos($urlGet, 'youporn.com') > 0 || strpos($urlGet, 'tnaflix.com') > 0 || strpos($urlGet, 'javbuz.com') > 0 || strpos($urlGet, 'letfap.com') > 0 ) ? 1 : 0;
	
	if($isXvideo == 1){
		require "simplehtmldom/simple_html_dom.php";
		$chGet = curl_init($urlGet);	
		curl_setopt($chGet, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($chGet, CURLOPT_FOLLOWLOCATION, TRUE);
		if(strpos($urlGet, 'xvideos') > 0){
			curl_setopt($chGet, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3');    
		}
		curl_setopt($chGet, CURLOPT_AUTOREFERER, TRUE);
		$resultGet = curl_exec($chGet);    

		curl_close($chGet);    

		 // Create a DOM object
		$htmlGet = new simple_html_dom();
		// Load HTML from a string
		$htmlGet->load($resultGet);	
		
		if(strpos($urlGet, 'xvideos') > 0){			
			$aGet  = $htmlGet->find('script',7)->innertext;
			
			$tmp1 = explode("setVideoUrlHigh('", $resultGet);
			
			if(isset($tmp1[1])){
				$tmp2 = explode("');", $tmp1[1]);         
			}else{
				
				$tmp1 = explode("setVideoUrlLow('", $resultGet);     
				
				$tmp2 = explode("');", $tmp1[1]);         
			}      
			$urlVIDEO = $tmp2[0];
			
		}elseif(strpos($urlGet, 'hihi.com') > 0 ){
			if($htmlGet->find('#player source', 0)){
				$urlVIDEO = $htmlGet->find('#player source', 0)->src;				
			}
			if($htmlGet->find('#player-openload', 0)){
				$urlVIDEO = $htmlGet->find('#player-openload', 0)->src;	
				$is_hihi = 1;
				$isXvideo = 0;
			}
		}elseif(strpos($urlGet, 'javbuz.com') > 0 ){
			$urlVIDEO = $htmlGet->find('source[data-res]', 0)->src;
			
		}elseif(strpos($urlGet, 'redtube.com') > 0){
			$urlVIDEO = $htmlGet->find('source', 0)->src;
		}elseif(strpos($urlGet, 'youporn.com') > 0){
			$urlVIDEO = $htmlGet->find('.downloadOption', 0)->find('a', 0)->href;
		}else{
			$htmlGet = file_get_html($urlGet);
			$urlVIDEO = "http:".$htmlGet->find('a.vaDown', 0)->href;
		}		
	}
}

//var_dump('<pre>', $arrDetailH,'</pre>');die;
?>
<style>
#closeAds img{ cursor : pointer }
@media only screen and (max-width: 768px) {
		#closeAds{
			right:0;
		}
}
@media only screen and (min-width: 1000px) {
		#closeAds{
			right:143px;
		}
}
</style>
<div id="main"><div class="wrap cf">
	
	<div class="entry-header cf">
	<div class="inner cf">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	
		<?php dp_post_actions($post->ID); ?>
	</div><!-- end .entry-header>.inner -->
	</div><!-- end .entry-header -->
	
	<div id="content" role="main">
		<?php while (have_posts()) : the_post(); global $post;?>
		
		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		
		<?php 		
		function getUserIP(){
			$client  = @$_SERVER['HTTP_CLIENT_IP'];	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR']; $remote  = $_SERVER['REMOTE_ADDR'];
			if(filter_var($client, FILTER_VALIDATE_IP)){ $ip = $client;	}
			elseif(filter_var($forward, FILTER_VALIDATE_IP)){ $ip = $forward;}
			else{ $ip = $remote;}
			return $ip;
		}
		$user_ip = getUserIP();
		$checkLocation = json_decode(file_get_contents('http://freegeoip.net/json/'.$user_ip));		
		$isVN = $checkLocation->country_code == 'VN' ? true : false;	
		?>
		<?php if($isXvideo == 1){ ?>
		<div id="videos">
			<div class="hero-unit" style="position:relative"> 
				<video id='videoPlayer' style="position:relative" preload='metadata' controls poster="<?php echo $arrDetailH['dp_video_poster'][0];?>" style="border: 1px solid; background: black;">
	                <source id="mp4Source" src="<?php echo $urlVIDEO;?>" type="video/mp4">               
	            </video>				
        	</div>
		</div><!-- end #video-->
		<?php } ?>
		<?php if($is_hihi == 1){ 

		?>
		<div id="videos" style="height:400px">
			
				<iframe width="100%" height="100%" src="<?php echo $urlVIDEO;?>" frameborder="0" allowfullscreen></iframe>
			
		</div>
		<?php } ?>
		<?php if( $isXvideo == 0 && $is_hihi ==  0){?>
		<div id="video">
			<div class="screen fluid-width-video-wrapper">
				<?php dp_video($post->ID, get_option('dp_single_video_autoplay')); ?>
			</div><!-- end .screen -->
		</div><!-- end #video-->
		<?php } ?>
		
		 chèn code phim 18+
		
		<div id="details" class="section-box">
			<div class="section-content">
			
			<?php if(function_exists('rdfa_breadcrumb')){ rdfa_breadcrumb(); } ?>
			<div id="info"<?php if(!empty($info_toggle)) echo ' class="" data-less-height="'.$info_toggle.'"'; ?>>
				<p class="entry-meta">
					
					<span class="time"><?php the_date(); ?></span>
					
					<?php edit_post_link(__('Edit', 'dp'), ' <span class="sep">/</span> '); ?>
				</p>

				<div class="entry-content rich-content">
					<?php the_content(); ?>
					<?php wp_link_pages(array('before' => '<p class="entry-nav pag-nav"><span>'.__('Pages:', 'dp').'</span> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				</div><!-- end .entry-content -->
			
				<div id="extras">
					<h4><?php _e('Category:', 'dp'); ?></h4> <?php the_category(', '); ?>
					<?php the_tags('<h4>'.__('Tags:', 'dp').'</h4>', ', ', ''); ?>
				</div>
			</div><!-- end #info -->
			</div><!-- end .section-content -->
			
			<?php if($info_toggle > 0) { ?>
			<div class="info-toggle">
				<a href="#" class="info-toggle-button info-more-button">
					<span class="more-text"><?php _e('Show more', 'dp'); ?></span> 
					<span class="less-text"><?php _e('Show less', 'dp'); ?></span>
				</a>
			</div>
			<?php } ?>
		</div><!--end #deatils-->
		
		</div><!-- end #post-<?php the_ID(); ?> -->
		
		<?php 
			dp_related_posts(array(
				'number'=>get_option('dp_related_posts'), 
				'view'=>get_option('dp_related_posts_view', 'grid-mini')
			)); 
		?>

        <?php comments_template('', true); ?>

		<?php endwhile; ?>
	</div><!-- end #content -->

	<?php get_sidebar(); ?>

</div></div><!-- end #main -->
<style type="text/css">
.hero-unit {
    margin: 0 auto 0 auto;
}

.hero-unit video {
    width: 100%;
}
</style>
<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#btnDown').click(function(){
		jQuery('#downloadForm').submit();
	});
	jQuery('#closeAds').click(function(){
		jQuery('#phong-ads, #closeAds').remove();		
	});
	jQuery('#videoPlayer').bind('play', function (e) {		
    jQuery('#phong-ads, #closeAds').remove();
});
		var video = jQuery("#videoPlayer");
		var windowObj = jQuery(window);

		function onResizeWindow() {
			resizeVideo(video[0]);
		}

		function onLoadMetaData(e) {
			resizeVideo(e.target);
		}

		function resizeVideo(videoObject) {
			var percentWidth = videoObject.clientWidth * 100 / videoObject.videoWidth;
			var videoHeight = videoObject.videoHeight * percentWidth / 100;
			video.height(videoHeight);
		}

		video.on("loadedmetadata", onLoadMetaData);
		windowObj.resize(onResizeWindow);
	}
);

</script>
<?php get_footer(); ?>

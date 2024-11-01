<?php
class wpmugs {
	
	// Fire off our menu
	function page_settings_init() {
		add_menu_page( __('WPMU Google Sitemap', 'wpmugs'), __('Google Sitemap', 'wpmugs'), 5, 'wpmugs-settings', array('wpmugs','admin_page_settings') );
	}
	
	// Display the content of our administration page
	function admin_page_settings() {
		if ( isset($_POST['posts'] ) ) { wpmugs::store('posts', $_POST['posts']); }
		if ( isset($_POST['pages'] ) ) { wpmugs::store('pages', $_POST['pages']); }
		if ( isset($_POST['blogidx'] ) ) { wpmugs::store('blogidx', $_POST['blogidx']); }
		if ( isset($_POST['ch_posts'] ) ) { wpmugs::store('ch_posts', $_POST['ch_posts']); }
		if ( isset($_POST['ch_pages'] ) ) { wpmugs::store('ch_pages', $_POST['ch_pages']); }
		if ( isset($_POST['ch_blogidx'] ) ) { wpmugs::store('ch_blogidx', $_POST['ch_blogidx']); }
		
		if ( isset($_POST['urlname'] ) ) { wpmugs::store('urlname', $_POST['urlname']); }
		
		include( WPMUGS_BASE . 'wpmu-gs.tpl.php' );
	}
	
	// Return select with changefreq
	function getchangefreq($selected='') {
		$data = array('never','yearly','monthly','weekly','daily','hourly','always');
		foreach ( $data as $k ) {
			$html .= '<option value="' . $k . '"';
			if ( $k == $selected ) $html .= ' SELECTED';
			$html .= '>' . ucfirst($k) . '</option>';
		}
		return $html;
	}
	
	
	// Store data
	function store($key,$value) { return update_option('wpmugs_' . $key, $value); }
	
	// Get stored data
	function get($key) { return get_option('wpmugs_' . $key); }
	
	/**
	 * Adds a XML entry to the XML
	 * 
	 * @param array $xml The variable containing the XML
	 * @param varchar $location The URL to the post or page
	 * @param varchar $lastmod Date and time of the last modification made
	 * @param varchar $changefreq How often the URL content changes
	 * @param varchar $priority The priority of the page
	 */
	function wpmu_gs_addEntry(&$xml,$location,$lastmod,$changefreq='weekly',$priority='0.5') {
		$xml[] = '<url>';
		$xml[] = '<loc>' . $location . '</loc>';
		$xml[] = '<lastmod>' . date("c", strtotime($lastmod)) . '</lastmod>';
		$xml[] = '<changefreq>' . $changefreq . '</changefreq>';
		$xml[] = '<priority>' . $priority . '</priority>';
		$xml[] = '</url>';
	}
	
	function showsitemap() {
		
		// Make sure some variables are global
		global $wpdb, $wp_rewrite;
	
		// Initiate WP-Rewrite
		$wp_rewrite = new wp_rewrite();
		$wp_rewrite->init();
		
		// Setup the beginning of the XML
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	
		// Get all blogs in the system
		$blogs = $wpdb->get_results("SELECT * FROM `" . $wpdb->blogs . "` WHERE `public` = '1' AND `archived` = '0' AND `spam` = '0' AND `deleted` = '0'");

		// Loop through the blogs
		if ( is_array($blogs) ) {
			foreach ( $blogs as $blog ) {
				
				// Add the base address to the blog and set is as more prioritized
				self::wpmu_gs_addEntry($xml,'http://' . $blog->domain . $blog->path ,$blog->last_updated,self::get('ch_blogidx'),self::get('blogidx'));
				
				// Switch to the blog
				switch_to_blog($blog->blog_id);
		
				// Get all posts for that blog that's published
				$posts = $wpdb->get_results("SELECT `ID`, `post_modified`, `post_type` FROM `" . $wpdb->posts . "` WHERE `post_status` = 'publish'");
				foreach ( $posts as $post ) {
					$url = post_permalink( $post->ID );
					// If it's a page, set it to weekly, otherwise to daily
					if ( $post->post_type == 'page' ) {
						$change = self::get('ch_pages');
						$prio = self::get('pages');
					} else {
						$change = self::get('ch_posts');
						$prio = self::get('posts');
					}
					if ( $url != '' ) self::wpmu_gs_addEntry($xml, $url ,$post->post_modified,$change,$prio);
				}
		
				// Jump back to the original blog
				restore_current_blog();
				
			}
		}
		
		// End the XML
		$xml[] = '</urlset>';
	
		// Output as XML
		header("Content-type: text/xml");
		echo implode("\n", $xml);
		
		// Time to quit
		exit();

	}
	
	// Handle URL's
	function custom_sub_page() {
		if ( is_404() ) {
			$siteurl = explode('?',$_SERVER['REQUEST_URI']);
			$siteurl = $siteurl[0];
			if ( substr($siteurl,-1) == '/' ) $siteurl = substr($siteurl,0,(strlen($siteurl)-1));
			$siteurl = explode('/',$siteurl);
			if ( $siteurl[count($siteurl)] == '' ) unset( $siteurl[(count($siteurl))]);
			if ( end($siteurl) == wpmugs::get('urlname') ) {
				header("HTTP/1.1 200 OK");
				self::showsitemap();
				exit();
			}
		}
	}
	
}
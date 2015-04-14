<?php
if (defined('EXTRA_OLD_BROWSER_RESTRICTIONS') && EXTRA_OLD_BROWSER_RESTRICTIONS) {
	// CSS & JS
	function extra_oldbrowser_enqueue_styles() {
		// CSS
		wp_enqueue_style( 'extra-oldbrowser', EXTRA_MODULES_URI . '/oldbrowser/front/css/oldbrowser.less', array('fancybox'), false, 'all' );

		// JS
		wp_enqueue_script('jquery-browser', EXTRA_MODULES_URI.'/oldbrowser/front/js/jquery.browser.min.js', array('jquery'), false, true);
		wp_enqueue_script('extra-oldbrowser', EXTRA_MODULES_URI.'/oldbrowser/front/js/oldbrowser.js', array('jquery-browser', 'fancybox'), false, true);

		$restrictions = apply_filters('extra_oldbrowser_restrictions', array(
			array('msie', 7),
			array('mozilla', 4),
			array('safari', 4),
			array('chrome', 30),
			array('opera', 12),
			array('netscape', 'any')
		));

		wp_localize_script('extra-oldbrowser', 'extra_oldbrowser_restrictions', $restrictions);
	}
	add_action('wp_enqueue_scripts', 'extra_oldbrowser_enqueue_styles');
	function extra_oldbrowser_enqueue_styles_login() {
		// FANCYBOX
		wp_enqueue_style( 'fancybox', EXTRA_URI . '/assets/css/jquery.fancybox.css', array(), false, 'all' );
		wp_enqueue_script('fancybox', EXTRA_URI . '/assets/js/lib/jquery.fancybox.js', array('jquery'), null, true);

		extra_oldbrowser_enqueue_styles();
	}
	add_action('login_init', 'extra_oldbrowser_enqueue_styles_login');



	// HTML
	function extra_oldbrowser_head(){ ?>

		<div style="display:none;">
		<div id="oldBrowser" class="oldBrowser-<?php bloginfo("language"); ?>">
			<h3 class="title"><?php _e("Mettez à jour votre navigateur pour consulter ce site", "extra"); ?></h3>
			<ul class="browsers">
				<li class="firefox"><a href="http://www.firefox.com" target="_blank"><span>Mozilla Firefox</span></a></li>
				<li class="ie"><a href="http://windows.microsoft.com/ie" target="_blank"><span>Internet Explorer</span></a></li>
				<li class="chrome"><a href="http://www.google.com/chrome" target="_blank"><span>Google Chrome</span></a></li>
				<li class="safari"><a href="http://www.apple.com/safari/" target="_blank"><span>Safari</span></a></li>
				<li class="oldBrowserCleaner"></li>
			</ul>
		</div>
		</div>

	<?php }
	add_action('login_footer', 'extra_oldbrowser_head');
	add_action('wp_footer', 'extra_oldbrowser_head');
}


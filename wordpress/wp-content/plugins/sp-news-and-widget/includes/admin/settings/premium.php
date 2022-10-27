<?php
/**
 * Plugin Premium Offer Page
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">

	<style>
		.wpos-new-feature{ font-size: 10px; color: #fff; font-weight: bold; background-color: #03aa29; padding:1px 4px; font-style: normal; }
		.wpos-plugin-pricing-table thead th h2{font-weight: 400; font-size: 2.4em; line-height:normal; margin:0px; color: #2ECC71;}

		table.wpos-plugin-pricing-table{width:100%; text-align: left; border-spacing: 0; border-collapse: collapse; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
		.wpos-plugin-pricing-table th, .wpos-plugin-pricing-table td{font-size:14px; line-height:normal; color:#444; vertical-align:middle; padding:12px;}

		.wpos-plugin-pricing-table colgroup:nth-child(1) { width: 31%; border: 0 none; }
		.wpos-plugin-pricing-table colgroup:nth-child(2) { width: 22%; border: 1px solid #ccc; }
		.wpos-plugin-pricing-table colgroup:nth-child(3) { width: 25%; border: 10px solid #2ECC71; }

		/* Tablehead */
		.wpos-plugin-pricing-table thead th {background-color: #fff; background:linear-gradient(to bottom, #ffffff 0%, #ffffff 100%); text-align: center; position: relative; border-bottom: 1px solid #ccc; padding: 1em 0 1em; font-weight:400; color:#999;}
		.wpos-plugin-pricing-table thead th:nth-child(1) {background: transparent;}
		.wpos-plugin-pricing-table thead th:nth-child(3) p{color:#000;}

		/* Tablebody */
		.wpos-plugin-pricing-table tbody th{background: #fff; border-left: 1px solid #ccc; font-weight: 600;}
		.wpos-plugin-pricing-table tbody th span{font-weight: normal; font-size: 87.5%; color: #999; display: block;}

		.wpos-plugin-pricing-table tbody td{background: #fff; text-align: center;}
		.wpos-plugin-pricing-table tbody td .dashicons{height: auto; width: auto; font-size:30px;}
		.wpos-plugin-pricing-table tbody td .dashicons-no-alt{color: #ff2700;}
		.wpos-plugin-pricing-table tbody td .dashicons-yes{color: #2ECC71;}

		.wpos-plugin-pricing-table tbody tr:nth-child(even) th,
		.wpos-plugin-pricing-table tbody tr:nth-child(even) td { background: #f5f5f5; border: 1px solid #ccc; border-width: 1px 0 1px 1px; }
		.wpos-plugin-pricing-table tbody tr:last-child td {border-bottom: 0 none;}

		/* Table Footer */
		.wpos-plugin-pricing-table tfoot th, .wpos-plugin-pricing-table tfoot td{text-align: center; border-top: 1px solid #ccc;}
		.wpos-plugin-pricing-table tfoot a, .wpos-plugin-pricing-table thead a{font-weight: 600; color: #fff; text-decoration: none; text-transform: uppercase; display: inline-block; padding: 1em 2em; background: #ff2700; border-radius: .2em;}

		.wpos-epb{color:#ff2700 !important;}
		.h-blue{color:#0055fb ;}
		.wpos-deal-heading{padding:0px 10px;}
	</style>

	<br/>
	<h3 style="text-align:center"><?php esc_html_e( 'Compare "WP News and Scrolling Widgets" Free VS Pro', 'sp-news-and-widget' ); ?></h3>
	<table class="wpos-plugin-pricing-table">
		<colgroup></colgroup>
		<colgroup></colgroup>
		<colgroup></colgroup>
		<thead>
			<tr>
				<th></th>
				<th>
					<h2><?php esc_html_e('Free', 'sp-news-and-widget'); ?></h2>
				</th>
				<th>
					<h2 class="wpos-epb"><?php esc_html_e('Premium', 'sp-news-and-widget'); ?></h2>
					<h3 class="wpos-deal-heading"><?php esc_html_e('Choose best pricing in', 'sp-news-and-widget'); ?> <span class="h-blue"><?php esc_html_e('Annual', 'sp-news-and-widget'); ?></span> or <span class="h-blue"><?php esc_html_e('Lifetime', 'sp-news-and-widget'); ?></span> <?php esc_html_e('deal', 'sp-news-and-widget'); ?></h3>
					<a href="<?php echo WPNW_PLUGIN_LINK_UPGRADE; ?>" target="_blank"><?php esc_html_e('Buy Now', 'sp-news-and-widget'); ?></a>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th></th>
				<td></td>
				<td>
				<h3 class="wpos-deal-heading"><?php esc_html_e('Choose best pricing in', 'sp-news-and-widget'); ?> <span class="h-blue"><?php esc_html_e('Annual', 'sp-news-and-widget'); ?></span> or <span class="h-blue"><?php esc_html_e('Lifetime', 'sp-news-and-widget'); ?></span> <?php esc_html_e('deal', 'sp-news-and-widget'); ?></h3>
				<a href="<?php echo WPNW_PLUGIN_LINK_UPGRADE; ?>" target="_blank"><?php esc_html_e('Buy Now', 'sp-news-and-widget'); ?></a></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
			<th><?php esc_html_e('Designs', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Designs that make your website better', 'sp-news-and-widget'); ?></span></th>
			<td>2</td>
			<td>120+</td>
			</tr>
			<tr>
				<th><?php esc_html_e('Shortcodes', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Shortcode provide output to the front-end side', 'sp-news-and-widget'); ?></span></th>
				<td><?php esc_html_e('1 (Grid, List)', 'sp-news-and-widget'); ?></td>
				<td><?php esc_html_e('6 (Grid, Slider, Carousel, List, Gridbox, GridBox Slider, News Ticker)', 'sp-news-and-widget'); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Shortcode Parameters', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Add extra power to the shortcode', 'sp-news-and-widget'); ?></span></th>
				<td>9</td>
				<td>30+</td>
			</tr>
			<tr>
				<th><?php esc_html_e('Shortcode Generator', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Play with all shortcode parameters with preview panel. No documentation required!!', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('WP Templating Features', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('You can modify plugin html/designs in your current theme.', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Widgets', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('WordPress Widgets to your sidebars.', 'sp-news-and-widget'); ?></span></th>
				<td>2</td>
				<td>7</td>
			</tr>
			<tr>
			<th><?php esc_html_e('Drag & Drop Post Order Change', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Arrange your desired post with your desired order and display', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Gutenberg Block Supports', 'sp-news-and-widget'); ?><span><?php esc_html_e('Use this plugin with Gutenberg easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Elementor Page Builder Support', 'sp-news-and-widget'); ?> <em class="wpos-new-feature">New</em><span><?php esc_html_e('Use this plugin with Elementor easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Beaver Builder Support', 'sp-news-and-widget'); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e('Use this plugin with Beaver Builder easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('SiteOrigin Page Builder Support', 'sp-news-and-widget'); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e('Use this plugin with SiteOrigin easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Divi Page Builder Native Support', 'sp-news-and-widget'); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e('Use this plugin with Divi Builder easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Fusion (Avada) Page Builder Native Support', 'sp-news-and-widget'); ?> <em class="wpos-new-feature">New</em><span><?php esc_html_e('Use this plugin with Fusion Builder easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('WPBakery Page Builder Support', 'sp-news-and-widget'); ?><span><?php esc_html_e('Use this plugin with Visual Composer easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Custom Read More link for Post', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Redirect post to third party destination if any', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Publicize', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Support with Jetpack to publish your News post on', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Display Desired Post', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Display only the post you want', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Display Post for Particular Categories', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Display only the posts with particular category', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Exclude Some Posts', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Do not display the posts you want', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Exclude Some Categories', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Do not display the posts for particular categories', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Post Order / Order By Parameters', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Display post according to date, title and etc', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Multiple Slider Parameters', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Slider parameters like autoplay, number of slide, sider dots and etc.', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Slider RTL Support', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Slider supports for RTL website', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Automatic Update', 'sp-news-and-widget'); ?><span><?php esc_html_e('Get automatic plugin updates', 'sp-news-and-widget'); ?></span></th>
				<td><?php esc_html_e('Lifetime', 'sp-news-and-widget'); ?></td>
				<td><?php esc_html_e('Lifetime', 'sp-news-and-widget'); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Support', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Get support for plugin', 'sp-news-and-widget'); ?></span></th>
				<td><?php esc_html_e('Limited', 'sp-news-and-widget'); ?></td>
				<td><?php esc_html_e('1 Year', 'sp-news-and-widget'); ?></td>
			</tr>
		</tbody>
	</table>			
</div>
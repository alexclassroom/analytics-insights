<?php
/**
 * Author: Alin Marcu
 * Author URI: https://deconf.com
 * Copyright 2013 Alin Marcu
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit();
if ( ! class_exists( 'AIWP_Frontend_Setup' ) ) {

	final class AIWP_Frontend_Setup {

		private $aiwp;

		public function __construct() {
			$this->aiwp = AIWP();
			// Styles & Scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'load_styles_scripts' ) );
		}

		/**
		 * Styles & Scripts conditional loading
		 *
		 * @param
		 *            $hook
		 */
		public function load_styles_scripts() {
			if ( AIWP_Tools::is_amp() ){
				return;
			}
			$lang = get_bloginfo( 'language' );
			$lang = explode( '-', $lang );
			$lang = $lang[0];
			/*
			 * Item reports Styles & Scripts
			 */
			if ( AIWP_Tools::check_roles( $this->aiwp->config->options['access_front'] ) && $this->aiwp->config->options['frontend_item_reports'] ) {
				wp_enqueue_style( 'aiwp-nprogress', AIWP_URL . 'common/nprogress/nprogress' . AIWP_Tools::script_debug_suffix() . '.css', null, AIWP_CURRENT_VERSION );
				wp_enqueue_style( 'aiwp-frontend-item-reports', AIWP_URL . 'front/css/item-reports' . AIWP_Tools::script_debug_suffix() . '.css', null, AIWP_CURRENT_VERSION );
				$country_codes = AIWP_Tools::get_countrycodes();
				$country_codes = array_flip($country_codes);
				if ( 'None' !== $this->aiwp->config->options['ga_target_geomap'] && isset( $country_codes[$this->aiwp->config->options['ga_target_geomap']] ) ) {
					$region = sanitize_text_field($country_codes[$this->aiwp->config->options['ga_target_geomap']]);
				} else {
					$region = false;
				}
				wp_enqueue_style( "wp-jquery-ui-dialog" );
				wp_register_script( 'googlecharts', 'https://www.gstatic.com/charts/loader.js', array(), null );
				wp_enqueue_script( 'aiwp-nprogress', AIWP_URL . 'common/nprogress/nprogress' . AIWP_Tools::script_debug_suffix() . '.js', array( 'jquery' ), AIWP_CURRENT_VERSION );
				wp_enqueue_script( 'aiwp-frontend-item-reports', AIWP_URL . 'common/js/reports6' . AIWP_Tools::script_debug_suffix() . '.js', array( 'aiwp-nprogress', 'googlecharts', 'jquery', 'jquery-ui-dialog' ), AIWP_CURRENT_VERSION, true );
				/* @formatter:off */
				wp_localize_script( 'aiwp-frontend-item-reports', 'aiwpItemData', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'security' => wp_create_nonce( 'aiwp_frontend_item_reports' ),
					'dateList' => array(
						'today' => __( "Today", 'analytics-insights' ),
						'yesterday' => __( "Yesterday", 'analytics-insights' ),
						'7daysAgo' => sprintf( __( "Last %d Days", 'analytics-insights' ), 7 ),
						'14daysAgo' => sprintf( __( "Last %d Days", 'analytics-insights' ), 14 ),
						'30daysAgo' =>  sprintf( __( "Last %d Days", 'analytics-insights' ), 30 ),
						'90daysAgo' =>  sprintf( __( "Last %d Days", 'analytics-insights' ), 90 ),
						'365daysAgo' =>  sprintf( _n( "%s Year", "%s Years", 1, 'analytics-insights' ), __('One', 'analytics-insights') ),
						'1095daysAgo' =>  sprintf( _n( "%s Year", "%s Years", 3, 'analytics-insights' ), __('Three', 'analytics-insights') ),
					),
					'reportList' => array(
						'uniquePageviews' => __( "Sessions", 'analytics-insights' ),
						'users' => __( "Users", 'analytics-insights' ),
						'organicSearches' => __( "Engagement", 'analytics-insights' ),
						'pageviews' => __( "Page Views", 'analytics-insights' ),
						'visitBounceRate' => __( "Bounce Rate", 'analytics-insights' ),
						'locations' => __( "Location", 'analytics-insights' ),
						'referrers' => __( "Referrers", 'analytics-insights' ),
						'searches' => __( "Searches", 'analytics-insights' ),
						'trafficdetails' => __( "Traffic", 'analytics-insights' ),
						'technologydetails' => __( "Technology", 'analytics-insights' ),
					),
					'i18n' => array(
							__( "A JavaScript Error is blocking plugin resources!", 'analytics-insights' ), //0
							__( "Traffic Mediums", 'analytics-insights' ),
							__( "Visitor Type", 'analytics-insights' ),
							__( "Search Engines", 'analytics-insights' ),
					 	__( "Language", 'analytics-insights' ),
					 	__( "Sessions", 'analytics-insights' ),
							__( "Users", 'analytics-insights' ),
							__( "Page Views", 'analytics-insights' ),
							__( "Bounce Rate", 'analytics-insights' ),
						 __( "Session Duration", 'analytics-insights' ),
							__( "Pages/Session", 'analytics-insights' ),
							__( "Invalid response", 'analytics-insights' ),
							__( "Error:", 'analytics-insights' ),
							__( "Processing data, please check again in a few hours", 'analytics-insights' ),
							__( "report generated by", 'analytics-insights' ), //14
							__( "This plugin needs an authorization:", 'analytics-insights' ) . ' <strong>' . __( "authorize the plugin", 'analytics-insights' ) . '</strong>!',
							__( "Browser", 'analytics-insights' ), //16
							__( "Operating System", 'analytics-insights' ),
							__( "Screen Resolution", 'analytics-insights' ),
							__( "Mobile Brand", 'analytics-insights' ),
							__( "Future Use", 'analytics-insights' ),
							__( "Future Use", 'analytics-insights' ),
							__( "Future Use", 'analytics-insights' ),
							__( "Future Use", 'analytics-insights' ),
							__( "Future Use", 'analytics-insights' ),
							__( "Future Use", 'analytics-insights' ), //25
						 __( "Organic Search", 'analytics-insights' ),
						 __( "Engaged Sessions", 'analytics-insights' ),
						 __( "Total Engagement", 'analytics-insights' ),
							__( "Precision: ", 'analytics-insights' ), //29
						 __( "Search ...", 'analytics-insights' ),
					),
					'colorVariations' => AIWP_Tools::variations( sanitize_text_field($this->aiwp->config->options['theme_color'] ) ),
					'region' => $region,
					'mapsApiKey' => apply_filters( 'aiwp_maps_api_key', sanitize_text_field( $this->aiwp->config->options['maps_api_key'] ) ),
					'language' => get_bloginfo( 'language' ),
					'filter' => esc_url( $_SERVER["REQUEST_URI"] ),
					'viewList' => false,
					'scope' => 'front-item',
				 )
				);
				/* @formatter:on */
			}
		}
	}
}

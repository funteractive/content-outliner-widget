<?php
/*
Plugin Name: Content Outiline Widget
Author: Keisuke Imura
Plugin URI: http://funteractive.jp/
Description: This plugin can use widget that display content html outline.
Version: 0.1.0
Author URI: http://funteractive.jp/
Text Domain: content-outline-widget
 */

function load_content_outline_widget() {
	register_widget( 'ContentOutlineWidget' );
}
add_action( 'widgets_init', 'load_content_outline_widget' );

class ContentOutlineWidget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'content-outline-widget', // Base ID
			'コンテンツテキストウィジェット', // Name
			array( 'description' => '記事の見出しを抽出して目次を表示', ) // Args
		);

		add_action( 'wp_enqueue_scripts', array( &$this, 'print_script' ) );
		add_action( 'wp_footer', array( &$this, 'print_trigger_script' ) );
		add_action( 'widgets_init', array( &$this, 'load_content_outline_widget' ) );
	}

	/**
	 * ライブラリを読み込む
	 */
	public function print_script() {
		if( !is_admin() ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'content-outline', plugins_url( '/content-outline.js' , __FILE__ ) );
		}
	}

	/**
	 * jQueryの処理を実行する
	 */
	public function print_trigger_script() {
		$script = <<< EOL
<script type="text/javascript">
(function($){
	$('#contentOutlineWidget').contentOutline();
})(jQuery);
</script>
EOL;
		echo $script;
	}

	/**
	 * 表の画面の表示
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$contents_area_id = apply_filters( 'widget_title', $instance['contents_area_id'] );

		echo $args['before_widget'];
		if ( ! empty( $contents_area_id ) )
			echo $args['before_title'] . $contents_area_id . $args['after_title'];

		$html = '<aside id="contentOutlineWidget">' . PHP_EOL
			. '</aside>' . PHP_EOL;
		echo $html;

		echo $args['after_widget'];
	}

	/**
	 * 管理画面の入力フォーム
	 *
	 * @param array $instance
	 * @return string|void
	 */
	public function form( $instance ) {
		foreach( $instance as $key ) {
			if ( isset( $instance[ $key ] ) ) {
				${$key} = $instance[ $key ];
			}
			else {
				$key = 'New title';
			}
		}

		$html = '<p>' . PHP_EOL
			. '<label for="' . $this->get_field_id( 'contents_area_id' ) . '">' . 'コンテンツエリアid' . '</label>' . PHP_EOL
			. '<input class="widefat" id="' . $this->get_field_id( 'contents_area_id' ) . '" name="' . $this->get_field_name( 'contents_area_id' ) . '" type="text" value="' . esc_attr( $contents_area_id ) . '" />' . PHP_EOL
			. '</p>' . PHP_EOL
			. '<p>' . PHP_EOL
			. '<label for="' . $this->get_field_id( 'contents_area_class' ) . '">' . 'コンテンツエリアclass' . '</label>' . PHP_EOL
			. '<input class="widefat" id="' . $this->get_field_id( 'contents_area_class' ) . '" name="' . $this->get_field_name( 'contents_area_class' ) . '" type="text" value="' . esc_attr( $contents_area_class ) . '" />' . PHP_EOL
			. '</p>' . PHP_EOL;

		echo $html;
	}

	/**
	 * ウィジェットを更新した際の処理
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['contents_area_id'] = ( ! empty( $new_instance['contents_area_id'] ) ) ? strip_tags( $new_instance['contents_area_id'] ) : '';
		$instance['contents_area_class'] = ( ! empty( $new_instance['contents_area_class'] ) ) ? strip_tags( $new_instance['contents_area_class'] ) : '';

		return $instance;
	}

}

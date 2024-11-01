<?php
/**
 * SynergyPress WordPress plugin.
 *
 * @link    https://formsynergy.com/synergypress-wordpress-plugin/
 * @version 1.6.0
 * @since   1.0
 * @package synergy-press
 **/

namespace SynergyPress;

/**
 * \SynergyPress\WP_Plugin()
 *
 * This class contains tools and methods used by the Synergy Press plugin.
 */
class WP_Plugin {

	/**
	 * Renders form inputs.
	 *
	 * @param array $params Parameters.
	 * @param mixed $value  Mixed.
	 */
	public function render( $params, $value = '' ) {
		$html = '';
		if ( isset( $params['prepend'] ) ) :
			$html .= $params['prepend'];
		endif;
		switch ( $params['input']['type'] ) :
			case 'text':
			case 'email':
			case 'tel':
			case 'search':
			case 'url':
			case 'number':
				$html .= isset( $params['input']['before'] )
					? wp_filter_post_kses( $params['input']['before'] )
					: '';

				$html .= ' <label 
                                class="' . ( ! isset( $params['input']['show-label'] )
										|| ! $params['input']['show-label']
										? 'screen-reader-text'
										: '' ) . '" 
                                for="' . sanitize_html_class( $params['input']['id'] ) . '">
                                    ' . sanitize_text_field( $params['input']['label'] ) . '
                            </label>';

				$html .= isset( $params['input']['separator'] )
					? wp_filter_post_kses( $params['input']['separator'] )
					: '';

				$html .= '
                    <input 
                        ' . ( isset( $params['input']['readonly'] ) && $params['input']['readonly'] ? 'readonly' : '' ) . '
                        type="' . sanitize_html_class( $params['input']['type'] ) . '" 
                        name="' . $params['input']['name'] . '" 
                        value="' . sanitize_text_field( $value ) . '" 
                        id="' . sanitize_html_class( $params['input']['id'] ) . '" 
                        placeholder="' . ( isset( $params['input']['placeholder'] )
							? sanitize_text_field( $params['input']['placeholder'] )
							: sanitize_html_class( $params['input']['label'] ) ) . '" 
                            ' . ( isset( $params['input']['status'] ) ? sanitize_html_class( $params['input']['status'] ) : '' ) . '
                        class="fs-input widefat">';

				$html .= isset( $params['input']['description'] )
					? '<p class="description">' . wp_filter_post_kses( $params['input']['description'] ) . '</p>'
					: '';

				$html .= isset( $params['input']['after'] )
					? wp_filter_post_kses( $params['input']['after'] )
					: '';
				break;

			case 'domain':
					$html .= isset( $params['input']['before'] )
						? wp_filter_post_kses( $params['input']['before'] )
						: '';

					$html .= ' <label 
									class="' . ( ! isset( $params['input']['show-label'] )
											|| ! $params['input']['show-label']
											? 'screen-reader-text'
											: '' ) . '" 
									for="' . sanitize_html_class( $params['input']['id'] ) . '">
										' . sanitize_text_field( $params['input']['label'] ) . '
								</label>';

					$html .= isset( $params['input']['separator'] )
						? wp_filter_post_kses( $params['input']['separator'] )
						: '';

					$html .= '
						<input 
							' . ( isset( $params['input']['readonly'] ) && $params['input']['readonly'] ? 'readonly' : '' ) . '
							type="' . sanitize_html_class( $params['input']['type'] ) . '" 
							name="' . $params['input']['name'] . '" 
							value="' . filter_var( $value, FILTER_VALIDATE_DOMAIN ) . '" 
							id="' . sanitize_html_class( $params['input']['id'] ) . '" 
							placeholder="' . ( isset( $params['input']['placeholder'] )
								? sanitize_text_field( $params['input']['placeholder'] )
								: sanitize_text_field( $params['input']['label'] ) ) . '" 
								' . ( isset( $params['input']['status'] ) ? sanitize_html_class( $params['input']['status'] ) : '' ) . '
							class="fs-input widefat">';

					$html .= isset( $params['input']['description'] )
						? '<p class="description">' . wp_filter_post_kses( $params['input']['description'] ) . '</p>'
						: '';

					$html .= isset( $params['input']['after'] )
						? wp_filter_post_kses( $params['input']['after'] )
						: '';
				break;

			case 'textarea':
				$html .= isset( $params['input']['before'] )
					? esc_textarea( $params['input']['before'] )
					: '';

				$html .= '
                    <label 
                        class="' . ( ! isset( $params['input']['show-label'] ) || ! $params['input']['show-label']
							? 'screen-reader-text'
							: '' ) . '" 
                        for="' . sanitize_html_class( $params['input']['id'] ) . '">
                            ' . sanitize_text_field( $params['input']['label'] ) . '
                    </label>';

				$html .= isset( $params['input']['separator'] )
					? wp_filter_post_kses( $params['input']['separator'] )
					: '';
				$html .= '
                    <textarea ' . ( isset( $params['input']['readonly'] ) && $params['input']['readonly'] ? 'readonly' : '' ) . '
                        name="' . $params['input']['name'] . '" 
                        id="' . sanitize_html_class( $params['input']['id'] ) . '" 
                        placeholder="' . ( isset( $params['input']['placeholder'] )
							? sanitize_text_field( $params['input']['placeholder'] )
							: sanitize_text_field( $params['input']['label'] ) ) . '" 
                            ' . sanitize_html_class( $params['input']['status'] ) . '  
                        class="fs-textarea"
                        rows="5" 
                        cols="40">' . esc_textarea( $value ) . '</textarea>';

				$html .= isset( $params['input']['description'] )
					? '<p class="description">' . wp_filter_post_kses( $params['input']['description'] ) . '</p>'
					: '';

				$html .= isset( $params['input']['after'] )
					? wp_filter_post_kses( $params['input']['after'] )
					: '';
				break;

			case 'select':
				$html .= isset( $params['input']['before'] )
					? wp_filter_post_kses( $params['input']['before'] )
					: '';

				$html .= '
                    <label 
                        class="' . ( ! isset( $params['input']['show-label'] ) || ! $params['input']['show-label']
							? 'screen-reader-text'
							: '' ) . '" 
                        for="' . sanitize_html_class( $params['input']['id'] ) . '">
                             ' . sanitize_text_field( $params['input']['label'] ) . '
                    </label>';

				$html .= isset( $params['input']['separator'] )
					? wp_filter_post_kses( $params['input']['separator'] )
					: '';

				$data = '';

				if ( isset( $params['input']['data'] ) ) :
					foreach ( $params['input']['data'] as $data_key => $data_value ) :
						$data .= ' data-' . sanitize_html_class( $data_key ) . '-fs="' . sanitize_html_class( $data_value ) . '" ';
					endforeach;
				endif;

				$html .= '
                    <select 
                        ' . ( isset( $params['input']['readonly'] ) && $params['input']['readonly'] ? 'readonly' : '' ) . '
                        name="' . $params['input']['name'] . '" 
                        id="' . sanitize_html_class( $params['input']['id'] ) . '" 
                        ' . ( isset( $params['input']['status'] ) ? sanitize_html_class( $params['input']['status'] ) : '' ) . ' 
                        ' . $data . ' 
                        class="fs-select widefat">';

				foreach ( $params['input']['options'] as $option_value => $option_label ) :
					$html .= '
                            <option value="'
							. sanitize_key( $option_value ) . '" '
							. ( $option_value === $value ? 'selected' : '' )
							. ' ' . $data . '>'
							. sanitize_text_field( $option_label )
							. '</option>';
					endforeach;

				$html .= '</select>';
				$html .= isset( $params['input']['description'] )
					? '<p class="description">' . wp_filter_post_kses( $params['input']['description'] ) . '</p>'
					: '';

				$html .= isset( $params['input']['after'] )
					? ewp_filter_post_kses( $params['input']['after'] )
					: '';
				break;

			case 'checkbox':
			case 'radio':
				$html .= isset( $params['input']['before'] )
						? wp_filter_post_kses( $params['input']['before'] )
						: '';
				foreach ( $params['input']['options'] as $item ) {
					$html .= '<div class="' . sanitize_html_class( $params['input']['type'] ) . '-input">';
					$html .= '
                            <label for="' . sanitize_html_class( $item['id'] ) . '"';
					if ( isset( $item['data'] ) ) :
						foreach ( $item['data'] as $data_name => $data_value ) :
							$html .= ' data-' . sanitize_html_class( $data_name ) . '-fs="' . sanitize_html_class( $data_value ) . '" ';
							endforeach;
						endif;
					$html .= '>';
					$html .= '<input ' . ( isset( $params['input']['readonly'] ) && $params['input']['readonly'] ? 'readonly' : '' ) . '
                                    type="' . sanitize_html_class( $params['input']['type'] ) . '" 
                                    id="' . sanitize_html_class( $item['id'] ) . '" 
                                    name="' . $item['name'] . '" 
                                    value="' . sanitize_text_field( $item['value'] ) . '" 
                                    class="fs-' . sanitize_html_class( $params['input']['type'] ) . '"
                                    ' . ( $item['value'] === $value || $item['checked'] ? 'checked' : '' ) . '>';

					$html .= ( ! isset( $item['show-label'] ) || $item['show-label'] ? sanitize_text_field( $item['label'] ) : '' ) . '
                                </label>';

					$html .= isset( $item['description'] )
						? '<p class="description">' . wp_filter_post_kses( $item['description'] ) . '</p>'
						: '';

					$html .= '</div>';
				}
				$html .= isset( $params['input']['after'] )
					? wp_filter_post_kses( $params['input']['after'] )
					: '';
				break;
		endswitch;

		if ( isset( $params['append'] ) ) :
			$html .= wp_filter_post_kses( $params['append'] );
		endif;

		return $html;
	}

	/**
	 * Will return module including a related tag.
	 *
	 * @param array $module Module.
	 * @return array
	 */
	public function normalize_module( $module ) {
		if ( ! isset( $module['params']['etag'] ) ) :
			return $module;
		endif;
		$data = explode( ':', $module['params']['etag'] );
		if ( $data ) :
			$module['related'] = array(
				'attribute' => 'data-fs-' . str_replace( 'on', '', $data[0] ),
				'value'     => $data[1],
			);
		endif;
		if ( ! isset( $module['fstrigger'] ) || '.' === $module['fstrigger'] ) :
			$module['fstrigger'] = '.fs-trigger-' . $module['moduleid'];
		endif;
		if ( ! isset( $module['fsplacement'] ) || '.' === $module['fsplacement'] ) :
			$module['fsplacement'] = '.fs-placement-' . $module['moduleid'];
		endif;
		return $module;
	}

	/**
	 * Will retrieve the event trigger from a giver module.
	 *
	 * @param array $module module.
	 * @return string.
	 */
	public function get_event_trigger( $module ) {
		if ( ! isset( $module['params']['etag'] ) ) :
			return 'click';
		endif;
		$data = explode( ':', $module['params']['etag'] );
		if ( $data ) :
			return array(
				'data-fs-' . str_replace( 'on', '', $data[0] )  => $data[1],
			);
		endif;
	}

	/**
	 * Will convert kebob case produced by WP esc_title to camel case.
	 *
	 * @param string $word Word.
	 */
	public function camel_case( $word ) {
		return lcfirst( str_replace( ' ', '', ucwords( str_replace( '-', ' ', $word ) ) ) );
	}

	/**
	 * Returns the current protocol.
	 *
	 * @param string $url URL.
	 * @return string
	 */
	public function get_proto( $url ) {
		$parse_url = wp_parse_url( $url );
		return $parse_url['scheme'] . '://';
	}

	/**
	 * Form Synergy requires a hash in order
	 * to track the process of an interaction.
	 */
	public function token() {
		$token = get_home_url();

		// IP address.
		if ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) :
			$token .= ip2long( $_SERVER['REMOTE_ADDR'] );
		endif;

		// User-agent.
		if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) :
			$token .= wp_unslash( $_SERVER['HTTP_USER_AGENT'] );
		endif;

		if ( function_exists( 'hash' ) ) :
			return md5( hash( 'sha256', $token ) );
		else :
			return md5( sha1( $token ) );
		endif;
	}
}

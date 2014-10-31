<?php

if ( ! class_exists( 'Maera_CWA' ) ) {

	/**
	* Custom Widget Areas Class
	*/
	class Maera_CWA {

		/**
		 * Class constructor
		 */
		public function __construct() {

			$extra_widget_areas = $this->extra_widget_areas_array();

			foreach ( $extra_widget_areas as $ewa ) {
				add_action( $ewa['action'], array( $this, 'extra_widget_areas_wrapper'), $ewa['priority'], 2 );
			}

			add_action( 'widgets_init', array( $this, 'widget_areas' ), 12 );
			add_filter( 'kirki/controls', array( $this, 'customizer_controls' ) );
			add_action( 'customize_register', array( $this, 'customizer_section' ) );

		}


		/**
		 * Return an array of the extra widget area regions
		 */
		function extra_widget_areas_array() {

			$defaults = array();
			return apply_filters( 'maera/widgets/areas', $defaults );

		}


		/**
		 * Customizer section
		 */
		function customizer_section( $wp_customize ) {
			global $maera_i18n;
			$wp_customize->add_section( 'custom_widget_areas' , array(
				'title'    => $maera_i18n['customwidgetareas'],
				'priority' => 999,
			) );
		}


		/**
		 * Customizer controls for custom widget areas
		 */
		function customizer_controls( $controls ) {

			global $maera_i18n;

			$extra_widget_areas = $this->extra_widget_areas_array();

			$i = 1;

			foreach ( $extra_widget_areas as $area => $settings ) {

				$controls[] = array(
					'type'     => 'select',
					'setting'  => $area . '_widgets_nr',
					'label'    => sprintf( $maera_i18n['numberofwidgetareasin'], $settings['name'] ),
					'section'  => 'custom_widget_areas',
					'default'  => $settings['default'],
					'choices'  => apply_filters( 'maera/widgets/areas/values', array( 0, 1, 2, 3, 4, 6 ) ),
					'priority' => $i,
				);

				$i++;
			}

			return $controls;

		}


		/**
		 * Register sidebars and widgets
		 */
		function widget_areas() {

			$areas = $this->extra_widget_areas_array();

			$class        = apply_filters( 'maera/widgets/class', '' );
			$before_title = apply_filters( 'maera/widgets/title/before', '<h3 class="widget-title">' );
			$after_title  = apply_filters( 'maera/widgets/title/after', '</h3>' );

			foreach ( $areas as $area => $settings ) {

				$areas_nr = get_theme_mod( $area . '_widgets_nr', $settings['default'] );

				if ( 0 < $areas_nr ) {

					for ( $i = 0;  $i < $areas_nr;  $i++ ) {

						register_sidebar( array(
							'name'          => $settings['name'] . ' ' . $i,
							'id'            => $area . '_' . $i,
							'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
							'after_widget'  => '</section>',
							'before_title'  => $before_title,
							'after_title'   => $after_title,
						) );

					}

				}

			}

		}


		/**
		 * Extra widget areas wrapper
		 */
		function extra_widget_areas_wrapper( $area = null, $class = '' ) {

			// Do not proceed if we have not specified an area
			if ( is_null( $area ) ) {
				return;
			}

			$areas_nr = (int) get_theme_mod( $area . '_widgets_nr', 0 );

			$class = $class . ' ' . $class . '-' . $area;

			if ( 0 < $areas_nr ) : ?>

				<div class="<?php echo $class; ?>">

					<?php for ( $i = 0;  $i < $areas_nr;  $i++ ) : ?>
						<?php $this->extra_widgets_render_content( $areas_nr, $area, $i ); ?>
					<?php endfor; ?>

				</div>

			<?php endif;

		}


		function extra_widgets_render_content( $areas_nr, $area, $i ) {

			$class = apply_filters( 'maera/extra_widgets/' . $area . '/' . $i, 'column-' . ( 12 / $areas_nr ), $area, $areas_nr ); ?>

			<div class="<?php echo $area . '_' . $i . ' ' . $class; ?>">
				<?php dynamic_sidebar( $area . '_' . $i ); ?>
			</div>

			<?php

		}

	}

}

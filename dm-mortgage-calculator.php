<?php
/*
Plugin Name: DM Mortgage Calculator
Plugin URI:  dm-mortgage-calculator.php
Description: A simple Mortgage Calculator widget.
Version:     0.1
Author:      Derek Morash
Author URI:  http://derekmorash.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: dm-mortgage-calculator
*/

class dm_mortgage_widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'dm_mortgage_widget', // Base ID
            __( 'DM Mortgage Calculator', 'text_domain' ), // Name
            array(
                'classname'   => 'dm_mortgage_widget',
                'description' => __( 'A Mortgage Calculator Widget', 'text_domain' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     * @see WP_Widget::widget()
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }
        ?>
        <form>
            <p>
                <label for="price">Purchase Price:</label>
                <?php
                if ( empty( $instance['price'] ) ) {
                ?>
                    <input id="price" type='text' value="300,000" placeholder="Purchase Price" />
                <?php
                } else {
                ?>
                    <input id="price" type='text' value="<?php echo $instance['price']; ?>" placeholder="Purchase Price" />
                <?php
                }
                ?>

            </p>
        </form>
        <?php
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     * @see WP_Widget::form()
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
        $price = esc_attr( $instance['price'] );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>"
                   type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'price' ); ?>"><?php _e( 'Purchase Price:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'price' ); ?>" name="<?php echo $this->get_field_name( 'price' ); ?>"
                   type="text" value="<?php echo esc_attr( $price); ?>">
        </p>
    <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     * @see WP_Widget::update()
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['price'] = strip_tags( $new_instance['price'] );

        return $instance;
    }

} // class dm_mortgage_widget

// register dm_mortgage_widget widget
function register_dm_mortgage_widget() {
    register_widget( 'dm_mortgage_widget' );
}
add_action( 'widgets_init', 'register_dm_mortgage_widget' );
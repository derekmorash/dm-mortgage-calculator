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
        ?>
        <style>
            .dm-mortgage-calculator {
                /*background-color: aquamarine;*/
            }
        </style>
        <div class="dm-mortgage-calculator"><!-- Container for widget -->
        <?php
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }
        ?>
            <form>
                <div>
                    <label for="mortgageAmount">Mortgage Amount:</label>
                    <input id="mortgageAmount" type='text' value="<?php echo $instance['mortgageAmount']; ?>" placeholder="Mortgage Amount" />
                </div>
                <div>
                    <label for="term">Mortgage Term:</label>
                    <input id="term" type='text' value="<?php echo $instance['term']; ?>" placeholder="Mortgage Term" />
                </div>
                <div>
                    <label for="rate">Interest Rate:</label>
                    <input id="rate" type='text' value="<?php echo $instance['rate']; ?>" placeholder="Interest Rate" />
                </div>

                <div>
                    <input type="button" value="Submit"/>
                </div>
            </form>
        </div> <!-- End Widget Container -->
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
        $mortgageAmount = esc_attr( $instance['mortgageAmount'] );
        $term = esc_attr( $instance['term'] );
        $rate = esc_attr( $instance['rate'] );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>"
                   type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'mortgageAmount' ); ?>"><?php _e( 'Mortgage Amount:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'mortgageAmount' ); ?>" name="<?php echo $this->get_field_name( 'mortgageAmount' ); ?>"
                   type="text" value="<?php echo esc_attr( $mortgageAmount ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'term' ); ?>"><?php _e( 'Mortgage Term:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'term' ); ?>" name="<?php echo $this->get_field_name( 'term' ); ?>"
                   type="text" value="<?php echo esc_attr( $term ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'rate' ); ?>"><?php _e( 'Interest Rate:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'rate' ); ?>" name="<?php echo $this->get_field_name( 'rate' ); ?>"
                   type="text" value="<?php echo esc_attr( $rate ); ?>">
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
        $instance['mortgageAmount'] = strip_tags( $new_instance['mortgageAmount'] );
        $instance['term'] = strip_tags( $new_instance['term'] );
        $instance['rate'] = strip_tags( $new_instance['rate'] );

        return $instance;
    }

} // class dm_mortgage_widget

// register dm_mortgage_widget widget
function register_dm_mortgage_widget() {
    register_widget( 'dm_mortgage_widget' );
}
add_action( 'widgets_init', 'register_dm_mortgage_widget' );
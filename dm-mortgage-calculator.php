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
                background-color: aquamarine;
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
                    <label for="price">Purchase Price:</label>
                    <input id="price" type='text' value="<?php echo $instance['price']; ?>" placeholder="Purchase Price" />
                </div>
                <div>
                    <label for="downPayment">Down Payment:</label>
                    <input id="downPayment" type='text' value="<?php echo $instance['downPayment']; ?>" placeholder="Down Payment" />
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
                    <label for="propertyTax">Property Tax:</label>
                    <input id="propertyTax" type='text' value="<?php echo $instance['propertyTax']; ?>" placeholder="Property Tax" />
                </div>
                <div>
                    <label for="propertyInsurance">Property Insurance:</label>
                    <input id="propertyInsurance" type='text' value="<?php echo $instance['propertyInsurance']; ?>" placeholder="Property Insurance" />
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
        $price = esc_attr( $instance['price'] );
        $downPayment = esc_attr( $instance['downPayment'] );
        $term = esc_attr( $instance['term'] );
        $rate = esc_attr( $instance['rate'] );
        $propertyTax = esc_attr( $instance['propertyTax'] );
        $propertyInsurance = esc_attr( $instance['propertyInsurance'] );
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
        <p>
            <label for="<?php echo $this->get_field_id( 'downPayment' ); ?>"><?php _e( 'Down Payment:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'downPayment' ); ?>" name="<?php echo $this->get_field_name( 'downPayment' ); ?>"
                   type="text" value="<?php echo esc_attr( $downPayment); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'term' ); ?>"><?php _e( 'Mortgage Term:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'term' ); ?>" name="<?php echo $this->get_field_name( 'term' ); ?>"
                   type="text" value="<?php echo esc_attr( $term); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'rate' ); ?>"><?php _e( 'Interest Rate:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'rate' ); ?>" name="<?php echo $this->get_field_name( 'rate' ); ?>"
                   type="text" value="<?php echo esc_attr( $rate); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'propertyTax' ); ?>"><?php _e( 'Property Tax:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'propertyTax' ); ?>" name="<?php echo $this->get_field_name( 'propertyTax' ); ?>"
                   type="text" value="<?php echo esc_attr( $propertyTax); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'propertyInsurance' ); ?>"><?php _e( 'Property Insurance:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'propertyInsurance' ); ?>" name="<?php echo $this->get_field_name( 'propertyInsurance' ); ?>"
                   type="text" value="<?php echo esc_attr( $propertyInsurance); ?>">
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
        $instance['downPayment'] = strip_tags( $new_instance['downPayment'] );
        $instance['term'] = strip_tags( $new_instance['term'] );
        $instance['rate'] = strip_tags( $new_instance['rate'] );
        $instance['propertyTax'] = strip_tags( $new_instance['propertyTax'] );
        $instance['propertyInsurance'] = strip_tags( $new_instance['propertyInsurance'] );

        return $instance;
    }

} // class dm_mortgage_widget

// register dm_mortgage_widget widget
function register_dm_mortgage_widget() {
    register_widget( 'dm_mortgage_widget' );
}
add_action( 'widgets_init', 'register_dm_mortgage_widget' );
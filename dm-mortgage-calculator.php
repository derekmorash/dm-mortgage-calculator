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
                /*font-family: "Times New Roman";*/
            }
        </style>
        <div class="dm-widget"><!-- Container for widget -->
        <?php
        if ( ! empty( $instance['dmTitle'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['dmTitle'] ). $args['after_title'];
        }
        ?>
            <div id="dm-form-container" class="dm-form-container">
                <form class="dm-form">
                    <fieldset id="dm-amount-field">
                        <label for="dm-amount" class="dm-form-label">Mortgage Amount: ($)</label>
                        <input class="dm-form-input" id="dm-amount" type='text' value="<?php echo $instance['dmMortgageAmount']; ?>" placeholder="Mortgage Amount ($)" />
                    </fieldset>
                    <fieldset id="dm-rate-field">
                        <label for="dm-rate" class="dm-form-label">Interest Rate: (%)</label>
                        <input class="dm-form-input" id="dm-rate" type='text' value="<?php echo $instance['dmRate']; ?>" placeholder="Interest Rate (%)" />
                    </fieldset>
                    <fieldset id="dm-term-field">
                        <label for="dm-term" class="dm-form-label">Mortgage Term: (years)</label>
                    <input class="dm-form-input" id="dm-term" type='text' value="<?php echo $instance['dmTerm']; ?>" placeholder="Mortgage Term (years)" />
                    </fieldset>

                    <fieldset>
                        <input id="dm-submit" type="button" value="Submit">
                    </fieldset>
                </form>
                <!-- Validation Error Messages -->
                <p id="dm-empty" class="dm-error dm-hidden">Please fill out the highlighted boxes.</p>
                <p id="dm-amount-error" class="dm-error dm-hidden">Please enter a positive amount <br>(eg. 200000.00)</p>
                <p id="dm-rate-error" class="dm-error dm-hidden">Please enter a rate with two decimal places (eg. 2.05)</p>
                <p id="dm-term-error" class="dm-error dm-hidden">Please enter a valid number of years<br>(eg. 1-35)</p>
            </div>
            <div id="dm-chart-container" class="dm-chart-container dm-hidden">
                <p>
                    Estimated payment for your home
                </p>
                <p><span id="dm-monthly-payment" class="dm-bold"></span> per month</p>
                <p><span id="dm-overall-payment" class="dm-bold"></span> overall</p>
                <div id="dm-chart"></div>
            </div>
        </div>
        <?php
        echo $args['after_widget'];

    }

    /**
     * Back-end widget form.
     * @see WP_Widget::form()
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $dmTitle = ! empty( $instance['dmTitle'] ) ? $instance['dmTitle'] : __( 'New title', 'text_domain' );
        $dmMortgageAmount = esc_attr( $instance['dmMortgageAmount'] );
        $dmRate = esc_attr( $instance['dmRate'] );
        $dmTerm = esc_attr( $instance['dmTerm'] );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'dmTitle' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'dmTitle' ); ?>" name="<?php echo $this->get_field_name( 'dmTitle' ); ?>"
                   type="text" value="<?php echo esc_attr( $dmTitle ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'dmMortgageAmount' ); ?>"><?php _e( 'Mortgage Amount:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'dmMortgageAmount' ); ?>" name="<?php echo $this->get_field_name( 'dmMortgageAmount' ); ?>"
                   type="text" value="<?php echo esc_attr( $dmMortgageAmount ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'dmRate' ); ?>"><?php _e( 'Interest Rate:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'dmRate' ); ?>" name="<?php echo $this->get_field_name( 'dmRate' ); ?>"
                   type="text" value="<?php echo esc_attr( $dmRate ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'dmTerm' ); ?>"><?php _e( 'Mortgage Term:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'dmTerm' ); ?>" name="<?php echo $this->get_field_name( 'dmTerm' ); ?>"
                   type="text" value="<?php echo esc_attr( $dmTerm ); ?>">
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
        $instance['dmTitle'] = ( ! empty( $new_instance['dmTitle'] ) ) ? strip_tags( $new_instance['dmTitle'] ) : '';
        $instance['dmMortgageAmount'] = strip_tags( $new_instance['dmMortgageAmount'] );
        $instance['dmRate'] = strip_tags( $new_instance['dmRate'] );
        $instance['dmTerm'] = strip_tags( $new_instance['dmTerm'] );

        return $instance;
    }

} // class dm_mortgage_widget

// register dm_mortgage_widget widget
function register_dm_mortgage_widget() {
    register_widget( 'dm_mortgage_widget' );
}
add_action( 'widgets_init', 'register_dm_mortgage_widget' );
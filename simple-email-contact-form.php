<?php
/**
* Plugin Name: Simple SpamFree Contact Form
* Plugin URI: https://www.github.com/gregnau/simple-spamfree-contact-form
* Description: Simple and fast email contact form plugin, with honeypot safety against spam. Just use the [contact] tag to display it anywhere.
* Version: 1.1
* Author: Greg Nau (gregnau)
* Author URI: https://gregnau.github.io
**/


// Main Class
class Contact_Form {

	// Class Constructor
    public function __construct() {
        $this->define_hooks();
    }

    public function controller() {
        // When "Submit" button pressed, send the message in email
		if( isset( $_POST['submit'] ) ) {

			// Prepare email contents
            $name = filter_input( INPUT_POST, 'name', FILTER_SANITIZE_STRING );
            $email = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_STRING | FILTER_SANITIZE_EMAIL );
			$subject = filter_input( INPUT_POST, 'subject', FILTER_SANITIZE_STRING );
            $message = filter_input( INPUT_POST, 'message', FILTER_SANITIZE_STRING );

            // Send the email, if the "Subject" is empty (honeypot)
			if($subject == '') { wp_mail("heni.bogdan@gmail.com",$name,$message); }
        }
    }

    // Display Contact Form
    public function display_form() {
		// Load and apply the stylesheet for hiding "Subject" field
		wp_register_style('form_css', plugins_url('simple-spamfree-contact-form.css',__FILE__ ));
    	wp_enqueue_style('form_css');

        $name = filter_input( INPUT_POST, 'name', FILTER_SANITIZE_STRING );
        $email = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_STRING | FILTER_SANITIZE_EMAIL );
		$subject = filter_input( INPUT_POST, 'subject', FILTER_SANITIZE_STRING );
        $message = filter_input( INPUT_POST, 'message', FILTER_SANITIZE_STRING );

        $output = '';  // Clear output buffer

		// Display Contact Form elements
        $output .= '<form method="post">';
        $output .= '    <p>';
        $output .= '        ' . $this->display_text( 'name', 'Name', $name );
		$output .= '        ' . $this->display_text( 'email', 'Email', $email );
		$output .= '        ' . $this->display_text( 'subject', 'Subject', $subject );
        $output .= '    </p>';
        $output .= '    <p>';
        $output .= '        ' . $this->display_textarea( 'message', 'Message', $message );
        $output .= '    </p>';
        $output .= '    <p style="text-align:right">';
        $output .= '        <input type="submit" name="submit" id="submit" value="Submit" />';
        $output .= '    </p>';
        $output .= '</form>';

        return $output;
    }

    // Display text field for sender details
    private function display_text( $name, $label, $value = '' ) {
        $output = '';	// Clear output buffer

		// Display label and input
        $output .= '<label id="' . esc_attr( $name ) . '">' . esc_html__( $label, 'contact' ) . ':</label>';
        $output .= '<input type="text" name="' . esc_attr( $name ) . '" id="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';

        return $output;
    }

    // Display textarea field
    private function display_textarea( $name, $label, $value = '' ) {
        $output = '';	// Clear output buffer

	    // Display label and textarea
		$output .= '<label id="' . esc_attr( $name ) . '"> ' . esc_html__( $label, 'contact' ) . ':</label>';
        $output .= '<textarea name="' . esc_attr( $name ) . '" id="' . esc_attr( $name ) . '" >' . esc_html( $value ) . '</textarea>';

        return $output;
    }

    // Define hooks for plugin
    private function define_hooks() {
        add_action( 'wp', array( $this, 'controller' ) );	// Send email

        add_shortcode( 'contact', array( $this, 'display_form' ) );		// Add shortcode
    }
}

new Contact_Form();


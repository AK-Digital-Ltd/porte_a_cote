<?php 

function displayTrackingInformation() {
    $tracking_info = get_post_meta( get_the_ID(), 'tracking_information', true );
    if ( ! empty( $tracking_info ) ) {
        echo '<div class="tracking-information">';
        echo '<h2>Tracking Information</h2>';
        echo '<p>' . esc_html( $tracking_info ) . '</p>';
        echo '</div>';
    }
}

?>
<?php if ( defined( 'ISWDS' ) ) : ?>
    <div class="wsu-cta wsu-cta--width-inline wsu-spacing-after--sxxsmall">
        <a href="#<?php echo esc_attr( $atts['data_widget_id'] ); ?>-skiplink" class="wsu-button wsu-button--style-outline wsu-button--size-small wsu-button--style-skip">
            Skip Social Media Feed
        </a>
    </div>
<?php endif; ?>
<a class="twitter-timeline"
    href="<?php echo esc_url( $atts['href'] ); ?>"
    data-widget-id="<?php echo esc_attr( $atts['data_widget_id'] ); ?>"><?php echo esc_html( $atts['name'] ); ?>
</a>
<?php if ( defined( 'ISWDS' ) ) : ?>
    <div id="<?php echo esc_attr( $atts['data_widget_id'] ); ?>-skiplink"></div>
<?php endif; ?>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script><?php

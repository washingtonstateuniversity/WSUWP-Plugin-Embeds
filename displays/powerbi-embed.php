<?php

/**
 * @var $path string Relative path for embed
 * @var $height string iframe height
 * @var $width string iframe width
 */
?>
<iframe width="<?php echo esc_attr( $width ); ?>" height="<?php echo esc_attr( $height ); ?>" src="https://app.powerbi.com/<?php echo esc_attr( $path ); ?>" frameborder="0" allowFullScreen="true"></iframe>

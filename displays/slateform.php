<?php
/**
 * Embed code for Slate form. Used in the slateform shortcode.
 * 
 * @since 0.13.0
 * 
 * @var string $form_id Slate form id.
 */
?>
<div id="form_<?php echo esc_attr( $form_id ); ?>">Loading...</div><script async="async" src="https://futurecoug.wsu.edu/register/?id=<?php echo esc_attr( $form_id ); ?>&output=embed&div=form_<?php echo esc_attr( $form_id ); ?>">/**/</script>

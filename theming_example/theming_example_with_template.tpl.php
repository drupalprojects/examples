<?php
/**
 * @file
 * Template file for theming_example_with_template.
 *
 * $content comes from arguments definition in hook_theme(), and is modified by
 * the preprocess function template_preprocess_theming_example_with_template().
 * $aside is added by the same preprocess function.
 *
 */
?>
<!-- theming_example_with_template template -->
<div>
<h3>This is in a template file.</h3>
<?php print $content; ?>
<aside><?php print $aside; ?></aside>
</div>
<!-- /theming_example_with_template template -->

<?php
/**
 * @file
 * This is the template file for the hierarchy style plugin.
 */
?>
<?php if( empty($parent) ): ?>
<div class="root">
  <?php print render($children) ?>
</div>

<?php elseif ( empty($children) ): ?>
<div class="item leaf">
  <?php print render($parent) ?>
</div>

<?php else: ?>
<div class="item group">
    <div class="parent"><?php print render($parent) ?></div>
    <div class="children"><?php print render($children) ?></div>
</div>
<?php endif ?>

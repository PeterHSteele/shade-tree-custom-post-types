<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Shade_Tree_CPT
 * @subpackage Shade_Tree_CPT/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="cpt-admin-page">
  <h1><?php esc_html_e('Add, Edit, or Delete Post Types', $this->textdomain) ?></h1>
  <p><?php esc_html_e('The current post types are:', $this->textdomain) ?></p>
  <ul>
    <?php foreach( $post_types as $key => $post_type ) : ?>
      <li><?php echo esc_html($key) ?></li>
    <?php endforeach; ?>
  </ul>
  <form action="<?php echo esc_url(admin_url('admin-post.php')) ?>" method="post">
    <input type="hidden" name="action" value="edit_post_types">
    <?php wp_nonce_field('edit custom post types', 'ringo_starr'); ?>
    <div class="field">
      <label for="add-post-type-singular-input"><?php esc_html_e('(Required) Singular Name of Post Type to Add', $this->textdomain)?></label>
      <p class="description"><?php esc_html_e("For example, 'movie'.", $this->textdomain ) ?></p>
      <input required id="add-post-type-singular-input" type="text" value="" name="edit-post-types[singular_name]">
    </div>
    <div class="field">
      <label for="add-post-type-plural-input"><?php esc_html_e('(Required) Plural name of post type to add', $this->textdomain) ?></label>
      <p class="description"><?php esc_html_e("For example, 'movies'.", $this->textdomain ) ?></p>
      <input required id="add-post-type-plural-input" type="text" value="" name="edit-post-types[name]">
    </div>
    <div class="field">
      <label for="post-type-key-input"><?php esc_html_e('Post Type Key', $this->textdomain) ?></label>
      <p class="description">
        <?php 
        esc_html_e( 
          "This is the internal name wordpress will use to refer to your post type. It should contain only lowercase letters, hyphens and underscores. It's a good idea to make it the same as the plural name.",
          $this->textdomain
        );
        ?></p>
      <input required id="post-type-key-input" type="text" value="" name="edit-post-types[key]">
    </div>
    <input type="submit" value="Create Post Type">
  </form>
  <h2><?php esc_html_e('Delete a Post Type', $this->textdomain) ?></h2>
  <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' )) ?>">
    <input type="hidden" name="action" value="delete_post_type">
    <?php wp_nonce_field('Shade Tree delete post type', 'shade_tree_delete_type') ?>
    <div class="field">
      <label for="type-to-delete-input"><?php esc_html_e('Post Type Key') ?></label>
      <input id="type-to-delete-input" type="text" value="" name="delete-type[key]">
    </div>
  </form>
</div>
<?php
/**
 * Sole Mates homepage: the catalog is the homepage.
 * Shows a short intro, then the full product catalog.
 */
get_header(); ?>

<main id="main" class="site-main">
  <section class="sole-mates-intro" style="padding: 2rem 0 1rem;">
    <h1>Sole Mates</h1>
    <p>Shoes for every step — boots, sandals, sneakers, slippers and runners. Every pair is $1 for this demo.</p>
    <p><a class="button" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Shop all shoes</a></p>
  </section>

  <?php echo do_shortcode( '[products limit="12" columns="3" orderby="menu_order" order="ASC"]' ); ?>
</main>

<?php get_footer(); ?>

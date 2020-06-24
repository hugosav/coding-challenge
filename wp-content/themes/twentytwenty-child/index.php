<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();
?>
<div id="fb-root"></div>
<main id="site-content" role="main">

<h1>Ninja Name generator</h1>

<div class="alert">To generate a ninja name enter some words separated by space and press send</div>

<input type="text" id="buzzwords">
<?php
    $link = admin_url('admin-ajax.php?action=name_generator&nonce='.$nonce);
    echo '<button id="send" class="name_generator" data-nonce="' . $nonce . '" href="' . $link . '">Send</button>';
?>
<div id="intro" style="color:#fff; margin-top:15px;"></div>
</main><!-- #site-content -->

</script>

<?php
//get_footer();

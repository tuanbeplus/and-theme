<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */

$headOffice = get_field('head_office', 'option');
$postalAddress = get_field('postal_address', 'option');
$phone = get_field('phone', 'option');
$national = get_field('national', 'option');
$email = get_field('email', 'option');
$facebook = get_field('facebook', 'option');
$twitter = get_field('twitter', 'option');
$linkedin = get_field('linkedin', 'option');
$youtube = get_field('youtube', 'option');
$footer_logo = get_field('footer_logo', 'option');
$footerLinks = get_field('footer_links', 'option');

?>
<?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>

    </div><!-- #content -->

	<footer id="colophon" class="site-footer <?php echo wp_bootstrap_starter_bg_class(); ?>" role="contentinfo">
		<div class="container pt-3 pb-3">
            <div class="row">
                <div class="site-logo col-md-3">
                    <img src="<?php echo $footer_logo; ?>" alt="Australian Network on Disability"/>
                </div>
                <div class="site-info col-md-9">
                    <div class="row">
                        <div class="col-md-6 col-lg-2 detail">
                            <h2>Head office</h2>
                            <p><?php echo $headOffice; ?></p>
                        </div>
                        <div class="col-md-6 col-lg-2 detail">
                            <h2>Postal address</h2>
                            <p><?php echo $postalAddress; ?></p>
                        </div>
                        <div class="col-md-6 col-lg-2 detail">
                            <h2>Phone</h2>
                            <a href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a>
                        </div>
                        <div class="col-md-6 col-lg-2 detail">
                            <h2>National</h2>
                            <a href="tel:<?php echo $national; ?>"><?php echo $national; ?></a>
                        </div>
                        <div class="col-md-6 col-lg-2 detail">
                            <h2>Email</h2>
                            <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
                        </div>
                    </div>
                </div>
                <div class="social col-12">
                    <h2>Follow us</h2>
                    <a href="<?php echo $facebook; ?>" target="_blank">
                        <img src="<?php echo AND_IMG_URI.'facebook.svg'; ?>" alt="Facebook" />
                    </a>
                    <a href="<?php echo $twitter; ?>" target="_blank">
                        <img src="<?php echo AND_IMG_URI.'social-icons-x.svg'; ?>" alt="Twitter" style="width:30px" />
                    </a>
                    <a href="<?php echo $linkedin; ?>" target="_blank">
                        <img src="<?php echo AND_IMG_URI.'linkedin.svg'; ?>" alt="LinkedIn" />
                    </a>
                    <a href="<?php echo $youtube; ?>" target="_blank">
                        <img src="<?php echo AND_IMG_URI.'youtube.svg'; ?>" alt="YouTube" />
                    </a>
                </div>
                <div class="col-12 bottom">
                    <div class="row">
                        <hr class="col-12 border"/>
                        <div class="col-8 col-md-6 ctas">
                            <?php if(!empty($footerLinks)): foreach($footerLinks as $link): ?>
                            <a href="<?php echo $link['link']; ?>" target="_blank"><?php echo $link['name']; ?></a>
                            <?php endforeach; endif; ?>
                        </div>
                        <div class="col-md-6 copyrights">
                            <p>ACN 605 683 369 &copy; 2024 Australian Disability Network</p>
                        </div>
                    </div>
                </div>
            </div>
		</div>
    </footer>

<?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<script type="text/javascript"  src="<?php echo get_template_directory_uri().'/assets/js/main.js?rand=2346236'; ?>"></script>

<?php wp_footer(); ?>
</body>
</html>

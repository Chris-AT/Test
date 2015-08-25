<?php
get_header();
//added this comment ?>

<div class="container-fluid">
    
    <div class="row missing-row">
        <div class="col-xs-12">
            <div class="missing">
                <?php echo get_option('label_missing'); ?>:&nbsp;<?php echo get_option('person_name'); ?>
            </div> <!-- .missing -->
        </div> <!-- col-xs-12 -->
    </div> <!-- .row -->
</div> <!-- .container-fluid -->
<div class="container">
    <div class="row person-description">
        <div class="col-md-6 col-sm-12">
            <img class="person-main-picture" src="<?php echo get_option('person_main_picture_url'); ?>"/>
        </div>
        <div class="col-md-6 col-sm-12 attributes">
            <?php 
            $attributes = get_option('attribute');
            $attributeLabels = get_option('attributeLabel');
            for($i = 0; $i < count($attributes); $i++) {
                echo '<div><b>' . $attributeLabels[$i] . '</b>' . ': ' . $attributes[$i] . '</div>';
            } ?>
        </div>
    </div> <!-- .row -->
    <?php
            if(get_option('person_gallery_URLs') != '') : ?>
                <div class="row">
                    
                        <div id="links">
                            <?php
                                $URLs = get_option('person_gallery_URLs');
                                $IDs = get_option('person_gallery_IDs');
                                for($i = 0; $i < count($URLs); $i++) : ?>
                                
                                    <a href="<?php echo $URLs[$i]; ?>" class="col-xs-12 col-sm-4 col-md-3 col-lg-2 gallery-slider-item">
                                        <img src="<?php echo $URLs[$i]; ?>" class="gallery-picture">
                                    </a>
                                
                                <?php
                                endfor; ?>
                        
                    </div>
                </div>
                <?php 
            endif; ?>
    <div class="row contact">
        <div class="col-xs-12">
            <h2>Contact Information</h2>
            <div><span class="fa fa-envelope-o">&nbsp;</span><?php echo get_option('email_to_contact'); ?></div>
            <div><span class="fa fa-phone-square">&nbsp;</span><?php echo get_option('phone_to_contact'); ?></div>
        </div>
    </div>
    <div class="row updates">
        <div class="col-xs-12">
            <?php $query = new WP_Query('posts_per_page=-1'); ?>
            <?php while($query->have_posts()) : $query->the_post(); ?>
            <h2><?php the_title(); ?></h2>
            <div class="details"><span class="fa fa-clock-o">&nbsp;<?php the_time('G:i - F j, Y'); ?></span></div>
                <?php the_content(); ?>
            <?php endwhile; ?>
        </div>
    </div>
</div> <!-- .container -->
<script type="text/javascript">
    jQuery(document).ready(function() {
        $('.gallery-slider-item').magnificPopup({
            type: 'image',
            gallery: {enabled: true}
        }); 
    });
</script>
<?php get_footer();

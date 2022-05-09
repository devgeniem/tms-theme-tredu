<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

use \TMS\Theme\Tredu\Comments;
?>

<section class="section section--comments">
    <div class="columns">
        <div class="column is-10 is-offset-1">
            <div class="is-content-grid">
                <h2>
                    <?php esc_html_e( 'Comments', 'tms-theme-tredu' ); ?>
                </h2>

                <div class="comments-area">
                    <?php if ( have_comments() ) : ?>
                        <div class="comments__list-container">
                            <?php
                            wp_list_comments(
                                [
                                    'reply_text' => __( 'Reply', 'tms-theme-tredu' ),
                                    'callback'   => Closure::fromCallable( [ Comments::class, 'comment_callback' ] ),
                                    'style'      => 'div',
                                ]
                            );
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="comments__form-container">
                        <?php comment_form(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

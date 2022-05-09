<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu;

/**
 * This class handles the Comments section modifications.
 */
class Comments implements Interfaces\Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {
        \add_filter( 'comment_reply_link', [ $this, 'amend_reply_link_class' ], 10, 1 );
        \add_filter( 'comment_form_fields', [ $this, 'customize_comment_form_fields' ], 10, 2 );
        \add_filter( 'comment_form_submit_button', [ $this, 'override_comment_form_submit' ], 10, 0 );
        \add_filter( 'get_comment_author', [ $this, 'get_comment_author_name' ], 10, 2 );
    }

    /**
     * Get comment author name.
     *
     * @param string $author     Original author name.
     * @param int    $comment_id Comment ID.
     *
     * @return string
     */
    public function get_comment_author_name( string $author, int $comment_id ) : string {
        $author_id = $this->get_author_override_id( $comment_id );

        return ! empty( $author_id ) ? get_the_title( $author_id ) : $author;
    }

    /**
     * Get comment custom author from meta field.
     *
     * @param int $comment_id Comment ID.
     *
     * @return int|false
     */
    public static function get_author_override_id( int $comment_id ) {
        $author_id = get_comment_meta( $comment_id, 'author', true );

        return $author_id ?? false;
    }

    /**
     * Customize reply link.
     *
     * @param string $link The HTML markup for the comment reply link.
     *
     * @return string
     */
    public function amend_reply_link_class( string $link ) : string {
        return str_replace( 'comment-reply-link', 'comment-reply-link button is-primary', $link );
    }

    /**
     * Comment form submit button.
     *
     * @return string
     */
    public function override_comment_form_submit() : string {
        return sprintf(
            '<button name="submit" type="submit" id="submit" class="button button--icon is-primary" >%s %s</button>',
            __( 'Send Comment', 'tms-theme-tredu' ),
            '<svg class="icon icon--chevron-right icon--medium">
                <use xlink:href="#icon-arrow-right"></use>
            </svg>'
        );
    }

    /**
     * Customize comment form fields.
     *
     * @param array $fields Form fields.
     *
     * @return array
     */
    public function customize_comment_form_fields( array $fields ) : array {
        unset( $fields['url'] );
        unset( $fields['cookies'] );

        $comment_field = $fields['comment'];
        unset( $fields['comment'] );
        $fields['comment'] = $comment_field;

        return $fields;
    }

    /**
     * Custom wp_list_comments callback.
     *
     * @param \WP_Comment $comment Current comment object.
     * @param array       $args    Callback args.
     * @param int         $depth   Comment depth.
     *
     * @return void
     */
    public static function comment_callback( \WP_Comment $comment, array $args, int $depth ) { // phpcs:ignore
        ?>
    <div id="comment-<?php comment_ID(); ?>" <?php comment_class( $comment ? 'parent' : '', $comment ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment__body">
            <?php if ( '0' === $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation has-text-weight-bold has-text-primary">
                    (<?php esc_html_e( 'Your comment is awaiting moderation', 'tms-theme-tredu' ); ?>)
                </p>
            <?php endif; ?>

            <div class="comment__content mb-6 has-word-break-break-all keep-vertical-spacing">
                <?php comment_text(); ?>
            </div>

            <div class="comment__footer is-flex-tablet is-justify-content-space-between">
                <div class="comment__info mr-2">
                    <div class="comment__author">
                        <?php
                        echo sprintf(
                            '<a href="%s" class="%s">%s</a>',
                            esc_url( get_comment_link( $comment ) ),
                            'h5 comment__heading has-text-black',
                            esc_html( get_comment_author_link( $comment ) )
                        );
                        ?>

                        <?php if ( ! empty( self::get_author_override_id( $comment->comment_ID ) ) ) : ?>
                            <div class="comment__author-badge is-inline-flex ml-1">
                                <?php esc_html_e( 'Blog author', 'tms-theme-tredu' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <p class="comment__date mt-2 mb-0 has-text-weight-semibold has-text-black has-text-small">
                        <time datetime="<?php get_comment_time( 'c' ); ?>">
                            <?php
                            echo esc_html(
                                sprintf( '%s - %s', get_comment_date( '', $comment ), get_comment_time() )
                            );
                            ?>
                        </time>
                    </p>

                    <?php
                    edit_comment_link(
                        __( 'Edit', 'tms-theme-tredu' ),
                        ' <span class="edit-link">', '</span>'
                    );
                    ?>
                </div>

                <?php
                if ( '1' === $comment->comment_approved ) {
                    comment_reply_link(
                        [
                            'depth'     => $depth,
                            'max_depth' => get_option( 'thread_comments_depth' ),
                            'before'    => '<div class="comment__reply is-flex is-align-items-center mt-4 mt-0-tablet">', // phpcs:ignore
                            'after'     => '</div>',
                        ]
                    );
                }
                ?>
            </div>
        </article>
        <?php
    }
}

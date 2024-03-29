<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Traits;

use TMS\Theme\Tredu\Settings;

/**
 * Trait Sharing
 *
 * @package TMS\Theme\Tredu\Traits
 */
trait Sharing {

    /**
     * Share links
     *
     * @return mixed
     */
    public function share_links() {
        return apply_filters(
            'tms/theme/share_links',
            $this->get_share_links()
        );
    }

    /**
     * Return share button default class.
     *
     * @return mixed|void
     */
    public function share_link_classes() {
        $link_class = apply_filters(
            'tms/theme/share_links/link_class',
            'has-background-accent'
        );

        $icon_class = apply_filters(
            'tms/theme/share_links/icon_class',
            'is-accent-invert'
        );

        return [
            'link' => $link_class,
            'icon' => $icon_class,
        ];
    }

    /**
     * Get share links
     *
     * @return null|array
     */
    protected function get_share_links() : ?array {
        $selected_channels = Settings::get_setting( 'some_channels' );

        if ( empty( $selected_channels ) ) {
            return null;
        }

        $channels          = $this->get_channels();
        $selected_channels = array_filter( $selected_channels, fn( $item ) => isset( $channels[ $item ] ) );
        $current_post      = get_queried_object();
        $event_query_var   = get_query_var( 'event-id' );
        $overwrite_url     = '';

        if ( ! $current_post instanceof \WP_Post ) {
            return [];
        }

        // If the url has event-id url parameter set, use the whole url as share link
        // instead of getting the permalink of the post.
        if ( ! empty( $event_query_var ) ) {
            $overwrite_url = get_permalink( $current_post->ID ) . '?event-id=' . $event_query_var;
        }

        return array_map( function ( $selected_channel ) use ( $channels, $current_post, $overwrite_url ) {
            $item = $channels[ $selected_channel ];

            $item['link'] = strtr(
                $item['link'],
                [
                    '%title' => $current_post->post_title,
                    '%url'   => $overwrite_url ?: get_the_permalink( $current_post->ID ),
                ]
            );

            return $item;
        }, $selected_channels );
    }

    /**
     * Get channels
     *
     * @return array
     */
    protected function get_channels() : array {
        $channels = [
            'facebook'  => [
                'link'          => 'https://www.facebook.com/sharer/sharer.php?u=%url',
                'icon'          => 'facebook',
                'extra_classes' => [],
                'sr_text'       => __( 'Share on Facebook', 'tms-theme-tredu' ),
            ],
            'email'     => [
                'link'          => 'mailto:?subject=%title&body=%url',
                'icon'          => 'email',
                'extra_classes' => [],
                'sr_text'       => __( 'Share by email', 'tms-theme-tredu' ),
            ],
            'link'      => [
                'link'          => '%url',
                'icon'          => 'link',
                'extra_classes' => [ 'js-copy-to-clipboard' ],
                'sr_text'       => __( 'Copy link to clipboard', 'tms-theme-tredu' ),
                'callback_text' => __( 'Copied to clipboard', 'tms-theme-tredu' ),
            ],
            'whatsapp'  => [
                'link'          => 'https://wa.me/?text=%url',
                'icon'          => 'whatsapp',
                'extra_classes' => [],
                'sr_text'       => __( 'Share on Whatsapp', 'tms-theme-tredu' ),
            ],
            'twitter'   => [
                'link'          => 'https://twitter.com/intent/tweet?text=%url',
                'icon'          => 'twitter',
                'extra_classes' => [],
                'sr_text'       => __( 'Share on Twitter', 'tms-theme-tredu' ),
            ],
            'linkedin'  => [
                'link'          => 'https://www.linkedin.com/sharing/share-offsite/?url=%url',
                'icon'          => 'linkedin',
                'extra_classes' => [],
                'sr_text'       => __( 'Share on LinkedIn', 'tms-theme-tredu' ),
            ],
            'vkontakte' => [
                'link'          => 'http://vk.com/share.php?url=%url&title=%title',
                'icon'          => 'vkontakte',
                'extra_classes' => [],
                'sr_text'       => __( 'Share on Vkontakte', 'tms-theme-tredu' ),
            ],
            'line'      => [
                'link'          => 'line://msg/text/%url',
                'icon'          => 'line',
                'extra_classes' => [],
                'sr_text'       => __( 'Share on Line', 'tms-theme-tredu' ),
            ],
        ];

        return apply_filters( 'tms/theme/share_links/channels', $channels );
    }
}

<?php


function canvas_breadcrumbs( $area, $direction = 'right' ) {

    $directions = array(
        'rtl'  => 0,
        'ltr' => 1
    );

    switch ($direction) {
        case 'right':
            $direction = $directions['ltr'];
            break;

        case 'left':
            $direction = $directions['rtl'];
            break;

        default:
            $direction = $directions['ltr'];
            break;
    }

    $direction = ($direction) ? 'right' : 'left';

    $home_icon = canvas_get_svg_icon( array(
        'icon'  => 'home',
        'size'  => 'sm'
    ) );

	$breadcrumb = '<span class="breadcrumb root icon icon-sm">' . $home_icon .'</span>' . '<span class="breadcrumb root">' . get_bloginfo('name') . '</span>';


    if ( ! is_front_page() ) {


        $separator_icon = canvas_get_svg_icon( array(
            'icon'  => 'chevron-' . $direction,
            'size'  => 'xs'
        ) );

    	$separator = '<span class="separator icon icon-xs">'.$separator_icon.'</span>';
    	$breadcrumb = '<a href="'. get_option('home') .'" class="breadcrumb link link-secondary"><span class="icon icon-sm">'. $home_icon .'</span></a>';
        $root = ( get_option( 'page_for_posts' ) ) ? $separator . '<a href="'. get_permalink( get_option( 'page_for_posts' ) ) .'" class="breadcrumb link link-secondary">'. get_the_title( get_option( 'page_for_posts' ) ) .'</a>' : '';

        if( is_archive() ) {

            if ( is_day() ) {

                $breadcrumb .= $root . $separator . '<span class="breadcrumb">' . get_the_date() . '</span>';

            } elseif ( is_month() ) {

                $breadcrumb .= $root . $separator . '<span class="breadcrumb">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'canvas' ) ) . '</span>';

            } elseif ( is_year() ) {

                $breadcrumb .= $root . $separator . '<span class="breadcrumb">' . get_the_date( _x( 'Y', 'yearly archives date format', 'canvas' ) ) . '</span>';

            } else {

               $breadcrumb .= $root;

            }

        }

        if( is_category() ) {

            $categories = array_reverse( get_the_category() );

            if( ! empty( $categories ) ) {

                foreach ( $categories as $category ) {

                    // If we're on the current category archive page then stop the breadcrumb here.
                    if( is_category( $category->name ) ) {

                        $breadcrumb .= $separator . '<span class="breadcrumb">' . __( 'Category', 'canvas' ) . ': ' . esc_html( $category->name ) . '</span>';

                        break;

                    } else {

                        $breadcrumb .= $separator . '<a href="'. get_category_link( $category->term_id ) .'" class="breadcrumb link link-secondary"><span>' . __( 'Category', 'canvas' ) . ': ' . esc_html( $category->name ) . '</span></a>';

                    }

                }

            }

        }

        if( is_tag() ) {

            $prefix = __( 'Tag', 'canvas' ) . ': ';

            $breadcrumb .= $separator . '<span class="breadcrumb">' . esc_html( single_tag_title( $prefix, false ) ) . '</span>';

        }
	
        if ( is_single() ) {

        	$title = ( ! empty( get_the_title() ) ) ? get_the_title() : '';

            $breadcrumb .= $root . $separator . '<span class="breadcrumb">' . $title . '</span>';
        }
	
        if ( is_page() ) {

            global $post;

            $page_breadcrumbs = '';

            $ancestors = get_post_ancestors( $post->ID );

            if( ! empty( $ancestors ) ) {

                foreach ( $ancestors as $key => $ancestor ) {
                   
                    $page_breadcrumbs .= $separator . '<a href="'. get_permalink( $ancestor ) .'" class="breadcrumb link link-secondary">'. get_the_title( $ancestor ) .'</a>';

                }

            }

            $title = ( ! empty( get_the_title() ) ) ? $separator . '<span class="breadcrumb">' . get_the_title() . '</span>' : '';

            $page_breadcrumbs .= $title;

            $breadcrumb .= $page_breadcrumbs;
        }

        if( is_404() ) {

            $title = $separator . '<span class="breadcrumb">404</span>';

            $breadcrumb .= $title;

        }

        if( is_home() ) {

            $title = ( get_option( 'page_for_posts' ) ) ? get_the_title( get_option( 'page_for_posts' ) ) : get_bloginfo( 'title' );

            $breadcrumb .= $separator . '<span class="breadcrumb">' . $title . '</span>';

            if( is_paged() ) {

                $paged = get_query_var( 'paged', 0 );

                $page_number = '— ' . __( 'Page', 'canvas' ) . ' ' . $paged;

                $breadcrumb .= '<span class="breadcrumb">' . $page_number . '</span>';

            }

        }


        // if ( is_woocommerce() ) {

        //     $breadcrumb = woocommerce_breadcrumb( array( 

        //         'delimiter'     => $separator,
        //         'wrap_before'   => false,
        //         'wrap_after'    => false,
        //         'home'          => $home_icon

        //      ) );

        // }

    }

        echo '<div id="'. $area .'-breadcrumbs" class="breadcrumbs">' . $breadcrumb . '</div>';

}

function canvas_footer_breadcrumbs() {

    return apply_filters( 'canvas_footer_breadcrumbs', canvas_breadcrumbs('footer') );

}

add_action('canvas_footer_breadcrumb_area', 'canvas_footer_breadcrumbs');


function canvas_back_to_top_arrow() {

    $arrow_up = canvas_get_svg_icon( array(
            'icon'  => 'arrow-up',
            'size'  => 'sm'
    ) );

    echo '<a href="#top" class="btn btn-pill btn-circle btn-primary"><span class="icon icon-sm">'. $arrow_up .'</span></a>';

}

add_action('canvas_footer_back_to_top_area', 'canvas_back_to_top_arrow');


function canvas_paginator(){
 
    global $wp_query;

    $pagination = '';

    $trigger_text = __( 'Load More', 'canvas' );

    $cog_icn = canvas_get_svg_icon( array(

        'icon'  => 'settings',
        'size'  => 'sm'

     ) );
 
    if( $wp_query->max_num_pages > 1 )
        $pagination = '<div id="canvas-loadmore" class="btn-group"><button id="loadmore-settings" class="btn btn-pill btn-circle btn-default"><span class="icon icon-sm">'. $cog_icn .'</span></button><a href="#load-more" class="canvas-loadmore btn btn-pill btn-primary">' . esc_html( $trigger_text ) . '</a></div>';
 
    // replace first page before printing it
    echo $pagination;
}


function canvas_the_author() {

    $author_id = get_the_author_meta('ID');

    $author_link = get_the_author_meta('user_email');

    $author_name = get_the_author();

    $author_avatar = canvas_get_author_avatar( $author_id, array( 

        'height'    => 50,
        'width'     => 50

     ) );


    $author_block = '<div id="author-' . esc_attr( $author_id ) .'" class="post-author" ><a href="'. esc_url( $author_link ) .'" class="author-avatar"></a></div>';

    echo apply_filters( 'canvas_author_block', $author_block, $author_id );

}


function canvas_blog_link() {

    $list_icn = canvas_get_svg_icon( array( 

        'icon'      => 'list',
        'size'      => 'sm'

     ) );

    $blog_archive_link = get_post_type_archive_link( 'post' );

    $list_link = '<span class="nav-link nav-blog-link" ><a href="'. esc_attr( $blog_archive_link ) .'" class="link"><span class="icon">' . $list_icn . '</span></a></span>';

    echo apply_filters( 'canvas_blog_link',  $list_link, $list_icn, $blog_archive_link );

}

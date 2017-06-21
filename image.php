<?php
nocache_headers();
            status_header( '404' );
            $wp_query->is_404=true;
            $wp_query->is_single=false;
            $wp_query->is_singular=false;
            $wp_query->post_count=0;
            $wp_query->page=0;
            $wp_query->query['page']='';
            $wp_query->query['posts']=array();
            $wp_query->query['post']=array();
            $wp_query->posts=array();
            $wp_query->post=array();
            $wp_query->queried_object=array();
            $wp_query->queried_object_id=0;
            locate_template('404.php', true);
            exit;
?>
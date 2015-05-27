<?php

    if (!class_exists( 'post_author' ) ) {
        include_once ('classes/post_author.php');
        $post_author_filter = new post_author_filter();
    }
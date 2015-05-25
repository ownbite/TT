<ul class="socialmedia-list">
    <li class="fbook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink()); ?>">Facebook</a></li>
    <li class="twitter"><a href="http://twitter.com/share?url=<?php echo urlencode(wp_get_shortlink()); ?>">Twitter</a></li>
    <li class="linkedin"><a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode(get_the_permalink()); ?>&amp;title=<?php echo get_the_title(); ?>&amp;summary=<?php echo get_the_excerpt(); ?>&amp;source=Helsingborg.se">Linkedin</a></li>
</ul>
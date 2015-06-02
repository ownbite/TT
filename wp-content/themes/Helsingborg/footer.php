
            <footer class="footer">
                <div class="row">
                	<?php dynamic_sidebar("footer-area"); ?>
                </div>
            </footer>
        </div><!-- /.main-site-container -->

    </div><!-- /.inner-wrap -->
</div><!-- /.off-canvas-wrap -->

<script type="text/javascript">
    $(document).ready( function() {
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        jQuery.post(ajaxurl, { action: 'big_notification' }, function(response) {
        	jQuery('.alert').append(response);
        });
    });
</script>

<script src="<?php echo get_template_directory_uri() ; ?>/js/helsingborg/vergic.js"></script>
<script type="text/javascript">function googleTranslateElementInit() {new google.translate.TranslateElement({pageLanguage:"sv",autoDisplay:false,gaTrack:true,gaId:"UA-16678811-1"},"google-translate-element");}</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<!-- # WP FOOTER # -->
<?php wp_footer(); ?>

<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-PVK49V"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>
    // GA
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-16678811-1', 'auto');
    ga('send', 'pageview');

    // TM
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PVK49V');
</script>

</body>
</html>

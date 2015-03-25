<?php
/*
* Accessability menu list
*/
global $wp;
$current_url = home_url(add_query_arg(array(),$wp->request));
?>
<ul class="accessability-menu rs_skip rs_preserve">
    <li>
        <a id="listen" onclick="javascript:readpage(this.href, 'read-speaker-player'); return false;"  class="icon speaker" title="lyssna på innehållet" href="http://app.eu.readspeaker.com/cgi-bin/rsent?customerid=5507&amp;lang=sv_se&amp;readid=article&amp;url=<?php echo $current_url; ?>">Lyssna</a>
    </li>
    <li>
        <div id="google-translate"></div>
        <script type="text/javascript">
            function googleTranslateElementInit() {
              new google.translate.TranslateElement({pageLanguage: 'sv', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false, gaTrack: true, gaId: 'UA-16678811-1'}, 'google-translate');
            }
        </script>
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    </li>
</ul>
<div id="read-speaker-player" class="rs_skip rs_preserve"></div>
<script>
    jQuery(document).ready(function() {
        var rspkrElm;
        ReadSpeaker.q(function() {
            rspkrElm = $rs.get('#listen');
            rspkr.c.addEvent('onUIShowPlayer', function() {
                rspkrElm.innerHTML = 'Sluta lyssna';
                $rs.setAttr(rspkrElm, 'onclick', 'rspkr.ui.getActivePlayer().close(); return false;');
                rspkrElm.onclick = new Function("rspkr.ui.getActivePlayer().close(); return false;");
            });
            rspkr.c.addEvent('onUIClosePlayer', function() {
                rspkrElm.innerHTML = 'Lyssna';
                $rs.setAttr(rspkrElm, 'onclick', 'readpage(this.href, "read-speaker-player"); return false;');
                rspkrElm.onclick = new Function('readpage(this.href, "read-speaker-player"); return false;');
            });
        });
    });
</script>

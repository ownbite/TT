<?php
/*
Template Name: Artikelsida
*/
get_header();
?>

<div class="article-page-layout row">
    <!-- main-page-layout -->
        <div class="main-area large-9 columns">

        <div class="main-content row">
            <!-- SIDEBAR LEFT -->
            <div class="sidebar sidebar-left large-4 medium-4 columns">
                <div class="search-container row">
                    <div class="search-inputs large-12 columns">
                        <input type="text" placeholder="Vad letar du efter?" name="search"/>
                        <input type="submit" value="Sök">
                    </div>
                </div><!-- /.search-container -->

                <div class="row">

                    <!-- large-up menu-->
                  <?php dynamic_sidebar("left-sidebar"); ?>
                  <?php Helsingborg_sidebar_menu(); ?>
                    <!-- END large up menu-->

                </div><!-- /.row -->
            </div><!-- /.sidebar-left -->

            <div class="large-8 medium-8 columns">

                <!-- Slider -->
                <?php $hasSlides = (is_active_sidebar('slider-area') == TRUE) ? '' : 'no-image'; ?>
                <div class="row <?php echo $hasSlides; ?>">
                  <div class="large-12 columns slider-container">
                      <!-- START ORBIT -->
                      <div class="orbit-container">
                        <ul class="example-orbit" data-orbit data-options="navigation_arrows:false;slide_number:false;timer:false;">
                          <?php dynamic_sidebar("slider-area"); ?>
                        </ul>
                      </div>
                      <!-- END ORBIT -->
                  </div><!-- /.slider-container -->
                </div><!-- /.row -->



                <div class="listen-to">
                    <a href="#" class="icon"><span>Lyssna på innehållet</span></a>
                </div>

                <article class="article">

                <h1 class="article-title">Översiktsplanering</h1>

                <div class="ingress">
                    En översiktsplan beskriver utveckling av den fysiska miljön på lång sikt. Den ger vägledning för hur mark- och vattenområden ska användas samt hur den byggda miljön ska användas, utvecklas och bevaras.
                </div><!-- /.ingress -->
                <div class="article-body">
                    <p>
                    Alla kommuner ska ha en aktuell översiktsplan som omfattar hela kommunen, vilket anges i plan- och bygglagen. Kommunfullmäktige måste varje mandatperiod besluta om planens aktualitet, det vill säga ta ställning till om översiktsplanen är aktuell eller ej.
                    </p>
                    <p>
                        En översiktsplan är inte juridiskt bindande men ska vara en långsiktig ledstjärna som ger stöd och är underlag för kommande planering (till exempel i bygglov och detaljplaner). Översiktsplanen ger invånare, näringsliv och myndigheter vägledning i hur kommunen kommer att ställa sig till olika förfrågningar.
                    </p>
                    <h2>Helsingborgs översiktsplan</h2>
                    <p>Helsingborgs översiktsplan består av <a title="Läs om ÖP 2010 här." href="/Medborgare/Trafik-och-stadsplanering/Oversiktsplan-och-detaljplaner/Oversiktsplanering/gallande-oversiktsplaner/Oversiktsplan-2010/">ÖP 2010</a>, som gäller hela kommunens yta samt&nbsp; fördjupade översiktsplaner (numera kallat ändring av översiktsplan) som gäller för delområden som&nbsp;<a title="Läs om fördjupad översiktsplan för Gantofta här." href="/Medborgare/Trafik-och-stadsplanering/Oversiktsplan-och-detaljplaner/Oversiktsplanering/gallande-oversiktsplaner/gantofta/">Gantofta</a>&nbsp;, <a title="Läs om  fördjupad översiktsplan för H+ här." href="/Medborgare/Trafik-och-stadsplanering/Oversiktsplan-och-detaljplaner/Oversiktsplanering/gallande-oversiktsplaner/h/">H+</a> samt <a title="Läs om ändring av Helsingborgs översiktsplan, Allerum - Hjälmshult, här." href="/Medborgare/Trafik-och-stadsplanering/Oversiktsplan-och-detaljplaner/Oversiktsplanering/gallande-oversiktsplaner/allerum-hjalmshult/">Allerum – Hjälmshult</a>.&nbsp;Det finns&nbsp;även tematiskt tillägg till översiktsplanen avseende <a title="Läs om tematiskt tillägg avseende vindkraft här." href="/Medborgare/Trafik-och-stadsplanering/Oversiktsplan-och-detaljplaner/Oversiktsplanering/gallande-oversiktsplaner/vindkraft/">vindkraft</a>.</p>

                    <h3>Rubrik som är H3</h3>
                    <p>Helsingborgs översiktsplan förklarades som aktuell i&nbsp;februari 2014 av&nbsp;Helsingborgs kommunfullmäktige. <a title="Läs om aktualisering av Helsingborgs översiktsplan här." href="/Medborgare/Trafik-och-stadsplanering/Oversiktsplan-och-detaljplaner/Oversiktsplanering/gallande-oversiktsplaner/aktualisering/">Här kan du läsa&nbsp;mer om aktualisering av Helsingborgs översiktsplan.</a></p>
                    <p>
                        ÖP 2010 är en strategisk översiktsplan som lägger grunden för rullande översiktsplanering. Det innebär att frågor av allmän och övergripande karaktär ska utredas vidare. Efter hand som behovet uppstår kan översiktsplanen kompletteras med planeringsunderlag och styrdokument, vilket har gjorts för exempelvis Allerum-Hjälmshult, Gantofta eller klimatpanpassning. </p>
                        <h4>Rubrik som är h4 med en massa text</h4>
                        <h5>Rubrik som är h5 med en massa text</h5>
                        <h6>Rubrik som är h6 med en massa text</h6>
                        <br/>
                    <p>
                        ÖP 2010 redovisar tydliga strategier och övergripande geografiska ställningstaganden. Det innebär att översiktsplanens markanvändningskarta inte i detalj redovisar samtliga ställningstaganden och avvägningar på varje enskild yta, utan att många avvägningar får ske i kommande planering.
                    </p>

                    <ul class="socialmedia-list inline-list">
                        <li class="fbook"><a href="#">Facebook</a></li>
                        <li class="twitter"><a href="#">Twitter</a></li>
                        <li class="instagram"><a href="#">Instagram</a></li>
                    </ul>

                </div><!-- /.article-body -->

            </article>

            <!-- List "puffar" + "blockpuffar" se : http://www.helsingborg.se/Medborgare/Uppleva-och-gora/ + http://www.helsingborg.se/Medborgare/Trafik-och-stadsplanering/ -->

            <?php
            // Only print out if content area has any widgets available
            if ( (is_active_sidebar('content-area') == TRUE) ) : ?>
              <ul class="block-list page-block-list page-list large-block-grid-3 medium-block-grid-3 small-block-grid-2">
                <?php dynamic_sidebar("content-area"); ?>
              </ul>
            <?php endif; ?>

            <ul class="block-list page-block-list page-list large-block-grid-3 medium-block-grid-3 small-block-grid-2">
                <li>
                    <a href="#" desc="link-desc">
                        <img src="http://www.placehold.it/300x200" alt="alt-text"/>
                        <h2 class="list-title">Bibliotek</h2>
                        <div class="list-content">
Fusce euismod ante sed aliquam porta. Nam dui mauris, posuere eget lobortis vel, vehicula congue dui. Phasellus pellentesque et lacus quis suscipit.
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" desc="link-desc">
                        <img src="http://www.placehold.it/300x200" alt="alt-text"/>
                        <h2 class="list-title">Kultur i Helsingborg</h2>
                        <div class="list-content">
Fusce euismod ante sed aliquam porta. Nam dui mauris, posuere eget lobortis vel, vehicula congue dui. Phasellus pellentesque et lacus quis suscipit.
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" desc="link-desc">
                        <img src="http://www.placehold.it/300x200" alt="alt-text"/>
                        <h2 class="list-title">Friluftsliv i Helsingborg</h2>
                        <div class="list-content">
Fusce euismod ante sed aliquam porta. Nam dui mauris, posuere eget lobortis vel, vehicula congue dui. Phasellus pellentesque et lacus quis suscipit.
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" desc="link-desc">
                        <img src="http://www.placehold.it/300x200" alt="alt-text"/>
                        <h2 class="list-title">Fritidsgårdar och mötesplatser</h2>
                        <div class="list-content">
Fusce euismod ante sed aliquam porta. Nam dui mauris, posuere eget lobortis vel, vehicula congue dui. Phasellus pellentesque et lacus quis suscipit.
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" desc="link-desc">
                        <img src="http://www.placehold.it/300x200" alt="alt-text"/>
                        <h2 class="list-title">Anläggningar och sporthallar</h2>
                        <div class="list-content">
Fusce euismod ante sed aliquam porta. Nam dui mauris, posuere eget lobortis vel, vehicula congue dui. Phasellus pellentesque et lacus quis suscipit.
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" desc="link-desc">
                        <img src="http://www.placehold.it/300x200" alt="alt-text"/>
                        <h2 class="list-title">Fritidschecken pulshöjaren</h2>
                        <div class="list-content">
Fusce euismod ante sed aliquam porta. Nam dui mauris, posuere eget lobortis vel, vehicula congue dui. Phasellus pellentesque et lacus quis suscipit.
                        </div>
                    </a>
                </li>
            </ul>

            <!-- news list -->
            <section class="news-section">
            <ul class="news-list-large row">
                <li class="news-item large-12 columns">
                    <div class="row">
                        <div class="large-5 medium-4 small-4 columns news-image">
                            <img src="http://www.placehold.it/300x300" alt="alt-text">
                        </div>
                        <div class="large-7 medium-8 small-8 columns news-content">
                            <h2 class="news-title">Du kan förtidsrösta innan valdagen</h2>
                            <span class="news-date">28 aug 2014 15:58</span>
                            <p>Den 27 augusti-13 september kan du förtidsrösta på flera platser i Helsingborg. För att rösta måste du le- gitimera dig, ta med giltig id-handling och röstkort till vallokalen.</p>
                            <a href="#" class="read-more">Läs mer</a>
                        </div>
                    </div>
                </li>
            </ul>
            </section>

            <section class="news-section">
            <h2 class="section-title">Fler nyheter</h2>

            <div class="divider fade">
                    <div class="upper-divider"></div>
                    <div class="lower-divider"></div>
                </div>

            <ul class="news-list-small row">
                <li class="news-item large-12 columns">
                    <div class="row">
                        <div class="large-9 medium-9 small-9 columns news-content">
                            <h2 class="news-title">
                                <a href="#">Två gångbroar i Vallåkra avstängda under renovering</a>
                            </h2>
                        </div><!-- /.large-12 -->
                        <div class="large-3 medium-3 small-3 columns">
                            <span class="news-date">5 sep 2014</span>
                        </div>
                    </div><!-- /.row -->
                </li>
                <li class="news-item large-12 columns">
                    <div class="row">
                        <div class="large-9 medium-9 small-9 columns news-content">
                            <h2 class="news-title">
                                <a href="#">Enklare att sortera skräp på stan</a>
                            </h2>
                        </div><!-- /.large-12 -->
                        <div class="large-3 medium-3 small-3 columns">
                            <span class="news-date">5 sep 2014</span>
                        </div>
                    </div><!-- /.row -->
                </li>
                <li class="news-item large-12 columns">
                    <div class="row">
                        <div class="large-9 medium-9 small-9 columns news-content">
                            <h2 class="news-title">
                                <a href="#">Två gångbroar i Vallåkra avstängda under renovering</a>
                            </h2>
                        </div><!-- /.large-12 -->
                        <div class="large-3 medium-3 small-3 columns">
                            <span class="news-date">5 sep 2014</span>
                        </div>
                    </div><!-- /.row -->
                </li>

            </ul>
            </section>

            <!-- END LIST + BLOCK puffs :-) -->
        </div><!-- /.columns -->
    </div><!-- /.main-content -->

        <div class="lower-content row">
            <div class="sidebar large-4 columns">
                <div class="row">

                </div><!-- /.row -->
            </div><!-- /.sidebar -->

            <section class="large-8 columns">
                <ul class="block-list news-block-list large-block-grid-3 medium-block-grid-3 small-block-grid-2">
                        <li>
                            <img src="http://www.placehold.it/330x170" alt="alt-text"/>
                        </li>
                        <li>
                            <img src="http://www.placehold.it/330x370" alt="alt-text"/>
                        </li>
                        <li>
                            <img src="http://www.placehold.it/330x270" alt="alt-text"/>
                        </li>
                        <li>
                            <img src="http://www.placehold.it/330x270" alt="alt-text"/>
                        </li>
                        <li>
                            <img src="http://www.placehold.it/330x270" alt="alt-text"/>
                        </li>
                        <li>
                            <img src="http://www.placehold.it/330x270" alt="alt-text"/>
                        </li>
                    </ul>

            </section>

        </div><!-- /.lower-content -->
    </div>  <!-- /.main-area -->

    <div class="sidebar sidebar-right large-3 columns">
        <div class="row">

          <?php /* Add the page's widgets */ ?>
          <?php dynamic_sidebar("right-sidebar"); ?>

    </div><!-- /.rows -->
</div><!-- /.sidebar -->

</div><!-- /.article-page-layout -->
</div><!-- /.main-site-container -->


<?php get_footer(); ?>

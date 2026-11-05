<?php
declare(strict_types=1);
$meta['title'] = 'Q.E.D';
?>
<header class="flex blurbg bg-def-2 border-b">
    <nav class="w100p acjc">
        <a href="#" class="nav-button hover-white bora-s c-lightpurple semibold">
            Home
        </a>
        <a href="<?= e($base_path, ENT_QUOTES, 'UTF-8') ?>/about" class="nav-button hover-white bora-s c-white">
            About
        </a>
        <a href="https://github.com/Qwidio/QED" target="_blank" rel="noopener noreferrer" class="nav-button hover-white bora-s c-semiwhite">
            GitHub
        </a>
    </nav>
</header>
<section class="fld acjc topMg-10 pad-b txtc posr">
    <div class="bg-half-purple border-purple bora-s pad-s-s pad-m-v c-lightpurple txt-s semibold bottomMg-s10">
        Kinda Next.js • Pure PHP • With Bloated CSS
    </div>
    <h1 class="txt-maintext bold txt-shad-1 topMg-s5 bottomMg-s10">
        Recreation of NextJS
    </h1>
    <p class="txt-b c-semiwhite maxw70 sideMg topMg-s5 bottomMg-10 txtc">
        An LFI-safe, zero-dependency App Router delivering file-based routing, nested layout trees, 
        and co-located server actions with standard Apache/Nginx simplicity.
    </p>
    <div class="flex row acjc gap10 topMg-5 wrap">
        <a href="<?= e($base_path, ENT_QUOTES, 'UTF-8') ?>/about" class="pad-n-s pad-s-v bgc-purple hover-text-orange bora-n c-white bold txt-b posr box-shad-black-1 ovh">
            More about this
        </a>
        <button type="button" onclick="popMsg('Told you so!')" class="pad-n-s pad-s-v bg-half-gray hover-white border-2 bora-n c-white semibold txt-b points">
            totally safe button
        </button>
    </div>
</section>
<section class="fld acjc topMg-5 pad-n">
    <div class="posr pad-n w100p bg-def-1 border-purple bora-n box-shad-black-1 flex wrap space-between">
        <div class="flex fld acjc w20p minw15 pad-s">
            <span class="posr txt-30 bold c-orange">0</span>
            <p class="posr txt-s c-semiwhite txtc topMg-s5">External Dependencies</p>
            <p class="posr txt-ms c-gray txtc">Idk if that's a good thing or not</p>
        </div>
        <div class="flex fld acjc w20p minw15 pad-s">
            <span class="posr txt-30 bold c-blue">&lt; 12ms</span>
            <p class="posr txt-s c-semiwhite txtc topMg-s5">Average TTFB Latency</p>
            <p class="posr txt-ms c-gray txtc">the real loading is longer because php</p>
        </div>
        <div class="flex fld acjc w20p minw15 pad-s">
            <span class="posr txt-30 bold c-blue">3-Layer</span>
            <p class="posr txt-s c-semiwhite txtc topMg-s5">LFI Defense Confinement</p>
            <p class="posr txt-ms c-gray txtc">because yes</p>
        </div>
        <div class="flex fld acjc w20p minw15 pad-s">
            <span class="posr txt-30 bold c-orange">100%</span>
            <p class="posr txt-s c-semiwhite txtc topMg-s5">Prepared Statement Queries</p>
            <p class="posr txt-ms c-gray txtc">Like what I'd mostly do on other projects</p>
        </div>
    </div>
  </section>
  <p class="txt-ms c-gray">holy ai looking site ain't it? don't worry it ain't ai</p>
    
  <div id="alertcard">
      <div id="alertcontent">Alert Message Placeholder</div>
      <div id="borderanimate"></div>
  </div>
<script>
function popMsg(msg) {
    const card = document.getElementById('alertcard');
    const content = document.getElementById('alertcontent');
    if (!card || !content) return;
    
    content.innerText = msg;
    card.style.transform = 'translateX(0)';
    
    const border = document.getElementById('borderanimate');
    if (border) {
        border.style.animation = 'none';
        border.offsetHeight;
        border.style.animation = null;
    }
    
    setTimeout(() => {
        card.style.transform = 'translateX(100vw)';
    }, 4500);
}
</script>
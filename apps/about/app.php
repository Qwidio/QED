<?php
declare(strict_types=1);
$meta['title'] = 'About Q.E.D';
?>
<header class="flex blurbg bg-def-2 border-b">
    <nav class="w100p acjc">
        <a href="<?= e($base_path, ENT_QUOTES, 'UTF-8') ?>/" class="nav-button hover-white bora-s c-white">
            Home
        </a>
        <a href="#" class="nav-button hover-white bora-s c-lightpurple semibold">
            About
        </a>
        <a href="https://github.com/Qwidio/QED" target="_blank" rel="noopener noreferrer" class="nav-button hover-white bora-s c-semiwhite">
            GitHub
        </a>
    </nav>
</header>
<section class="fld acjc topMg-10 pad-b txtc posr">
    <div class="bg-half-purple border-purple bora-s pad-s-s pad-m-v c-lightpurple txt-s semibold bottomMg-s10">
        Kinda true but also exaggerated
    </div>
    <h1 class="txt-maintext bold txt-shad-1 topMg-s5 bottomMg-s10">
        Architectural Optimization in <span>Q.E.D.</span>
    </h1>
    <p class="txt-b c-semiwhite maxw70 sideMg topMg-s5 txtc">
        An overview of what makes Q.E.D differ from basic PHP and NextJS
    </p>
    <div class="pad-s-v txt-s semibold c-gray">
        idk why I'd even bother making this
    </div>
</section>
<section class="pad-n-s fld">
    <div class="w100p flex row wrap space-between gap10 topMg-s10">
        <div class="w30p minw20 bg-def-1 border-1 bora-n pad-n box-shad-black-1 posr">
            <div class="pad-s-s pad-m-v bgc-red bora-s wmaxc bottomMg-s10">
                <span class="txt-s bold c-white">Traditional PHP</span>
            </div>
            <h3 class="txt-b bold c-white">Spaghetti Routing</h3>
            <ul class="topMg-s10 pad-nl txt-s c-semiwhite flex fld gap10">
                <li>Manual <span>require 'header.php'</span> in every file</li>
                <li>Frequent "Headers already sent" redirect failures</li>
                <li>Global variable collisions and leaking database credentials</li>
                <li>Fragile relative asset paths (<span>../../image.png</span>)</li>
                <li>Verbose, error-prone <span>bind_param()</span> ceremonies</li>
            </ul>
        </div>
        <div class="w30p minw20 bg-def-1 border-blue bora-n pad-n box-shad-black-1 posr">
            <div class="pad-s-s pad-m-v bgc-blue bora-s wmaxc bottomMg-s10">
                <span class="txt-s bold c-white">Next.js</span>
            </div>
            <h3 class="txt-b bold c-white">Heavy Complexity</h3>
            <ul class="topMg-s10 pad-nl txt-s c-semiwhite flex fld gap10">
                <li>Massive <span>node_modules</span> and heavy container images</li>
                <li>Complex build pipelines (Webpack, Turbopack, Babel)</li>
                <li>Cold-start serverless latencies and hydration mismatches</li>
                <li>Incompatible with simple shared hosting or standard LAMP</li>
            </ul>
        </div>
        <div class="w30p minw20 bg-def-1 border-purple bora-n pad-n box-shad-black-1 posr">
            <div class="pad-s-s pad-m-v bgc-purple bora-s wmaxc bottomMg-s10">
                <span class="txt-s bold c-white">this project</span>
            </div>
            <h3 class="txt-b bold c-white">Best of Both Worlds(maybe)</h3>
            <ul class="topMg-s10 pad-nl txt-s c-lightpurple flex fld gap10">
                <li>Zero dependencies: No Node, npm, Composer, or external CLI</li>
                <li>Inside-out output buffering: dynamic metadata & redirects</li>
                <li>Global CSRF defense with co-located Server Actions</li>
                <li>Runs instantly on standard Apache/Nginx + PHP 8.2+</li>
            </ul>
        </div>
    </div>
</section>
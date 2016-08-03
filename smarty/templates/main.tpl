<!DOCTYPE html>
<html lang="{$t->get('iso_lang')}">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{$t->get('website_description')}">
    <meta name="keywords" content="{$t->get('website_keywords')}">
    <meta name="author" content="{$t->get('website_author')}">
    <link type="image/x-icon" href="{$settings->app_url}favicon.ico" rel="shortcut icon">

    <meta property="og:image" content="{$og_image}"/>
	<meta property="og:title" content="{$title} - {$t->get('app_title')}"/>
	<meta property="og:url" content="{$request_uri}"/>
    <meta property="og:app_id" content="{$settings->og_app_id}"/>
	<meta property="og:site_name" content="{$t->get('app_name')}"/>
	<meta property="og:type" content="website"/>
    <meta property="og:locale" content="{$t->get('locale')}" />
    <meta property="og:description" content="{$og_description}"/>
    {* <meta property="article:author" content="{$og_author}"/> *}
    <meta property="article:publisher" content="{$settings->og_publisher}"/>
    <meta name="twitter:site" content="{$t->get('twitter_handle')}" />
    <meta property="twitter:url" content="{$request_uri}"/>
    <meta property="twitter:image" content="{$og_image}"/>
    <meta property="twitter:description" content="{$og_description}"/>




    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="theme-color" content="#ffffff">



{*    <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.5/yeti/bootstrap.min.css" rel="stylesheet">*}
    <link href="{$settings->app_url}libs/bootstrap.min.css" rel="stylesheet">
{*   <link href="//cdn.bootcss.com/bootswatch/3.3.5/flatly/bootstrap.min.css" rel="stylesheet">*}
    <link href="{$settings->app_url}css/project.css" rel="stylesheet">
    <script src="{$settings->app_url}libs/jquery-1.11.3.min.js"></script>
    <script src="{$settings->app_url}libs/bootstrap.min.js"></script>
    <link href="{$settings->app_url}libs/font-awesome.min.css" rel="stylesheet">
{*    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>*}
{*    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>*}
{*    <script src="../jquery.stickytableheaders.min.js"></script>*}
    <title>{$title} | {$t->get('app_title')}</title>

    {block name=additionalHead}{/block}

    {block name=lastHead}{/block}
  </head>
  <body>
    {include "header.tpl"}

    <div class="container">
        <!-- Page Content -->
        {block name=body}{/block}
        <!-- /Page Content -->
        {include "footer.tpl"}
    </div>


    {block name=js}{/block}
    <!-- google analytics -->
    {* <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', "{$t->get(google_tracking_id)}"]);
      _gaq.push(['_trackPageview']);
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
    <!-- /google analytics -->

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter{$t->get(yandex_metrica_id)} = new Ya.Metrika({
                        id:{$t->get(yandex_metrica_id)},
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/{$t->get(yandex_metrica_id)}" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter --> *}

  </body>
</html>

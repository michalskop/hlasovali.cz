<!DOCTYPE html>
<html lang="{$text['lang']}">
  <head>
    <title>{$title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{$text['description']}">
    <meta name="keywords" content="{$text['keywords']}">
    <meta name="author" content="{$text['author']}">
    
    <meta property="og:image" content="{$text['og:image']}"/>
	<meta property="og:title" content="{$text['og:title']}"/>
	<meta property="og:url" content="{$text['og:url']}"/>
	<meta property="og:site_name" content="{$text['og:site_name']}"/>
	<meta property="og:type" content="website"/>
	
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/sandstone/bootstrap.min.css">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/page.css">
    <link rel="shortcut icon" title="Shortcut icon" href="http://www.zelenykruh.cz/favicon.ico" />
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type='text/javascript' src="http://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="/js/filter.js"></script>
{block name=additionalHead}{/block} 
  </head>
  <body>
{include "header.tpl"}
<div class="container">
    <div class="well opaque">
        <div class="form-group col-md-8" style="float:none">
            <div class="input-group">
                <input type="texst" placeholder="{$text['select_people_parties']}" class="form-control input-lg non-opaque typeahead" id="search-input">
                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
            </div>
        </div>
    </div>
</div>
{block name=body}{/block}  
{include "footer.tpl"}
{include "google_analytics.tpl"}
  </body>
</html>

<?php
$url = "http://berita.borneoclimate.info/feed";
$rss = simplexml_load_file($url);
if($rss)
{
//echo '<h1>'.$rss->channel->title.'</h1>';
//echo '<li>'.$rss->channel->pubDate.'</li>';
$items = $rss->channel->item;
//print_r($items);
echo '<ul>';
foreach($items as $item)
{
$title = $item->title;
$link = $item->link;
$published_on = $item->pubDate;
$description = $item->description;
$ns_dc = $item->children('http://purl.org/dc/elements/1.1/');

echo '<li style="list-style:none;">';
echo '<h3><a href="'.$link.'">'.$title.'</a></h3>';
echo '<div class="post_date"><span class="glyphicon glyphicon-user"></span> '.$ns_dc->creator.' <span class="glyphicon glyphicon-calendar"></span> '.date('d F Y - H:i',strtotime($published_on)).'</div>';
echo '</li>';
//echo '<p>'.$description.'</p>';
}
echo '</ul>';
}
?>

<?php 
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	>

    <channel>
	<title><?php echo $feed_name; ?></title>
	<link><?php echo $feed_url; ?></link>
    <description><?php echo $page_description; ?></description>
    <dc:language><?php echo $page_language; ?></dc:language>
    <dc:creator><?php echo $creator_email; ?></dc:creator>
    <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>

	<?php foreach($posts as $post): ?>
        <item>
          <title><?php echo xml_convert($post['PostTitle']); ?></title>
          <link><?php echo site_url('site/article/' . $post['PostSlug']) ?></link>
          <guid><?php echo site_url('site/article/' . $post['PostSlug']) ?></guid>
          <description>
              <![CDATA[
                  <?php /*echo character_limiter($post['PostContent'], 200);*/ echo  $post['PostContent'];?>
              ]]>
          </description>
          <content:encoded>
              <![CDATA[
                  <?=$post['PostContent']?>
              ]]>
          </content:encoded>
      	  <pubDate><?php echo $post['PostDate']; ?></pubDate>
        </item>
    <?php endforeach; ?>

	</channel>
</rss>



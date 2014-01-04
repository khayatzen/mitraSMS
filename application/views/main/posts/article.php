<script type="text/javascript">
$(document).ready(function() {

	$('#submit').click(function() {
		$.ajax({
		url: "<?php echo site_url('site/ajax_add_comment'); ?>",
		type: 'POST',
		data: $("#CommentPostForm").serialize(),
		success: function(msg) {
			alert(msg);
		}
		});
		return false;
	});
});

function popup(mylink, windowname)
  {
  if (! window.focus)return true;
  var href;
  if (typeof(mylink) == 'string')
	href=mylink;
  else
	href=mylink.href;
  window.open(href, windowname, 'width=400,height=200,scrollbars=yes');
  return false;
}

</script>
<br>
<div id="blog" class="blog">
	<div class="blogpost">
		<div class="header">
			<h2 class="title"><?php echo $post['PostTitle']; ?></h2>
			<div class="meta">
				Pada <?php echo simple_date($post['PostDate']); ?> | diposkan oleh: <?php echo $post['username']; ?>
			</div>
		</div>
		<div class="content">
			<?php echo htmlspecialchars_decode($post['PostContent']); ?>
                        <?php //echo '<b>coba</b>';?>
		</div>
	</div>	
	<div class="sharebutton">
	  <?php
		$url = site_url() . "/site/article/" . $post['PostSlug'];
		$text = $post['PostTitle'];
//        $short_url = $this->tinyurl->shorten($url);
          $short_url = $url;
	  ?>
	  <a href="<?=share_url('twitter',	array('url'=>$url, 'text'=>$text . " "  . $short_url, 'via'=>'borneoclimate'))?>" onClick="return popup(this, 'Share to Twitter')">Share to Twitter</a> | 
	  <a href="<?=share_url('facebook',	array('url'=>$url, 'text'=>$text . " "  . $short_url))?>" onClick="return popup(this, 'Share to Facebook')">Share to Facebook</a>
	</div>
	<?php if (!empty($comments)) { ?>
	<hr>
	<div class="comments">
		<div class="header">
			<?php foreach ($comments as $key => $comment) { ?>
				<div class="comment">
					<p><?php echo $comment['CommentContent']; ?><br>Oleh : <?php echo $comment['CommentAuthor']; ?>, <?php echo date("d-m-Y H:i", strtotime($comment['CommentDate'])); ?></p>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>
	<hr>
	<div class="commentform">
<h2>Tinggalkan Komentar</h2>
		<form method="post" id="CommentPostForm">
			<input type="hidden" name="postID" value="<?php echo $post['postID']; ?>">
			<?php if (!$this->tank_auth->is_logged_in()) { ?>
				<label for="CommentAuthor">Nama</label><br/>
 <input type="text" name="CommentAuthor" id="CommentAuthor" style="width: 250px;"><br>
				<label for="CommentAuthorEmail">Email</label><br/><input type="text" name="CommentAuthorEmail" style="width: 250px;">
				<br><br>
			<?php } else { ?>
				<input type="hidden" name="CommentAuthor" value="<?php echo $post['username']; ?>">
				<input type="hidden" name="CommentAuthorEmail" value="<?php echo $post['email']; ?>">
				Loggedin as <?php echo $post['username']; ?>, <br>
			<?php } ?>
<label for="CommentContent">Isi Komentar</label><br/>
			<textarea name="CommentContent" rows="7" cols="50"></textarea>
		<br><br>
			<input type="submit" class="button" value="Comment" id="submit" />
		</form>
	</div>
</div>

<?php $this->load->view('front/widget/sidebar');?>
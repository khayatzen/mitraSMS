<div class="title_page"><h2>Komentar</h2></div>
<div class="admin_post_container">
    <?php echo form_open('admin/blog_Posts/delete');?>


<table class="grid_display" cellpadding="0" cellspacing="0">
    <tr>        
		<th>#</th>
        <th>Tanggal</th>
        <th>Author</th>
        <th>Komentar</th>
        <th>Pada Tulisan</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($comments as $comment) { ?>

        <tr>
            <td><?php echo form_checkbox('postID[]',$comment['postID']); ?></td>
            <td><?php echo $comment['CommentDate']; ?></td> 
            <td><?php if (!empty($comment['CommentAuthor'])) {
					echo $comment['CommentAuthor'];
				}else if(!empty($comment['username'])){
					echo $comment['username'];
				}else{echo 'Anonim';}
                                ?></td>
            <td><?php echo $comment['CommentContent']; ?></td>
            <td><a target="_blank" href="<?=site_url().'/site/article/'.$comment['PostSlug']?>"><?php echo $comment['PostTitle']; ?></a></td>
            <td>
				<?php 
					if ($comment['CommentApproved'] == 'unapprove') {
						echo "<a href='" . site_url() . "/posts/approve_comment/ " . $comment['commentID'] . "'>Approve</a>";
					} else {
						echo "Approved";
					}
				?>
        </tr>
 
<?php } ?>
</table>
    <div id="paging">
        <?php echo $pagination; ?>
    </div>

<br>
<input type="submit" class="button" value="delete" onclick="return confirm('Anda yakin akan menghapus komentar ini ?');">

<?php echo form_close();?>
</div>


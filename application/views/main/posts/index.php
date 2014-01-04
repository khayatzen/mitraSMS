<div class="title_page"><h2>Tulisan</h2></div>
<div class="admin_post_container">    
    <span class="button"><?= anchor('posts/add','Tambah baru')?></span>
    <?php //print_r($posts);?>
    <?php echo form_open('posts/delete_post');?>


<table class="grid_display" cellpadding="0" cellspacing="0">
    <tr>
		<th></th>    
        <th>Judul</th>
        <th>Penulis</th>
        <th>Kategori</th>
        <th>Komentar</th>
        <th>Tanggal</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($posts as $post) { ?>

        <tr>
            <td><?php echo form_checkbox('postID[]',$post['postID']); ?></td>
            <td>
                <a href="<?php echo site_url().'/posts/update_post/'.$post['postID'];?>" >
                <?php                    
                    echo '<strong>'.$post['PostTitle'].'</strong>';                    
		?>
                </a>
                <?php
                if (!empty($post['PostStatus'])) {
                    echo "<span style='color:#D64545;;padding:3px;'>[ " . $post['PostStatus'] . " ]</span>";
                }
                ?>
                </td> 
            <td class="smaller"><?php echo $post['username']; ?></td>
            <td class="smaller"><?php echo $post['CategoryName']; ?></td>
            <td class="smaller"><?php echo $post['CommentCount']; ?></td>
            <td class="smaller"><?php echo $post['PostDate']; ?></td>           
            <td><a href="<?php echo site_url().'/posts/update_post/'.$post['postID'];?>" >Edit</a>
        </tr>
 
<?php } ?>
</table>
    <div id="paging">
        <?php echo $pagination; ?>
    </div>

<br>
<input type="submit" value="delete" onclick="return confirm('Anda yakin akan menghapus tulisan ini ?');">

<?php echo form_close();?>
</div>


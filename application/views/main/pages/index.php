<div class="title_page"><h2>Halaman</h2></div>
<div class="admin_post_container">    
    <span class="button"><?= anchor('pages/add','Tambah baru')?></span>
    <?php //print_r($pages);?>
    <?php echo form_open('pages/delete_page');?>


<table class="grid_display" cellpadding="0" cellspacing="0">
    <tr>
		<th></th>    
        <th>Judul</th>
        <th>Penulis</th>
        <th>Tanggal</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($pages as $page) { ?>

        <tr>
            <td><?php echo form_checkbox('postID[]',$page['postID']); ?></td>
            <td><?php 
					echo $page['PostTitle'];
					if (!empty($page['PostStatus'])) {
						echo "<span> (" . $page['PostStatus'] . ")</span>";
					}
			?></td> 
            <td><?php echo $page['username']; ?></td>
            <td><?php echo $page['PostDate']; ?></td>           
            <td><a href="<?php echo site_url().'/pages/update_page/'.$page['postID'];?>" >Edit</a>
        </tr>
 
<?php } ?>
</table>
<?php echo $pagination; ?>
<br>
<input type="submit" value="delete" onclick="return confirm('Anda yakin akan menghapus tulisan ini ?');">

<?php echo form_close();?>
</div>

 

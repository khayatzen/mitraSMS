<div class="title_page"><h2>Kategori</h2></div>
<div class="admin_post_container">    
    <span class="button"><?= anchor('posts/add_category','Tambah baru', "id='add_category'")?></span>
<?php echo form_open('posts/delete_category');?>
<table class="grid_display" cellpadding="0" cellspacing="0">
    <tr>
		<th>ID</th>
        <th>Nama</th>
        <th>Diskripsi</th>
		<th>Aksi</th>
    </tr>
    <?php foreach ($categories->result_array() as $category) { ?>

        <tr>
            <td><?php echo form_checkbox('categoryID[]', $category['categoryID']); ?></td>
            <td><?php echo $category['CategoryName']; ?></td> 
            <td><?php echo $category['CategoryDescription']; ?></td>         
            <td><a href="<?php echo site_url().'/posts/update_category/'.$category['categoryID'];?>" >Edit</a>
        </tr>
 
<?php } ?>
</table>
<?php echo $pagination; ?>
<br>
<input type="submit" class="button" value="delete" onclick="return confirm('Anda yakin akan menghapus kategori ini ?');">
<?php echo form_close();?>
</div>


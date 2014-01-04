<script language="javascript" src="<?php echo $this->config->item('js_path');?>jgcharts/jgcharts.js"></script>
<script type="text/javascript">
	var microblog = new jGCharts.Api();
    jQuery('<img>')
    .attr('src', api.make({data : [[153, 60], [113, 70], [120, 80]], 
                           axis_labels : ['2008','2007','2006'], 
                           legend : ['SMS', 'WEB']}))
    .appendTo("#microblog");
</script>
<div class="title_page"><h2>Statistik</h2></div>
<div class="admin_post_container">    
<h3>Microblog Stats<h3>
<div id="microblog"></div>
<br>
</div>

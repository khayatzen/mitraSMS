<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
	  	<!-- Brand and toggle get grouped for better mobile display -->
	  <div class="navbar-header">
	    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	      <span class="sr-only">Toggle navigation</span>
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	    </button>
	    <a class="navbar-brand" href="<?php echo site_url();?>"><?= $this->config->item('site_title');?></a>
	  </div>

	  <!-- Collect the nav links, forms, and other content for toggling -->
	  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	    <ul class="nav navbar-nav navbar-right">
	    <!--		
	      <li class="dropdown">
	        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Kabar Kampung <b class="caret"></b></a>
	        <ul class="dropdown-menu">
	          <li><a href="http://berita.borneoclimate.info/kategori/kegiatan/">Kagiatan</a></li>
	          <li><a href="http://berita.borneoclimate.info/kategori/feature/">Feature</a></li>
	          <li><a href="http://berita.borneoclimate.info/kategori/analisis/">Analisis</a></li>
	          <li><a href="http://berita.borneoclimate.info/kategori/suara-warga/">Suara Warga</a></li>
	          <li class="divider"></li>
	          <li><a href="http://berita.borneoclimate.info">Kabar Kampung</a></li>
	        </ul>
	      </li>
	      <li class="dropdown">
	        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Galeri <b class="caret"></b></a>
	        <ul class="dropdown-menu">
	          <li><a href="http://berita.borneoclimate.info/kategori/audio/">Audio</a></li>
	          <li><a href="http://berita.borneoclimate.info/kategori/video/">Video</a></li>	          
	        </ul>
	      </li>
	  	-->
	      <li><?php echo anchor('microblog/microblogging','SMS Microblogging')?></li>
	      <li><?=anchor('microblog/stream_map','Peta SMS')?></li>
	    <!--  
	      <li><a href="http://berita.borneoclimate.info/kontributor/">Kontributor</a></li>
	      <li><?=anchor('site/page/tentang-kami','Tentang Kami')?></li>
		-->
	    </ul>
	    
	  </div><!-- /.navbar-collapse -->
  </div>
</nav>
<link rel="stylesheet" type="text/css" src="<?=$this->config->item('css_path')?>front/map.css"/>
<link rel="stylesheet" href="<?= $this->config->item('css_path')?>front/microblog.widget.css" type="text/css" />
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="<?=$this->config->item('js_path')?>front/infobox.js"></script>
<?php $this->load->view('js_init/microblog/stream_map');?>
</div>

<div id="wrapper">
    <div id="sidebar-wrapper" class="col-xs-12 col-md-3">
        <div id="sidebar-column">                        
            <h4>Tampil/Sembunyikan</h4>
            <div class="well">
                <ul class="map_legenda" style="list-style:none;margin:0;">
                    <li>
                        <input type="checkbox" name="poly_moratorium" id="poly_moratorium" value="1" checked="checked"/>
                        <label for="poly_moratorium">Wilayah Moratorium</label>
                    </li>
                    <li>
                        <input type="checkbox" name="poly_desa" id="poly_desa" value="1" checked="checked"/>
                        <label for="poly_desa">Wilayah Desa</label>
                    </li>
                    <li>
                        <input type="checkbox" name="mark_desa" id="mark_desa" value="1"/>
                        <label for="mark_desa">Titik Desa</label>
                    </li>
                </ul>
            </div>
            <h4>Daftar Desa</h4>
            <div class="well">
                <input class="form-control input-sm" type="text" placeholder="Cari desa . . ." value="" name="search_query" id="village_search_input"/>   
                <div id="village_list_container">
                    <div class="loading"></div>                               
                    <div id="village_list"></div> 
                </div>
                <div id="village_pagination"></div>
            </div>                                                    
        </div>
    </div>
    <div id="main-wrapper" class="col-xs-12 col-md-9 pull-right">
        <div id="main-column">
          <div id="mainMap"></div>              
        </div>
    </div>
</div>
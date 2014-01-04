<link href="<?= base_url() . $this->config->item('multimedia_css', 'multimedia'); ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url() . $this->config->item('jmedia_styles', 'multimedia'); ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url() . $this->config->item('jmedia_player_controls', 'multimedia'); ?>" rel="stylesheet" type="text/css" />

<script language="javascript" type="text/javascript" src="<?= base_url() . $this->config->item('jmedia_utils_slider', 'multimedia') ?>"></script>
<script language="javascript" type="text/javascript" src="<?= base_url() . $this->config->item('jmedia_utils_activity', 'multimedia') ?>"></script>
<script language="javascript" type="text/javascript" src="<?= base_url() . $this->config->item('jmedia_utils_controls', 'multimedia') ?>"></script>
<script language="javascript" type="text/javascript" src="<?= base_url() . $this->config->item('jmedia_packages_mmfull', 'multimedia') ?>"></script>
<script language="javascript" type="text/javascript" src="<?= base_url() . $this->config->item('jmedia_plugins_fullwindow', 'multimedia') ?>"></script>

<div id="mmg_media_wrapper" class="media_wrapper">
    <ul></ul>
    <div class="more" style="display:none">
        <a id="mmg_more" href="#">Load More...</a>
    </div>
</div>
<div id="mmg_overlay" class="overlay"></div>
    <div id="mmg_preview" class="preview">
        <div id="mmg_preview_loading" class="preview_loading"></div>
            <div class="preview_wrap"></div>
                <div id="mmg_nav" class="nav">
                    <a href="#" class="prev"></a>
                    <a href="#" class="next"></a>
                </div>
    </div>
<div id="mmg_description" class="description"></div>
<script src="<?php echo base_url() . $this->config->item('jquery_json', 'multimedia'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url() . $this->config->item('jquery_viewport', 'multimedia'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url() . $this->config->item('jquery_multimedia', 'multimedia'); ?>" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="<?=$this->config->item('css_path')?>front/microblog.css"/>

<?php $this->load->view('js_init/microblog/microblogging');?>
<?php $this->load->view('front/widget/microblog_conversation');?>

<div id="microblogging">
    <!-- Tabs Container -->
    <div id="microblog-tabs">
        <!-- Tabs menu -->
        <ul>
            <li><a href="#broadcast-stream">Tahukah Anda?</a></li>
            <li><a href="#conversation-stream">Pendapat Warga</a></li>
        </ul>
        <!-- Tabs Content -->
        <div id="broadcast-stream">
            <div id="broadcast_message_container">                
                <div id="broadcast_message_stream"></div>
                <div class="loading"></div>
                <button id="more_broadcast_stream" type="button" data-loading-text="Memuat..." class="btn btn-primary btn-block more_stream">
                  Sebelumnya
                </button>
                <!-- <input type="button" class="more_stream" name="more_stream" id="more_broadcast_stream" value="Sebelumnya"/>-->
            </div>
        </div>
        <div id="conversation-stream">
            <div id="microblog_conversation_container">                
                <div id="microblog_conversation_stream"></div>
                <div class="loading"></div>
                <button id="more_conversation_stream" type="button" data-loading-text="Memuat..." class="btn btn-primary btn-block more_stream">
                  Sebelumnya
                </button>
                <!-- <input type="button" class="more_stream" name="more_stream" id="more_conversation_stream" value="Sebelumnya"/> -->
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('front/widget/sidebar');?>

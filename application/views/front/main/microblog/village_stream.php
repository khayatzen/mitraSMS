<div id="conversation-stream" style="width:400px;height:inherit;background-color: #fff;padding:10px;">
<div style="border:1px solid #F2F2F2; background-color:#E8E8E8;padding:5px 10px;margin-bottom:10px;">    
<strong>Desa/Kelurahan</strong> : <?=$village_name?><br/>
<strong>Kecamatan</strong> : <?=$subdistrict_name?><br/>
<strong>Kabupaten</strong> : <?=$district_name?><br/>
</div>
<h2>Jumlah SMS : <?=$rows?></h2>
    <div id="village_conversation_container">
<!--        <div class="loading"></div>
        <div class="customScrollBox" style="height:300px;">
        <div class="horWrapper">
        <div class="container" style="height:300px;">
        <div class="content village_message_container">-->
        <div id="village_conversation_stream" style="border:1px solid #F2F2F2;">
            <?php //print_r($messages);?>
            <?php if(count($messages) > 0):?>
            <?php foreach($messages AS $message):if(isset($message['globaldate'])):?>
            <div id="stream_item" stream_date="<?=$message['globaldate']?>" style="padding:0 10px;">
                <div>
                    <strong><u><?=$message['number']?></u></strong><br/>
                    <?=$message['TextDecoded']?><br/>
                    <div class="stream_date" date="<?=$message['globaldate']?>"><?=$message['nicedate']?> | <?=$message['globaldate']?></div>                    
                </div>                
            </div>
            <?php endif;endforeach;?>
            <?php endif;?>
        </div>
<!--        <div class="dragger_container" style="height:300px;">
        <div class="dragger"></div>
        </div>
        </div>                     
        </div>            -->
<!--        <input type="button" class="more_stream" name="more_stream" id="more_conversation_stream" value="Sebelumnya"/>-->
<!--        </div>
        </div>-->
    </div>    
</div> 

<?php
/**
 * Kalkun
 * An open source web based SMS Management
 *
 * @package		Kalkun
 * @author		Kalkun Dev Team
 * @license		http://kalkun.sourceforge.net/license.php
 * @link		http://kalkun.sourceforge.net
 */

// ------------------------------------------------------------------------

/**
 * Microblog_model Class
 *
 * Handle all microblog database activity
 *
 * @package	Kalkun
 * @subpackage	Microblog
 * @category	Models
 */
class Microblog_model extends Model {

  	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function Microblog_model()
	{
		parent::Model();
	}
        function getFolders($id_user=1){
            $this->db->from('user_folders');
            $this->db->where('id_user',$id_user);
            return $this->db->get();
        }
        function getFolderName($id_folder){
            $this->db->select('name');
            $this->db->where('id_folder',$id_folder);
            return $this->db->get('user_folders')->row('name');
        }
        function isResponseMessage($id_inbox){
            $this->db->from('folder_message_receive');
            $this->db->where('id_inbox',$id_inbox);
            $row = $this->db->get()->num_rows();
            if($row>0)return true;
            return false;
        }
        function existsReply($id_inbox){
            //$this->db->from('folder_message_sent');
            $this->db->where('replayed_inbox_id',$id_inbox);
            $row = $this->db->get('folder_message_sent')->num_rows();
            if($row>0)return true;
            return false;
        }
        function getSentitemsID($id_inbox){
            $this->db->select('id_sentitems');
            $this->db->where('replayed_inbox_id',$id_inbox);
            return $this->db->get('folder_message_sent')->row('id_sentitems');
        }
        function getFolderMessageSentID($id_inbox){
            $this->db->select('ID');
            $this->db->where('replayed_inbox_id',$id_inbox);
            return $this->db->get('folder_message_sent')->row('ID');
        }
        function getInboxID($folder_message_sent_id){
            $this->db->select('id_inbox');
            $this->db->where('folder_message_sent_id',$folder_message_sent_id);
            return $this->db->get('folder_message_receive')->row('id_inbox');
        }
        function messageReplyed($id_sentitems){
            $this->db->where('folder_message_sent_id',$id_sentitems);
            $rows =  $this->db->get('folder_message_receive')->num_rows();
            if($rows>0)return true;
            return false;
        }
}
?>

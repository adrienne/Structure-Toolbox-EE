<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * Structure Toolbox Plugin
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Plugin
 * @author	 	  André Elvan
 * @link		    http://www.andreelvan.net/
 */

$plugin_info = array(
	'pi_name'		=> 'Structure Toolbox',
	'pi_version'	=> '1.0',
	'pi_author'		=> 'André Elvan',
	'pi_author_url'	=> 'http://www.andreelvan.net/',
	'pi_description'=> 'Helper functions for Structure',
	'pi_usage'		=> Structure_toolbox::usage()
);


class Structure_toolbox {

	public $return_data;
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
	}

  
  /**
   * Returns ID's of the parents of the supplied ID's
   * 
   * @return string  A pipe-separated list of ID's to parents 
   */
  function get_parent_ids() {
		$ids = $this->EE->TMPL->fetch_param('ids', '');
    if ($ids=='') return '';

    $arr_ids = array_filter(explode('|', $ids));
    if (count($arr_ids)<1) return '';

    $parent_ids = array();
    $qry = $this->EE->db->select('parent_id')->where_in('entry_id', $arr_ids)->get('exp_structure');
    foreach ($qry->result() as $row) {
      $parent_ids[] = $row->parent_id;
    }

    if (count($parent_ids)<1) return '';

		$this->return_data = implode('|', $parent_ids);
		return $this->return_data;
	}

  
  /**
   * Returns ID's of the top-most level
   * 
   * @return string  A pipe-separated list of ID's to entries at the top level 
   */
  function get_top_level_ids() {
		$show_hidden = $this->EE->TMPL->fetch_param('show_hidden', 'no') == 'yes' ? true : false;
    
    $where_arr = array('parent_id' => 0, 'entry_id !=' => 0);
    
    if (!$show_hidden) {
      $where_arr['hidden'] = 'n';
    }  

    $ids = array();
    $qry = $this->EE->db->select('entry_id')->where($where_arr)->get('exp_structure');
    
    foreach ($qry->result() as $row) {
      $ids[] = $row->entry_id;
    }

    if (count($ids)<1) return '';

		$this->return_data = implode('|', $ids);
		return $this->return_data;
	}

    
  
  
	// ----------------------------------------------------------------
  
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>

 
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.structure_toolbox.php */
/* Location: /system/expressionengine/third_party/structure_toolbox/pi.structure_toolbox.php */
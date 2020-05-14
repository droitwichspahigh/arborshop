<?php
/*
 * Mysql Ajax Table Editor
 *
 * Copyright (c) 2014 Chris Kitchen <info@mysqlajaxtableeditor.com>
 * All rights reserved.
 *
 * See COPYING file for license information.
 *
 * Download the latest version from
 * http://www.mysqlajaxtableeditor.com
 */
require('../../../bin/auth.php');

require_once('DBC.php');
require_once('Common.php');
require_once('php/lang/LangVars-en.php');
require_once('php/AjaxTableEditor.php');
class CkEditor extends Common
{
	protected $Editor;
	protected $instanceName = 'mate1_';
	
	protected function setHeaderFiles()
	{
		$this->headerFiles[] = '<script type="text/javascript" src="//cdn.jsdelivr.net/ckeditor/4.0.1/ckeditor.js"></script>';
	}
	
	protected function displayHtml()
	{
		$html = '
			
			<br />
			
			<div class="mateAjaxLoaderDiv"><div id="ajaxLoader1"><img src="images/ajax_loader.gif" alt="Loading..." /></div></div>
			
			<br /><br />
			
			<div id="'.$this->instanceName.'information">
			</div>
			
			<div id="'.$this->instanceName.'titleLayer" class="mateTitleDiv">
			</div>
			
			<div id="'.$this->instanceName.'tableLayer" class="mateTableDiv">
			</div>
			
			<div id="'.$this->instanceName.'recordLayer" class="mateRecordLayerDiv">
			</div>		
			
			<div id="'.$this->instanceName.'searchButtonsLayer" class="mateSearchBtnsDiv">
			</div>';
			
		echo $html;
		
		// Set default session configuration variables here
		$defaultSessionData['orderByColumn'] = 'first_name';

		$defaultSessionData = base64_encode($this->Editor->jsonEncode($defaultSessionData));
		
		$javascript = '	
			<script type="text/javascript">
				var ' . $this->instanceName . ' = new mate("' . $this->instanceName . '");
				' . $this->instanceName . '.setAjaxInfo({url: "' . $_SERVER['PHP_SELF'] . '", history: true});
				' . $this->instanceName . '.init("' . $defaultSessionData . '");
				
				function addCkEditor(id)
				{
					if(CKEDITOR.instances[id])
					{
					   CKEDITOR.remove(CKEDITOR.instances[id]);
					}
					CKEDITOR.replace(id);
				}
				
			</script>';
		echo $javascript;
	}

	protected function initiateEditor()
	{
		$tableColumns['item_id'] = array(
			'display_text' => 'ID', 
			'perms' => 'TVQSXO'
		);
		$tableColumns['name'] = array(
			'display_text' => 'Item Name', 
		    'req' => true, 
		    'perms' => 'EVCTAXQSHO'
		);
		$tableColumns['description'] = array(
		    'display_text' => 'Description',
		    'perms' => 'EVCTAXQSHO',
		    'textarea' => array('rows' => 8, 'cols' => 25),
		    'sub_str' => 30
		);
		$tableColumns['imgpath'] = array(
			'display_text' => 'Name of image file', 
			'perms' => 'EVCTAXQSHO'
		);
		$tableColumns['price'] = array(
			'display_text' => 'Price', 
			'perms' => 'EVCTAXQSHO',
			'req' => true		    
		);
		$tableColumns['enabled'] = array(
		    'display_text' => 'Enabled for purchase',
		    'perms' => 'EVCTAXQSHO',
            'checkbox' => array(
                'checked_value' => '1',
                'un_checked_value' => '0'
            ),
		    'default' => '1',
			'req' => true
		);
		$tableColumns['allowed_yeargroups'] = array(
			'display_text' => 'Allowed year groups', 
			'req' => true, 
			'perms' => 'EVCTAXQSHO', 
/*			'display_mask' => 'date_format(hire_date,"%d %M %Y")', 
			'order_mask' => 'date_format(hire_date,"%Y %m %d")',
			'calendar' => array(
				'js_format' => 'dd MM yy', 
				'options' => array('showButtonPanel' => true)
			),
			'col_header_info' => 'style="width: 250px;"'
*/
		); 
		
		$tableName = 'items';
		$primaryCol = 'item_id';
		$errorFun = array(&$this,'logError');
		$permissions = 'EAVDQCSXHOI';
		
		$this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
		$this->Editor->setConfig('tableInfo','cellpadding="1" cellspacing="1" align="center" width="1100" class="mateTable"');
		$this->Editor->setConfig('orderByColumn','first_name');
		$this->Editor->setConfig('tableTitle','ArborShop Stock Editor');
		$this->Editor->setConfig('addRowTitle','Add Stock Item');
		$this->Editor->setConfig('editRowTitle','Edit Stock Item');
		// $this->Editor->setConfig('addScreenFun',array(&$this,'addCkEditor'));
		// $this->Editor->setConfig('editScreenFun',array(&$this,'addCkEditor'));
		$this->Editor->setConfig('instanceName',$this->instanceName);
		$this->Editor->setConfig('persistentAddForm',false);
		$this->Editor->setConfig('paginationLinks',true);
	}
	
	function __construct()
	{
		if (!isset($_SESSION)) {
			session_start();
		}
		ob_start();
		$this->initiateEditor();
		if(isset($_POST['json']))
		{
			if(ini_get('magic_quotes_gpc'))
			{
				$_POST['json'] = stripslashes($_POST['json']);
			}
			$this->Editor->data = $this->Editor->jsonDecode($_POST['json'],true);
			$this->Editor->setDefaults();
			$this->Editor->main();
		}
		else if(isset($_GET['mate_export']))
		{
			$this->Editor->data['sessionData'] = $_GET['session_data'];
			$this->Editor->setDefaults();
			ob_end_clean();
			header('Cache-Control: no-cache, must-revalidate');
			header('Pragma: no-cache');
			header('Content-type: application/x-msexcel');
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$this->Editor->tableName.'.csv"');
			// Add utf-8 signature for windows/excel
			echo chr(0xEF).chr(0xBB).chr(0xBF);
			echo $this->Editor->exportInfo();
			exit();
		}
		else
		{
			$this->setHeaderFiles();
			$this->displayHeaderHtml();
			$this->displayHtml();
			$this->displayFooterHtml();
		}
	}
}
new CkEditor();
?>

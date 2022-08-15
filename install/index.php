<?
IncludeModuleLangFile(__FILE__);
Class tools_size extends CModule{
	var $MODULE_ID = "tools.size";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	
	function tools_size(){
		$arModuleVersion = array();
		
		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");
		
		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)){
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}
		$this->PARTNER_NAME = GetMessage("TLS_PARTNER_NAME");
		$this->MODULE_NAME = GetMessage("TLS_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("TLS_MODULE_DESCRIPTION");
        $this->PARTNER_URI="https://asdaff.github.io/";
	}
	
	function InstallFiles($arParams = array()){
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/tools.size/install/admin",
		             $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true, true);
		return true;
	}
	
	function UnInstallFiles(){
		unlink($_SERVER[DOCUMENT_ROOT]."/bitrix/admin/size.php");
		return true;
	}
	
	function DoInstall(){
		global $DOCUMENT_ROOT, $APPLICATION;
		$this->InstallFiles();
		RegisterModule("tools.size");
	}
	
	function DoUninstall(){
		global $DOCUMENT_ROOT, $APPLICATION;
		$this->UnInstallFiles();
		UnRegisterModule("tools.size");
	}
}
?>
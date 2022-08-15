<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

IncludeModuleLangFile(__FILE__);

$APPLICATION->SetTitle(GetMessage("TLS_SET_TITLE"));
?>


<?
function dirToArray($dir) {   
   $result = array(); 
   $cdir = scandir($dir); 
   foreach ($cdir as $key => $value) { 
      if (!in_array($value,array(".",".."))) { 
         if (is_dir($dir.DIRECTORY_SEPARATOR.$value)) $result["isfolder_".$value] = dirToArray($dir.DIRECTORY_SEPARATOR.$value);   
         else $result[] = array(
                                "NAME"=>$value,
                                "SIZE"=>filesize($dir.DIRECTORY_SEPARATOR.$value)
                                ); 
      } 
   } 
   return $result; 
} 
function ConvertBytes($number){
        $len = strlen ($number);
        if ($len < 4){ return sprintf("<span class='size silver'>%d b</span>", $number); }
        if ($len>= 4 && $len <=6){ return sprintf("<span class='size green'>%0.2f Kb</span>", $number/1024); }
        if ($len>= 7 && $len <=9){ return sprintf("<span class='size orange'>%0.2f Mb</span>", $number/1024/1024); }
        return sprintf("<span class='size red'>%0.2f Gb</span>", $number/1024/1024/1024);
    }
function pr($a){
    print "<pre>"; print_r($a); print "</pre>";
}
function recursive_print($arDir){
    ksort($arDir);
    print "<ul>";
    foreach($arDir as $key=>$value){
        if($key=="size_folder") continue;
        $folder=(substr($key,0,9)=="isfolder_")?true:false;
        $class=($folder)?"folder":"file";
        if($folder){
            $folder_name=substr($key,9,strlen($key));
            $folder_size=ConvertBytes($value[size_folder]);
            print "<li class='folder'><div class='folder_name'>$folder_name $folder_size</div>";
            recursive_print($value);
            print "</li>";
        }else{
            $size=ConvertBytes($value[SIZE]);
            print "<li class='file'>$value[NAME] $size</li>";
        }
    }
    print "</ul>";
}
function normalize_arDir(&$arDir){
    $sum_size_dir=0;
    foreach($arDir as $key=>$value){ 
        if(substr($key,0,9)=="isfolder_"){
            normalize_arDir($value);
            $arDir[$key]=$value;
            $sum_size_dir+=$value[size_folder];
        } else{
            $sum_size_dir+=$value[SIZE];
        }    
    }
    $arDir[size_folder]=$sum_size_dir;
}
    $path=$_SERVER[DOCUMENT_ROOT];
    $arDir=dirToArray($path);
    normalize_arDir($arDir);
    ?>
    <style>
        .tools_size{
            font-size:14px;
        }
        .tools_size ul ul{
            display:none;
            margin:0px;
            padding:0px;
            margin-left:15px;
        }
        .tools_size li{
            list-style-type: none;  
            padding-left:20px;
            font-size:14px 
        }
        .tools_size li.file:before{
            content: "\25ba";
            position: absolute;
            margin-left: -15px;
            font-size: 8px;
            margin-top: 5px;
        }
        .tools_size li.folder:before{
             content: "\2752";
             position:absolute;
             margin-left:-20px;
             color: #4c00fd;
        }
        .tools_size li.folder{
             cursor:pointer;
        }
        .tools_size li.folder.open>ul{
            display:block;
        }
        .tools_size .size{
            font-size:12px;
        }
        .tools_size .size.silver{
            color:silver;
        }
        .tools_size .size.green{
            color: #32CF10;
        }
        .tools_size .size.orange{
            color: #FAA912;
        }
        .tools_size .size.red{
            color:red;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $("li.folder").click(function(e){
                e.stopPropagation();
                $(this).toggleClass("open");
            });
        });
    </script>
    <?
    print "<div class='tools_size'>";
        print "<div class='all_size'>".GetMessage("TLS_ALL_SIZE")." ".ConvertBytes($arDir[size_folder])."<br>(".GetMessage("TLS_INFO").")</div>";
        recursive_print($arDir);
    print "</div>"
?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
<?php

function xoops_module_update_tad_lunch2(&$module, $old_version) {
    GLOBAL $xoopsDB;

    mk_dir(XOOPS_ROOT_PATH."/uploads/tad_lunch2");
    mk_dir(XOOPS_ROOT_PATH."/uploads/tad_lunch2/thumbs");
    if(!chk_chk1()) go_update1();
    if(!chk_chk2()) go_update2();

    return true;
}

function chk_chk1(){
  global $xoopsDB;
  $sql="select count(*) from ".$xoopsDB->prefix("tad_lunch2_files_center");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}


function go_update1(){
  global $xoopsDB;
  $sql="CREATE TABLE `".$xoopsDB->prefix("tad_lunch2_files_center")."` (
    `files_sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '檔案流水號',
    `col_name` varchar(255) NOT NULL default '' COMMENT '欄位名稱',
    `col_sn` smallint(5) unsigned NOT NULL default 0 COMMENT '欄位編號',
    `sort` smallint(5) unsigned NOT NULL default 0 COMMENT '排序',
    `kind` enum('img','file') NOT NULL default 'img' COMMENT '檔案種類',
    `file_name` varchar(255) NOT NULL default '' COMMENT '檔案名稱',
    `file_type` varchar(255) NOT NULL default '' COMMENT '檔案類型',
    `file_size` int(10) unsigned NOT NULL default 0 COMMENT '檔案大小',
    `description` text NOT NULL COMMENT '檔案說明',
    `counter` mediumint(8) unsigned NOT NULL default 0 COMMENT '下載人次',
    `original_filename` varchar(255) NOT NULL default '' COMMENT '檔案名稱',
    `hash_filename` varchar(255) NOT NULL default '' COMMENT '加密檔案名稱',
    `sub_dir` varchar(255) NOT NULL default '' COMMENT '檔案子路徑',
    PRIMARY KEY (`files_sn`)
) ENGINE=MyISAM";
  $xoopsDB->queryF($sql);
}


function chk_chk2(){
  global $xoopsDB;
  $sql="SHOW INDEX FROM `".$xoopsDB->prefix("tad_lunch2_data")."` where Key_name='date_target'";
  $result=$xoopsDB->query($sql);
  $num=$xoopsDB->getAffectedRows();
  if(empty($num)) return false;
  return true;
}


function go_update2(){
  global $xoopsDB;
  $sql="ALTER TABLE `".$xoopsDB->prefix("tad_lunch2_data")."` ADD UNIQUE `date_target` ( `lunch_target` , `lunch_date` ) ";
  $xoopsDB->queryF($sql);
}





//建立目錄
function mk_dir($dir=""){
    //若無目錄名稱秀出警告訊息
    if(empty($dir))return;
    //若目錄不存在的話建立目錄
    if (!is_dir($dir)) {
        umask(000);
        //若建立失敗秀出警告訊息
        mkdir($dir, 0777);
    }
}

//拷貝目錄
function full_copy( $source="", $target=""){
  if ( is_dir( $source ) ){
    @mkdir( $target );
    $d = dir( $source );
    while ( FALSE !== ( $entry = $d->read() ) ){
      if ( $entry == '.' || $entry == '..' ){
        continue;
      }

      $Entry = $source . '/' . $entry;
      if ( is_dir( $Entry ) ) {
        full_copy( $Entry, $target . '/' . $entry );
        continue;
      }
      copy( $Entry, $target . '/' . $entry );
    }
    $d->close();
  }else{
    copy( $source, $target );
  }
}


function rename_win($oldfile,$newfile) {
   if (!rename($oldfile,$newfile)) {
      if (copy ($oldfile,$newfile)) {
         unlink($oldfile);
         return TRUE;
      }
      return FALSE;
   }
   return TRUE;
}


function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
                unlink($dirname."/".$file);
            else
                delete_directory($dirname.'/'.$file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

?>

<?php
#注册插件
require_once __DIR__ . "/function.php";
RegisterPlugin("WebDir", "ActivePlugin_WebDir");

function ActivePlugin_WebDir()
{
  Add_Filter_Plugin('Filter_Plugin_Zbp_BuildTemplate', 'WebDir_BuidTemp');
  Add_Filter_Plugin('Filter_Plugin_ViewAuto_Begin', 'WebDir_Rewrite');
  Add_Filter_Plugin('Filter_Plugin_Cmd_Begin', 'WebDir_Upload');
  Add_Filter_Plugin('Filter_Plugin_Zbp_CheckRights', 'WebDir_CheckRights');
}
function WebDir_CheckRights($action, $level)
{
  global $zbp;
  if ($action === "WebDirPst") {
    if (!$zbp->CheckRights('root')) {
      $zbp->ShowError(6);
      die();
    } else {
      $GLOBALS['hooks']['Filter_Plugin_Zbp_CheckRights']['WebDir_CheckRights'] = PLUGIN_EXITSIGNAL_RETURN;
      return true;
    }
  }
}
function WebDir_CheckExtName($name)
{
  global $zbp;
  $e = GetFileExt($name);
  $extList = $zbp->option['ZC_UPLOAD_FILETYPE'];
    // 无论如何，禁止.php、.php5之类的文件的上传
  if (preg_match('/php/i', $e)) {
    return false;
  }

  return HasNameInString($extList, $e);
}
function WebDir_Trim($value)
{
  $value = trim($value);
  $value = trim($value, "_#");
  return $value;
};
function WebDir_Upload()
{
  global $zbp;
  $action = GetVars('act', 'GET');
  $type = GetVars('type', 'GET');
  if ($action === "WebDirPst") {
    CheckIsRefererValid();
    $rlt = array();
    foreach ($_FILES as $key => $value) {
      if ($_FILES[$key]['error'] == 0) {
        if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
          if (!WebDir_CheckExtName($_FILES[$key]['name'])) {
            $zbp->ShowError(26, __FILE__, __LINE__);
            return false;
          }
          $file = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? iconv("UTF-8", "GBK", $_FILES[$key]['name']) : $_FILES[$key]['name'];
          $temp_arr = explode(".", $file);
          $temp_arr = array_map("WebDir_Trim", $temp_arr);
          $file_ext = strtolower(array_pop($temp_arr));
          $file = array_shift($temp_arr) . "#-#" . date("YmdHi") . '.' . $file_ext;
          $rlt['name'] = $_FILES[$key]['name'];
          move_uploaded_file($_FILES[$key]['tmp_name'], WebDir_Path("base") . $file);
        }
      }
    }
    if ($type !== "ajax") {
      $zbp->SetHint('good');
      Redirect(WebDir_Path('main.php', 'host'));
    } else {
      JsonReturn((object)$rlt);
    }
  }
}
function WebDir_Rewrite($inpurl, $url)
{
  global $zbp;
    // $url = trim(urldecode($url), '/');
  $r = UrlRule::OutputUrlRegEx("{%host%}WebDir/", 'article');
  $m = array();
  if (preg_match($r, $url, $m) == 1) {
    WebDir_Main();
    $GLOBALS['hooks']['Filter_Plugin_ViewAuto_Begin']['WebDir_Rewrite'] = PLUGIN_EXITSIGNAL_RETURN;
    return null;
  }
}
function WebDir_BuidTemp(&$templates)
{
  global $zbp;
  $templates['WebDir'] = file_get_contents(WebDir_Path("html"));
}
function WebDir_Main()
{
  global $zbp;
  $x = new WebDir(WebDir_Path("base"), WebDir_Path("base", "host"), $zbp->option['ZC_UPLOAD_FILETYPE']);
  $x->open_dir();
  // echo $x->dir;
  // echo $x->out("dir");
  if ($zbp->user->ID > 0) {
    $copy = $zbp->template->templateTags['copyright'];
    $copy .= ' | <a href="' . WebDir_Path("main", "host") . '" title="管理">后台管理</a>';
    $zbp->template->SetTags('copyright', $copy);
  }
  $zbp->template->SetTags('dirs', $x->out("dir"));
  $zbp->template->SetTags('files', $x->out("file"));
  $zbp->template->Display("WebDir");
}
function WebDir_Path($file, $t = "path")
{
  global $zbp;
  $result = $zbp->$t . "zb_users/plugin/WebDir/";
  switch ($file) {
    case "base":
      return $result . "usr/";
      break;
    case "baseV":
      return str_replace(trim($zbp->path, "/"), "", WebDir_Path("base"));
      break;
    case "html":
      return $result . "var/WebDir.html";
      break;
    case "main":
      return $result . "main.php";
      break;
    default:
      return $result . $file;
  }
}
function InstallPlugin_WebDir()
{
  global $zbp;
  $zbp->BuildTemplate();
  $dir = WebDir_Path("base");
  if (!is_dir($dir)) {
    @mkdir($dir);
  }
}
function UninstallPlugin_WebDir()
{
  global $zbp;
  $zbp->BuildTemplate();
}

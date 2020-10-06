<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action = 'root';
if (!$zbp->CheckRights($action)) {
  $zbp->ShowError(6);
  die();
}
if (!$zbp->CheckPlugin('WebDir')) {
  $zbp->ShowError(48);
  die();
}
InstallPlugin_WebDir();
$blogtitle = '前台附件下载列表';
if ($zbp->option['ZC_STATIC_MODE'] == 'ACTIVE'){
  $url = $zbp->host . "?WebDir";
} else {
  $url = $zbp->host . "WebDir/";
}
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle; ?> <a href="main.php" title="刷新"><small>刷新</small></a></div>
  <div class="SubMenu"></div>
  <div id="divMain2">
    <form class="search" name="upload" id="upload" method="post" enctype="multipart/form-data" action="<?php echo BuildSafeCmdURL('act=WebDirPst'); ?>"><p><input type="file" name="file" size="60">&nbsp;&nbsp;<input type="submit" class="button" value="提交" onclick="">&nbsp;&nbsp;<input class="button" type="reset" value="重置"></p></form>
    <h3>前台地址：<a title="当前地址" target="_blank" href="<?php echo $url; ?>"><?php echo $url ?></a></h3>
    <h3>文件路径：<?php echo WebDir_Path("baseV") ?></h3>
    --------
    <h3><a href="<?php echo $zbp->host?>zb_users/plugin/AppCentre/main.php?id=1859#tab-comment" title="反馈" target="_blank">反馈建议</a>&nbsp;&nbsp;<a href="https://github.com/maysrp/webdir" title="maysrp-webdir" target="_blank">方案及重要代码来源</a></h3>
    <div class="xnxf">
      <h5>－－－－下边是推广链接－－－－</h5>
    </div>
  </div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>

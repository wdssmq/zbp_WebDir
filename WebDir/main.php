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
$blogtitle = 'WebDir';
$url = $zbp->host . "WebDir/";
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
// echo date("Y-m-d-H-i-s");
// echo rand(90,3);
// echo rand(3,9);
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle; ?> <a href="main.php" title="刷新"><small>刷新</small></a></div>
  <div class="SubMenu"></div>
  <div id="divMain2">
    <form class="search" name="upload" id="upload" method="post" enctype="multipart/form-data" action="<?php echo BuildSafeCmdURL('act=WebDirPst'); ?>"><p><input type="file" name="file" size="60">&nbsp;&nbsp;<input type="submit" class="button" value="提交" onclick="">&nbsp;&nbsp;<input class="button" type="reset" value="重置"></p></form>
    <h3>前台地址：<a title="当前地址" target="_blank" href="<?php echo $url; ?>"><?php echo $url ?></a></h3>
    <h3>文件路径：<?php echo WebDir_Path("baseV") ?></h3>
    <h3>方案及部分代码来源：<a href="https://github.com/maysrp/webdir" titile="maysrp-webdir" target="_blank">https://github.com/maysrp/webdir</a></h3>
    <div class="xnxf">
      <h5>－－－－下边是推广链接－－－－</h5>
      <p>－－<b><a title="注册 - 一览阅读" target="_blank" href="https://www.yilan.io/signup?inviteCode=59ca38d5ebdbdd981524823e">注册 - 一览阅读</a>（送7天VIP）</b></p>
      <p>－－<a title="Z-Blog资讯 - 一览" target="_blank" href="http://www.yilan.io/subject/5b9ba744fcd36fbc1d1618ab">Z-Blog资讯 - 一览</a></p>
      <p>－－<a title="沉冰浮水 - 一览" target="_blank" href="http://www.yilan.io/site/5b9ba575f43f90c11d460025">沉冰浮水 - 一览</a></p>
    </div>
  </div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>
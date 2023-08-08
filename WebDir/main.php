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

$act = GetVars('act', 'GET');
// $suc = GetVars('suc', 'GET');
if ($act == 'save') {
  CheckIsRefererValid();
  foreach ($_POST as $key => $val) {
    if (substr($key, 0, 5) == 'read_') {
      continue;
    }
    $zbp->Config('WebDir')->$key = trim($val);
  }
  $zbp->SaveConfig('WebDir');
  $zbp->BuildTemplate();
  $zbp->SetHint('good');
  // Redirect('./main.php' . ($suc == null ? '' : "?act={$suc}"));
  echo "ok";
  die();
}


InstallPlugin_WebDir();
$blogtitle = '前台附件下载';
if ($zbp->option['ZC_STATIC_MODE'] == 'ACTIVE') {
  $url = $zbp->host . "?WebDir";
} else {
  $url = $zbp->host . "WebDir/";
}
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle; ?></div>
  <div class="SubMenu">
    <a href="main.php" title="首页"><span class="m-left m-now">首页</span></a>
    <?php require "about.php"; ?>
  </div>
  <div id="divMain2">
    <!-- ----------------- -->
    <form class="search" name="upload" id="upload" method="post" enctype="multipart/form-data" action="<?php echo BuildSafeCmdURL('act=WebDirPst'); ?>">
      <p>
        <input type="file" name="file" size="60">&nbsp;&nbsp;<input type="submit" class="button" value="提交" onclick="">&nbsp;&nbsp;<input class="button" type="reset" value="重置">
        <?php if ($zbp->CheckPlugin('mz_CloudflareBase')) { ?>
          &nbsp;&nbsp;|&nbsp;&nbsp;使用 Cloundflare R2：
          <?php zbpform::zbradio("use_r2", $zbp->Config("WebDir")->use_r2); ?>
          <?php zbpform::text("bucket_name", $zbp->Config("WebDir")->bucket_name, "15%"); ?>
          <?php zbpform::hidden("save_action", BuildSafeURL('main.php?act=save'), "15%"); ?>
          <input type="button" class="button js-save" value="保存">
        <?php } ?>
      </p>
    </form>
    <h3>前台地址：<a title="当前地址" target="_blank" href="<?php echo $url; ?>"><?php echo $url ?></a></h3>
    <h3>文件路径：<?php echo WebDir_Path("baseV") ?></h3>
    <!-- ----------------- -->
    --------
    <h3><a href="<?php echo $zbp->host ?>zb_users/plugin/AppCentre/main.php?id=1859#tab-comment" title="反馈" target="_blank">反馈建议</a>&nbsp;&nbsp;<a href="https://github.com/maysrp/webdir" title="maysrp-webdir" target="_blank">方案及重要代码来源</a></h3>
  </div>
</div>

<script>
  $(() => {
    $(".js-save").click(() => {
      const $this = $(this);
      const data = {
        use_r2: $("#use_r2").val(),
        bucket_name: $("#bucket_name").val().trim(),
      };
      console.log(data);

      if ('1' === data.use_r2 && !data.bucket_name) {
        alert("请指定使用的 bucket");
      }
      $.ajax({
        url: $("#save_action").val(),
        type: "post",
        data,
        success: function(result) {
          if ("ok" === result) {
            $this.val("保存成功");
            setTimeout(() => {
              window.location.reload();
            }, 1370);
          }
        },
      });
    });
  });
</script>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>

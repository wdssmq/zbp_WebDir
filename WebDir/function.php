<?php

/**
 *
 * 主体代码修改自：maysrp/webdir: 网站目录
 * 主体代码修改自：https://github.com/maysrp/webdir
 * 当前文件作者：沉冰浮水
 * 当前作者主页：https://www.wdssmq.com
 *
 **/
class WebDir
{
  public $baseDir;
  public $baseUrl;
  public $fileType;
  public $dir;
  public $file = array();
  public $dirdir = array();
  public $notex;
  public $notdir;
  function __construct($baseDir = "./", $baseUrl = "./", $fileType)
  {
    $this->baseDir = $baseDir;
    $this->baseUrl = $baseUrl;
    $this->fileType = $fileType;
    $this->notex = array("php", "js", "tgz", "dat");//不允许显示的后缀名文件
    $this->notdir = array(".git", "a", "phpmyadmin");//不允许显示的文件夹
    $path = GetVars("dir", "GET");
    if (!empty($path)) {
      foreach ($this->notdir as $key => $value) {
        if (strtolower($_GET['dir']) == $value) {
          $_GET['dir'] = ".";
        }
      }
      $tom = trim($_GET['dir']);
      $tam = str_replace("..", ".", $tom);
      $this->dir = $this->baseDir . $tam;
    } else {
      $this->dir = $this->baseDir;

    }
  }
  function open_dir()
  {
    if (is_dir($this->dir)) {
      $this->file = GetFilesInDir($this->dir, $this->fileType);
      $this->dirdir = GetDirsInDir($this->dir);
      rsort($this->file);
      rsort($this->dirdir);
    } else {
      InstallPlugin_WebDir();
      echo "error";
    }
  }
  function out($type = "")
  {
    $array = array();
    if ($type === "dir") {
      foreach ($this->dirdir as $key => $value) {
        if (in_array(strtolower($value), $this->notdir)) {
          continue;
        }
        $dir = (object)array();
        $dir->url = $this->dirurl($value);
        $dir->name = $this->filename($value);
        $dir->icon = "glyphicon glyphicon-list";
        $dir->size = "目录";
        $dir->mtime = $this->mtime($this->dir . $value);
        $array[] = $dir;
      }
    } else {
      foreach ($this->file as $key => $value) {
        $file = (object)array();
        $file->url = $this->dirurl($value);
        $file->name = $this->filename($value);
        $file->icon = $this->icon($value);
        $file->size = $this->size($value);
        $file->mtime = $this->mtime($value);
        $file->act = floor((time() - filemtime($value)) / 86400) > 13 ? "del warning" : "none";
        $file->download = $this->download($value);
        $array[] = $file;
      }
    }
    return $array;
  }
  function dirurl($dir)
  {
    $urf = str_replace($this->baseDir, '', $dir);
    return rawurlencode($urf);
  }
  function download($file)
  {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
      $file = iconv("GBK", "UTF-8", $file);
    $url = str_replace($this->baseDir, $this->baseUrl, $file);
    // $url = str_replace("#", "%23", $url);
    return $url;
  }
  function filename($file)
  {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
      $file = iconv("GBK", "UTF-8", $file);
    $file = str_replace("#", "", $file);
    $ar = explode("/", $file);
    return array_pop($ar);
  }
  function ex($string)
  {
    $ar = explode(".", $string);
    $ex = array_pop($ar);
    return strtolower($ex);
  }
  function icon($file)
  {
    $ex = $this->ex($file);
    switch ($ex) {
      case 'png':
      case 'jpg':
      case 'gif':
      case 'bmp':
      case 'jpeg':
        return "glyphicon glyphicon-picture";
        break;
      case 'torrent':
        return "glyphicon glyphicon-magnet";
        break;
      case 'mp3':
        return "glyphicon glyphicon-music";
        break;
      case 'mp4':
      case 'ogg':
      case 'webm':
        return "glyphicon glyphicon-film";
        break;
      case 'xls':
      case 'xlsx':
      case 'doc':
      case 'docx':
      case 'ppt':
      case 'pptx':
        return "glyphicon glyphicon-pencil";
        break;
      case 'pdf':
        return "glyphicon glyphicon-book";
        break;
      case 'txt':
      case 'md':
        return "glyphicon glyphicon-file";
        break;
      default:
        return "glyphicon glyphicon-file";
        break;
    }
  }
  function type($file)
  {
    $ex = $this->ex($file);
    switch ($ex) {
      case 'png':
      case 'jpg':
      case 'gif':
      case 'bmp':
      case 'jpeg':
        return "img";
        break;
      case 'torrent':
        return "torrent";
        break;
      case 'mp3':
        return "mp3";
        break;
      case 'mp4':
      case 'ogg':
      case 'webm':
        return "video";
        break;
      case 'xls':
      case 'xlsx':
      case 'doc':
      case 'docx':
      case 'ppt':
      case 'pptx':
        return "other";
        break;
      case 'pdf':
        return "pdf";
        break;
      case 'txt':
      case 'json':
      case 'xml':
      case 'html':
      case 'md':
        return "text";
        break;
      default:
        return "other";
        break;
    }
  }
  function size($file)
  {
    $fz = filesize($file);
    if ($fz > (1024 * 1024 * 1024)) {
      return sprintf("%.2f", $fz / (1024 * 1024 * 1024)) . "GB";
    } elseif ($fz > (1024 * 1024)) {
      return sprintf("%.2f", $fz / (1024 * 1024)) . "MB";
    } elseif ($fz > 1024) {
      return sprintf("%.2f", $fz / 1024) . "KB";
    } else {
      return $fz . "B";
    }
  }
  function mtime($file)
  {
    return date("Y-m-d H:i:s", filemtime($file));
  }
  function atime($file)
  {
    return date("Y-m-d H:i:s", fileatime($file));
  }
  function ctime($file)
  {
    return date("Y-m-d H:i:s", filectime($file));

  }
  function pre()
  {
    $dir_array = explode("/", $this->dir);
    $num = count($dir_array);
    if ($num >= 2) {
      @array_shift($dir_array);
      $url = "<a class=\"text-success\" href=?>/.</a>";
      foreach ($dir_array as $key => $value) {
        $step = $step . $value . "/";
        $url = $url . "<a class=\"text-success\" href=\"?dir=" . $step . "\">/" . $value . "</a>";
      }
      return $url;
    }
  }
}
?>

## 开发用备忘

### bootstrap 和 bootstrap-fileinput

对应的前端文件在 git 中排除，需要执行下边的命令来下载：

```bash

BOOTSTRAP_VER="3.3.0"
FILEINPUT_VER="4.3.8"

# css 文件
mkdir -p lib && curl -fL "https://cdn.jsdelivr.net/npm/bootstrap@${BOOTSTRAP_VER}/dist/css/bootstrap.min.css" -o "lib/bootstrap.min.css" && curl -fL "https://cdn.jsdelivr.net/npm/bootstrap-fileinput@${FILEINPUT_VER}/css/fileinput.min.css" -o "lib/bootstrap-fileinput.min.css"

# js 文件
mkdir -p lib && curl -fL "https://cdn.jsdelivr.net/npm/bootstrap-fileinput@${FILEINPUT_VER}/js/fileinput.min.js" -o "lib/bootstrap-fileinput.min.js" && curl -fL "https://cdn.jsdelivr.net/npm/bootstrap-fileinput@${FILEINPUT_VER}/js/locales/zh.min.js" -o "lib/bootstrap-fileinput.zh.min.js"

# font 文件
mkdir -p fonts && set -e
for f in glyphicons-halflings-regular.eot glyphicons-halflings-regular.svg glyphicons-halflings-regular.ttf glyphicons-halflings-regular.woff; do
  curl -fL "https://cdn.jsdelivr.net/npm/bootstrap@${BOOTSTRAP_VER}/dist/fonts/$f" -o "fonts/$f"
done
# woff2 在部分版本可能不存在，存在则下载，不存在则跳过
curl -fL "https://cdn.jsdelivr.net/npm/bootstrap@${BOOTSTRAP_VER}/dist/fonts/glyphicons-halflings-regular.woff2" -o "fonts/glyphicons-halflings-regular.woff2" || true
ls -l fonts

```

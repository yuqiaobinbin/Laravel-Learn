```
 wget https://nodejs.org/dist/v10.9.0/node-v10.9.0-linux-x64.tar.xz    // 下载

tar xf  node-v10.9.0-linux-x64.tar.xz

cd node-v10.9.0-linux-x64/ 

./bin/node -v     

解压文件的 bin 目录底下包含了 node、npm 等命令，我们可以使用 ln 命令来设置软连接：
```
```
ln -s /usr/software/nodejs/bin/npm   /usr/local/bin/ 

ln -s /usr/software/nodejs/bin/node   /usr/local/bin/
```
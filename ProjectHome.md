<div>先安装好CodeBuilder，下载地址 <strong>金山快盘附件：</strong>codeBuilder1.0.zip (245.2 KB)<br>
<br>
在构建之前，请先确认<font face='Calibri, serif'>SP</font>配置文件中的数据库信息正确，填入正确的前缀（一般为空），务必将<font face='Calibri, serif'>smarty</font>的起止符修改为<font face='Calibri, serif'><font color='#ff0000'>&lt;{</font></font>和 <font face='Calibri, serif'><font color='#ff0000'>}&gt;</font></font>构建<br>
<br>
Unknown end tag for </div><br>
<br>
<br>
<div>示例：</div><br>
<div><font face='Calibri, serif'>1.</font>浏览器打开<font face='Calibri, serif'><br>
<a href='http://spurl/index.php?c=CodeBuilder'>http://spurl/index.php?c=CodeBuilder</a>

Unknown end tag for </font>

输入默认密码 <font face='Calibri, serif'>123456</font>登录（密码的修改可以在<font face='Calibri, serif'>controller/CodeBuilder.php</font>中找到）<br>
<img src='http://ponycui-my.stor.sinaapp.com/readme/1.jpg' alt='' height='233' border='0' width='468'><br>
<br>
<font face='Calibri, serif'>2.</font>进入此主界面后选择<font face='Calibri, serif'>Model</font>

Unknown end tag for </div>

<img src='http://ponycui-my.stor.sinaapp.com/readme/2.jpg' alt='' border='0' width='468' height='233'><br>
<br>
<div><font face='Calibri, serif'>3.</font>然后选择需要创建<font face='Calibri, serif'>Model</font>的表选择好表后，<font face='Calibri, serif'>Model</font>类名会自动填充，这里我们选择<font face='Calibri, serif'>onlinedown</font>表</div><img src='http://ponycui-my.stor.sinaapp.com/readme/3.jpg' alt='' border='0' width='328' height='231'><br>
<br>
<div><font face='Calibri, serif'>4.</font>然后点击“预览”程序会告诉你即将生成的文件，以及它们的位置内容，确认无误后，点击“生成”</div><img src='http://ponycui-my.stor.sinaapp.com/readme/4.jpg' alt='' border='0' width='500' height='186'><br>
<br>
<br>
<div><font face='Calibri, serif'>5.</font>出现如下信息时，表明<font face='Calibri, serif'>Model</font>已经生成成功了，如果提示出错信息，请检查<font face='Calibri, serif'>model</font>文件夹是否有写入权限，你也可以到<font face='Calibri, serif'>model</font>下查看是否已经生成文件。<br>
<img src='http://ponycui-my.stor.sinaapp.com/readme/5.jpg' alt='' border='0' width='500' height='162'><br>
<br>
<br>
<font face='Calibri, serif'>6.</font>然后，我们可以生成<font face='Calibri, serif'>CURD</font>了，左侧菜单选择<font face='Calibri, serif'>CURD</font>

Unknown end tag for </div>

<img src='http://ponycui-my.stor.sinaapp.com/readme/6.jpg' alt='' height='243' border='0' width='321'><br>
<br>
<br>
<div><font face='Calibri, serif'>7.</font>选择刚才生成的<font face='Calibri, serif'>MOnlinedownModel</font>类，<font face='Calibri, serif'>Controller</font>类名输入<font face='Calibri, serif'>onlinedown</font>（这里你可以自定输入自己需要的<font face='Calibri, serif'>controller</font>名称）。</div><br>
<img src='http://ponycui-my.stor.sinaapp.com/readme/7.jpg' alt='' height='220' border='0' width='500'><br>
<br>
<div><font face='Calibri, serif'>8.</font>同样的，我们点“预览”，检查一下代码，再点“生成”，这样，一个表的<font face='Calibri, serif'>CURD</font>就完成了，怎么访问呢？</div><br>
<br>
<br>
<div><font face='Calibri, serif'>9.</font>浏览器输入<font face='Calibri, serif'><a href='http://spurl/index.php?c=onlinedown'>http://spurl/index.php?c=onlinedown</a></font>（<font face='Calibri, serif'>c=</font>后面填的是我们刚才输入的<font face='Calibri, serif'>controller</font>类名）怎么样，很炫吧，可以添加、删除、查看、编辑表中的记录</div><img src='http://ponycui-my.stor.sinaapp.com/readme/8.jpg' alt='' height='293' border='0' width='500'><br>
<br>
<br>
<div><font face='Calibri, serif'>10.</font>接下去的功能，就让你自己开发整合了，加个用户权限等等的就是一个完美的管理后台了。</div><br>
<div><font face='Calibri, serif'>11.</font>本程序使用的前端工具是<font face='Calibri, serif'>bootstrap</font>和<font face='Calibri, serif'>jQuery</font>，如果需要学习的话可以上他们的官方网站。</div><br>
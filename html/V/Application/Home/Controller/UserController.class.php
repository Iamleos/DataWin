<?php
namespace Home\Controller;
use Think\Controller;
use Think\Upload;
use Think\Image;
class UserController extends Controller {
    public function login(){
        if(IS_POST){
            $data = I('post.');
            $res = D('User')->login($data['username'],$data['userpwd']);
            If($res){

                $this->success("登陆成功",U('Home/User/home'));
                $_SESSION['user'] = $data['username'];
            }
            else{
                $this->error("登录失败");
            }
        }else{
            $this -> display();
        }
    }
    public function register(){
        if(IS_POST){
            $data = I('post.');

            D('User')-> register($data);
            $this->success("注册成功,请登录",U('Home/Index/index'),3);
        }else{
            $this -> display();
        }
    }

    public function upload()
    {
        if (IS_POST) {
            $data = I('post.');

            //这里划分一下允许上传的文件类型，与3.1相比，文件类型不再是数组类型了，而是字符串，这是个区别。
            $setting = array(
                'mimes' => '', //允许上传的文件MiMe类型
                'maxSize' => 31457280, //上传的文件大小限制 (0-不做限制)
                'exts' => 'jpg,gif,png,jpeg,bmp', //允许上传的文件后缀
                'autoSub' => true, //自动子目录保存文件
                'subName' => array('uniqid', ''), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
//                'rootPath' => './Public/Upload', //保存根路径
                'savePath' => './', //保存路径
                'saveName' => '',
            );
            /* 调用文件上传组件上传文件 */
            //实例化上传类，传入上面的配置数组
            $upload = new \Think\Upload($setting);
            $image = new \Think\Image();
            $info = $upload->upload();
            //这里判断是否上传成功
            if (!$info) {
                $this->error($upload->getError());
            } else {
                //var_dump($info);//上传成功就输出返回的结果
                $test["title"] = $data["title"];
                $test["classify"] = $data["select"];
                $test["creat_time"] = time();
                $test["user"] = $_SESSION['user'];

                $url = $_POST["url"];
                //var_dump($url);
                $html = file_get_contents($url);
                $save = $info[0]["savepath"];
                $path = str_replace(array(".", "/"), "", $save);
                //通过正则表达式提取里面的图片url
                $names = $info[0]["savename"];
                $image->open("./Uploads/$path/$names");
                $image->thumb(900, 500,\Think\Image::IMAGE_THUMB_CENTER)->save("./Uploads/$path/thumb.jpg");
                $arr = array();
                $preg = '/<img[\w\W]*?data-src="([\w\W]*?)"[\w\W]*?\/>/';
                preg_match_all($preg, $html, $arr);
                //var_dump($arr[1]);
                mkdir("./Uploads/html/$path");
                chmod("./Uploads/html/$path", 0777);
                for ($i = 0; $i < count($arr[1]); $i++) {
                    //将提取的图片url下载下来。
                    $imgurl = $arr[1][$i];
                    $img = file_get_contents($imgurl);
                    $tar_img = $i . '.jpg';
                    file_put_contents("./Uploads/html/$path/$tar_img", $img);
                    //echo strpos($html,$imgurl);
                    $len = strlen($imgurl) + strpos($html, $imgurl);
                    $html = substr($html, 0, strpos($html, $imgurl)) . "{$tar_img}" . substr($html, $len);
                }
                $html = str_replace("data-src", "src", $html);
                file_put_contents("./Uploads/html/$path/temp.html", $html);


                //var_dump($data["url"]);
                $test["html"] = "115.159.205.133/V/Uploads/html/$path/temp.html";
                $tupian = "115.159.205.133/V/Uploads/$path/thumb.jpg";
                $test["photo"] = $tupian;

                $op=file_get_contents("./test.html");
                file_put_contents("./Uploads/html/$path/test.html", $op);
                $ob=file_get_contents("./jquery-1.8.3.min.js");
                file_put_contents("./Uploads/html/$path/jquery-1.8.3.min.js", $ob);
                $od=file_get_contents("./test.php");
                file_put_contents("./Uploads/html/$path/test.php", $od);
                file_put_contents("./Uploads/html/$path/1.php","<?php
	echo file_get_contents(\"test.html\");?>");

                $pin="/V/Uploads/html/$path/test.html";
                echo "<script language='javascript' type='text/javascript'>";
                echo "window.open('$pin')";
                echo "</script>";

                if(file_exists("./Uploads/html/$path/test.html")){
                    $test["height"]=("115.159.205.133/V/Uploads/html/$path/a.txt");
                }else{
                    $test["height"]="0";
                }


                var_dump($test["height"]);
                $result = $this->add($test) ;
//                var_dump(file_exists("./Uploads/html/$path/test.html"));
//                var_dump($a);
                if ($result > 0) {
                    $open = array(
                        'html' => $test["html"],
                        'photo' => $test["photo"],
                        'title' => $test["title"],
                        'height' => $test["height"]
                    );
                    //$Model = new \Think\Model();
                    //var_dump(json_encode($Model->query("select * from tp_upload order by id desc")));

                    $this->show(json_encode($open));
                    $this->success("上传成功",U('Home/User/home'),5);
                }

            }
        } else {
            $this->display();
        }
    }
    public function add($test){
        $m = M(upload);
        if($m->add($test)>0){
            return 1;}
    }
    public function test($path){
        //$path = "57888a1f9393e";
        if(file_exists("./Uploads/html/$path/test.html")){
            $a =file_get_contents("./Uploads/html/$path/a.txt");}
        else {
            $a=0;
        }
        return $a;
        //$this->display("./Uploads/html/$path/test.html");
        //$height=$_GET['height'];
        //return $height;
    }
    public function log(){
        $m = M(upload);
        $open["morning"]["data"]=$m ->where('classify="今日晨读"')->order('id DESC')->limit(5)->select();
        if(count($open["morning"]["data"])>1){$open["morning"]["status"]=true;}else{$open["morning"]["status"]=false;}

        $open["actor"]["data"]=$m ->where('classify="艺人数据分析"')->order('id DESC')->limit(5)->select();
        if(count($open["actor"]["data"])>1){$open["actor"]["status"]=true;}else{$open["actor"]["status"]=false;}

        $open["moviedata"]["data"]=$m ->where('classify="电影数据分析"')->order('id DESC')->limit(5)->select();
        if(count($open["moviedata"]["data"])>1){$open["moviedata"]["status"]=true;}else{$open["moviedata"]["status"]=false;}

        $open["moviepro"]["data"]=$m ->where('classify="影视项目总分析"')->order('id DESC')->limit(5)->select();
        if(count($open["moviepro"]["data"])>1){$open["moviepro"]["status"]=true;}else{$open["moviepro"]["status"]=false;}

        $open["study"]["data"]=$m ->where('classify="行业研究"')->order('id DESC')->limit(5)->select();
        if(count($open["study"]["data"])>1){$open["study"]["status"]=true;}else{$open["study"]["status"]=false;}

        $open["summary"]["data"]=$m ->where('classify="月咨询总结"')->order('id DESC')->limit(5)->select();
        if(count($open["summary"]["data"])>1){$open["summary"]["status"]=true;}else{$open["summary"]["status"]=false;}

        $open["news"]["data"]=$m ->where('classify="最新消息"')->order('id DESC')->limit(5)->select();
        if(count($open["news"]["data"])>1){$open["news"]["status"]=true;}else{$open["news"]["status"]=false;}

        echo(json_encode($open));

    }
    public function logout(){
        $_SESSION['user'] = NULL;
        $this -> success('退出成功',U('Home/Index/index'));
    }
    public function home(){
        $this->display();
    }
    public function download(){
        $this->show("sorry,小王还在努力中");

    }


}

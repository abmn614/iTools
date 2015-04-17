<?php 

/**
 * 文件上传类
 */

Class UploadFile{
    private $path = 'uploads';
    private $maxsize = 104857600; // 100M
    private $allowtype = array('jpg', 'png');
    private $israndname = true;
    private $error;
    private $errormsg;
    private $old_name;
    private $tmp_name;
    private $newFileName;
    private $ext;
    private $size;

    function upload($field = 'file', $config = array()){

        // $_FILES为空
        if (empty($_FILES)) {
            $this->error = 5;
            $this->errormsg = $this->getError();
            return false;
        }

        // 解析$_FILES
        $name = $_FILES[$field]['name'];
        $tmp_name = $_FILES[$field]['tmp_name'];
        $error = $_FILES[$field]['error'];
        $size = $_FILES[$field]['size'];

        // 覆盖默认项
        foreach ($config as $k => $v) {
            if (array_key_exists($k, get_class_vars(get_class($this)))) {
                $this->$k = $v;
            }
        }

        // 检查上传目录
        if (!$this->checkPath()) {
            $this->errormsg = $this->getError();
            return false;
        }

        $return = true;
        // 多文件处理
        if (is_array($name)) {
            // 循环检查文件合法性
            for ($i = 0; $i < count($name); $i++) {
                if ($this->setFiles($name[$i], $tmp_name[$i], $size[$i], $error[$i])) {
                    if (!$this->checkSize() || !$this->checkType()) {
                        $errors[] = $this->getError();
                        $return = false;
                    }
                } else {
                    $errors[] = $this->getError();
                    $return = false;
                }
                // 如果异常则重置文件属性
                if (!$return) {
                    $this->setFiles();
                }
            }

            // 循环上传文件
            if ($return) {
                for ($i = 0; $i < count($name); $i++) { 
                    if ($this->setFiles($name[$i], $tmp_name[$i], $size[$i], $error[$i])) {
                        $this->setNewFileName();
                        if (!$this->copyFile()) {
                            $errors[] = $this->getError();
                            $return = false;
                        }
                        $fileNames[] = $this->newFileName;
                    }
                }
                $this->newFileName = $fileNames;
            }
            $this->errormsg = $errors;
            return $return;
        // 单个文件上传
        } else {
            if ($this->setFiles($name, $tmp_name, $size, $error)) {
                 if ($this->checkSize() && $this->checkType()) {
                    $this->setNewFileName();
                    if ($this->copyFile()) {
                        return true;
                    }else{
                        $return = false;
                    }
                 }else{
                    $return = false;
                 }
            }else{
                $return = false;
            }
            if (!$return) {
                $this->errormsg = $this->getError();
                return $return;
            }
        }
    }

/* 检查上传目录 */
    function checkPath(){
        if (empty($this->path)) {
            $this->error = -5;
            return false;
        }
        if (!file_exists($this->path) || !is_writable($this->path)) {
            if (!@mkdir($this->path, 0755)) {
                $this->error = -4;
                return false;
            }
        }
        return true;
    }

/* 初始化文件属性 */
    function setFiles($name = '', $tmp_name = '', $size = 0, $error = 0){
        $this->error = $error;
        // 如果不为0则报错
        if ($error) {
            return false;
        }
        $this->old_name = $name;
        $this->tmp_name = $tmp_name;
        $this->ext = array_pop(explode('.', $name));
        $this->size = $size;
        return true;
    }

/* 检查文件大小 */
    function checkSize(){
        if ($this->size > $this->maxsize) {
            $this->error = -2;
            return false;
        }else{
            return true;
        }
    }

/* 检查文件扩展名 */
    function checkType(){
        if (in_array(strtolower($this->ext), $this->allowtype)) {
            return true;
        } else {
            $this->error = -1;
            return false;
        }
    }

/* 设置新文件名 */
    function setNewFileName(){
        if ($this->israndname) {
            $this->newFileName = date('YmdHis') . '_' . rand(1,1000000) . '.' . $this->ext;
        }else{
            $this->newFileName = $this->old_name;
        }
    }

/* 获取上传后的文件名 */
    function getFileName(){
        return $this->newFileName;
    }

/* 复制文件 */
    function copyFile(){
        if (!$this->error) {
            $path = rtrim($this->path, '/') . '/' . $this->newFileName;
            if (@move_uploaded_file($this->tmp_name, $path)) {
                return true;
            } else {
                $this->error = -3;
                return false;
            }
        }else{
            return false;
        }
    }

/* 自定义错误编号 */
    private function getError() {
      $str = "上传文件<font color='red'>{$this->originName}</font>时出错 : ";
      switch ($this->error) {
        case 5: $str .= "文件太大，请检查服务器配置"; break;
        case 4: $str .= "没有文件被上传"; break;
        case 3: $str .= "文件只有部分被上传"; break;
        case 2: $str .= "上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值"; break;
        case 1: $str .= "上传的文件超过了php.ini中upload_max_filesize选项限制的值"; break;
        case -1: $str .= "未允许类型"; break;
        case -2: $str .= "文件过大,上传的文件不能超过{$this->maxsize}个字节"; break;
        case -3: $str .= "上传失败"; break;
        case -4: $str .= "建立存放上传文件目录失败，请重新指定上传目录"; break;
        case -5: $str .= "必须指定上传文件的路径"; break;
        default: $str .= "未知错误";
      }
      return $str.'<br>';
    }

/* 获取错误信息 */
    public function getErrorMsg(){
        return $this->errormsg;
    }

}
<?php 

/**
 * 数据库操作类
 */

Class Model
{   

    public $con = NULL; // pdo对象
    public $tab = ''; // 表名
    public $fields = '*'; // 字段
    public $where = ''; // 条件
    public $order = ''; // 排序
    public $limit = ''; // 条数

    // array(
    //     'type'      => 'mysql',
    //     'host'      => 'localhost',
    //     'port'      => 3306,
    //     'dbname'    => 'test',
    //     'username'  => 'root',
    //     'password'  => '',
    //     'names'     => 'utf8'
    //     )
/* 构造函数 */
    function __construct($config, $tab){
        try {
            $this->con = new PDO("{$config['type']}:host={$config['host']};dbname={$config['dbname']};port={$config['port']};", "{$config['username']}", "{$config['password']}", array(PDO::ATTR_PERSISTENT => true, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES `{$config['names']}`"));
        } catch (PDOException $e) {
            echo "Error : " . $e->getMessage() . '<br />';
            die("{$config['type']}数据库连接失败");
        }
        $this->tab = $tab;
    }

/* FIELDS 字段 */
    function fields($fields){
        $this->fields = $fields;
        return $this;
    }

/* WHERE 条件 */
    function where($where){
        $this->where = 'WHERE ' . $where;
        return $this;
    }

/* ORDER BY 排序 */
    function order($order){
        $this->order = 'ORDER BY ' . $order;
        return $this;
    }

/* LIMIT 条数 */
    function limit($limit){
        $this->limit = 'LIMIT ' . $limit;
        return $this;
    }

/* SELECT 查询 */
    function select($fields = ''){
        if (empty($fields)) {
            $fields = $this->fields;
        }
        $sql = "SELECT {$fields} FROM {$this->tab} {$this->where} {$this->order} {$this->limit}";
        // 清除，以免影响其他定义
        $this->where = $this->order = $this->limit = '';
        return $this->querySql($sql);
    }

/* INSERT 增加 */
    function insert($data){
        foreach ($data as $k => $v) {
            $fields_arr[] = $k;
            $values_arr[] = "'$v'";
        }
        $fields = implode(',', $fields_arr);
        $values = implode(',', $values_arr);
        $sql = "INSERT INTO {$this->tab} ({$fields}) VALUES ({$values})";
        if (!$this->con->exec($sql)) {
            exit("插入失败");
        }
        return $this->con->lastInsertId();
    }

/* UPDATE 修改 */
    function update($data){
        // 一定要有 WHERE 条件限制
        if (empty($this->where)) {
            exit("未限制条件");
        }
        foreach ($data as $k => $v) {
            $sets .= "{$k}='{$v}',";
        }
        $sets = rtrim($sets, ',');
        $sql = "UPDATE {$this->tab} SET {$sets} {$this->where}";
        $this->where = '';
        return $this->exeSql($sql);
    }

/* DELETE 删除 */
    function delete(){
        // 一定要有 WHERE 条件限制
        if (empty($this->where)) {
            exit("未限制条件");
        }
        // 只支持单条删除        
        $sql = "DELETE FROM {$this->tab} {$this->where}";
        $this->where = '';
        return $this->exeSql($sql);
    }

/* COUNT 查询总条数 */
    function count(){
        $sql = "SELECT COUNT(*) AS count FROM {$this->tab} {$this->where}";
        $count_arr = $this->querySql($sql);
        $this->where = '';
        return $count_arr[0]['count'];
    }

/* 执行SQL语句 */
    function querySql($sql){
        $query = $this->con->query($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $rst = $query->fetchAll();
        return $rst;
    }
    function exeSql($sql){
        $affectedRows = $this->con->exec($sql);
        return $affectedRows;
    }
}

/* 工厂模式 */
function M($config, $tab){
    return new Model($config, $tab);
}
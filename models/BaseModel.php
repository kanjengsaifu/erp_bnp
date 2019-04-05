<?php

abstract class BaseModel{
    public static $db;
	protected $host="localhost";
    protected $username="root";
	protected $password="root123456";

    // protected $host="192.168.0.131";
    // protected $username="admin";    
    // protected $password="123456";
    protected $db_name="revelsoft_erp_bnp";

    function __construct(){
        static::$db = mysqli_connect($host, $username, $password, $db_name);
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }
}
?>
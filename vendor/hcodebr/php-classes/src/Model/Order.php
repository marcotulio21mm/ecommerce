<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Model\Cart;

class Order extends Model{

    const SUCCESS= "Order-Success";
    const ERROR= "Order-Error";

    public function save()
 {
     $sql = new Sql();
     $results = $sql->select("CALL sp_addresses_save(:idaddress, :idperson, :desaddress, :descomplement, :descity, :desstate, :descountry, :deszipcode, :desdistrict)", [
         ':idaddress'=>$this->getidaddress(),
         ':idperson'=>$this->getidperson(),
         ':desaddress'=>$this->getdesaddress(),
         ':descomplement'=>$this->getdescomplement(),
         ':descity'=>$this->getdescity(),
         ':desstate'=>$this->getdesstate(),
         ':descountry'=>$this->getdescountry(),
         ':deszipcode'=>$this->getdeszipcode(),
         ':desdistrict'=>$this->getdesdistrict()
     ]);
     if (count($results) > 0) {
         $this->setData($results[0]);
     }
 }

    public function get($idorder){

        $sql= new Sql();

        $results= $sql->select("
            SELECT * 
            FROM tb_orders a 
            INNER JOIN tb_ordersstatus b USING(idstatus) 
            INNER JOIN tb_carts c USING(idcart) 
            INNER JOIN tb_users d ON d.iduser=a.iduser 
            INNER JOIN tb_addresses e USING(idaddress) 
            INNER JOIN tb_persons f ON f.idperson= d.idperson 
            WHERE a.idorder= :idorder   
        
        ",[
            ':idorder'=>$idorder
        ]);

        if(count($results)>0){

            $this->setData($results[0]);
        }
    }

    public function listAll(){

        $sql= new Sql();

        $sql->select("
            SELECT * 
            FROM tb_orders a 
            INNER JOIN tb_ordersstatus b USING(idstatus) 
            INNER JOIN tb_carts c USING(idcart) 
            INNER JOIN tb_users d ON d.iduser=a.iduser 
            INNER JOIN tb_addresses e USING(idaddress) 
            INNER JOIN tb_persons f ON f.idperson= d.idperson 
            WHERE a.idorder= :idorder   
            ORDER BY a.dtregister DESC
        ");
    }

    public function delete(){

        $sql= new Sql();

        $sql->query("DELETE FROM tb_orders WHERE idorder=:idorder",[
            'idorder'=>$this->getidorder()
        ]);
    }

    public function getCart():Cart{

        $cart= new Cart();

        $cart->get((int)$this->getidcart());

        return $cart;
    }

    public static function setError($msg)
	{

		$_SESSION[Order::ERROR] = $msg;

	}

	public static function getError()
	{

		$msg = (isset($_SESSION[Order::ERROR]) && $_SESSION[Order::ERROR]) ? $_SESSION[Order::ERROR] : '';

		Order::clearError();

		return $msg;

	}

	public static function clearError()
	{

		$_SESSION[Order::ERROR] = NULL;

	}

    public static function setSuccess($msg)
	{

		$_SESSION[Order::SUCCESS] = $msg;

	}

	public static function getSuccess()
	{

		$msg = (isset($_SESSION[Order::SUCCESS]) && $_SESSION[Order::SUCCESS]) ? $_SESSION[Order::SUCCESS] : '';

		Order::clearSuccess();

		return $msg;

	}

	public static function clearSuccess()
	{

		$_SESSION[Order::SUCCESS] = NULL;

	}



}


?>
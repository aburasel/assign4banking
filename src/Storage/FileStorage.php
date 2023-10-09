<?php

declare(strict_types=1);

namespace App\Storage;

use App\Model\Model;
use App\Model\Transaction;
use App\Model\User;
use AppConstants;

class FileStorage implements Storage
{


    public function save(string $model, array $data): bool
    {
        $items = $this->loadAll($model);
        $items[] = $data;
        file_put_contents($this->getModelPath($model), serialize($items));
        return true;
    }


    public function loadAll(string $model): array
    {
        $data = array();
        if (file_exists($this->getModelPath($model))) {
            $data = unserialize(file_get_contents($this->getModelPath($model)));
            //echo "<pre>";var_dump($data);exit();
        }

        if (!is_array($data)) {
            return [];
        }

        //echo"=";var_dump($data);
        return $data;
    }
    public function loadWhere($model, array $where): ?array
    {
        $result=array();
        if ($where) {
            $email=$where['email'];
          } else {
            $email = null;
          }

        if (file_exists($this->getModelPath($model))) {
            $data = unserialize(file_get_contents($this->getModelPath($model)));
        }
        //var_dump($data);exit();
        if ($data) {
            if($email==null){
                return $data;
            }else{
                foreach($data as $datum){
                    if($datum['email']==$email){
                        $result[]=$datum;
                    }
                }
                return $data;
            }
            
        } else {
            return null;
        }
    }
    public function loadJoinWhere($model1, $model2, array $where, array $joinBetween, array $select): ?array
    {
        $data[$model1] = unserialize(file_get_contents($this->getModelPath($model1)));
        $data[$model2] = unserialize(file_get_contents($this->getModelPath($model2)));
        //print_r($where);exit();
        //Imply WHERE filter
        
        foreach ($where as $key => $value) {
            $table = explode(".", $key)[0];
            $column = explode(".", $key)[1];
            
            foreach ($data[$table] as $keyItem => $item) {
                if(!$value){
                    continue;
                }
                if ($item[$column] != $value) {
                    unset($data[$table][$keyItem]);
                }
            }
            $allColumn="";
            foreach($select as $table => $col){
                $allColumn.=$col.",";
            }
            $allColumn = rtrim($allColumn, ",");
            $allColumnArray=explode(",",$allColumn);
            // $join = "";
            foreach ($joinBetween as $key1 => $key2) {
              $join1= $key1;
              $join2= $key2;
              break;
            }
            // $join = rtrim($join, "AND");
            //print_r($joinBetween);
            $result=array();
                foreach($data[$model1] as $key => $item){
                    //print_r($item);
                    foreach($data[$model2] as $key2 => $item2){
                        //print_r($item2);
                        if($item[$join1]==$item2[$join2]){


                            $subItems="";
                            foreach($allColumnArray as $col){
                                if(array_key_exists($col,$item)){
                                    //$subItems.= "'".$col."'"."=>".$item[$col].",";
                                    $anArray[$col]=$item[$col];
                                }
                                if(array_key_exists($col,$item2)){
                                    //$subItems.= "'".$col."'"."=>".$item2[$col].",";
                                    $anArray[$col]=$item2[$col];
                                }
                            }
                            $result[]=$anArray;
                             
                        }
                        
                    }
                    
                }
            
            
    
        }

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function getModelPath(string $model): string
    {
        return 'data/' . $model . ".txt";
    }
}

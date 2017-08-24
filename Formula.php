<?php
/**
 *  @file       formula.php
 *  @author     laipiyang <462166282@qq.com>
 *  @since      2017-08-24 16:08:39
 *  @update
 *  @description    
 */

class Formula
{
    public function handle($calexp,Array $row) {
        if (count($row)) {
            $calexp = preg_replace_callback('/[A-Za-z]+/',function($matches) use ($row) {
                return $row[$matches[0]];
            },$calexp);
        }
        
        return function_exists('eval') ? eval("return $calexp;") : $this->calc($calexp);   
    }

    #递归计算小括号，*/大于+-
    private function calc($exp) {
        $left_idx = strrpos($exp,'(');
        if($left_idx!==false) {//has ()
            $left_exp = substr($exp,0,$left_idx);
            $exp = substr($exp,$left_idx+1);
            $right_idx = strpos($exp,')',true);
            $right_exp = substr($exp,$right_idx+1);
            $mid_exp = substr($exp,0,$right_idx);
            if (is_numeric(substr($left_exp,-1))) {
                $left_exp .= '*';
            }
            if (is_numeric(substr($right_exp,0,1))) {
                $right_exp = '*'.$right_exp;
            }
            return $this->calc($left_exp.$this->calc($mid_exp).$right_exp);
        } else {//+-*/
            $m = [];
            preg_match_all('/(\d+\.\d*|[\+\-\*\/]|\d+)/',$exp,$matches);
            while($s = array_shift($matches[1])) {
                if(!is_numeric($s) && ($s=='*' || $s=='/')) {
                    $s = $this->opr(array_pop($m), $s, array_shift($matches[1]));
                }
                array_push($m, $s);
            }
            $result = array_shift($m);
            while($s = array_shift($m)) {
               if(!is_numeric($s) && ($s=='+' || $s=='-')) {
                    $result = $this->opr($result,$s,array_shift($m));
                }
            }
            return $result;
        }
    }

    private function opr($n1, $opt, $n2) {
        switch ($opt) {
            case '+':
                return floatval($n1) + floatval($n2);
                break;
            case '-':
                return floatval($n1) - floatval($n2);
                break;
            case '*':
                return floatval($n1) * floatval($n2);
                break;
            case '/':
                return floatval($n1) / floatval($n2);
                break;
        }    
    }
}


$calexp = 'Perimeter*0.3-0.5*(WindowAndDoorArea+GroundArea)';
$row['Perimeter']=5;
$row['WindowAndDoorArea']=2;
$row['GroundArea']=4;

$calexp = '2(5)3';
$row = [];

$ret = (new Formula())->handle($calexp,$row);
var_dump($ret);

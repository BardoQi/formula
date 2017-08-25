<?php
class FormulaTest extends PHPUnit_Framework_TestCase
{
    public function testFormula()
    {
        $expression = new Formula;
        $calexp = '2(5)3';
        $row = [];

        $this->assertEquals( $expression->handle($calexp,$row), 30 );
    }

    public function testFormula1()
    {
        $expression = new Formula;
        $calexp = 'Perimeter*0.3-0.5*(WindowAndDoorArea+GroundArea)';
        $row['Perimeter']=5;
        $row['WindowAndDoorArea']=2;
        $row['GroundArea']=4;

        $this->assertEquals( $expression->handle($calexp,$row), -1.5 );
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

register_shutdown_function(function(){
    var_dump(error_get_last());
});

require 'vendor/autoload.php';
require 'src/VoDocExtractor.php';
require 'src/MethodDocExtractor.php';
class xx
{
    
    /**
     * 这是一个int xxx
     * ccccx
     * cxcccccccc
     * @var xx
     */
    public $x;
    
    
    /**
     * 这是y
     * @var xx
     */
    public $y;

    /**
      * 我是简单的描述
      * xxxxx
      * @param int $x hello im description [false]
      * @param array $y hhsghssx
      * @return xx [x,y,z]
     */
    public function test($x, $y)
    {
        
    }

}


$voExactor = new ryanc\api_doc\VoDocExtractor();
var_dump($voExactor->getDoc(xx::class));

$methodExactor = new ryanc\api_doc\MethodDocExtractor();
var_dump($methodExactor->getDoc(xx::class, 'test'));
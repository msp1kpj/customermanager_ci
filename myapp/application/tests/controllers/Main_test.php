<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Main extends TestCase
{
    public function test_index(){
        $output = $this->request('GET', 'index');
        $expected = "";

        $this->assertContains($expected, $output);
    }
}
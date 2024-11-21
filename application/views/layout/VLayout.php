<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    $this->parser->parse('layout/VHeader', $data);
    $this->parser->parse('layout/VContent', $data);
    $this->parser->parse('layout/VFooter', $data);   

<?php

class Morph_CheckEnv_Mongo
{
    public function __construct()
    {
        App_CheckEnv::assert( class_exists( 'MongoDB' ),  'MongoDB class cannot be found' );
    }
}
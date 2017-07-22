<?php

interface I {
    
}

class A implements I
{
    
}

var_dump(isset(class_implements('A')[I::class]));
var_dump(isset(class_implements('B')[I::class]));

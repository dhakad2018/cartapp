<?php
function cart_total($cart) {
    $total = 0;
    if(!empty($cart)){
        foreach($cart as $item){
            $total += $item['price'] * $item['qty'];
        }
    }
    return $total;
}
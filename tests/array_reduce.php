<?php

$fns = [
    function ($result, $carry) {
        $result = $carry($result + 1);
        return $result + 100;
    },
    function ($result, $carry) {
        return $carry($result + 2);
    },
];

$then = array_reduce(array_reverse($fns), function ($carry, $item) {
    return function ($result) use($carry, $item) {
        return $item($result, $carry);
    };
}, function ($result) {
    return $result;
});


var_dump($then(0));
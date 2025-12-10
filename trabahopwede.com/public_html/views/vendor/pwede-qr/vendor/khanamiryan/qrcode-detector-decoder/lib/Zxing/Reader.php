<?php
namespace Zxing;

interface Reader {
    public function decode($image, $hints = null);
}
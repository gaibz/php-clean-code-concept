<?php

/**
 * Konsep penggunaan variable yang efisien
 * @author : Herlangga Sefani <https://github.com/gaibz>
 */

/**
 * RULES :
 * - return secepatnya dan jangan nesting terlalu dalam. terlalu banyak if - else bisa bikin code jadi susah dimengerti
 * - gunakan type hinting sebisa mungkin selama masih memungkinkan
 * - jangan menggunakan kata yang tidak diperlukan
 * - gunakan parameter default
 * - manfaatkan phpdoc buat parameter description
 */

// Contoh 1 :
// ini sulit dimengerti dan nested if nya terlalu dalam
function isFruit($fruitname) : bool {
    if($fruitname) {
        if(is_string($fruitname)) {
            $fruitname = strtolower($fruitname);
            if($fruitname === 'apple') {
                return true;
            }
            else if($fruitname === 'banana') {
                return true;
            }
            else if($fruitname === 'grape') {
                return true;
            }
            else {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

// singkat padat dan jelas
function isVegetable(string $name = '') : bool {
    if(empty($name)) {
        return false; // return secepatnya jika data tidak sesuai
    }

    $name = strtolower($name);
    $vegetables = ['spinach', 'tomato', 'potato'];

    return in_array($name, $vegetables);
}


// contoh 2 :
// terlalu panjang. padahal bisa dipersingkat
function isOdd($n) : bool{
    if(is_int($n)) {
        if($n >= 0 && $n <= 50) {
            $cek = $n % 2;
            if($cek > 0) {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

// singkat padat dan jelas
function isEven(int $n) : bool {
    if($n < 0 || $n > 50) {
        return false;
    }

    return ($n % 2) === 0;
}


// Contoh 2 :
// penggunaan kata yang sama dengan parent nya
class Laptop {
    public $laptop_processor;
    public $laptop_ram;
    public $laptop_gpu;
    public $laptop_model;
    public $laptop_price;
}

// seharusnya penggunaan kata variable bisa diminimalisir dengan mengurangi yang tidak perlu
// dan lebih diperjelas dengan menggunakan type hinting
class Pc {
    public string $processor;
    public float $ram;
    public string $gpu;
    public string $model;
    public float $price;
}


// contoh 3 :
// ini kurang bagus karena $name bisa jadi null, integer, atau tipe data lainnya
function createLaptop($name = '') : void {
    // .....
}

// ini cukup bagus, sering dipake banyak framework. tapi masih kurang baik
function createPc($name = null) : void {
    $name = isset($name) ? $name : ''; // oldschool style php
    $name = is_string($name) ? $name : ''; // contoh oldschool lain
    $name = $name ?? ''; // php 7 null coallescing
    $name ??= ''; // php 7.4 null coallescing
    // ....
}

// ini paling bagus. jelas dan gak bikin ambigu karna pake type hinting
function createMiniPc(string $name = '') : void {
    // ....
}


// contoh 4 :
// sebenernya ini udah bagus. tapi masih perlu sedikit polesan dari phpdoc biar lebih jelas $laptop disini isinya apa aja
function setLaptop(object $laptop) : void {
    // ....
}

// manfaatkan phpdoc biar jelas @param dan @return nya apa
/**
 * function description
 * @param object $mini_pc {processor, ram, gpu, model, price}
 * @return void
 */
function setMiniPc(object $mini_pc) : void {
    // ....
}

// much better pake type hinting dari object yang udah didesain sebelumnya
/**
 * function description
 * @param Pc $pc
 * @return void
 */
function setPc(Pc $pc) : void{
    // ...
}
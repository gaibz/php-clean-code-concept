<?php
/**
 * Memahami konsep kontrol struktur yang mudah dipahami
 * @author : Herlangga Sefani <https://github.com/gaibz>
 */

/**
 * RULES :
 * - gunakan identical operator (=== daripada ==)
 * - logic harus simple dan se-efisien mungkin
 * - gunakan ? (question mark) dibanding if jika memang value nya sederhana
 * - gunakan switch untuk nilai yg sudah diketahui dan gunakan if untuk expression
 * - hindari nested terlalu dalam
 * - Lanjutkan looping secepatnya jika nilai tidak sesuai
 * - keluar dari looping secepatnya jika nilai sudah didapatkan
 */

// contoh 1 :
// ini seharusnya bisa disederhanakan dan disempurnakan
$number = 1; // initialization
$number_type = "";
if($number == 0) {
    $number_type = "neutral";
}
else if($number > 0) {
    $number_type = "positive";
}
else
{
    $number_type = "negative";
}

// sedikit sederhana tapi masih kurang bagus
$number = 0; // initialization
$number_type = "";
if ($number === 0) $number_type = "neutral";
else if ($number > 0) $number_type = "positive";
else $number_type = "negative";

// kalau masih bisa dalam 1 baris kenapa harus banyak2 ??
$number = -1; // initialization
$number_type = $number === 0 ? "neutral" : ($number > 0 ? "positive" : "negative");


// contoh 2 (pengecekan 2 kondisi):
// kurang bagus dan ga indah dipandang
function getTitleBad(int $age = 0, string $gender="") : string {
    $genders = ["male","female"];
    if(in_array($gender, $genders)) {
        if($age > 20) {
            if($gender === "male") {
                return "Mister";
            }
            else if($gender === "female") {
                return "Madam";
            }
            else {
                return "";
            }
        } else {
            if($age > 0) {
                if($gender === "male") {
                    return "Boy";
                }
                else if($gender === "female") {
                    return "Girl";
                }
                else {
                    return "";
                }
            }
            else {
                return "";
            }
        }
    }
    else
    {
        return '';
    }
}

// good ,, gampang dipahami dan enak dipandang
function getTitle(int $age = 0, string $gender = "") : string {
    $genders = ["male","female"];
    if(!in_array($gender, $genders) || $age <= 0) {
        return ""; // return secepatnya jika data tidak sesuai
    }
    $is_male = ($gender === "male");
    // gunakan ? dibanding if jika memang sederhana
    return $age > 20 ?
        ($is_male ? "Mister" : "Madam") :
        ($is_male ? "Boy" : "Girl");
}


// contoh 3 (logic didalam looping):
// initialization
$cars = ['', 'Avanza','Xenia','Mobilio','Ertiga'];
// bad
foreach($cars as $car) {
    if(!empty($car)) {
        if($car === "Avanza") {
            echo "Toyota";
        }
        else if($car === "Xenia") {
            echo "Daihatsu";
        }
        else if($car === "Mobilio") {
            echo "Honda";
        }
        else if($car === "Ertiga") {
            echo "Ertiga";
        }
    }
}

// good
foreach($cars as $car) {
    if(empty($car)) continue; // lanjutkan secepatnya jika data tidak sesuai

    if($car === "Avanza") echo "Toyota";
    else if($car === "Xenia") echo "Daihatsu";
    else if($car === "Mobilio") echo "Honda";
    else if($car === "Ertiga") echo "Ertiga";
}

// contoh lain
// Ceritanya ini mau nyari nilai yg sama dan kalo udah ketemu berhenti

// bad, gak enak diliatnya
$found = false;
foreach($cars as $key => $car) {
    if($found === false) {
        if($car === 'Avanza') {
            echo 'Found in key : '.$key.PHP_EOL;
            $found = true;
        }
    }
    // loooping tetap berjalan meskipun nilai found nya dirubah jadi true code blocking tetap berjalan
}

// good, simple dan enak diliatnya
foreach($cars as $key => $car) {
    if($car === 'Xenia') {
        echo "Found in key : ".$key;
        break; // keluar looping secepatnya jika nilai sudah didapatkan
    }
}

// Tambahan (if vs switch)
// NOTE : Switch hanya untuk variable yang nilai nya sudah diketahui dan tidak membutuhkan expresi
// if akan melebar ke samping
$car = "Avanza";
if($car === "Xenia" || $car === "Avanza" || $car === "Fortuner") {
    $vendor = "Toyota";
}
else if($car === "Jazz" || $car === "Mobilio" || $car === "CRV") {
    $vendor = "Honda";
}
// kadang ada yang dibikin kaya gini biar kebawah tapi jadi gak enak diliatnya
else if(
    $car === "Ertiga" ||
    $car === "Karimun" ||
    $car === "Jimny"
) {
    $vendor = "Suzuki";
}
else {
    $vendor = "";
}

$bike = "Vario";
// switch bisa di grouping ke bawah dibanding if yang menyamping
switch($bike) {
    case "Mio" :
    case "Nmax" :
    case "Xmax" :
        $vendor = "Yamaha";
        break;

    case "Beat" :
    case "Vario" :
    case "PCX" :
        $vendor = "Honda";
        break;

    default :
        $vendor = "";
        break;
}

// Tambahan (for vs while)
// for hanya untuk nilai yang sudah diketahui
for($index=0; $index <= 1000; $index++) {
    // do some work
    // ...
    // ...
    echo $index;
}

// while untuk nilai yang belum diketahui
$run = true;
while($run) {
    // do some work
    // ....
    // ....
    // jika work sudah selesai nonaktifkan loop
    $run = false;
}

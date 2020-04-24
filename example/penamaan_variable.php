<?php
/**
 * Konsep Penamaan Variable yang baik dan benar
 * @author : Herlangga Sefani <https://github.com/gaibz>
 */

/**
 * RULES :
 * - Gunakan nama variable yang jelas dan mudah dipahami.
 * - Gunakan lowercase dipisah dengan underscore atau camelCase, tapi lebih baik jika lowercase aja dengan underscore
 * - nama variable tidak lebih dari 3 kata
 * - gunakan ending s untuk kata yang punya nilai banyak (array).
 * - reusable variable
 * - masukkan variable ke dalam object jika memang memiliki parent yang sama
 */

// contoh 1 :
$n = "Your Name"; // gak jelas , n bisa diartikan sebagai number juga dalam f(n)
$name = "Your Name"; // jelas dan mudah dipahami

// Contoh 2 :
$ymd = date("Y-m-d"); // gak jelas,
$current_date = date("Y-m-d"); // jelas dan mudah dipahami

// contoh 3 :
$string_42 = '42'; // maksudnya string_42 tuh apa ?? dan kenapa nilainya string padahal 42 itu integer ?
$fourty_two_string = '42'; // jelas

// contoh 4 :
$users = ['Gaib']; // ceritanya banyak data

foreach($users as $k => $v) {
    // $v gak jelas dan $k maksudnya apa ? index kah ?
    echo $v;
}

foreach($users as $key => $user) {
    // $user jelas maksudnya adalah single user, dan $key adalah key dari array .. jelas dan mudah dipahami
    echo $user;
}

// much better for array looping
array_walk($users, function($user, $key)
{
    // lebih baik karena variable $user dan $key ada didalam scope ..
    echo $user;
});

// Pengecualian :
// penyingkatan variable boleh dilakukan didalam scope jika scope itu tidak besar dan hanya untuk indexing angka
// contoh 1 :
for($i = 0; $i < 10; $i ++) {
    // $i disini dia cuma sebagai index didalam scope, dan mudah diartikan kalau $i itu singkatan dari $index
    echo $i;
}

// tetapi akan lebih baik jika
for($index = 0; $index < 10; $index ++ ) {
    // $index disini lebih jelas kalau maksudnya adalah index
    echo $index;
}

// contoh 5 :
$first_name = "Herlangga";
$last_name = "Sefani";

$full_name = "Herlangga Sefani"; // bad, non reusable,

$full_name = $first_name." ".$last_name; // good ,, reusable variable

$full_name = implode(" ", [$first_name, $last_name]); // just another example


// bad , karena jadi ga reusable
if(json_decode('{"a":"a"}')->a === "a") {
    echo true;
}

// good, dibikin jadi reusable variable dulu
$json = json_decode('{"a":"a"}');
if($json->a === "a") {
    echo true;
}

// contoh 6 :
// bad , karena jadi banyak nama variable padahal tujuannya adalah membuat user
$user_info = "info";
$user_data = "data";
$user_name = "Gaib";

// bagus, tapi masih kurang benar, karena array harusnya sebagai kumpulan data bukan sebagai object
$user = [
    'info' => 'info',
    'data' => 'data',
    'name' => 'Gaib'
];

// good, disatukan jadi object
$user = new stdClass();
$user->info = "info";
$user->data = "data";
$user->name = "Gaib";

// contoh 7 :
// ceritanya ada fungsi kaya gini
function saveUser(string $name, string $country) {
    return $name." ".$country;
}
// just in case punya variable yg harusnya object tapi mesti dibikin array

$user = ['Gaib', 'Indonesia']; // bad karena nanti panggilnya harus $user[0], $user[1], dst....
saveUser($user[0], $user[1]); // sulit dipahami 0 dan 1 maksudnya apa ??

// good, karena nanti panggilnya jelas $user['name'], $user['country']
$user = [
    'name' => "Gaib",
    'country' => "Indonesia"
];
saveUser($user['name'], $user['country']);

// better, compile dulu jadi object, biar pemanggilan selanjutnya adalah object
$user = (object) $user;
saveUser($user->name, $user->country);


// contoh 8 :
// gunakan nilai yang mudah dipahami
$json = json_encode($user, 16 | 32 | 2); // bad, maksudnya 16 32 2 itu apa ??
$json = json_encode($user, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK | JSON_HEX_AMP); // good, ngerti maksudnya

// contoh 9 :
// Bad
class UserBad {
    // gak jelas maksudnya 1 itu apa ??
    public $roles = 1;
}
$user = new UserBad();
// maksudnya 3 itu apa ?
if($user->roles === 3) {
    // ....
    return false;
}
// ini buat apa lagi ?
$user->roles = 2;

// Good
class UserGood {
    public const ROLE_ADMIN = 1;
    public const ROLE_USER = 2;
    public const ROLE_GUEST = 3;

    public $roles = self::ROLE_ADMIN; // secara default role nya adalah admin. jelas
}
$user = new UserGood();

// jelas ngecek kalo role ini guest atau bukan
if($user->roles === UserGood::ROLE_GUEST) {
    // ....
    return false;
}
$user->roles = UserGood::ROLE_USER; // jelas kalo ini dibikin jadi role user
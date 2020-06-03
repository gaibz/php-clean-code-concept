<?php
/**
 * @author : Herlangga Sefani <https://github.com/gaibz>
 */

/**
 * RULES :
 * - Selalu gunakan PHPDOC sebagai function description
 * - Nama fungsi maximal adalah 3 kata dengan penulisan camelCase dan harus mendeskripsikan tujuan dari fungsi tersebut
 * - Jangan mengulang2 statement .. tapi buat function untuk statement tersebut.
 * - Fungsi harus mendeskripsikan tujuan yang jelas dan to the point
 * - Pisahkan fungsi jika berbeda tujuan
 * - Jangan merubah nilai global variable didalam fungsi
 * - Parameter fungsi harus dibawah 2 jangan kebanyakan (Gunakan interface / object jika memungkinkan)
 * - jangan membuat fungsi global, tetapi jika terpaksa gunakan pengecekan function_exists() sebelum fungsi
 * - Hindari bool flag sebisa mungkin
 * - Return secepatnya (cek kemungkinan kesalahan terlebih dahulu)
 * - Hapus fungsi yang tidak terpakai (Manfaatkan git sebagai version control)
 * - pindahkan semua statement didalam anonymous function ke dalam function baru agar bisa reusable
 */


// Contoh 1 :
// ini memungkinkan terjadinya bug yang susah di tracking dan code jadi susah dimaintenance
$base = 10;
$height = 20;
$area_of_triangle = round(($base * $height) / 2);
// ....
// sementara itu, entah di line berapa ataupun file lainnya ditemukan code yang sama persis cuma beda value aja
$base = 15;
$height = 29;
$area_of_triangle = round(($base * $height) / 2);

// sebaiknya buat jadi function
function areaOfTriangle(int $base = 0, int $height = 0) :  int {
    $area = ($base * $height) / 2;
    return round($area);
}

// ....
// sementara itu, di line berikutnya dan seterusnya ataupun file lainnya tinggal panggil fungsi tersebut jika dibutuhkan
$area_of_triangle = areaOfTriangle(10, 20);


// Contoh 2 :
// Fungsi terlalu banyak statement yang tidak relevan dengan fungsi tersebut.
function getStudent(int $id) {
    // create curl instance
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "example.com");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    $students = json_decode($output);
    foreach($students as $student) {
        if($student->id === $id) {
            return $student;
        }
    }

    return null;
}

// sebaiknya pisah fungsi jadi beberapa bagian. hal ini untuk memungkinkan reusable function nantinya
function getCURL(string $url = '') : string {
    // create curl instance
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

function getTeachers() : array {
    $teachers = getCURL('https://somedomain.com/to/fetch/teachers');
    return json_decode($teachers);
}

function getTeacher(int $id) : object {
    $teachers = getTeachers();
    foreach($teachers as $teacher) {
        if($teacher->id === $id) {
            return $teacher;
        }
    }

    // return empty object kalo datanya gak ditemukan
    return (object)[];
}

// lebih baik lagi kalau dibikin object oriented dengan dependency injection biar nanti kalo unit testing bisa gampang
interface CURL_Driver_Interface {
    public function get(string $url = '') : string ;
}

class CURL_Driver implements  CURL_Driver_Interface {

    public function get(string $url = '') : string
    {
        // create curl instance
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    //.....
}

class CURL_Driver_Test implements CURL_Driver_Interface {
    public function get(string $url = '') : string
    {
        return '[]'; // ceritanya json array of object
    }
    //.....
}

class Teacher {
    private CURL_Driver_Interface $curl;

    // kita pakai dependency injection
    function __construct(CURL_Driver_Interface $curl)
    {
        $this->curl = $curl;
    }

    public function getAll() : array {
        $teachers = $this->curl->get('https://somedomain.com/to/fetch/teachers');
        return json_decode($teachers);
    }

    public function get(int $id ) : object {
        $teachers = $this->getAll();
        foreach($teachers as $teacher) {
            if($teacher->id === $id) {
                return $teacher;
            }
        }

        return (object)[];
    }
    // .... dst..
}

// production
$curl = new CURL_Driver();
$teacher = new Teacher($curl);

// unit testing
$curl = new CURL_Driver_Test();
$teacher = new Teacher($curl);

// Contoh 3 :
// ini gak bagus .. karena username bisa aja dirubah dari arah yang tidak diduga2 ..
$username = "gaibz";
function changeUsername() : void {
    global $username;
    $username = strtoupper($username);
}

// jangan merubah nilai global variable didalam fungsi
$password = "123456";
function setPassword(string $password = '') {
    return md5($password);
}

$password = setPassword("superSecretPassword123456");

// Contoh 4 :
// ini sebenernya bagus, tapi bisa bentrok kalo ada orang yg nulis fungsi serupa
function config() : array {
    return [
        'key' => 'val'
    ];
}

// jika memang maksa pengen bikin global function maka pakai function_exists() buat ngecek dulu
if(!function_exists('config')) {
    function config() : array {
        return [
            'key' => 'val'
        ];
    }
}

// tapi lebih baik lagi kalo dibikin object oriented dengan namespace
//namespace Configuration; // di comment biar ga error karena namespace di tengah file
class Config {

    public function get() : array {
        return [
            'key' => 'val'
        ];
    }

}

// selanjutnya tinggal pakai instance Config
//\Configuration\Config // kalo pake namespace
function applyConfig(Config $config) {
    // ... do something
}


// Contoh 5 :
// jadi bingung mau milih yang mana ?
function getNameOld() {
    // ....
}

function getNameNew() {
    // ....
}

// manfaatkan git / sub versioning untuk manage code yang tidak terpakai
function getName() {
    // ....
}


// Contoh 6 :
// kalo anonymous function jadi ga reusable
function getStudentId(array $students, int $id) : array {
    return array_filter($students, function($student) use ($id)
    {
        // .. semua blok dalam sini jadi ga reusable
        return $student->id === $id; // non reusable
    });
}

// good, filterStudentName jadi reusable ..
function filterStudentName(object $student, string $name) {
    return $student->name === $name;
}
function getStudentName(array $students, string $name) {
    return array_filter($students, function($student) use ($name) {
        return filterStudentName($student, $name);
    });
}

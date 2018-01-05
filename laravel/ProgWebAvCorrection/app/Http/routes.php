<?php

/*
|--------------------------------------------------------------------------
| Welcome route
|--------------------------------------------------------------------------
*/
Route::get('/', 'WelcomeController@index');

/*
 |--------------------------------------------------------------------------
 | Authentification routes
 |--------------------------------------------------------------------------
 */
Route::group(['prefix' => '/auth'], function() {
    Route::get('/login', 'AuthController@login');
    Route::get('/google', 'AuthController@google');
    Route::get('/googleCallback', 'AuthController@googleCallback');
    Route::get('/logout', 'AuthController@logout');
    Route::post('/check', 'AuthController@check');
});

/*
 |--------------------------------------------------------------------------
 | Authenticate users only
 |--------------------------------------------------------------------------
 */
Route::group(['middleware' => 'auth'], function() {
    Route::get('/private1', function () {return 'your are logged';});
    Route::get('/private2', function () {return 'welcome logged user';});
    Route::get('/chat', function () {return view('chat/chat');});
    Route::resource('item', 'ItemController');
});

Route::get('/service', function () {
    $truc = App::make('App/Services/Truc');
    return $truc->machin();
});

Route::get('/truc', function () {
//    $nb = 0;
//    for ($i=0 ;$i<1000; $i++){
//        $nb += 0.01;
//    }
//    $nb = ((int)($nb * 1000 + 0.5)) / 1000;
//    dd($nb);
    $strDecompose = "e\xCC\x81le\xCC\x80ve"; // forme décomposée Normalizer::FORM_D
    $strCompose = "élève . test l'"; // Editeur de code: UTF8 - form composé Normalizer::FORM_C
    //$c = Request::input($c);
    //$str2 = normalizer_normalize($str2, Normalizer::FORM_D);


    //$strCompose = preg_replace('/v|l/', 'HELLO', $strCompose);
    //$out = preg_replace('~\p{Lu}~u', ' LETTRE MAJUSCULE ', $strCompose);
    $out = normalizer_normalize($strCompose, Normalizer::FORM_D);
    $out = preg_replace('~[^\p{L}|\p{Nd}]|\p{M}~u', '', $out);

    $collator = new Collator('fr_CH');
    //$collator->setStrength(Collator::QUATERNARY);
    if ($collator->compare($strDecompose, $strCompose) === 0) {
        dd("Chaine identique");
    }

    dd($out);

//    echo transliterator_create('Any-Name')->transliterate($str2);
//    echo "<br>";
//    echo transliterator_create('Any-Name')->transliterate($str2Decompose);
//    dd(transliterator_create('Any-Name')->transliterate($str1));

});

















Route::get('/utf8', function (){
    $normalize = App::make('App/Services/Normalize');

    echo '<pre>';
    // comparaison de deux chaines de caractères:
    $str1 = "e\xCC\x81le\xCC\x80ve";
    $str2 = 'élève';
    echo "'{$str1}' is the same as '{$str2}' ? ";
    var_dump($normalize->compare($str1, $str2) === 0);

    // tri alphabétique de noms / prénoms
    $people = array(
        "von Neuman, John",
        "Zorg, Jean Baptiste Emmanuel",
        "Renaud, Céline",
        "Renaud, Ce\xCC\x81lèv",
        "Émilien, George",
        "de La Fontaine, Jean",
    );
    print_r($people);
    usort($people, function ($p1, $p2) {
        $normalize = App::make('App/Services/Normalize');
        $p1 = $normalize->trimBeforeFirstUppercase($p1);
        $p2 = $normalize->trimBeforeFirstUppercase($p2);
        return $normalize->compare($p1, $p2);
    });
    print_r($people);

    // test de quelques palindrome
    $palindromes = array(
        "Une êve réel après Neptune. En Ut. Penser. Palée, Revée. Nu.",
        "20.02 À l'étape, épate-la 2002",
        "E\xCC\x81le\xCC\x80ve le",
        "しんぶんし",
        "Kayak sugus",
    );
    function isPalindrom ($palindrome) {
        $normalize = App::make('App/Services/Normalize');
        $palindrome = $normalize->onlyLettersAndDigits($palindrome);
        $palindrome = $normalize->removeDiacritics($palindrome);
        $str = mb_strtolower($palindrome);
        $left = 0;
        $right = mb_strlen($str)-1;
        while ($left < $right
                && mb_substr($str, $left, 1) == mb_substr($str, $right, 1)) {
            $left++; $right--;
        }
        return $left >= $right;
    }
    foreach ($palindromes as $palindrome) {
        echo "'{$palindrome}' is a palindrom ? ";
        var_dump(isPalindrom($palindrome));
    }
    echo '</pre>';
});
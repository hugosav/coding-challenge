<?php

/* Fetch some data from wikipedia pages by Query parameter */

function query_wikipedia_api($search_terms) {

    //convert each words to an array
    $args = explode('+', $search_terms);

    //search each word in wikipedia and log into an array
    foreach ($args as $key => $arg) {
       $url = "https://en.wikipedia.org/w/rest.php/v1/search/page?q=$arg&limit=10";
       $response = wp_remote_get($url);

       $results[] = $response['body'];
    }

    //We decode each json in array
    foreach ($results as $key => $result) {
      $result = json_decode($result);
    }

    //return pages results
    return $result->pages;
}

/* 
  This function generate a ninja name based by the user entry
  and some ninja related words 
*/
function name_generator($request) {

  //looking for the entries
  $args = $request['user_entry'];

  //Generating wikipedia results from user entries
  $wiki_results = query_wikipedia_api($args);

  //Define an array of words related to ninja
  $ninja_words = array('ninja','shuriken','martial+arts',"nunchaku",'bruce+lee','dragon');

  /*
    Random array key to select ninja word 
    that will be used to generate the word bank 
  */
  $random_ninja_word = array_rand($ninja_words,1);

  
  $ninja_words_results = query_wikipedia_api($ninja_words[$random_ninja_word]);

  //Generate two wordbanks from wikipedia results 
  $ninja_word_bank = generate_word_bank($ninja_words_results, true);
  $user_word_bank = generate_word_bank($wiki_results,false);

  //Generate ninja name from both word banks 
  $name['ninja_name'] = generate_ninja_names($user_word_bank,$ninja_word_bank);

  //Return a json result and kill the process 
  echo json_encode($name);

  die();
}

/*Generate a word bank to use from Wikipedia pages title */

function generate_word_bank($wiki_results,$isNinja) {

  $word_bank = array();

  //for each $wiki_results we select the page title
  foreach ($wiki_results as $key => $wiki_result) {

    //create an array of words from the page title
    $stripped_words = explode(' ', $wiki_result->description);

    //We fetch title for ninja or empty desc (Title is more relevant)
    if($isNinja || empty($stripped_words)) {
        //create an array of words from the page title
        $stripped_words = explode(' ', $wiki_result->title);
    }

    //Remove short and most of the time useless words from array 
    $stripped_words = array_values(array_filter($stripped_words,function($v){ return strlen($v)  > 4; }));

    if(!empty($stripped_words)) {
        //We select the first word of the title array
        $rng = array_rand($stripped_words,1);
        $stripped_word = strtolower($stripped_words[$rng]);
        //We validate the word selected 
        $stripped_word = validate_words($stripped_word,$word_bank);
    }
    else {
      $stripped_word = "invalid";
    }


    if($stripped_word === "invalid") {
    }
    else{
      // Add the selected word to the word bank
      $word_bank[] = $stripped_word;
    }

  }
  
  return $word_bank;
}

/* This function is generating multiple name based by the word banks*/

function generate_ninja_names($user_word_bank, $ninja_word_bank) {
  
  //Randomize some array key to select random words from the bank 
  $random_array_key = array_rand($user_word_bank, 2);
  $random_ninja_key = array_rand($ninja_word_bank, 2);

  //fetching words from user_word_banks 
  $random_firstname = $user_word_bank[$random_array_key[0]];
  $random_lastname = $user_word_bank[$random_array_key[1]];

  //same for ninja words
  $random_ninja = $ninja_word_bank[$random_ninja_key[0]];
  $random_ninja_2= $ninja_word_bank[$random_ninja_key[1]];

  if(empty($random_firstname) || empty($random_lastname)) {
    die();
  }

  //Create different name variation based on the selected words
  $name["ninja_name_1"] = "The $random_firstname of the $random_ninja";
  $name["ninja_name_2"] = "The $random_firstname $random_lastname of the $random_ninja";
  $name["ninja_name_3"] = "Master $random_firstname  of the $random_ninja";
  $name["ninja_name_4"] = "The $random_lastname $random_ninja_2 of $random_ninja";
  $name["ninja_name_5"] = "Master $random_lastname of $random_ninja_2";
  $name["ninja_name_6"] = "Master $random_ninja $random_lastname";
  $name["ninja_name_7"] = "The $random_ninja in $random_firstname";
  $name["ninja_name_8"] = "$random_ninja_2 $random_firstname $random_ninja";
  $name["ninja_name_9"] = "$random_ninja_1 $random_lastname $random_ninja_2";
  
  //Select a random name from $name array
  $random_name = array_rand($name, 1);

  //Creating a new array to avoid sending useless data with the name selected
  $name["ninja_name"] = $name[$random_name];

  //Stripping special characters
  $ninja_name = preg_replace("/[^a-zA-Z0-9_ -]/s", "", $name["ninja_name"]);
  $ninja_name = str_replace("  ", " ", $ninja_name);
  return $ninja_name;

}

/* We validate if the words selected are meeting some criteria */
function validate_words($stripped_word, $word_bank) {
  /*if the word meet these conditions it's declared invalid and loop until we find one that meet the criteria */
  if(empty($stripped_word) || is_int($stripped_word) || in_array($stripped_word, $word_bank)) {
    $stripped_word = "invalid";
  }

  return $stripped_word;
}

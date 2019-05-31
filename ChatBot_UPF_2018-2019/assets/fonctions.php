<?php
//set timezone
date_default_timezone_set("Pacific/Tahiti");

//principal declaration

$codeWeather = array('200' => 'orage',
                                '201' => 'orage',
                                '202' => 'orage',
                                '230' => 'orage',
                                '231' => 'orage',
                                '232' => 'orage',
                                '233' => 'orage',
                                '300' => 'bruine',
                                '301' => 'bruine',
                                '302' => 'bruine',
                                '500' => 'pluie',
                                '501' => 'pluie',
                                '502' => 'pluie',
                                '511' => 'pluie',
                                '520' => 'pluie',
                                '521' => 'pluie',
                                '522' => 'pluie',
                                '600' => 'neige',
                                '601' => 'neige',
                                '602' => 'neige',
                                '610' => 'neige',
                                '611' => 'neige',
                                '612' => 'neige',
                                '621' => 'neige',
                                '622' => 'neige',
                                '623' => 'neige',
                                '700' => 'brouillard',
                                '711' => 'brouillard',
                                '721' => 'brouillard',
                                '731' => 'brouillard',
                                '741' => 'brouillard',
                                '751' => 'brouillard',
                                '800' => 'soleil',
                                '801' => 'nuageux',
                                '802' => 'nuageux',
                                '803' => 'nuageux',
                                '804' => 'nuageux',
                                '900' => 'pluie'
                            );

$listJour = array( '1' => 'lundi',
                    '2' => 'mardi',
                    '3' => 'mercredi',
                    '4' => 'jeudi',
                    '5' => 'vendredi',
                    '6' => 'samedi',
                    '7' => 'dimanche'
                );

/**
 * $bool indicate wether the day is in the future or not
 */
function codeToMessage($string, $bool, $lieu, $temperature, $weather, $date) {
    // parse la première lettre du lieu
    $lieu = strtoupper($lieu[0]) . substr($lieu, 1, strlen($lieu));
    if ($bool) {
        //today
        switch ($string) {
            case 'orage':
                $toSay = "Il y a de l'orage ";
                break;
            case 'bruine':
                $toSay = "Il y a de la bruine ";
                break;
            case 'pluie':
                $toSay = "Il y a de la pluie ";
                break;
            case 'neige':
                $toSay = "Il neige ";
                break;
            case 'brouillard':
                $toSay = "Il y a du brouillard ";
                break;
            case 'soleil':
                $toSay = "Il y a du soleil ";
                break;
            case 'nuageux':
                $toSay = "Il y a des nuages ";
                break;
            default:
                return "<h1>ERREUR : Pas de code météo detecté !</h1>";
        }
    } else {
        //later
        switch ($string) {
            case 'orage':
                $toSay = "Il y aura de l'orage ";
                break;
            case 'bruine':
                $toSay = "Il y aura de la bruine ";
                break;
            case 'pluie':
                $toSay = "Il y aura de la pluie ";
                break;
            case 'neige':
                $toSay = "Il neigera ";
                break;
            case 'brouillard':
                $toSay = "Il y aura du brouillard ";
                break;
            case 'soleil':
                $toSay = "Il y aura du soleil ";
                break;
            case 'nuageux':
                $toSay = "Il y aura des nuages ";
                break;
            default:
                return "<h1>ERREUR : Pas de code météo detecté !</h1>";
        }
    }
    
    return $toSay . " à " . $lieu . " (" . $temperature . "°C) le " . $date . ".";
}


/**
 * Use cURL function
 * Won't work if you use a proxy on your local server
 * For test purpose
 */
function queryLuis($string) {
    $query = [
        'subscription-key' => '4f2dd27cb3d547c394f22d36729413b0',
        'q' => $string
    ];
    $fields_string = http_build_query($query);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://westus.api.cognitive.microsoft.com/luis/v2.0/apps/4d7bcf1c-4ab5-481b-a4a1-52209a0dea3b?verbose=true&timezoneOffset=-360&".$fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data, true);
}


/**
 * cURL on weatherbit.io (only 3 types of queries needed for our project at the moment)
 */
function getWeatherJSONToday($ville) {
    $city = [
        'city' => $ville,
        'key' => "b6e8ee5c5cc849c0a7b73a6ed044bb99"
    ];
    $fields_string = http_build_query($city);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.weatherbit.io/v2.0/current?".$fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $data = curl_exec($ch);
    curl_close($ch);

    return(json_decode($data, true));
}


function getWeatherJSONWeekly($ville) {
    $city = [
        'city' => $ville,
        'key' => "b6e8ee5c5cc849c0a7b73a6ed044bb99"
    ];
    $fields_string = http_build_query($city);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.weatherbit.io/v2.0/forecast/daily?".$fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $data = curl_exec($ch);
    curl_close($ch);

    return(json_decode($data, true));
}

/**
 * Met en forme les messages pour être afficher sur du HTML
 */

function afficheMessageUser($string) {
    echo "  <div class=\"container_user\">
                    <div class=\"avatar_user \"></div></br></br>
                    <span class=\"conversation_bleu\">$string</span></br></br>
                </div>";
}
function afficheMessageBot($string) {
    echo "  <div class=\"container_bot\">
                    <div class=\"avatar_bot \"></div>
                    <span class=\"conversation_gris\">$string</span></br></br>
                </div>";
}




/**
 * Coeur de l'automate programmable
 */
function botReply($listLuis, $listJour, $codeWeather) {
    $topIntent = $listLuis["topScoringIntent"]["intent"];
    $topScore = $listLuis["topScoringIntent"]["score"];
    $nbEntity = count($listLuis["entities"]);

    if ($topScore > 0.7) {
        if ($topIntent == "meteo" || $topIntent == "lieux" || $topIntent == "temps") {
            for($i = 0; $i < $nbEntity; $i++) {
                if ($listLuis["entities"][$i]["type"] == "activity") {
                    $activity = $listLuis["entities"][$i]["entity"];
                }
                if ($listLuis["entities"][$i]["type"] == "lieux") {
                    $lieu = $listLuis["entities"][$i]["entity"];
                    $_SESSION["lieu"] = $lieu;
                }
                if ($listLuis["entities"][$i]["type"] == "date") {
                    $date = $listLuis["entities"][$i]["entity"];
                    $date = str_replace(" ", "", $date);
                    $_SESSION["date"] = $date;
                }
                if ($listLuis["entities"][$i]["type"] == "weather") {
                    $weather = $listLuis["entities"][$i]["entity"];
                    $_SESSION["weather"] = $weather;
                }
                if ($listLuis["entities"][$i]["type"] == "salutation") {
                    $salutation = $listLuis["entities"][$i]["entity"];
                }
            }
        } else if ($topIntent == "salutations") {
            for($i = 0; $i < $nbEntity; $i++) {
                if ($listLuis["entities"][$i]["type"] == "salutation") {
                    //upgrade : Add a list of greetings, and chose randomly wich one should be used
                    return "Bonjour !";
                }
            }
        } else if ($topIntent == "presentation") {
            for($i = 0; $i < $nbEntity; $i++) {
                if ($listLuis["entities"][$i]["type"] == "salutation") {
                    return "Bonjour, je suis un agent conversationnel, et mon objectif est de répondre au mieux à toute vos questions concernant la météo.";
                }
            }
            return "Je suis un agent conversationnel, et mon objectif est de répondre au mieux à toute vos questions concernant la météo.";
        } else if ($topIntent == "None") {
            return "????";
        }
    } else {
        return "Je n'ai pas l'impression de comprendre ce que vous dites ...";
    }





    if (isset($_SESSION["lieu"])) {
        if (isset($_SESSION["date"])) {
            $today = $listJour[date('N')];
            if ($today == $_SESSION["date"] || $_SESSION["date"] == "aujourd'hui") {
                $dataWeatherToday = getWeatherJSONToday($_SESSION["lieu"]);
            } else {
                $dataWeatherWeekly = getWeatherJSONWeekly($_SESSION["lieu"]);
                switch ($_SESSION["date"]) {
                    case 'lundi':
                        $day_of_weather = 1;
                        break;
                    case 'mardi':
                        $day_of_weather = 2;
                        break;
                    case 'mercredi':
                        $day_of_weather = 3;
                        break;
                    case 'jeudi':
                        $day_of_weather = 4;
                        break;
                    case 'vendredi':
                        $day_of_weather = 5;
                        break;
                    case 'samedi':
                        $day_of_weather = 6;
                        break;
                    case 'dimanche':
                        $day_of_weather = 7;
                        break;
                    case 'demain':
                        $day_of_weather = (date('N')+1)%7;
                        break;
                    case 'après demain':
                        $day_of_weather = (date('N')+2)%7;
                        break;
                    case 'après-demain':
                        $day_of_weather = (date('N')+2)%7;
                        break;
                }
                //index of array from weatherbit
                
                if ($day_of_weather > date('N')) {
                    $day = $day_of_weather - date('N');
                    echo "OUI";
                } else {
                    $day = -(date('N') - $day_of_weather) + 7;
                    echo "NON";
                }
                //$day = ($day_of_weather + $today) % 7;


            }
            if (isset($dataWeatherToday)) {
                $lieu = $dataWeatherToday['data'][0]['city_name'];
                $temperature = $dataWeatherToday['data'][0]['temp'];
                $date = substr($dataWeatherToday['data'][0]['datetime'], 5, -3);
                if (isset($_SESSION["weather"])) {
                    $weather = $_SESSION["weather"];
                } else {
                    $weather = "";
                }
                unset($_SESSION["weather"]);
                return codeToMessage($codeWeather[$dataWeatherToday['data'][0]['weather']['code']], true, $lieu, $temperature, $weather, $date);
            }
            if (isset($dataWeatherWeekly)) {
                $lieu = $dataWeatherWeekly['city_name'];
                $temperature = $dataWeatherWeekly['data'][$day]['temp'];
                $date = substr($dataWeatherWeekly['data'][$day]['valid_date'], 5);
                if (isset($_SESSION["weather"])) {
                    $weather = $_SESSION["weather"];
                } else {
                    $weather = "";
                }
                unset($_SESSION["weather"]);
                return codeToMessage($codeWeather[$dataWeatherWeekly['data'][$day]['weather']['code']], false, $lieu, $temperature, $weather, $date);
            }
            
        }
        return "Quand ça ?";
    } else {
        return "Où donc ?";
    }
    
}



?>
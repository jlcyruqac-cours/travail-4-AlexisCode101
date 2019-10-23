<?php
/* 
	Title: SmallCMS
	Descr: Mini gestionnaire de contenu base sur flasklike
	Author: Jean-Luc Cyr
	Date: 2019/10/18
*/
require('flasklike.php');

function zodiac($day, $month)
{
    // returns the zodiac sign according to $day and $month ( https://coursesweb.net/ )
    $zodiac = array('', 'Capricorne', 'Verseau', 'Poisson', 'Bélier', 'Taureau', 'Gémeaux', 'Cancer', 'Lion', 'Vierge', 'Balance', 'Scorpion', 'Sagittaire', 'Capricorne');
    $last_day = array('', 19, 18, 20, 20, 21, 21, 22, 22, 21, 22, 21, 20, 19);
    return ($day > $last_day[$month]) ? $zodiac[$month + 1] : $zodiac[$month];
}

////////////////////////////////////////////////////////////////
// Page d'accueil
$route_defs['/']['GET'] =
    function () {
        $params = ['page_title' => 'Horoscope',
        ];
        fl_render_template('templates/index.html', $params);
    };

////////////////////////////////////////////////////////////////
// Tentative de connexion
$route_defs['/']['POST'] =
    function () {
        $params = ['fname' => $_POST['first_name'],
            'lname' => $_POST['last_name'],
        ];
        if (fl_auth($_POST['user'], $_POST['pass'])) {
            fl_redirect('/menu');
            $params['message'] = "Login OK";
            fl_render_template('index2.html', $params);
        } else {
            $params['message'] = "Informations invalides";
            fl_render_template('index2.html', $params);
        }
    };

////////////////////////////////////////////////////////////////
// Get horoscope
$route_defs['/horoscope']['POST'] =
    function () {
        $params = ['first_name' => $_POST['first_name_input'],
            'last_name' => $_POST['last_name_input'],
            'birthday_date' => $_POST['birthday_date']
        ];

        # Horoscope dictionnary
        $horoscope_dict = [
            "Sagittaire"=> "Vous serez riche.",
            "Capricorne"=> "Vous ne serez pas riche.",
            "Verseau"=> "Vous trouverez l'amour.",
            "Poissons"=> "Vous ne trouverez pas l'amour.",
            "Bélier"=> "Vous serez chanceux aujourd'hui.",
            "Taureau"=> "Vous ne serez pas chanceux aujourd'hui.",
            "Gémeaux"=> "Vous serez riche.",
            "Cancer"=> "Vous ne serez pas riche.",
            "Lion"=> "Vous trouverez l'amour.",
            "Vierge"=> "Vous ne trouverez pas l'amour.",
            "Balance"=> "Vous serez chanceux aujourd'hui.",
            "Scorpion"=> "Vous ne serez pas chanceux aujourd'hui."
        ];

        # Init status var
        $status = 'Les paramètres suivants sont manquant : ';
        $status_arg = '';

        preg_match("'(\d{2})[/.-](\d{2})[/.-](\d{4})$'", $params['birthday_date'], $matches);

        # Validate input
        if ($params['last_name'] == '') {
            $status_arg .= ' | nom de famille | ';
        }

        if ($params['first_name'] == '') {
            $status_arg .= ' | prénom | ';
        }

        if ($params['birthday_date'] == '') {
            $status_arg .= ' | date de naissance | ';
        }
        # If missing input
        if ($status_arg) {
            $status .= $status_arg;
            $params = ['status' => $status];
            return fl_render_template('templates/input_error.html', $params);
        }

        # If missing input
        if (!$matches) {
            $params = ['status' => "Veuillez entrer une date sous la forme mm/dd/yyyy"];
            return fl_render_template('templates/input_error.html', $params);
        }

        $month = date("m",strtotime($params['birthday_date']));
        $day = date("d",strtotime($params['birthday_date']));

        $astro_sign = zodiac($day, $month);
        $params['astro_sign'] = $astro_sign;

        $params['horoscope_dict'] = $horoscope_dict[$astro_sign];

        fl_render_template('templates/horoscope_display.html', $params);
    };


////////////////////////////////////////////////////////////////
// and after all definitions should call flasklike_run()
fl_run();

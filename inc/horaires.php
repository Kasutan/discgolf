<?php

add_shortcode('horaires','ds_affiche_horaires');
function ds_affiche_horaires($args) {
	if(!function_exists('get_field')) {
		return;
	}

	ob_start();
	$plage_horaire=esc_html(get_field('dg_plage_horaire','option'));	
	$jours_fermeture=wp_kses_post(get_field('dg_jours_fermeture','option'));
	$jours_array=explode(',',$jours_fermeture);

	$aujourdhui=date('d/m/Y');

	echo '<p class="horaires">';

	if(in_array($aujourdhui,$jours_array)) {
		echo 'Fermeture exceptionnelle aujourd\'hui';
	} else {
		printf('Ouvert aujourd\'hui : %s',$plage_horaire);
	}

	echo '<br><span class="sep"></span>';

	$demain_stamp=strtotime(date('YmdY').' + 1day');
	$demain=date('d/m/Y',$demain_stamp);
	if(in_array($demain,$jours_array)) {
		echo 'Fermeture exceptionnelle demain';
	} else {
		printf('Ouvert demain : %s',$plage_horaire);
	}

	echo '</p>';

	return ob_get_clean();
}

add_shortcode('horaires-complets','ds_affiche_horaires_complets');
function ds_affiche_horaires_complets($args) {
	if(!function_exists('get_field')) {
		return;
	}
	
	ob_start();
	$plage_horaire=esc_html(get_field('dg_plage_horaire','option'));	
	$jours_fermeture=wp_kses_post(get_field('dg_jours_fermeture','option'));
	if(!empty($jours_fermeture)) {
		$jours_array=explode(',',$jours_fermeture);
	} else {
		$jours_array=false;
	}
	$jours_display=implode(', ',$jours_array);
	
	echo '<p class="horaires complet">';

	printf('Ouvert du lundi au dimanche : %s</br>',$plage_horaire);

	if(!empty($jours_array)) {
		printf('sauf aux dates suivantes&nbsp: <strong>%s</strong>',$jours_display);
	}

	echo '</p>';

	return ob_get_clean();
}
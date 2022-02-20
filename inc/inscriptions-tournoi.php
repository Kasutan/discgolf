<?php

add_shortcode('inscriptions','ds_affiche_inscriptions');
function ds_affiche_inscriptions($atts) {
	if(!class_exists('Forminator_API')) {
		return;
	}

	$atts = shortcode_atts( array(
		'id-formulaire' => '530',
	), $atts, 'inscriptions' );

	$entries=Forminator_API::get_entries( $atts['id-formulaire'] );
	if(empty($entries)) {
		return;
	}


	$affichage="public";
	if(is_user_logged_in(  ) && current_user_can( 'manage_options')) {
		$affichage="admin";
	}



	ob_start();


		$aujourdhui=date('w');
		$maintenant = date('H'); //GMT = 2h d'écart avec l'heure française l'été
		if($aujourdhui == 0 && $maintenant < 10) {
			//On est dimanche matin, le prochain tournoi est aujourd'hui
			$prochain_tournoi=date("d/m/Y");
			$prochaine_limite=date("Y-m-d").' 10:00';
		} else {
			$prochain_tournoi=date("d/m/Y", strtotime("next Sunday"));
			$prochaine_limite=date("Y-m-d", strtotime("next Sunday")).' 10:00';
		}

		if($aujourdhui == 0 && $maintenant > 10) {
			//On est dimanche après-midi, le tournoi précédent est aujoud'hui
			$tournoi_precedent=date("d/m/Y");
			$limite_precedente=date("Y-m-d").' 10:00';
		} else {
			$tournoi_precedent=date("d/m/Y", strtotime("last Sunday"));
			$limite_precedente=date("Y-m-d", strtotime("last Sunday")).' 10:00';
		}

		$prochaine_limite_timestamp=strtotime($prochaine_limite);
		$limite_precedente_timestamp=strtotime($limite_precedente);


		printf('<p><strong>Date du prochain tournoi : %s</strong></p>',$prochain_tournoi);

		$liste=dg_prepare_liste_inscrits($entries, $affichage,$limite_precedente_timestamp,$prochaine_limite_timestamp);
		if(!empty($liste)) {
			echo '<ul>';
			echo $liste;
			echo '</ul>';
		} else {
			echo '<p>Aucune inscription pour le prochain tournoi</p>';
		}

	return ob_get_clean();

}

function dg_prepare_liste_inscrits($entries, $affichage,$from,$to) {
	$liste='';
	$nb_repas=$nb_inscrits=0;
	$emails=array();
	foreach($entries as $entry) {
		if($entry->is_spam) {
			break;
		}
		
		$date_inscription=$entry->date_created_sql;
		$timestamp_inscription=strtotime($date_inscription);
		$date_inscription_pour_affichage=date('d/m/Y à h:i',$timestamp_inscription);
		if($timestamp_inscription < $from) {
			//$entries est classé par date, de la plus récente à la plus ancienne. Quand on atteint une inscription qui est plus ancienne que dimanche dernier, on peut s'arrêter là
			break;
		} elseif ($timestamp_inscription < $to) {
			$nb_inscrits++;
			$infos=$entry->meta_data;
			$nom=$infos['name-1']['value']['last-name'];
			$prenom=$infos['name-1']['value']['first-name'];
			
			if($affichage=="admin") {
				$email=$infos['email-1']['value'];
				$emails[]=$email;
				$note=$infos['textarea-1']['value'];
				$avec_repas=isset($infos['checkbox-1']);
				$liste.=sprintf('<li><a href="mailto:%s">%s %s</a>, inscription du %s.',$email,$prenom,$nom,$date_inscription_pour_affichage);
				if($note) {
					$liste.=sprintf('</br><em>%s</em>',$note);
				}
				if($avec_repas) {
					$liste.=sprintf('</br><strong>avec repas</strong>');
					$nb_repas++;
				}
				$liste.='</li>';
			} else {
				$initiale=substr($nom,0,1);
				$liste.=sprintf('<li>%s %s. inscription du %s.</li>',$prenom,$initiale,$date_inscription_pour_affichage);
			}
		}
	}
	if($affichage=="admin") {
		if($nb_inscrits>0) {
			$liste.=sprintf('<li><strong>Total inscriptions : %s</strong></li>',$nb_inscrits);
		}
		if($nb_repas > 0) {
			$liste.=sprintf('<li><strong>Total repas : %s</strong></li>',$nb_repas);
		}
		if(!empty($emails)) {
			$liste.=sprintf('<li><a href="mailto:?bcc=%s">Envoyer un email groupé à toutes les personnes inscrites</a></li>',implode(",",$emails));
		}
	}

	return $liste;
}
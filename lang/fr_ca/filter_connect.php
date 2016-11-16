<?php // $Id: refineddata connect filter,v 1.1 2008/05/16 12:00:00 terryshane Exp $

$string['pluginname']					   = 'Filtre Connect';
$string['connect:editresource']  = 'Modifier la ressource sur Adobe';

$string['editaclogin']         	 = 'Administrateur peut modifier le Connexion AC sur le profil de l\'utilisateur';
$string['editaclogin_hint']    	 = '<font size=\"1\">Activer si vous voulez que les administrateurs soient en mesure d\'entrer les Connexions AC directement sur les profils (utilisateurs ajoutés via Connect)</font>';

$string['prefix']              	 = 'Préfixe pour l\'ID de l\'utilisateur';
$string['prefix_hint']         	 = '<font size=\"1\">Nous vous proposons un code de 4 caractères pour assurer des ID d\'utilisateur uniques sur les comptes hébergés sur Connect. </font>';

$string['aclogin']             	 = 'Connexion Adobe Connect';

$string['cachetime']           	 = 'Délai de cache "Connect';
$string['cachetime_hint']      	 = '<font size=\"1\">Entrez le nombre de minutes pour garder les données du cache de réunion et le contenu détails du serveur Connect<br />' .
                                   'Les valeurs plus longues permettront d\'améliorer considérablement les performances<br />' .
                                   'mais peut causer un ralentissement quand l\'information est modifié sur le serveur Connect.<br /></font>';

$string['mins']                  = ' minutes';
$string['hours']                 = ' heures';                                 
                                 
$string['emailaslogin']          = 'Utiliser le courriel comme connexion AC';
$string['emailaslogin_hint']     = '<font size=\"1\">Il doit correspondre au paramètre de votre compte Connect</font>';

$string['updatelogin']           = 'Mise à jour le connexion AC ';
$string['updatelogin_hint']      = '<font size=\"1\">Lorsque le courriel comme connexion est activée, mise à jour le connexion AC lorsque le courriel a changé.</font>';

$string['unameaslogin']          = 'Utilisez nom d\'utilisateur comme connexion AC';
$string['unameaslogin_hint']     = '<font size=\"1\">Ce sera remplacée par courriel comme connexion AC, si elle est définie.</font>';

$string['telephony']             = 'Afficher le numéro de téléphone de la réunion';
$string['telephony_hint']        = '<font size=\"1\">Désactiver si vous préférez les utilisateurs de composer un numéro à partir de la salle de réunion une fois qu\'ils entrent.</font>';

$string['mouseovers']            = 'l\'option de pointer la souris sur l\'icône pour les étudiants';
$string['mouseovers_hint']       = '<font size=\"1\">Désactiver si vous préférez les étudiants de ne pas avoir une case pour afficher lorsque vous pointez la souris sur sur une icône..</font>';

$string['oldplayer']             = 'Utilisez Flash player';
$string['oldplayer_hint']        = '<font size=\"1\">Lorsque cette option est activée, elle permet de désactiver le JWPlayer pour les vidéos flash et activer l\'ancien lecteur Flash.</font>';

$string['videoserver']           = 'Chemin d\'accès complet au lecteur Flash';
$string['videoserver_hint']      = '<font size=\"1\">Cette valeur sera pré-pendue aux noms de fichiers vidéo Flash qui ne sont pas complètement qualifiées.</font>';

$string['videophoto']            = 'Image photo par défaut';
$string['videophoto_hint']       = '<font size=\"1\">Le lecteur vidéo flash s\'affiche l\'image définie ci-dessus,<br />' .
                                         'sauf si un nom d\'image individuelle est accordée.</font>';

$string['videophotopath']        = 'Chemin d\'accès complet au images photo';
$string['videophotopath_hint']   = '<font size=\"1\">Le chemin défini ci-dessus sera ajouté à la vidéo Flash à moins que le nom de l\'image fixe est pleinement qualifié.</font>';

$string['nosso']                 = 'Désactivez mise à jour automatique des utilisateurs Adobe et connexion d\'authentification unique.';
$string['nosso_hint']            = '<font size=\"1\">Cela désactive le lien entre le SGA  et Adobe pour les utilisateurs. Les utilisateurs auront besoin d\'être  connecté à Adobe indépendamment.</font>';

$string['execsingle']            = 'Désactiver le traitement multi-déclaration.';
$string['execsingle_hint']       = '<font size=\"1\">Cela désactive les appels de multi-déclaration à Adobe utilisé pour l\'efficacité. Il va ralentir le système, ce qui est un problème pour certains sites.</font>';

$string['popup_height']          = 'Hauteur de la fenêtre contextuelle.';
$string['popup_height_hint']     = '<font size=\"1\">Hauteur de la fenêtre contextuelle lancé pour une réunion Adobe / présentation. Détermine la taille par défaut d\'une fenêtre en pixels.</font>';

$string['popup_width']          = 'Largeur de la fenêtre contextuelle.';
$string['popup_width_hint']     = '<font size=\"1\">Largeur de la fenêtre contextuelle lancé pour une réunion Adobe / présentation. Détermine la taille par défaut d\'une fenêtre en pixels.</font>';

$string['notfound']              = 'CETTE URL N\'EXISTE PAS\'';

$string['tollfree']              = 'Sans frais:';
$string['pphone']                = 'mot de passe:';

$string['launch_meeting']        = 'Cliquez pour entrer la salle de réunion';
$string['launch_content']        = 'Cliquez pour voir la présentation';
$string['launch_archive']        = 'Cliquez pour voir l\'enregistrement';

$string['launch_edit']           = 'Modifier cette ressource à Adobe Connect Central';

$string['views']                 = 'Vue(s) jusqu\'à présent';
$string['editcal']               = 'Synchronize the Calendar Event for this Meeting';
$string['viewattendees']         = 'Afficher la liste des participants de la réunion';
$string['mailattendees']         = 'Détails de la réunion envoyer par courriel';

$string['mailsubject']           = 'Re: $a->title';
$string['mailbody']              = 'Cher

S\'il vous plaît me rencontrer à une réunion en ligne intitulé: <a href=\"$a->url\">$a->title (click to launch)</a>

L\'événement aura lieu le: $a->date
Informations sur la conférence: $a->phone

Cordialement,

$a->de

$a->Sommaire

Veuillez noter que cet événement peut également être accessible directement à $a->cplongurl (connectez-vous comme un invité)';

$string['attendees']      = 'Participants à la réunion';
$string['eventname']      = 'Date de la réunion';
$string['login']          = 'Nom de connexion';
$string['fullname']       = 'Nom complet';
$string['minutes']        = 'Minutes de la réunion';
$string['entrytime']      = 'Première entrée';
$string['mtgrole']        = 'Rôle';
$string['attendeecount']  = 'Utilisateurs dans la salle de réunion';
$string['backtocourse']   = 'Retourner au cours';
$string['meeting']        = 'URL de la réunion';
$string['minabb']         = 'minute';
$string['minsabb']        = 'minutes';
$string['mymeetings']     = 'Mes prochaines réunions';
$string['myrecordings']   = 'Mes enregistrements';
$string['mtgenter']       = 'Cliquez pour entrer dans la salle de réunion: ';



///////////////////////////////////////////////////////////////////////////////////////////////
$string['refined_link_type']         = 'Type de ressource Connect';
$string['refined_link_mtg']          = 'Réunion';
$string['refined_link_preso']        = 'Présentation';
$string['refined_link_video']        = 'Vidéo';
$string['refined_link_recording']    = 'Enregistrement';
$string['refined_link_other']        = 'Autre';

$string['filtername']                = 'Connect';
$string['refineddescription']        = 'Configurez vos paramètres du serveur Adobe Connect';

$string['refinedprotocol']           = 'Protocole Préféré';
$string['refinedprotocol_hint']      = '<font size=\"1\">Sélectionnez comment les utilisateurs doivent se connecter à votre contenu Connect</font>';

$string['refineddomain']             = 'Connectez domaine du serveur';
$string['refineddomain_hint']        = '<font size=\"1\">C\'est le nom de domaine annoncés aux utilisateurs</font>';

$string['refinedadmindomain']        = 'Connectez domaine de l\'administrateur';
$string['refinedadmindomain_hint']   = '<font size=\"1\">Nom de domaine pour les appels API - généralement <b> admin.acrobat.com </ b> pour les comptes hébergés </ font> ';

$string['refinedaccount']            = 'ID du compte "Connect"';
$string['refinedaccount_hint']       = '<font size=\"1\">Apparaît dans l\'URL à Connect Central sur les pages de l\'administrateur</font>';

$string['refinedcacheedit']          = 'Vider le cache en mode de modification';
$string['refinedcacheedit_hint']     = '<font size\"1\">En mode de modification, le cache sera automatiquement effacé à moins que cette case est décochée.</font>';

$string['refinedcachenow']           = 'Vider le cache MAINTENANT';
$string['refinedcachenow_hint']      = '<font size\"1\">La prochaine fois que le cache est vérifiée, tous les contenus seront effacés. Cela vous permet ' .
                                       'de définir un temps pour garder la mémoire cache, et lorsque vous modifiez le contenu sur Connect, vous pouvez ' .
                                       'réinitialiser manuellement le cache.</font>';

$string['groupcommit']               = 'Amélioration des performances Groupe Commettre';
$string['groupcommit_hint']          = '<font size\"1\">Permet le "groupe commettre" dans chaque en-tête et pied de page dans le système. En activant, ' .
                                       'les connexions à Connect Central seront tamponnés pour les appels multiples au cours d\'un chargement de page. Pour les pages ' .
                                       'avec plusieurs icônes, la performance sera grandement améliorée.';

$string['refinedadminname']          = 'Connexion administrateur';
$string['refinedadminname_hint']     = '<font size=\"1\">Assurez-vous cet utilisateur existe dans votre compte Connect et possède des droits d\'administrateur complets</font>';

$string['refinedadminpassword']      = 'Mot de passe administrateur';
$string['refinedadminpassword_hint'] = '<font size=\"1\">Le mot de passe pour le nom d\'utilisateur ci-dessus</font>';

$string['refinedguestuser']          = 'Nom d\'utilisateur pour l\'invité';
$string['refinedguestuser_hint']     = '<font size=\"1\">Assurez-vous cet utilisateur existe dans votre compte Connect et n\'a pas de droits du tout</font>';

$string['refinedguestpassword']      = 'Mot de passe d\'invité';
$string['refinedguestpassword_hint'] = '<font size=\"1\">Le mot de passe pour le nom d\'utilisateur ci-dessus</font>';

$string['refinedsurveyurl']          = 'URL Enquête Gizmo';
$string['refinedsurveyurl_hint']     = '<font size=\"1\">Seulement requis si vous voulez enquêtes pré-réunion</font>';

$string['refinedtimeoffset']         = 'Paramètre pour réglage de l\'heure';
$string['refinedtimeoffset_hint']    = '<font size=\"1\">Nombre de secondes à ajouter (utilisez le signe moins pour soustraire)<br />' .
                                     'pour compenser la différence de temps entre votre fuseau horaire<br />' .
                                     'et le fuseau horaire du serveur.<br />' .
                                     '(par exemple -3600 rendre compte de l\'heure d\'été).</font>';

$string['defaultstate']              = 'Etat / Province défaut';
$string['configstate']               = 'Si vous définissez un état / Province ici, cette valeur sera sélectionnée par défaut pour les nouveaux comptes d\'utilisateurs. Ne réglez pas si vous voulez forcer les utilisateurs à choisir un état / province.';
$string['refined_clock']             = 'Horloge analogique';
$string['refined_clock_title']       = 'C\'est toujours le bon moment pour apprendre à Refined Data';

$string['refined_acp_login']         = 'Connexion Connect';
$string['refined_link_mtg_min']      = 'Minutes minimum en réunion requis';
$string['refined_link_slide_min']    = 'Vues minimales de diapositives requis';

$string['refined_mtg_view_preso']    = 'Cliquez pour voir la présentation: ';
$string['refined_mtg_view_rec']      = 'Cliquez pour voir l\'enregistrement: ';
$string['refined_mtg_view_file']     = 'Cliquez pour voir la ressource: ';

$string['refined_guest_login']       = 'Connexion à la réunion';
$string['refined_guest_fullname']    = 'Votre nom complet';
$string['refined_guest_submit']      = 'Entrez dans la salle de réunion';
$string['refined_guest_prompt']      = 'S\'il vous plaît entrer votre nom complet (nom et prénom) ci-dessus pour entrer dans la réunion';
$string['refined_guest_error']       = 'Vous devez entrer votre nom complet pour entrer dans la réunion';

$string['refined_url_notfound']      = 'CETTE URL N\'EXISTE PAS';

$string['refined_mtg_launch_cpro']   = 'Lancement Connect Central ';

$string['refined_mtg_hour_abb']      = 'hr';
$string['refined_mtg_hours_abb']     = 'hrs';

$string['refined_assignment']        = '<center><font size=\"+1\"><font color=\"red\">Attention:</font><br />' .
                                       'This is an auto-generated assignment type created to track access to ' .
                                       'Adobe Connect Meetings,<br />Slide Views of Presenter Presentations ' .
                                       'and viewing of Flash Video movies.<br /><br />' .
                                       'Do not manually create assignments using this form.<br /><br />' .
                                       'This assignment should remain hidden from students and is used ' .
                                       'only for tracking purposes.<br /><br />' .
                                       'You can override the participation records for course users ' .
                                       'by manually editing their grade for this assignment.</font></center>';

$string['refined_autologin']         = 'Connexion automatique:';
$string['refined_autologin_note']    = '(S\'il vous plaît remplacer ACPURL avec votre URL Connect personnalisée dans le lien ci-dessus.)';

$string['rtdocs']                    = 'RT User Guide';
$string['userdown']                  = 'Download Users';
$string['workbook']                  = 'Workbook';

$string['rtdocchp1']                 = 'Introduction';
$string['rtdocchp2']                 = 'Refined Tags';
$string['rtdocchp3']                 = 'Connect Activities';
$string['rtdocchp4']                 = 'Tutor Sessions';
$string['rtdocchp5']                 = 'Prerequisites ';
$string['rtdocchp6']                 = 'Event Reminders';
$string['rtdocchp7']                 = 'Webinar Enhancements';
$string['rtdocchp8']                 = 'Corporate Branding';
$string['rtdocchp9']                 = 'Job Functions';
$string['rtdocchp10']                = 'Access Tokens';
$string['rtdocchp11']                = 'Locations & Managers';
$string['rtdocchp12']                = 'Refined Reporting';
$string['rtdocchp13']                = 'Connect Filters';
$string['rtdocchp14']                = 'Intranet SSO';
$string['rtdocchp15']                = 'Security';

?>

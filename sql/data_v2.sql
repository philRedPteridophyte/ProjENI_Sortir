INSERT INTO `ville` (`id`, `nom_ville`, `code_postal`) VALUES
(1, 'Chartres-de-Bretagne', '35131'),
(2, 'Nantes', '44109'),
(3, 'Niort', '79000');

INSERT INTO `site` (`id`, `nom_site`) VALUES
(1, 'Chartres-de-Bretagne'),
(2, 'Nantes'),
(3, 'Niort'),
(4, 'Quimper');

INSERT INTO `etat` (`id`, `libelle`) VALUES
(1, 'Ouverte'),
(2, 'Fermée'),
(3, 'En cours'),
(4, 'En création'),
(5, 'Archivée'),
(6, 'Annulée');

INSERT INTO `participant` (`id`, `site_id`, `pseudo`, `nom`, `prenom`, `telephone`, `mail`, `mot_de_passe`, `administrateur`, `actif`, `urlPhoto`) VALUES
(1, 1, 'mpenaud', 'penaud', 'mathieu', '0680486582', 'mathieu.penaud99@sortir.com', '1234', 1, 1, NULL),
(2, 1, 'slegiemble', 'legiemble', 'sarah', '0681852638', 'sarah.legiemble@sortir.com', '4567', 1, 1, NULL),
(3, 1, 'trichard', 'richard', 'thierry', '0625242628', 'thierry.richard@sortir.com', '1456', 1, 1, NULL),
(4, 1, 'dleneveu', 'leneveu', 'damien', '0658759545', 'damien.leneveu@sortir.com', '16356', 1, 1, NULL),
(5, 1, 'predfren', 'redfren', 'phillip', '0712023548', 'phillip.redfren@sortir.com', '0000', 1, 1, 'https://i.stack.imgur.com/l60Hf.png'),
(6, 1, 'Jean22d', 'Loup', 'Jean', '645105045', 'jlds@gmail.com', '1234', 1, 1, 'https://i.stack.imgur.com/l60Hf.png');


INSERT INTO `lieu` (`id`, `ville_id`, `nom_lieu`, `rue`, `latitude`, `longitude`) VALUES
(1, 1, 'Bar du coin', 'rue de la soif', 11.1111, 2.22222),
(2, 1, 'Bowling Alma', '2 Rue du Bosphor', 48.0567332, -1.7859432),
(3, 1, 'Level 3 Verne-sur-Seiche', '1 rue Jeanne Malivel', 48.0649383, -1.6249046),
(4, 1, 'Gaumont Rennes', '12 Rue Yvonne Jean-Haffen', 48.1000972, -1.6761342),
(5, 2, 'Gaumont Nantes', '12 Place du Commerce', 47.1961365, -1.5816965),
(6, 2, 'Bar Le Tek', '2 Rue Copernic', 47.213981, -1.5607577);

INSERT INTO `sortie` (`id`, `etat_id`, `lieu_id`, `organisateur_id`, `nom`, `datedebut`, `duree`, `datecloture`, `nbinscriptionsmax`, `descriptioninfos`, `urlPhoto`) VALUES
(1, 3, 1, 5, 'James va au bar', '2021-03-28 00:00:00', 3, '2021-03-22 00:00:00', 8, 'hoal chicos de la macarena', NULL),
(2, 3, 1, 1, 'James veux boire', '2021-03-28 00:00:00', 1000, '2021-03-27 00:00:00', 20, 'Excursion pour consomation de boisson alcoolisé', NULL),
(3, 3, 1, 4, 'Excurtion Bar Fete de la truite', '2021-04-22 17:00:00', 300, '2021-04-08 09:45:00', 30, 'Pour fêter la truite, nous allons débuter notre soirée dans le bar du coin avant de poursuivre avec une petite randonnée le long de La Seiche.', NULL),
(4, 3, 5, 5, 'Go voir Free Player', '2021-04-12 16:09:00', 180, '2021-03-10 10:12:00', 20, 'Pour profiter de la réduction offert par l\'ENI pour une scéance au cinema en groupe, quoi de mieu qu\'un film avec Ryan Reynolds!', NULL),
(5, 3, 6, 3, 'Post ECF 13', '2021-04-02 18:30:00', 300, '2021-03-31 10:16:00', 23, 'Sortie au bar suite à l\'ECF de la semaine 13. (Consomez avec modération, l\'alcool est unterdit au mineur, vous connessez le rest...)', NULL),
 (7, 3, 3, 5, 'Anniversair de Tobi', '2021-05-13 10:24:00', 360, '2021-04-28 10:24:00', 40, 'Un petit Laser Game pour feter l\'anniversair de Tobi ce majestueux.', NULL);



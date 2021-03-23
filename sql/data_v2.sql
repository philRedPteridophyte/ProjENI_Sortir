INSERT INTO `ville` (`id`, `nom_ville`, `code_postal`) VALUES
(1, 'Chartres-de-Bretagne', '35131'),
(2, 'Nantes', '44109'),
(3, 'Niort', '79000');

INSERT INTO `site` (`id`, `nom_site`) VALUES
(1, 'Val André'),
(2, 'Baignoire');

INSERT INTO `etat` (`id`, `libelle`) VALUES
(1, 'Ouverte'),
(2, 'Fermée'),
(3, 'En cours'),
(4, 'En création'),
(5, 'Archivée'),
(6, 'Annulée');

INSERT INTO `participant` (`id`, `site_id`, `pseudo`, `nom`, `prenom`, `telephone`, `mail`, `mot_de_passe`, `administrateur`, `actif`) VALUES
(1, 1, 'mpenaud', 'penaud', 'mathieu', '0680486582', 'mathieu.penaud99@sortir.com', '1234', 1, 1),
(2, 1, 'slegiemble', 'legiemble', 'sarah', '0681852638', 'sarah.legiemble@sortir.com', '4567', 1, 1),
(3, 1, 'trichard', 'richard', 'thierry', '0625242628', 'thierry.richard@sortir.com', '1456', 1, 1),
(4, 1, 'dleneveu', 'leneveu', 'damien', '0658759545', 'damien.leneveu@sortir.com', '16356', 1, 1),
(5, 1, 'predfren', 'redfren', 'phillip', '0712023548', 'phillip.redfren@sortir.com', '0000', 1, 1);

INSERT INTO `lieu` (`id`, `ville_id`, `nom_lieu`, `rue`, `latitude`, `longitude`) VALUES
(1, 1, 'Bar du coin', 'rue de la soif', 11.1111, 2.22222),
(2, 1, 'Bowling', 'rue elsson', 11.1111, 2.22222);

INSERT INTO `sortie` (`id`, `etat_id`, `lieu_id`, `organisateur_id`, `nom`, `datedebut`, `duree`, `datecloture`, `nbinscriptionsmax`, `descriptioninfos`, `urlPhoto`) VALUES
(1, 3, 1, 5, 'James va au bar', '2021-03-28 00:00:00', 3, '2021-03-22 00:00:00', 8, 'hoal chicos de la macarena', NULL);




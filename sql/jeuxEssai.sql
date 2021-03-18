INSERT INTO `participants`(`pseudo`, `nom`, `prenom`, `telephone`, `mail`, `mot_de_passe`, `administrateur`, `actif`, `sites_no_site`)
VALUES ('mpenaud','penaud','mathieu','0680486582','mathieu.penaud99@sortir.com','1234','1','1','1');

INSERT INTO `participants`(`pseudo`, `nom`, `prenom`, `telephone`, `mail`, `mot_de_passe`, `administrateur`, `actif`, `sites_no_site`)
VALUES ('slegiemble','legiemble','sarah','0681852638','sarah.legiemble@sortir.com','4567','1','1','1');

INSERT INTO `participants`(`pseudo`, `nom`, `prenom`, `telephone`, `mail`, `mot_de_passe`, `administrateur`, `actif`, `sites_no_site`)
VALUES ('trichard','richard','thierry','0625242628','thierry.richard@sortir.com','1456','1','1','1');

INSERT INTO `participants`(`pseudo`, `nom`, `prenom`, `telephone`, `mail`, `mot_de_passe`, `administrateur`, `actif`, `sites_no_site`)
VALUES ('dleneveu','leneveu','damien','0658759545','damien.leneveu@sortir.com','16356','1','1','1');

INSERT INTO `participants`(`pseudo`, `nom`, `prenom`, `telephone`, `mail`, `mot_de_passe`, `administrateur`, `actif`, `sites_no_site`)
VALUES ('predfren','redfren','phillip','0712023548','phillip.redfren@sortir.com','0000','1','1','1');



INSERT INTO `villes` (`id`, `nom_ville`, `code_postal`) VALUES
(1, 'Chartres-de-Bretagne', '35131'),
(2, 'Nantes', '44109'),
(3, 'Niort', '79000');

INSERT INTO `lieux`(`id`, `villes_no_ville_id`, `nom_lieu`, `rue`, `latitude`, `longitude`) 
VALUES (1,1,'Bar du coin','rue de la soif',11.1111,2.22222)
,(2,1,'Bowling','rue elsson',11.1111,2.22222)
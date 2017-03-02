UPDATE "spip_rubriques" SET
"statut" = 'publie',
WHERE "id_rubrique" = '23';

update "spip_articles"
set 'id_rubrique'=22
WHERE "id_rubrique" NOT IN (15,19) AND "lang" = 'fr'

update "spip_articles"
set 'id_rubrique'=23
WHERE "lang" = 'en'

update "spip_breves"
set 'id_rubrique'=22
WHERE "lang" = 'fr'

update "spip_breves"
set 'id_rubrique'=23
WHERE "lang" = 'en'

DELETE FROM "spip_rubriques"
WHERE (("id_rubrique" = '1') OR ("id_rubrique" = '2') OR ("id_rubrique" = '3') OR ("id_rubrique" = '4') OR ("id_rubrique" = '6') OR ("id_rubrique" = '11') OR ("id_rubrique" = '12') OR ("id_rubrique" = '13') OR ("id_rubrique" = '14') OR ("id_rubrique" = '16') OR ("id_rubrique" = '18') OR ("id_rubrique" = '20') OR ("id_rubrique" = '21'));


select id, descarga_id from descarga_cfdis where descarga_id IN (select id from descargas where DATE_PART('month',created_at) < 11) order by id;
delete from descargas where id IN (select id from descargas where DATE_PART('month',created_at) < 11);


--descargas correctas
select * from descargas where id NOT IN(select id from descargas where DATE_PART('year',created_at) = 2021 OR (DATE_PART('year',created_at) = 2022 and DATE_PART('month',created_at)<11) order by id );

--descargas obsoletas
select id, user_id, created_at, updated_at from descargas where DATE_PART('year',created_at) = 2021 OR (DATE_PART('year',created_at) = 2022 and DATE_PART('month',created_at)<11) order by id

-- Borrado de descargas obsoletas en la base de datos
DELETE FROM descargas where id IN (select id from descargas where DATE_PART('year',created_at) = 2021 OR (DATE_PART('year',created_at) = 2022 and DATE_PART('month',created_at)<11));





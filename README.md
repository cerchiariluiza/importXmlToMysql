# importXmlToMysql

Extrair o xml do arquivo zipado, e coloque este arquivo numa pasta chamada extracted.
Mude a conexão com bd

crie a table:

create table processo (
nome varchar(30),
marca varchar(40)
dt_deposito date, 
dt_concessao date,
vigencia date,
estado varchar(30));

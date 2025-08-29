create database if not exists dbluumina
default character set utf8mb4
collate utf8mb4_unicode_ci;

use dbluumina;

create table usuario (
idUsuario int auto_increment primary key,
nome varchar(255) not null,
senha varchar(255) not null,
email varchar(120) not null
);

create table tipoUsuario (
idTipo int auto_increment primary key,
idUsuario int not null,
descricao varchar(50) not null,
foreign key (idUsuario) references usuario(idUsuario)
);
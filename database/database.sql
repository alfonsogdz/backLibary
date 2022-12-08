create database if not exists apistore;
use apistore;

create table users(
    id int(255) auto_increment not null,
    role varchar(20),
    name varchar(255),
    surname varchar(255),
    password varchar(255),
    created_at datetime DEFAULT null,
    updated_at datetime DEFAULT null,
    remember_token varchar(255),
    CONSTRAIT pk_users PRIMARY KEY(id)
)ENGINE=InnoDb;

create table books(
    id int (255) auto_increment not null,
    user_id int(255) not null
    title varchar(255),
    descripcion text,
    price varchar(30),
    status varchar(30),
    created_at datetime DEFAULT NULL,
    updated_at datetime DEFAULT NULL,
    CONSTRAIT pk_books PRIMARY KEY(id)
    CONSTRAIT fk_books_users FOREIGN KEY(user_id) REFERENCES users(id)
)ENGINE=InnoDb;


create table collection
(
    id int auto_increment,
    Name varchar(100) not null,
    collection_Desc text null,
    create_user int not null,
    create_date timestamp default CURRENT_TIMESTAMP() not null,
    constraint collection_pk
        primary key (id)
);
create table collectionItem
(
    id int auto_increment,
    idCollection int not null,
    `Desc` text null,
    Place text null,
    Author text null,
    Time text null comment 'Время создания (??)',
    Material text null,
    Size text null,
    Nom varchar(255) null,
    constraint collectionItem_pk
        primary key (id),
    constraint table_name_collection_id_fk
        foreign key (idCollection) references collection (id)
);

create index collectionItem_idCollection_index
    on collectionItem (idCollection);

alter table collectionItem
    add create_user int not null;

alter table collectionItem
    add create_date timestamp default CURRENT_TIMESTAMP() not null;
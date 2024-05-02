/*
Аннотация (описание): органичение символов?
Текстовая информация о предмете
Думаю, не надо ограничивать. Она так будет небольшой.
Место нахождения (памятник),Географический регион: Возможно тут нужен какойто выподающий список (один и тотже гео объект могут назвать 2я разными названиями + ошибки,описки)
Пока не надо.  Просто текстовое поле.
Авторство: это немного не полнял.. просто текстовое поле? без превязки к персоналиям?
Кто (ФИО) обнаружил предмет. С привязкой к персоналиям.
Время создания: просто текстовое поле?
Ага.
Материал, техника: просто текстовое поле? или может набросать список?
Пока не надо.
*/
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
/*

Авторство: это немного не полнял.. просто текстовое поле? без превязки к персоналиям?
Кто (ФИО) обнаружил предмет. С привязкой к персоналиям.

*/
create table `vm`.collection
(
    id              int auto_increment,
    Name            varchar(100)                          not null,
    collection_Desc text                                  null,
    create_user     int                                   not null,
    create_date     timestamp default CURRENT_TIMESTAMP() not null,
    constraint collection_pk
        primary key (id)
) ENGINE = InnoDB;

create table `vm`.collectionItem
(
    id           int auto_increment,
    idCollection int          not null,
    `Desc`       text         null,
    Place        text         null,
    Author       text         null,
    Time         text         null comment 'Время создания (??)',
    Material     text         null,
    Size         text         null,
    Nom          varchar(255) null,
    create_user int not null,
    create_date timestamp default CURRENT_TIMESTAMP() not null,
    INDEX (`idCollection`),
    constraint collectionItem_pk
        primary key (id),
    constraint table_name_collection_id_fk
        foreign key (idCollection) references collection (id)
) ENGINE = InnoDB;

CREATE TABLE `vm`.tag_collectionItem
(
    id               int auto_increment
        primary key,
    idTag            int not null,
    idCollectionItem int not null,
    INDEX (`idTag`),
    INDEX (`idCollectionItem`)
) ENGINE = InnoDB;


CREATE TABLE `vm`.`sci_theme_collectionItem`
(
    `id`               INT NOT NULL AUTO_INCREMENT,
    `idCollectionItem` INT NOT NULL,
    `idSciDepartment`  INT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX (`idCollectionItem`),
    INDEX (`idSciDepartment`)
) ENGINE = InnoDB;

CREATE TABLE `vm`.`sci_department_collection`
(
    `id`              INT NOT NULL AUTO_INCREMENT,
    `idCollection`    INT NOT NULL,
    `idSciDepartment` INT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX (`idCollection`),
    INDEX (`idSciDepartment`)
) ENGINE = InnoDB;

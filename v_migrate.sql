alter table person
    modify comment text null comment 'Биографические данные (кратко)' after dol;
alter table person
    add publications text null comment 'Основные публикации' after comment;
alter table person
    add awards text null comment 'Награды, звания' after publications;
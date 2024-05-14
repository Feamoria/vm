ALTER TABLE `tag_person`
    DROP FOREIGN KEY `tag_person_ibfk_1`;
ALTER TABLE `tag_person`
    ADD CONSTRAINT `tag_person_ibfk_1` FOREIGN KEY (`idPerson`) REFERENCES `person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE `tag_person`
    DROP FOREIGN KEY `tag_person_ibfk_2`;
ALTER TABLE `tag_person`
    ADD CONSTRAINT `tag_person_ibfk_2` FOREIGN KEY (`idTag`) REFERENCES `tag` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
alter table collectionItem
    add idFile int not null after idCollection;
ALTER TABLE `collectionItem`
    ADD INDEX (`idFile`);
ALTER TABLE `collectionItem`
    ADD CONSTRAINT `collectionitem_ibfk_1` FOREIGN KEY (`idFile`) REFERENCES `file` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE `collectionItem`
    ADD CONSTRAINT `collectionitem_ibfk_2` FOREIGN KEY (`idCollection`) REFERENCES `collection` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
alter table collectionItem modify idFile int default null null;
alter table file alter column date set default null;

alter table file alter column disc set default '';


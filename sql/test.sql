-- create a database called slim_db and insert this data

CREATE TABLE `friends` (
   `id` int(10) unsigned not null auto_increment,
   `name` varchar(255),
   `job` varchar(255),
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4;

INSERT INTO `friends` (`id`, `name`, `job`) VALUES 
('1', 'Sam', 'Gardener'),
('2', 'Molly', 'Chef'),
('3', 'Evan', 'Web Developer');
create table feeds (
	`id` int unsigned primary key,
	`url` text not null,
	`url_hash` char(32) not null
) engine='InnoDB';

create table items (
	`id` int unsigned primary key,
	`feed_id` int unsigned not null,
	`guid` text not null,

	foreign key (`feed_id`)
		references feeds(`id`)
		on update cascade
		on delete cascade
) engine='InnoDB';

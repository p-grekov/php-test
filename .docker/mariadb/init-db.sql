create table statistic (
    url varchar(255),
    length int,
    updated_at datetime default(now())
);
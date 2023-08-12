#!/bin/sh

clickhouse client -n <<-SQL

create database if not exists docker;

create table docker.statistic(
    url String,
    length UInt64,
    updated_at DateTime default now()
)
engine = Log
;

SQL
drop table users;
Create table users(
  id SERIAL primary key,
  mail varchar(350) unique,
  mdp varchar(300),
  role varchar(350)
)

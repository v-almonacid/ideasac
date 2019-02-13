# IdeasAC

![alt text](https://github.com/v-almonacid/ideasac/blob/master/images/screenshot.png)

ideasAC was a proof-of-concept that I developed in 2014 as a part of wikiAC,
a project that intended to re-write the Chilean Constitution through a
crowdsourcing platform (somewhat similar to the Wikipedia).

The ideasAC PoC was a less ambitious side-project. It basically relies on
people posting their ideas and proposals for a new constitution using the
hashtag #ideasAC on Twitter. The web backend would then track these tweets
and publish them on the site ideas.wikiac.cl, where people could also vote for
the ideas they support. The backend would also check the content of each tweet
in order to identify the most repeated words, which were then published in a
cloud tag in the frontend.

WikiAC and ideasAC were both initiatives created to support the Chilean social
movements advocating for a new constitution that would replace the one written
during the dictatorship of Augusto Pinochet.

**DISCLAIMER**: this was only a proof-of-concept and is not ready for production.
The project is not under development anymore and uses outdated dependencies.
Also, this was one of my first full-stack web projects so expect a messy,
buggy code.

## Stack Overview
This project was built using:
  - PHP 5.3.10
  - Slim microframework 2.42
  - MySQL 5.5.x
  - Ubuntu 12.04 LAMP environment
  - ReadBeanPHP 4.03
  - TwitterOAuth v0.2.0-beta2

## Setup
This project has been tested in a classic Ubuntu LAMP environment (recently
tested on an Ubuntu 16.04 machine). After setting up a PHP/MySQL environment,
follow the next steps.

### 1. Get Access to the Twitter API
Setup a twitter account and get the tokens required to get access to the public
API (see https://dev.twitter.com/apps). Add these tokens in `/inc/config.php`.

### 2. Clone this repo
```
git clone https://github.com/v-almonacid/ideasac.git
```
### 3. Apache conf
The Slim microframework requires to override some Apache directives, so make
sure that you have `AllowOverride All` set for your project environment.
Additionally, you must activate the `mod_rewrite` module:
```
sudo a2enmod rewrite
```

### 4. PHP dependencies
```
sudo apt-get install php-curl
sudo apt install php-mbstring
```
### 5. Database setup
Assuming you have mysql installed and running, open a terminal in your project
root and type:
```
mysql -u root -p  # type your password
create database ideasac;
source dataModel.sql
```
Nota: the `dataModel.sql` also populates the data base with fake data. You might
want to comment those lines.

In the file `inc/config.php`, edit the relevant data base parameters (user name
and password)

### 6. Test
You may check if your database and ORM are working properly by visiting
`<your_url>/inc/usuarioTest.php`.

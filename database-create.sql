SELECT 'Creating database navid_blacklist' AS ' ';

SHOW DATABASES;

CREATE DATABASE navid_blacklist;
CREATE USER 'blacklist_u'@'0.0.0.0' IDENTIFIED BY 'onaw8fh20oagubawgawg3i';
GRANT ALL ON navid_blacklist.* TO 'blacklist_u'@'0.0.0.0' IDENTIFIED BY 'onaw8fh20oagubawgawg3i' WITH GRANT OPTION;
GRANT ALL ON navid_blacklist.* TO 'blacklist_u'@'%' IDENTIFIED BY 'onaw8fh20oagubawgawg3i' WITH GRANT OPTION;
FLUSH PRIVILEGES;

SELECT 'Done creating database navid_blacklist. Listing databases...' AS ' ';

SHOW DATABASES;



CREATE TABLE IF NOT EXISTS  T_NEW_memberc (
  NMC_id INTEGER unsigned NOT NULL AUTO_INCREMENT,
  NMC_fk_NMY SMALLINT(3) NOT NULL,
  NMC_pseudo VARCHAR(50) UNIQUE NOT NULL,
  NMC_password VARCHAR(255),
  NMC_philosophy TEXT,
  PRIMARY KEY(NMC_id),
  CONSTRAINT FK_NMC_fk_NMY FOREIGN KEY (NMC_fk_NMY)
    REFERENCES T_NEW_membery (NMY_id)
) DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS T_NEW_newsc (
  NNC_id INTEGER unsigned NOT NULL AUTO_INCREMENT,
  NNC_fk_NMC INTEGER NOT NULL,
  NNC_title varchar(100) NOT NULL,
  NNC_content text NOT NULL,
  NNC_dateAdd datetime NOT NULL,
  NNC_dateModif datetime NOT NULL,
  PRIMARY KEY (NNC_id),
  CONSTRAINT FK_NNC_fk_NMC FOREIGN KEY (NNC_fk_NMC)
  	REFERENCES T_NEW_memberc (NMC_id)
) DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS T_NEW_commentc (
  NCC_id INTEGER unsigned NOT NULL AUTO_INCREMENT,
  NCC_fk_NNC INTEGER NOT NULL,
  NCC_fk_NMC INTEGER NOT NULL,
  NCC_content text NOT NULL,
  NCC_date datetime NOT NULL,
  PRIMARY KEY (NCC_id),
  CONSTRAINT FK_NCC_fk_NMC FOREIGN KEY (NCC_fk_NMC) 
  	REFERENCES T_NEW_newsc (NNC_id),
  CONSTRAINT FK_NCC_fk_NNC FOREIGN KEY (NCC_fk_NNC)
  	REFERENCES T_NEW_memberc (NMC_id)
) DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS T_NEW_membery (
  NMY_id SMALLINT(3) unsigned NOT NULL,
  NMY_name varchar(100) NOT NULL,
  PRIMARY KEY(NMY_id)
) DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS T_NEW_tagc (
  NTC_id INTEGER unsigned NOT NULL AUTO_INCREMENT,
  NTC_name varchar(100) NOT NULL,
  PRIMARY KEY(NTC_id)
) DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS T_NEW_tagd (
  NTD_id INTEGER unsigned NOT NULL AUTO_INCREMENT,
  NTD_fk_NNC INTEGER UNSIGNED NOT NULL,
  NTD_fk_NTC INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY (NTD_id),
  CONSTRAINT FK_NTD_fk_NNC FOREIGN KEY (NTD_fk_NNC)
  	REFERENCES T_NEW_newsc (NNC_id),
  CONSTRAINT FK_NTD_fk_NTC FOREIGN KEY (NTD_fk_NTC)
    REFERENCES T_NEW_tagc (NTC_id)
) DEFAULT CHARSET=utf8 ;

INSERT INTO t_new_membery (NMY_id, NMY_name) VALUES ('0', 'Administrator'), ('1', 'Author');
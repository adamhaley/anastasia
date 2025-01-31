# iso_country_list.sql
#
# This will create and then populate a MySQL table with a list of the names and 
# ISO 3166 codes for countries in existence as of the date below.
#
# Usage:
#	mysql -u username -ppassword database_name < iso_country_list.sql
# 
# For updates to this file, see http://27.org/isocountrylist/
# For more about ISO 3166, see http://www.iso.ch/iso/en/prods-services/iso3166ma/02iso-3166-code-lists/list-en1.html
#
# Wm. Rhodes <iso_country_list@27.org> 
# 6/27/02
#

CREATE TABLE IF NOT EXISTS country (
  id INT NOT NULL auto_increment,
  name VARCHAR(80) NOT NULL,
  iso CHAR(2) NOT NULL,
  PRIMARY KEY (id),
  INDEX by_name (name,iso),
  INDEX by_iso (iso,name)
);

INSERT INTO country values(NULL,"Afghanistan","AF");
INSERT INTO country values(NULL,"Albania","AL");
INSERT INTO country values(NULL,"Algeria","DZ");
INSERT INTO country values(NULL,"American Samoa","AS");
INSERT INTO country values(NULL,"Andorra","AD");
INSERT INTO country values(NULL,"Angola","AO");
INSERT INTO country values(NULL,"Anguilla","AI");
INSERT INTO country values(NULL,"Antarctica","AQ");
INSERT INTO country values(NULL,"Antigua and Barbuda","AG");
INSERT INTO country values(NULL,"Argentina","AR");
INSERT INTO country values(NULL,"Armenia","AM");
INSERT INTO country values(NULL,"Aruba","AW");
INSERT INTO country values(NULL,"Australia","AU");
INSERT INTO country values(NULL,"Austria","AT");
INSERT INTO country values(NULL,"Azerbaijan","AZ");
INSERT INTO country values(NULL,"Bahamas","BS");
INSERT INTO country values(NULL,"Bahrain","BH");
INSERT INTO country values(NULL,"Bangladesh","BD");
INSERT INTO country values(NULL,"Barbados","BB");
INSERT INTO country values(NULL,"Belarus","BY");
INSERT INTO country values(NULL,"Belgium","BE");
INSERT INTO country values(NULL,"Belize","BZ");
INSERT INTO country values(NULL,"Benin","BJ");
INSERT INTO country values(NULL,"Bermuda","BM");
INSERT INTO country values(NULL,"Bhutan","BT");
INSERT INTO country values(NULL,"Bolivia","BO");
INSERT INTO country values(NULL,"Bosnia and Herzegovina","BA");
INSERT INTO country values(NULL,"Botswana","BW");
INSERT INTO country values(NULL,"Bouvet Island","BV");
INSERT INTO country values(NULL,"Brazil","BR");
INSERT INTO country values(NULL,"British Indian Ocean Territory","IO");
INSERT INTO country values(NULL,"Brunei Darussalam","BN");
INSERT INTO country values(NULL,"Bulgaria","BG");
INSERT INTO country values(NULL,"Burkina Faso","BF");
INSERT INTO country values(NULL,"Burundi","BI");
INSERT INTO country values(NULL,"Cambodia","KH");
INSERT INTO country values(NULL,"Cameroon","CM");
INSERT INTO country values(NULL,"Canada","CA");
INSERT INTO country values(NULL,"Cape Verde","CV");
INSERT INTO country values(NULL,"Cayman Islands","KY");
INSERT INTO country values(NULL,"Central African Republic","CF");
INSERT INTO country values(NULL,"Chad","TD");
INSERT INTO country values(NULL,"Chile","CL");
INSERT INTO country values(NULL,"China","CN");
INSERT INTO country values(NULL,"Christmas Island","CX");
INSERT INTO country values(NULL,"Cocos (Keeling) Islands","CC");
INSERT INTO country values(NULL,"Colombia","CO");
INSERT INTO country values(NULL,"Comoros","KM");
INSERT INTO country values(NULL,"Congo","CG");
INSERT INTO country values(NULL,"Congo, The Democratic Republic of the","CD");
INSERT INTO country values(NULL,"Cook Islands","CK");
INSERT INTO country values(NULL,"Costa Rica","CR");
INSERT INTO country values(NULL,"Cote D'ivoire","CI");
INSERT INTO country values(NULL,"Croatia","HR");
INSERT INTO country values(NULL,"Cuba","CU");
INSERT INTO country values(NULL,"Cyprus","CY");
INSERT INTO country values(NULL,"Czech Republic","CZ");
INSERT INTO country values(NULL,"Denmark","DK");
INSERT INTO country values(NULL,"Djibouti","DJ");
INSERT INTO country values(NULL,"Dominica","DM");
INSERT INTO country values(NULL,"Dominican Republic","DO");
INSERT INTO country values(NULL,"East Timor","TP");
INSERT INTO country values(NULL,"Ecuador","EC");
INSERT INTO country values(NULL,"Egypt","EG");
INSERT INTO country values(NULL,"El Salvador","SV");
INSERT INTO country values(NULL,"Equatorial Guinea","GQ");
INSERT INTO country values(NULL,"Eritrea","ER");
INSERT INTO country values(NULL,"Estonia","EE");
INSERT INTO country values(NULL,"Ethiopia","ET");
INSERT INTO country values(NULL,"Falkland Islands (Malvinas)","FK");
INSERT INTO country values(NULL,"Faroe Islands","FO");
INSERT INTO country values(NULL,"Fiji","FJ");
INSERT INTO country values(NULL,"Finland","FI");
INSERT INTO country values(NULL,"France","FR");
INSERT INTO country values(NULL,"France, Metropolitan","FX");
INSERT INTO country values(NULL,"French Guiana","GF");
INSERT INTO country values(NULL,"French Polynesia","PF");
INSERT INTO country values(NULL,"French Southern Territories","TF");
INSERT INTO country values(NULL,"Gabon","GA");
INSERT INTO country values(NULL,"Gambia","GM");
INSERT INTO country values(NULL,"Georgia","GE");
INSERT INTO country values(NULL,"Germany","DE");
INSERT INTO country values(NULL,"Ghana","GH");
INSERT INTO country values(NULL,"Gibraltar","GI");
INSERT INTO country values(NULL,"Greece","GR");
INSERT INTO country values(NULL,"Greenland","GL");
INSERT INTO country values(NULL,"Grenada","GD");
INSERT INTO country values(NULL,"Guadeloupe","GP");
INSERT INTO country values(NULL,"Guam","GU");
INSERT INTO country values(NULL,"Guatemala","GT");
INSERT INTO country values(NULL,"Guinea","GN");
INSERT INTO country values(NULL,"Guinea-bissau","GW");
INSERT INTO country values(NULL,"Guyana","GY");
INSERT INTO country values(NULL,"Haiti","HT");
INSERT INTO country values(NULL,"Heard and Mc Donald Islands","HM");
INSERT INTO country values(NULL,"Holy See (Vatican City State)","VA");
INSERT INTO country values(NULL,"Honduras","HN");
INSERT INTO country values(NULL,"Hong Kong","HK");
INSERT INTO country values(NULL,"Hungary","HU");
INSERT INTO country values(NULL,"Iceland","IS");
INSERT INTO country values(NULL,"India","IN");
INSERT INTO country values(NULL,"Indonesia","ID");
INSERT INTO country values(NULL,"Iran (Islamic Republic of)","IR");
INSERT INTO country values(NULL,"Iraq","IQ");
INSERT INTO country values(NULL,"Ireland","IE");
INSERT INTO country values(NULL,"Israel","IL");
INSERT INTO country values(NULL,"Italy","IT");
INSERT INTO country values(NULL,"Jamaica","JM");
INSERT INTO country values(NULL,"Japan","JP");
INSERT INTO country values(NULL,"Jordan","JO");
INSERT INTO country values(NULL,"Kazakhstan","KZ");
INSERT INTO country values(NULL,"Kenya","KE");
INSERT INTO country values(NULL,"Kiribati","KI");
INSERT INTO country values(NULL,"Korea, Democratic People's Republic of","KP");
INSERT INTO country values(NULL,"Korea, Republic of","KR");
INSERT INTO country values(NULL,"Kuwait","KW");
INSERT INTO country values(NULL,"Kyrgyzstan","KG");
INSERT INTO country values(NULL,"Lao People's Democratic Republic","LA");
INSERT INTO country values(NULL,"Latvia","LV");
INSERT INTO country values(NULL,"Lebanon","LB");
INSERT INTO country values(NULL,"Lesotho","LS");
INSERT INTO country values(NULL,"Liberia","LR");
INSERT INTO country values(NULL,"Libyan Arab Jamahiriya","LY");
INSERT INTO country values(NULL,"Liechtenstein","LI");
INSERT INTO country values(NULL,"Lithuania","LT");
INSERT INTO country values(NULL,"Luxembourg","LU");
INSERT INTO country values(NULL,"Macau","MO");
INSERT INTO country values(NULL,"Macedonia, The Former Yugoslav Republic of","MK");
INSERT INTO country values(NULL,"Madagascar","MG");
INSERT INTO country values(NULL,"Malawi","MW");
INSERT INTO country values(NULL,"Malaysia","MY");
INSERT INTO country values(NULL,"Maldives","MV");
INSERT INTO country values(NULL,"Mali","ML");
INSERT INTO country values(NULL,"Malta","MT");
INSERT INTO country values(NULL,"Marshall Islands","MH");
INSERT INTO country values(NULL,"Martinique","MQ");
INSERT INTO country values(NULL,"Mauritania","MR");
INSERT INTO country values(NULL,"Mauritius","MU");
INSERT INTO country values(NULL,"Mayotte","YT");
INSERT INTO country values(NULL,"Mexico","MX");
INSERT INTO country values(NULL,"Micronesia, Federated States of","FM");
INSERT INTO country values(NULL,"Moldova, Republic of","MD");
INSERT INTO country values(NULL,"Monaco","MC");
INSERT INTO country values(NULL,"Mongolia","MN");
INSERT INTO country values(NULL,"Montserrat","MS");
INSERT INTO country values(NULL,"Morocco","MA");
INSERT INTO country values(NULL,"Mozambique","MZ");
INSERT INTO country values(NULL,"Myanmar","MM");
INSERT INTO country values(NULL,"Namibia","NA");
INSERT INTO country values(NULL,"Nauru","NR");
INSERT INTO country values(NULL,"Nepal","NP");
INSERT INTO country values(NULL,"Netherlands","NL");
INSERT INTO country values(NULL,"Netherlands Antilles","AN");
INSERT INTO country values(NULL,"New Caledonia","NC");
INSERT INTO country values(NULL,"New Zealand","NZ");
INSERT INTO country values(NULL,"Nicaragua","NI");
INSERT INTO country values(NULL,"Niger","NE");
INSERT INTO country values(NULL,"Nigeria","NG");
INSERT INTO country values(NULL,"Niue","NU");
INSERT INTO country values(NULL,"Norfolk Island","NF");
INSERT INTO country values(NULL,"Northern Mariana Islands","MP");
INSERT INTO country values(NULL,"Norway","NO");
INSERT INTO country values(NULL,"Oman","OM");
INSERT INTO country values(NULL,"Pakistan","PK");
INSERT INTO country values(NULL,"Palau","PW");
INSERT INTO country values(NULL,"Panama","PA");
INSERT INTO country values(NULL,"Papua New Guinea","PG");
INSERT INTO country values(NULL,"Paraguay","PY");
INSERT INTO country values(NULL,"Peru","PE");
INSERT INTO country values(NULL,"Philippines","PH");
INSERT INTO country values(NULL,"Pitcairn","PN");
INSERT INTO country values(NULL,"Poland","PL");
INSERT INTO country values(NULL,"Portugal","PT");
INSERT INTO country values(NULL,"Puerto Rico","PR");
INSERT INTO country values(NULL,"Qatar","QA");
INSERT INTO country values(NULL,"Reunion","RE");
INSERT INTO country values(NULL,"Romania","RO");
INSERT INTO country values(NULL,"Russian Federation","RU");
INSERT INTO country values(NULL,"Rwanda","RW");
INSERT INTO country values(NULL,"Saint Kitts and Nevis","KN");
INSERT INTO country values(NULL,"Saint Lucia","LC");
INSERT INTO country values(NULL,"Saint Vincent and the Grenadines","VC");
INSERT INTO country values(NULL,"Samoa","WS");
INSERT INTO country values(NULL,"San Marino","SM");
INSERT INTO country values(NULL,"Sao Tome and Principe","ST");
INSERT INTO country values(NULL,"Saudi Arabia","SA");
INSERT INTO country values(NULL,"Senegal","SN");
INSERT INTO country values(NULL,"Seychelles","SC");
INSERT INTO country values(NULL,"Sierra Leone","SL");
INSERT INTO country values(NULL,"Singapore","SG");
INSERT INTO country values(NULL,"Slovakia (Slovak Republic)","SK");
INSERT INTO country values(NULL,"Slovenia","SI");
INSERT INTO country values(NULL,"Solomon Islands","SB");
INSERT INTO country values(NULL,"Somalia","SO");
INSERT INTO country values(NULL,"South Africa","ZA");
INSERT INTO country values(NULL,"South Georgia and the South Sandwich Islands","GS");
INSERT INTO country values(NULL,"Spain","ES");
INSERT INTO country values(NULL,"Sri Lanka","LK");
INSERT INTO country values(NULL,"St. Helena","SH");
INSERT INTO country values(NULL,"St. Pierre and Miquelon","PM");
INSERT INTO country values(NULL,"Sudan","SD");
INSERT INTO country values(NULL,"Suriname","SR");
INSERT INTO country values(NULL,"Svalbard and Jan Mayen Islands","SJ");
INSERT INTO country values(NULL,"Swaziland","SZ");
INSERT INTO country values(NULL,"Sweden","SE");
INSERT INTO country values(NULL,"Switzerland","CH");
INSERT INTO country values(NULL,"Syrian Arab Republic","SY");
INSERT INTO country values(NULL,"Taiwan, Province of China","TW");
INSERT INTO country values(NULL,"Tajikistan","TJ");
INSERT INTO country values(NULL,"Tanzania, United Republic of","TZ");
INSERT INTO country values(NULL,"Thailand","TH");
INSERT INTO country values(NULL,"Togo","TG");
INSERT INTO country values(NULL,"Tokelau","TK");
INSERT INTO country values(NULL,"Tonga","TO");
INSERT INTO country values(NULL,"Trinidad and Tobago","TT");
INSERT INTO country values(NULL,"Tunisia","TN");
INSERT INTO country values(NULL,"Turkey","TR");
INSERT INTO country values(NULL,"Turkmenistan","TM");
INSERT INTO country values(NULL,"Turks and Caicos Islands","TC");
INSERT INTO country values(NULL,"Tuvalu","TV");
INSERT INTO country values(NULL,"Uganda","UG");
INSERT INTO country values(NULL,"Ukraine","UA");
INSERT INTO country values(NULL,"United Arab Emirates","AE");
INSERT INTO country values(NULL,"United Kingdom","GB");
INSERT INTO country values(NULL,"United States","US");
INSERT INTO country values(NULL,"United States Minor Outlying Islands","UM");
INSERT INTO country values(NULL,"Uruguay","UY");
INSERT INTO country values(NULL,"Uzbekistan","UZ");
INSERT INTO country values(NULL,"Vanuatu","VU");
INSERT INTO country values(NULL,"Venezuela","VE");
INSERT INTO country values(NULL,"Viet Nam","VN");
INSERT INTO country values(NULL,"Virgin Islands (British)","VG");
INSERT INTO country values(NULL,"Virgin Islands (U.S.)","VI");
INSERT INTO country values(NULL,"Wallis and Futuna Islands","WF");
INSERT INTO country values(NULL,"Western Sahara","EH");
INSERT INTO country values(NULL,"Yemen","YE");
INSERT INTO country values(NULL,"Yugoslavia","YU");
INSERT INTO country values(NULL,"Zambia","ZM");
INSERT INTO country values(NULL,"Zimbabwe","ZW");


<?php
/*
	 +----------------------------------------------------------------------+
	 | UPS Quick Cost                                                       |
	 +----------------------------------------------------------------------+
	 | Copyright (c) 2000 Darrell Brogdon                                   |
	 +----------------------------------------------------------------------+
	 | THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESSED OR IMPLIED     |
	 | WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES    |
	 | OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE          |
	 | DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE  |
	 | FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR         |
	 | CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF |
	 | SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR      |
	 | BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,|
	 | WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE |
	 | OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,    |
	 | EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                   |
	 +----------------------------------------------------------------------+
	 | Authors: Darrell Brogdon <darrell@brogdon.net>                       |
	 +----------------------------------------------------------------------+
*/

/* $Id: ups_quick_cost.php,v 1.1.1.1 2001/03/06 20:03:03 adamh Exp $ */

	class ups_quick_cost {
		// Local vars.
		var $origCountry	= "";
		var $origPostal		= "";
		var $destCountry	= "";
		var $destCity		= "";
		var $destPostal		= "";
		var $residential	= "";
		var $rate_chart		= "";
		var $container		= "";
		var $weight			= "";
		var $weight_std		= "";
		var $length			= "";
		var $width			= "";
		var $height			= "";
		var $length_std		= "";
		var $location 		= "http://www.ups.com/using/services/rave/qcost.cgi"; 
		var $section_flag	= false;
		var $svc 		= array();
		var $guaranteed 	= array();
		var $zone 			= array();
		var $rate 			= array();
		var $containers		= array(
									"Select Package Type" => "-1",
									"Your Packaging" => "00",
									"UPS Letter Envelope" => "01",
									"UPS Express Box" => "21",
									"UPS Tube" => "03",
									"UPS Worldwide Express 10KG Box" => "25",
									"UPS Worldwide Express 25KG Box" => "24");
		var $rates			= array(
									"Select Drop-off/Pickup" => "-1",
									"Daily Pickup Service" => "Regular Daily Pickup",
									"On Call Air Pickup" => "On Call Air",
									"One Time Pickup" => "One Time Pickup",
									"Drop-box Letter Center" => "Letter Center",
									"Customer Counter" => "Customer Counter");
		var $svc_type 		= array(
									"UPS SonicAir BestFlight",
									"UPS Next Day Air Early A.M.",
									"UPS Next Day Air",
									"UPS Next Day Air Saver",
									"UPS 2nd Day Air"
									);
		var $countries 		= array(
									"Albania" => "AL",
									"Algeria" => "DZ",
									"American Samoa" => "AS",
									"Andorra" => "AD",
									"Anguilla" => "AI",
									"Antigua & Barbuda" => "AG",
									"Argentina" => "AR",
									"Aruba" => "AW",
									"Australia" => "AU",
									"Austria" => "AT",
									"Azores" => "AP",
									"Bahamas" => "BS",
									"Bahrain" => "BH",
									"Bangladesh" => "BD",
									"Barbados" => "BB",
									"Belgium" => "BE",
									"Belize" => "BZ",
									"Belarus" => "BY",
									"Benin" => "BJ",
									"Bermuda" => "BM",
									"Bolivia" => "BO",
									"Bonaire" => "BL",
									"Bosnia" => "BA",
									"Botswana" => "BW",
									"Brazil" => "BR",
									"British Virgin Islands" => "VG",
									"Brunei" => "BN",
									"Bulgaria" => "BG",
									"Burkina Faso" => "BF",
									"Burundi" => "BI",
									"Cambodia" => "KH",
									"Cameroon" => "CM",
									"Canada *" => "CA",
									"Canary Islands" => "IC",
									"Cape Verde Islands" => "CV",
									"Cayman Islands" => "KY",
									"Central African Republic" => "CF",
									"Chad" => "TD",
									"Channel Islands" => "CD",
									"Chile" => "CL",
									"China, Peoples Republic of" => "CN",
									"Colombia" => "CO",
									"Congo" => "CG",
									"Cook Islands" => "CK",
									"Costa Rica" => "CR",
									"Croatia" => "HR",
									"Curacao" => "CB",
									"Cyprus" => "CY",
									"Czech Republic" => "CZ",
									"Denmark" => "DK",
									"Djibouti" => "DJ",
									"Dominica" => "DM",
									"Dominican Republic" => "DO",
									"Ecuador" => "EC",
									"Egypt" => "EG",
									"El Salvador" => "SV",
									"England" => "EN",
									"Equitorial Guinea" => "GQ",
									"Eritrea" => "ER",
									"Estonia" => "EE",
									"Ethiopia" => "ET",
									"Faeroe Islands" => "FO",
									"Federated States of Micronesia" => "FM",
									"Fiji" => "FJ",
									"Finland" => "FI",
									"France" => "FR",
									"French Guiana" => "GF",
									"French Polynesia" => "PF",
									"Gabon" => "GA",
									"Gambia" => "GM",
									"Georgia" => "GE",
									"Germany" => "DE",
									"Ghana" => "GH",
									"Gibraltar" => "GI",
									"Greece" => "GR",
									"Greenland" => "GL",
									"Grenada" => "GD",
									"Guadeloupe" => "GP",
									"Guam" => "GU",
									"Guatemala" => "GT",
									"Guinea" => "GN",
									"Guinea-Bissau" => "GW",
									"Guyana" => "GY",
									"Haiti" => "HT",
									"Holland" => "HO",
									"Honduras" => "HN",
									"Hong Kong" => "HK",
									"Hungary" => "HU",
									"Iceland" => "IS",
									"India" => "IN",
									"Indonesia" => "ID",
									"Israel" => "IL",
									"Italy" => "IT",
									"Ivory Coast" => "CI",
									"Jamaica" => "JM",
									"Japan" => "JP",
									"Jordan" => "JO",
									"Kazakhstan" => "KZ",
									"Kenya" => "KE",
									"Kiribati" => "KI",
									"Kosrae" => "KO",
									"Kuwait" => "KW",
									"Kyrgyzstan" => "KG",
									"Laos" => "LA",
									"Latvia" => "LV",
									"Lebanon" => "LB",
									"Lesotho" => "LS",
									"Liberia" => "LR",
									"Liechtenstein" => "LI",
									"Lithuania" => "LT",
									"Luxembourg" => "LU",
									"Macau" => "MO",
									"Macedonia" => "MK",
									"Madagascar" => "MG",
									"Madeira" => "ME",
									"Malawi" => "MW",
									"Malaysia" => "MY",
									"Maldives" => "MV",
									"Mali" => "ML",
									"Malta" => "MT",
									"Marshall Islands" => "MH",
									"Martinique" => "MQ",
									"Mauritania" => "MR",
									"Mauritius" => "MU",
									"Mexico" => "MX",
									"Moldova" => "MD",
									"Monaco" => "MC",
									"Montserrat" => "MS",
									"Morocco" => "MA",
									"Mozambique" => "MZ",
									"Myanmar" => "MM",
									"Namibia" => "NA",
									"Nepal" => "NP",
									"Netherlands" => "NL",
									"Netherlands Antilles" => "AN",
									"New Caledonia" => "NC",
									"New Zealand" => "NZ",
									"Nicaragua" => "NI",
									"Niger" => "NE",
									"Nigeria" => "NG",
									"Norfolk Island" => "NF",
									"Northern Ireland" => "NB",
									"Northern Mariana Islands" => "MP",
									"Norway" => "NO",
									"Oman" => "OM",
									"Pakistan" => "PK",
									"Palau" => "PW",
									"Panama" => "PA",
									"Papua New Guinea" => "PG",
									"Paraguay" => "PY",
									"Peru" => "PE",
									"Philippines" => "PH",
									"Poland" => "PL",
									"Ponape" => "PO",
									"Portugal" => "PT",
									"Puerto Rico *" => "PR",
									"Qatar" => "QA",
									"Republic of Ireland" => "IE",
									"Republic of Yemen" => "YE",
									"Reunion" => "RE",
									"Romania" => "RO",
									"Rota" => "RT",
									"Russia" => "RU",
									"Rwanda" => "RW",
									"Saba" => "SS",
									"Saipan" => "SP",
									"Saudi Arabia" => "SA",
									"Scotland" => "SF",
									"Senegal" => "SN",
									"Seychelles" => "SC",
									"Sierra Leone" => "SL",
									"Singapore" => "SG",
									"Slovakia" => "SK",
									"Slovenia" => "SI",
									"Solomon Islands" => "SB",
									"South Africa" => "ZA",
									"South Korea" => "KR",
									"Spain" => "ES",
									"Sri Lanka" => "LK",
									"St. Barthelemy" => "NT",
									"St. Christopher" => "SW",
									"St. Croix" => "SX",
									"St. Eustatius" => "EU",
									"St. John" => "UV",
									"St. Kitts & Nevis" => "KN",
									"St. Lucia" => "LC",
									"St. Maarten" => "MB",
									"St. Martin" => "TB",
									"St. Thomas" => "VL",
									"St. Vincent & the Grenadines" => "VC",
									"Sudan" => "SD",
									"Suriname" => "SR",
									"Swaziland" => "SZ",
									"Sweden" => "SE",
									"Switzerland" => "CH",
									"Syria" => "SY",
									"Tahiti" => "TA",
									"Taiwan" => "TW",
									"Tajikistan" => "TJ",
									"Tanzania" => "TZ",
									"Thailand" => "TH",
									"Tinian" => "TI",
									"Togo" => "TG",
									"Tonga" => "TO",
									"Tortola" => "TL",
									"Trinidad & Tobago" => "TT",
									"Truk" => "TU",
									"Tunisia" => "TN",
									"Turkey" => "TR",
									"Turks & Caicos Islands" => "TC",
									"Tuvalu" => "TV",
									"Uganda" => "UG",
									"Ukraine" => "UA",
									"Union Island" => "UI",
									"United Arab Emirates" => "AE",
									"United Kingdom" => "GB",
									"United States" => "US",
									"Uruguay" => "UY",
									"US Virgin Islands" => "VI",
									"Uzbekistan" => "UZ",
									"Vanuatu" => "VU",
									"Venezuela" => "VE",
									"Vietnam" => "VN",
									"Virgin Gorda" => "VR",
									"Wake Island" => "WK",
									"Wales" => "WL",
									"Wallis & Futuna Islands" => "WF",
									"Western Samoa" => "WS",
									"Yap" => "YA",
									"Yugoslavia" => "YU",
									"Zaire" => "ZR",
									"Zambia" => "ZM",
									"Zimbabwe" => "ZW"		
			);	

		// {{{ Constructor

		function ups_quick_cost()
		{
			return true;
		}

		// }}}
		// {{{ getCosts

		/**
		 * Go out to the URL defined in $location and get the raw data.
		 * Having gotten that data this function begins looking for specific
		 * data and storing it in the appropriate vars.
		 */
		function getCosts()
		{
			$this->setLocation();
			$fp = fopen($this->location, "r")
				or die("could not open location");
			while( !feof($fp) ) {
				$page_output = fgets($fp, 1024);

				// If we're at the section of code that pertains the pricing
				// data then start storing each line in an array.
				if( ereg("To add optional services, click on the arrow", $page_output) ) {
					$this->setFlag();
				}

				// Once we're at the end of that section then stop storing
				// the data.
				if( ereg("OPTIONAL SERVICES FOR UPS DOMESTIC SHIPPING SERVICES", $page_output) ) {
					$this->setFlag();
				}	

				// This part actually stores the data.
				if( $this->section_flag ) {
					$good_stuff[] = $page_output;
				}
			}
			fclose($fp);

			// Look for lines that match the service types.
			// Then store the succeeding lines in the appropriate variables
			for( $x=0; $x<count($good_stuff); $x++ ) {
//				print("<pre>" . htmlentities($good_stuff[$x]) . "</pre>");		// FOR DEBUGGING

				// With all but the first service type we can walk through an array
				// but with the first one we have to do it manually because the line
				// progression is slightly different.
				if( eregi($this->svc_type[0], $good_stuff[$x]) ) {
					$this->svc[] = $this->svc_type[0];
					$this->guaranteed[] = trim(strip_tags($good_stuff[$x + 1]));
					$this->zone[] = trim(strip_tags($good_stuff[$x + 2]));
					$this->rate[] = trim(strip_tags($good_stuff[$x + 3]));
				}

				for( $y=1; $y<count($this->svc_type); $y++ ) {
					if( eregi($this->svc_type[$y], $good_stuff[$x]) ) {
						$this->svc[] = $this->svc_type[$y];
						$this->guaranteed[] = trim(strip_tags($good_stuff[$x + 2]));
						$this->zone[] = trim(strip_tags($good_stuff[$x + 3]));
						$this->rate[] = trim(strip_tags($good_stuff[$x + 4]));
					}
				}
			}

			return true;
		}

		// }}}
		// {{{ getContainers()

		function getContainers()
		{
			return $this->containers;
		}

		// }}}
		// {{{ getSvcType

		/**
		 * Get an array of Service Types
		 */
		function getSvcType()
		{
			return $this->svc_type;
		}

		// }}}
		// {{{ getGuaranteed

		/**
		 * Get an array of 'guaranteed' values
		 */
		function getGuaranteed()
		{
			return $this->guaranteed;
		}

		// }}}
		// {{{ getZone

		/**
		 * Get an array of the zones
		 */
		function getZone()
		{
			return $this->zone;
		}

		// }}}
		// {{{ getRate

		/**
		 * Get an array of the rates
		 */
		function getRate()
		{
			return $this->rate;
		}
		
		// }}}
		// {{{ getSvc

		/**
		* Get array of Service Types that corresponds to rate array & zone array after calling getCosts
		*/
		function getSvc()
		{
			return $this->svc;
		}

		// }}}
		// {{{ getRates

		/**
		 * Get an array of the rate options
		 */
		function getRates()
		{
			return $this->rates;
		}

		// }}}
		// {{{ getRateChart

		/**
		 * Get an array of the rate charts
		 */
		function getRateCharts()
		{
			return $this->rate_chart;
		}

		// }}}
		// {{{ setShipInfo

		/**
		 * Set the shipping info
		 *
		 * @param $origCountry String Origination country code
		 * @see getCountryCode()
		 * @param $origPostal Int Origination postal/ZIP code
		 * @param $destCountry String Destination country code
		 * @see getCountryCode()
		 * @param $destCity String Destination city name
		 * @param $destPostal Int Destination postal/ZIP code
		 * @param $residential String Whether the destination location is a residential customer (YES|NO)
		 * @param $rate String The rate chart
		 * @see getRateChart()
		 * @param $container Int The packaging container
		 * @param $weight Int The weight of the package
		 * @param $weight_std The weight standard that $weight is in (lbs.|kgs.)
		 * @param $length Int The length of the package.  Only needed if $container is 00
		 * @param $width Int The width of the package. Only needed if $container is 00
		 * @param $height Int The height of the package. Only needed if $container is 00
		 * @param $length_std String The size standard that $length, $width, and $height is in (in.|cm.)
		 */
		function setShipInfo($origCountry, $origPostal, $destCountry, $destCity, $destPostal, $residential, 
						 	 $rate, $container, $weight, $weight_std, $length, $width, $height, $length_std)
		{
			$this->origCountry  = $origCountry;
			$this->origPostal	= $origPostal;
			$this->destCountry	= $destCountry;
			$this->destCity		= $destCity;
			$this->destPostal	= $destPostal;
			$this->residential	= $residential;
			$this->rate_chart	= $rate;
			$this->container	= $container;
			$this->weight		= $weight;
			$this->weight_std	= $weight_std;
			$this->length		= $length;
			$this->width		= $width;
			$this->height		= $height;
			$this->length_std	= $length_std;

			return true;
		}

		// }}}
		// {{{ setFlag

		/**
		 * Internal function used for setting a flag.
		 * This flag is used to start and stop the aquisition
		 * of specific data.
		 */
		function setFlag()
		{
			if( $this->section_flag ) {
				$this->section_flag = false;
			} else {
				$this->section_flag = true;
			}

			return true;
		}

		// }}}
		// {{{ getCountries

		/**
		 * Get an array of the countries and their codes
		 */
		function getCountries()
		{
			return $this->countries;
		}

		// }}}
		// {{{ getCountryCode

		/**
		 * Resolve a UPS supported country code with its full name
		 * 
		 * @param $name String Full name of the country
		 */
		function getCountryCode($name)
		{
			return $this->countries["$name"];
		}
		
		// }}}
		// {{{ getCountryName

		/**
		 * Resolve a UPS supported country name with its code
		 * 
		 * @param $code String Counry code
		 */
		function getCountryName($code)
		{
			// Probably a better way to do this but oh well...
			while ( list($key, $val) = each($this->countries) ) {
				if( $val == $code ) {
					return $key;
				} else {
					continue;
				}
			}
		}
	
		// }}}
		// {{{ setLocation

		/**
		 * Build the URL argument string based on the values given to the constructor
		 */
		function setLocation()
		{
			$location .= "?14_origCountry=" 	. $this->origCountry;
			$location .= "&15_origPostal=" 	. $this->origPostal;
			$location .= "&22_destCountry=" 	. $this->destCountry;
			$location .= "&20_destCity=" 		. $this->destCity;
			$location .= "&19_destPostal=" 	. $this->destPostal;
			$location .= "&49_residential=" 	. $this->residential;
			$location .= "&47_rate_chart=" 	. $this->rate_chart;
			$location .= "&48_container=" 	. $this->container;
			$location .= "&23_weight=" 		. $this->weight;
			$location .= "&weight_std=" 		. $this->weight_std;
			$location .= "&25_length=" 		. $this->length;
			$location .= "&26_width=" 		. $this->width;
			$location .= "&27_height=" 		. $this->height;
			$location .= "&length_std=" 		. $this->length_std;
			
			$space = urlencode(" ");
			$location = preg_replace("/[\s]/",$space,$location);
			$this->location .= $location;
			
			//echo $this->location ."\n<br>";
			return true;
		}

		// }}}
	}

?>

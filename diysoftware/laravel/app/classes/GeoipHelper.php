<?php

require_once(dirname(__FILE__).'/geoip.php');
require_once(dirname(__FILE__).'/geoipregionvars.php');
require_once(dirname(__FILE__).'/geoipcity.php');
require_once(dirname(__FILE__).'/timezone.php');


class GeoipHelper
{
	public $record;
	
	public function __construct()
	{
		$gi = geoip_open(dirname(__FILE__)."/GeoLiteCity.dat", GEOIP_STANDARD);
		$this->record = geoip_record_by_addr($gi, $_SERVER['REMOTE_ADDR']);
		
//		print $record->country_code . " " . $record->country_code3 . " " . $record->country_name . "\n";
//		print $record->region . " " . $GEOIP_REGION_NAME[$record->country_code][$record->region] . "\n";
//		print $record->city . "\n";
//		print $record->postal_code . "\n";
//		print $record->latitude . "\n";
//		print $record->longitude . "\n";
//		print $record->metro_code . "\n";
//		print $record->area_code . "\n";
//		print $record->continent_code . "\n";
		geoip_close($gi);
	}
	
	public function __get($var)
	{
		if (isset($this->record->{$var}))
		return $this->record->{$var};
		
		return null;
	}
	

}
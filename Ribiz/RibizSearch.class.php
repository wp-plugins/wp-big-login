<?php
ini_set( "soap.wsdl_cache_enabled", "0" );
/**
*This is the class that returns the result. If false then it returns an empty array.
*/
class Ribiz_Search {
	const VERSION = '1.0';

	private $client;
	
	/**
	 *The search function
	 *@var array
	 */
	private $searchArray = array();

	public function init() {
		//Connecting to soap server
		$this->client = new SoapClient( 'http://webservices.cibg.nl/Ribiz/OpenbaarV2.asmx?wsdl' ); 
	}
	/**
	 * Search function
	 *
	 * @return string|bool when found returns the bignumber else returns false
	 */
	public function search( $array = array() ) {
		if( ! sizeof( $array ) ) {
			$array = $this->searchArray;
		}
		//Giving back the results
		$result = $this->client->__soapCall( 'ListHcpApprox3' , $this->searchRequest( $array ) );
		if( ! is_soap_fault( $this->client) ) {
			if( property_exists( $result, 'ListHcpApprox' ) &&
				property_exists( $result->ListHcpApprox, 'ListHcpApprox3' ) &&
				property_exists( $result->ListHcpApprox->ListHcpApprox3, 'HcpNumber' )
				)	{
				//results have been found, return them
				return $result->ListHcpApprox->ListHcpApprox3->HcpNumber;
			} else {
				// no results have been found
				return false;
			}
		} else {
			/** the search array is malformed, not sure if we should throw an exception here so we're returning an empty array **/
			return false;
		}
	}	

	private function searchRequest( $array ) {
		return array( 'listHcpApproxRequest' => array( 'WebSite' => 'Ribiz' ) + $array);
	}
}

<?php

/**
 * API for connection to ARES system, contains public function "getDataByICO"  
 */

declare(strict_types=1);

use App\Model;

class Ares extends RestClient {
	
	private $publisher_id = 'bdp';
		
	public function __construct() {
		parent::__construct(array(
			'base_url' => "http://wwwinfo.mfcr.cz/cgi-bin/ares/",
			'headers' => ['Content-Type' => 'application/json', 'charset' => 'UTF-8']
		));
	}

	public function getDataByICO(int $ico) {
		
		$url = "darv_vreo.cgi?ico=" . $ico;

 		$handler = $this->get($url);
		
		 if ($handler->info->http_code == '200') { // if is http_code 200 (OK) continue to get XML data
			
			$xml = simplexml_load_string($handler->response); // get XML from response
			$ns = $xml->getDocNamespaces();

			// parse XML doc
			$data = $xml->children($ns['are']);
			$data_vypis = $data->children($ns['are'])->Vypis_VREO; // get main element Vypis_VREO for reading corp. data
			
			if ($data_vypis) {
				$zu = $data_vypis->children($ns['are'])->Zakladni_udaje; // get element "are:Zakladni_udaje" which contains corp. name and address
				$so = $data_vypis->children($ns['are'])->Statutarni_organ; // get element "are:Statutarni_organ" which contains statutory informations
				$so_last_director = $so->children($ns['are'])->Clen[count($so->children($ns['are'])->Clen) - 1];

				// return array with corp. data
				return [
					'name' => $zu->ObchodniFirma,
					'address' => (string) $zu->Sidlo->ulice . ' ' . $zu->Sidlo->cisloPop . ' ' . $zu->Sidlo->obec  . ' ' . $zu->Sidlo->psc,
					'dic' => 'CZ' . $ico,
					'statutory_name' => (string) ($so_last_director->fosoba->titulPred != '' ? $so_last_director->fosoba->titulPred  . ' ' : '') . $so_last_director->fosoba->jmeno . ' ' . $so_last_director->fosoba->prijmeni
				];
			}else{ // if main element isn't exists return 0
				return 0;
			}
		} else { // http_code isn't 200, there is error in connection to ARES, return -1
			return -1;
		}		
	}	
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setemail {

	protected $CI;

	public function __construct($params) {
		$this -> CI = &get_instance();
	}

	public function createmail($correos, $asuntomail, $htmlcontent, $formato) {
		// Load email settings
		$this -> CI -> load -> library('email');
		$emailVars = array($this -> CI -> load -> config('myemail'));
		$dateaction = date('d/m/Y H:i:s');		
		$this -> CI -> email->set_mailtype("html");
		$this -> CI -> email -> initialize($emailVars);
		$this -> CI -> email -> from(SMTP_FROM_EMAIL, SMTP_NAME_EMAIL);
		if (count($correos) > 1) {
			$this -> CI -> email -> to(implode(', ', $correos));
		} else {
			$this -> CI -> email -> to($correos);
		}
		$this -> CI -> email -> subject($asuntomail . ' | ' . SMTP_COMPANY_NAME . ' - ' . $dateaction . '');
        $datamail = array('titulo'=>$asuntomail ,'contenido' => $htmlcontent);
		$bodyMail = $this -> CI -> load -> view('mails/'.$formato, $datamail, TRUE);
		$this -> CI -> email -> message($bodyMail);
        if ($this -> CI -> email -> send()) {
              return TRUE;
        } else {
              return FALSE;
        }		
	}

}
?>